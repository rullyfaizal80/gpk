<?php

namespace App\Libraries;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Google\Service\Drive\Permission;

class GoogleDriveService
{
    private $client;
    private $service;
    
    // ID Root Folder Google Drive Utama Anda
    private $rootFolderId = '1cFds4YLreag8idXFGWFAk2rvQilSak7a'; 

    public function __construct()
    {
        $this->client = new Client();
        
        // Membaca file kredensial JSON di folder WRITEPATH
        $credentialsPath = WRITEPATH . 'credentials/google-drive.json';
        if (!file_exists($credentialsPath)) {
            // Fallback ke kredensial lama jika belum dipindahkan ke subfolder
            $credentialsPath = WRITEPATH . 'credentials.json';
        }

        $this->client->setAuthConfig($credentialsPath); 
        $this->client->addScope(Drive::DRIVE);
        $this->service = new Drive($this->client);
    }

    /**
     * Cari folder berdasarkan nama di dalam parent tertentu, atau buat baru jika belum ada
     * (Mendukung Shared Drive / Google Workspace Edu)
     */
    public function getOrCreateFolder($folderName, $parentId)
    {
        $query = "mimeType='application/vnd.google-apps.folder' and name='$folderName' and trashed=false and '$parentId' in parents";

        // Query pencarian dengan dukungan Shared Drive
        $results = $this->service->files->listFiles([
            'q'                         => $query,
            'spaces'                    => 'drive',
            'fields'                    => 'files(id, name)',
            'includeItemsFromAllDrives' => true,
            'supportsAllDrives'         => true
        ]);

        if (count($results->getFiles()) > 0) {
            return $results->getFiles()[0]->getId();
        }

        // Jika folder belum ada, buat baru
        $folderMetadata = new DriveFile([
            'name'     => $folderName,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents'  => [$parentId]
        ]);
        
        $folder = $this->service->files->create($folderMetadata, [
            'fields'            => 'id',
            'supportsAllDrives' => true
        ]);
        
        return $folder->id;
    }

    /**
     * Unggah Foto ke sub-folder: Root -> GPK -> Tahun -> Bulan
     */
    public function uploadImageToMonthlyFolder($filePath, $fileName, $mimeType)
    {
        $tahun = date('Y');
        $bulan = date('m-F'); // Format contoh: 11-November

        // 1. Cari / Buat Folder "GPK" di dalam Root Utama
        $gpkFolderId = $this->getOrCreateFolder('GPK', $this->rootFolderId);

        // 2. Cari / Buat Folder Tahun (misal: 2026) di dalam Folder "GPK"
        $tahunFolderId = $this->getOrCreateFolder($tahun, $gpkFolderId);

        // 3. Cari / Buat Folder Bulan (misal: 11-November) di dalam Folder Tahun
        $bulanFolderId = $this->getOrCreateFolder($bulan, $tahunFolderId);

        // 4. Siapkan Metadata File Foto
        $fileMetadata = new DriveFile([
            'name'    => $fileName,
            'parents' => [$bulanFolderId] 
        ]);

        $content = file_get_contents($filePath);
        
        // 5. Unggah File ke Drive (Dukungan Workspace & Shared Drives)
        $file = $this->service->files->create($fileMetadata, [
            'data'              => $content,
            'mimeType'          => $mimeType,
            'uploadType'        => 'multipart',
            'fields'            => 'id',
            'supportsAllDrives' => true 
        ]);

        // 6. Set Permission Publik (Reader) agar gambar dapat di-render di aplikasi
        $permission = new Permission([
            'type' => 'anyone',
            'role' => 'reader'
        ]);
        
        $this->service->permissions->create($file->id, $permission, [
            'supportsAllDrives' => true
        ]);

        return $file->id;
    }

    /**
     * Ambil raw byte data gambar untuk dirender oleh Controller (renderFoto)
     */
    public function getMedia($fileId)
    {
        $response = $this->service->files->get($fileId, [
            'alt'               => 'media',
            'supportsAllDrives' => true
        ]);
        
        return $response->getBody()->getContents();
    }

    /**
     * Hapus file di Google Drive berdasarkan File ID (Mendukung Shared Drive & Individual Drive)
     */
    public function deleteFile($fileId)
    {
        if (empty($fileId)) {
            return false;
        }

        try {
            // Opsi 1: Coba hapus permanen dari Drive
            $this->service->files->delete($fileId, [
                'supportsAllDrives' => true
            ]);
            return true;
        } catch (\Exception $e) {
            log_message('warning', 'Hapus permanen gagal, mencoba pindahkan ke Trash. ID (' . $fileId . '): ' . $e->getMessage());
            
            try {
                // Opsi 2: Fallback jika akun Service Account tidak punya izin Hapus Permanen di Shared Drive
                // Pindahkan file ke Sampah (Trash)
                $emptyFile = new DriveFile([
                    'trashed' => true
                ]);

                $this->service->files->update($fileId, $emptyFile, [
                    'supportsAllDrives' => true
                ]);
                return true;
            } catch (\Exception $ex) {
                log_message('error', 'Gagal memindahkan ke Trash Drive ID (' . $fileId . '): ' . $ex->getMessage());
                return false;
            }
        }
    }
}