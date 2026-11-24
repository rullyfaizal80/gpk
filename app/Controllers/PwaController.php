<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class PwaController extends BaseController
{
    use ResponseTrait;

    /**
     * Menyimpan atau memperbarui token subscription Web Push PWA user
     */
    public function subscribe()
    {
        // 1. Cek User ID dari berbagai variasi key session login yang umum
        $userId = session()->get('user_id') 
               ?? session()->get('id') 
               ?? session()->get('id_user') 
               ?? session()->get('logged_in');
        
        if (!$userId) {
            return $this->failUnauthorized('User belum terautentikasi / session login habis.');
        }

        $json = $this->request->getJSON(true);

        if (!$json || empty($json['endpoint'])) {
            return $this->failInvalidParam('Data subscription endpoint tidak valid atau kosong.');
        }

        $db = \Config\Database::connect();
        
        $endpoint        = $json['endpoint'];
        $publicKey       = $json['keys']['p256dh'] ?? null;
        $authToken       = $json['keys']['auth'] ?? null;
        $contentEncoding = $json['contentEncoding'] ?? 'aesgcm';

        // 2. Cek apakah endpoint sudah tersimpan untuk user tersebut
        $existing = $db->table('pwa_subscriptions')
                       ->where('user_id', $userId)
                       ->where('endpoint', $endpoint)
                       ->get()
                       ->getRowArray();

        $data = [
            'user_id'          => $userId,
            'endpoint'         => $endpoint,
            'public_key'       => $publicKey,
            'auth_token'       => $authToken,
            'content_encoding' => $contentEncoding,
            'updated_at'       => date('Y-m-d H:i:s'),
        ];

        if ($existing) {
            $db->table('pwa_subscriptions')
               ->where('id', $existing['id'])
               ->update($data);
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $db->table('pwa_subscriptions')->insert($data);
        }

        return $this->respondCreated([
            'status'  => true,
            'message' => 'Subscription PWA berhasil disimpan ke database',
            'user_id' => $userId
        ]);
    }
}