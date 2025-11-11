<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<h1>Detail Item</h1>

<p><strong>Nama Item:</strong> <?= esc($item['name']) ?></p>
<p><strong>Deskripsi:</strong> <?= esc($item['description']) ?></p>
<p><strong>Harga Akhir:</strong> Rp <?= number_format($finalPrice, 0, ',', '.') ?></p>
<p><strong>Status Pembayaran:</strong> <?= esc(ucfirst($paymentStatus)) ?></p>

<?php if ($paymentStatus === 'none' || $paymentStatus != 'completed'): ?>
    <form action="/payment/choose/<?= esc($item['id']) ?>" method="post">
        <button type="submit">Choose Payment</button>
    </form>
<?php else: ?>
    <p>Pembayaran telah selesai. Anda tidak dapat memilih metode pembayaran lagi.</p>
<?php endif; ?>

<?= $this->endSection() ?>
