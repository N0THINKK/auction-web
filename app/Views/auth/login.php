<!-- views/auth/login.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('css/custom.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/star.css') ?>" />
</head>
<body>
<div id="night-sky" style="--number-of-stars: 20"></div>
    <h2>Login</h2>

    <?php if (session()->getFlashdata('error')): ?>
        <div style="color: red;">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form action="/auth/doLogin" method="post">
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="/auth/register">Register here</a></p>

</body>
</html>
<script src="<?= base_url('js/star.js') ?>"></script>