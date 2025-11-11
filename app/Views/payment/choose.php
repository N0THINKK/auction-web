<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<h1>Pilih Metode Pembayaran</h1>

<form action="/payment/process/<?= esc($itemId) ?>" method="post">
    <label>
        <input type="radio" name="payment_method" value="cod" required>
        Cash on Delivery (COD)
    </label><br>
    <label>
        <input type="radio" name="payment_method" value="transfer" required>
        Transfer Bank (Kode Unik akan ditambahkan)
    </label><br>
    <?php if (!empty($creditCard)): // Cek apakah user memiliki kartu kredit ?>
        <label>
            <input type="radio" name="payment_method" value="debit" required>
            Kartu Debit
        </label><br>
    <?php else: ?>
        <p style="color: red;">Anda tidak dapat memilih Kartu Debit karena tidak memiliki kartu kredit.</p>
    <?php endif; ?>
    <br>
    <button type="submit">Proses Pembayaran</button>
</form>

<?= $this->endSection() ?>
