<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<h1>Verifikasi Pembayaran</h1>

<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Transaction ID</th>
            <th>Jumlah</th>
            <th>Metode</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($payments as $payment): ?>
            <tr>
                <td><?= esc($payment['id']) ?></td>
                <td><?= esc($payment['transaction_id']) ?></td>
                <td>Rp <?= number_format($payment['amount'], 0, ',', '.') ?></td>
                <td><?= ucfirst(esc($payment['payment_method'])) ?></td>
                <td><?= ucfirst(esc($payment['status'])) ?></td>
                <td>
                    <a href="/admin/verification/<?= esc($payment['id']) ?>">Verifikasi</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
