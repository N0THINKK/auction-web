<!-- views/layouts/default.php  -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My App</title>
    <link rel="stylesheet" href="<?= base_url('css/star.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('css/navbar.css') ?>" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('css/custom.css') ?>">
</head>
<body>
    <div id="night-sky" style="--number-of-stars: 20"></div> <!-- Latar belakang -->
    <!-- Navbar -->
    <?= view('partials/navbar') ?>

    <!-- Main Content -->
    <div class="content">
        <?= $this->renderSection('content') ?>
    </div>
</body>

</html>
<script src="<?= base_url('js/star.js') ?>"></script>
