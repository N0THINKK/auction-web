<!-- view/auth/register.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('css/custom.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/star.css') ?>" />
</head>
<body>
<div id="night-sky" style="--number-of-stars: 20"></div>
    <h2>Register</h2>

    <?php if (session()->getFlashdata('error')): ?>
        <div style="color: red;">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('success')): ?>
        <div style="color: green;">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <form action="/auth/doRegister" method="post">
        <div>
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div>
            <label for="password_confirm">Confirm Password:</label>
            <input type="password" name="password_confirm" id="password_confirm" required>
        </div>
        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="/auth/login">Login here</a></p>

</body>
</html>
<script src="<?= base_url('js/star.js') ?>"></script>