<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<h1>History Bid yang Dimenangkan</h1>

<?php if ($transactions): ?>
    <ul>
        <?php foreach ($transactions as $transaction): ?>
            <li>
                <a href="/profile/history/item/<?= $transaction['item_id'] ?>">
                    Item ID: <?= esc($transaction['item_id']) ?> - Final Price: Rp <?= number_format($transaction['final_price'], 0, ',', '.') ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Belum ada bid yang dimenangkan.</p>
<?php endif; ?>

<?= $this->endSection() ?>
