<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
    <h1>Your Profile</h1>
    <p><strong>Name:</strong> <?= esc($user['name']) ?></p>
    <p><strong>Email:</strong> <?= esc($user['email']) ?></p>
    <p><strong>Credit Card:</strong> <?= esc($user['credit_card'] ?: 'Not provided') ?></p>
    <a href="/profile/edit">Edit Profile</a><p></p>
    <a href="/profile/history">View Transaction History</a>
<?= $this->endSection() ?>
