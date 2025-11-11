<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<h1>Detail Pembayaran</h1>

<p><strong>ID:</strong> <?= esc($payment['id']) ?></p>
<p><strong>Transaction ID:</strong> <?= esc($payment['transaction_id']) ?></p>
<p><strong>Jumlah:</strong> Rp <?= number_format($payment['amount'], 0, ',', '.') ?></p>
<p><strong>Metode:</strong> <?= ucfirst(esc($payment['payment_method'])) ?></p>
<p><strong>Status:</strong> <?= ucfirst(esc($payment['status'])) ?></p>

<form action="/admin/verify/<?= esc($payment['id']) ?>" method="post">
    <button type="submit" name="action" value="approve">Verifikasi (Set Completed)</button>
    <button type="submit" name="action" value="reject">Tolak (Set Pending)</button>
</form>

<?= $this->endSection() ?>
