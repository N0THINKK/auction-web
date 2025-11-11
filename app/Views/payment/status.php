<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<h1>Status Pembayaran</h1>

<p><strong>Metode Pembayaran:</strong> <?= ucfirst(esc($payment['payment_method'])) ?></p>
<p><strong>Jumlah Pembayaran:</strong> Rp <?= number_format($payment['amount'], 0, ',', '.') ?></p>
<p><strong>Status:</strong> <?= ucfirst(esc($payment['status'])) ?></p>


<?php if ($payment['payment_method'] === 'bank_transfer' && $payment['status'] != 'completed'): ?>
    <p>Silakan lakukan transfer sebesar <strong>Rp <?= number_format($payment['amount'], 0, ',', '.') ?></strong> ke rekening 1400023388844.</p>
    <form action="/payment/confirm_transfer/<?= $payment['id'] ?>" method="post">
        <button type="submit">Saya Sudah Transfer</button>
    </form>
<?php endif; ?>


<a href="/">Kembali ke Beranda</a>

<?= $this->endSection() ?>
