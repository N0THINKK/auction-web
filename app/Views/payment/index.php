<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
    <h1>Daftar Pembayaran</h1>
    <?php if (isset($payments) && count($payments) > 0): ?>
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>ID Pembayaran</th>
                    <th>ID Transaksi</th>
                    <th>Jumlah</th>
                    <th>Tanggal Pembayaran</th>
                    <th>Metode Pembayaran</th>
                    <th>Status</th>
                    <th>Dibuat Pada</th>
                    <th>Diperbarui Pada</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $payment): ?>
                    <tr>
                        <td><?= $payment['id'] ?></td>
                        <td><?= $payment['transaction_id'] ?></td>
                        <td><?= $payment['amount'] ?></td>
                        <td><?= $payment['payment_date'] ?></td>
                        <td><?= $payment['payment_method'] ?></td>
                        <td><?= $payment['status'] ?></td>
                        <td><?= $payment['created_at'] ?></td>
                        <td><?= $payment['updated_at'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Tidak ada data pembayaran.</p>
    <?php endif; ?>
<?= $this->endSection() ?>

