<!-- views/transactions/history.php  -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi</title>
</head>
<body>
    <h1>Riwayat Transaksi</h1>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <table border="1">
        <thead>
            <tr>
                <th>ID Transaksi</th>
                <th>Item</th>
                <th>Status Pembayaran</th>
                <th>Status Transaksi</th>
                <th>Harga Akhir</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transactions as $transaction): ?>
                <tr>
                    <td><?= esc($transaction['id']) ?></td>
                    <td><?= esc($transaction['item_id']) ?></td>
                    <td><?= esc($transaction['payment_status']) ?></td>
                    <td><?= esc($transaction['status']) ?></td>
                    <td>Rp <?= number_format(esc($transaction['final_price']), 0, ',', '.') ?></td>
                    <td>
                        <?php if ($transaction['status'] == 'incomplete'): ?>
                            <a href="/transactions/complete/<?= esc($transaction['id']) ?>">Selesaikan Transaksi</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
