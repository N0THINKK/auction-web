<!-- views/partials/navbar.php  -->
<nav>
        <a href="<?= base_url('/') ?>">Home</a>
        <a href="<?= base_url('/products') ?>">Products</a>
        <a href="<?= base_url('/profile') ?>">Profile</a>
        <?php if (session()->get('user_id')): ?>
            <a href="<?= base_url('/logout') ?>">Logout</a>
        <?php else: ?>
            <a href="<?= base_url('/login') ?>">Login</a>
        <?php endif; ?>
        <span></span>
</nav>

