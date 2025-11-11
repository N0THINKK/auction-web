<!-- views/products/index.php  -->
<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<h1>Create New Product</h1>

<!-- Form untuk membuat produk baru -->
<form action="/products/store" method="post">
    <?= csrf_field() ?>
    
    <label for="name">Product Name:</label>
    <input type="text" id="name" name="name" value="" required="" style="margin-bottom: 5px;">
    <?= isset($validation) ? $validation->getError('name') : '' ?>

    <br>

    <label for="description">Description:</label>
    <textarea id="description" name="description" required><?= old('description') ?></textarea>
    <?= isset($validation) ? $validation->getError('description') : '' ?>

    <br>

    <label for="starting_price">Starting Price:</label>
    <input type="number" id="starting_price" name="starting_price" value="<?= old('starting_price') ?>" required>
    <?= isset($validation) ? $validation->getError('starting_price') : '' ?>

    <br><br>

    <!-- Tambahkan Input untuk End Time -->
    <label for="end_time">End Time:</label>
    <input type="datetime-local" id="end_time" name="end_time" value="<?= old('end_time') ?>" required>
    <?= isset($validation) ? $validation->getError('end_time') : '' ?>

    <br><br>
    
    <button type="submit">Create Product</button>
</form>

<?= $this->endSection() ?>
