<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<h1>Item Lelang: <?= esc($item['name']) ?></h1>

<!-- Tampilkan Pesan Flashdata -->
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<p><strong>Deskripsi:</strong> <?= esc($item['description']) ?></p>
<p><strong>Harga Awal:</strong> Rp <?= number_format($item['starting_price'], 0, ',', '.') ?></p>
<p><strong>Start Time:</strong> <?= esc($item['created_at']) ?></p>
<p><strong>End Time:</strong> <?= esc($item['end_time']) ?></p>
<p><strong>Harga Saat Ini:</strong> Rp <?= number_format($currentPrice, 0, ',', '.') ?></p>

<h3>Current Bid:</h3>
<?php if ($highestBid): ?>
    <p><strong>Bid Tertinggi:</strong> Rp <?= number_format($highestBid['bid_amount'], 0, ',', '.') ?> oleh User ID: <?= esc($highestBid['user_id']) ?></p>
<?php else: ?>
    <p><strong>Bid Tertinggi:</strong> Belum ada bid untuk item ini.</p>
<?php endif; ?>

<!-- Form untuk menambahkan bid baru -->
<h3>Bidding:</h3>
<?php 

if ((strtotime($item['end_time']) > time()) && $item['status'] === 'active'):?>
    <form action="/bids/create/<?= $item['id'] ?>" method="post">
        <label for="bid_amount">Bid Anda:</label>
        <input type="number" name="bid_amount" id="bid_amount" min="<?= $currentPrice + 1 ?>" required>
        <button type="submit">Kirim Bid</button>
    </form>
<?php else: 
    //var_dump(strtotime($item['end_time']));
    //var_dump(time());
?>
    <p>Lelang ini sudah berakhir.</p>
<?php endif; ?>

<?= $this->endSection() ?>
