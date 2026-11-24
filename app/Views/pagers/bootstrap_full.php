<?php
use CodeIgniter\Pager\PagerRenderer;

/** @var PagerRenderer $pager */
$pager->setSurroundCount(2);
?>

<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center align-items-center gap-2 mb-0">
        
        <!-- Tombol First & Previous -->
        <?php if ($pager->hasPreviousPage()) : ?>
            <li class="page-item">
                <a class="page-link rounded-3 px-3 py-2 border-0 shadow-sm" href="<?= $pager->getFirst() ?>" aria-label="First">
                    <i class="fas fa-angle-double-left"></i> First
                </a>
            </li>
            <li class="page-item">
                <a class="page-link rounded-3 px-3 py-2 border-0 shadow-sm" href="<?= $pager->getPreviousPage() ?>" aria-label="Previous">
                    <i class="fas fa-chevron-left me-1"></i> Prev
                </a>
            </li>
        <?php else: ?>
            <li class="page-item disabled">
                <span class="page-link rounded-3 px-3 py-2 border-0 bg-light text-muted"><i class="fas fa-chevron-left me-1"></i> Prev</span>
            </li>
        <?php endif ?>

        <!-- Angka Halaman -->
        <?php foreach ($pager->links() as $link) : ?>
            <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                <a class="page-link rounded-3 px-3 py-2 border-0 shadow-sm fw-bold" href="<?= $link['uri'] ?>">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <!-- Tombol Next & Last -->
        <?php if ($pager->hasNextPage()) : ?>
            <li class="page-item">
                <a class="page-link rounded-3 px-3 py-2 border-0 shadow-sm" href="<?= $pager->getNextPage() ?>" aria-label="Next">
                    Next <i class="fas fa-chevron-right ms-1"></i>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link rounded-3 px-3 py-2 border-0 shadow-sm" href="<?= $pager->getLast() ?>" aria-label="Last">
                    Last <i class="fas fa-angle-double-right"></i>
                </a>
            </li>
        <?php else: ?>
            <li class="page-item disabled">
                <span class="page-link rounded-3 px-3 py-2 border-0 bg-light text-muted">Next <i class="fas fa-chevron-right ms-1"></i></span>
            </li>
        <?php endif ?>

    </ul>
</nav>