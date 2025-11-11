<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
    <h1>Edit Profile</h1>
    <?php if (session()->getFlashdata('success')): ?>
        <p style="color: green;"><?= session()->getFlashdata('success') ?></p>
    <?php endif; ?>

    <form action="/profile/edit" method="POST">
        <?= csrf_field() ?>
        <div>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?= esc($user['name']) ?>" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= esc($user['email']) ?>" required>
        </div>
        <div>
            <label for="credit_card">Credit Card:</label>
            <input type="text" id="credit_card" name="credit_card" value="<?= esc($user['credit_card']) ?>">
        </div>
        <button type="submit">Update Profile</button>
    </form>
    <a href="/profile">Back to Profile</a>
<?= $this->endSection() ?>
