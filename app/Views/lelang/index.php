<!-- views/lelang/index.php  -->
<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<h1>All Auction Items</h1>

<!-- Menampilkan notifikasi success jika ada -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<!-- Menampilkan notifikasi error jika ada -->
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<!-- Tabel Daftar Produk -->
<table border="1">
    <thead>
        <tr>
            <th>Item Name</th>
            <th>Starting Bid</th>
            <th>Current Bid</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($itemsWithBids)): ?>
            <?php foreach ($itemsWithBids as $itemWithBid): ?>
                <tr>
                    <td>
                        <a href="/lelang/view/<?= esc($itemWithBid['item']['id']) ?>"><?= esc($itemWithBid['item']['name']) ?></a>
                    </td>
                    <td>Rp <?= number_format($itemWithBid['item']['starting_price'], 0, ',', '.') ?></td>
                    <td>
                        <?php if ($itemWithBid['highestBid']): ?>
                            Rp <?= number_format($itemWithBid['highestBid']['bid_amount'], 0, ',', '.') ?>
                        <?php else: ?>
                            Belum ada bid
                        <?php endif; ?>
                    </td>
                    <td><?= esc($itemWithBid['item']['created_at']) ?></td>
                    <td><?= esc($itemWithBid['item']['end_time']) ?></td>
                    <td>
                        <?php
                            $currentTime = date('Y-m-d H:i:s');
                            // Check if the auction has ended
                            if (strtotime($currentTime) > strtotime($itemWithBid['item']['end_time'])) {
                                // If there's no highest bid, consider the auction incomplete
                                echo ($itemWithBid['highestBid'] ? 'Completed' : 'Cancelled');
                            } else {
                                echo 'Ongoing';
                            }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">No auction items available.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<br><br>
<a href="../products">Create New Product</a>

<?= $this->endSection() ?>
