<?php
$currentPath = trim(uri_string(), '/');
$parts = $currentPath === '' ? [] : explode('/', $currentPath);

$segment1 = $parts[0] ?? '';
$segment2 = $parts[1] ?? '';

$navActive = static function (string $page) use ($segment1, $segment2, $currentPath): string {
    return match ($page) {
        'home'    => ($currentPath === '' || $currentPath === 'index.php') ? 'active' : '',
        'artikel' => ($segment1 === 'artikel' || ($segment1 === 'admin' && $segment2 === 'artikel')) ? 'active' : '',
        'about'   => $segment1 === 'about' ? 'active' : '',
        'contact' => $segment1 === 'contact' ? 'active' : '',
        default   => '',
    };
};
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'My Website') ?></title>
    <link rel="stylesheet" href="<?= base_url('/style.css'); ?>">
</head>
<body>
    <div id="container">
        <header>
            <h1>Layout Sederhana</h1>
            <div class="header-actions">
                <?php if (session()->get('logged_in')): ?>
                    <a href="<?= base_url('/admin/artikel'); ?>" class="header-admin-btn">Admin Panel</a>
                    <a href="<?= base_url('/user/logout'); ?>" class="header-admin-btn secondary">Logout</a>
                <?php else: ?>
                    <a href="<?= base_url('/user/login'); ?>" class="header-admin-btn">Login Admin</a>
                <?php endif; ?>
            </div>
        </header>

        <nav>
            <a href="<?= base_url('/'); ?>" class="<?= $navActive('home') ?>">Home</a>
            <a href="<?= base_url('/artikel'); ?>" class="<?= $navActive('artikel') ?>">Artikel</a>
            <a href="<?= base_url('/about'); ?>" class="<?= $navActive('about') ?>">About</a>
            <a href="<?= base_url('/contact'); ?>" class="<?= $navActive('contact') ?>">Kontak</a>
        </nav>

        <section id="wrapper">
            <section id="main">
                <?= $this->renderSection('content') ?>
            </section>
            <aside id="sidebar">
                <?= view_cell('App\\Cells\\ArtikelTerkini::render') ?>

                <div class="widget-box">
                    <h3 class="title">Widget Header</h3>
                    <ul>
                        <li><a href="<?= base_url('/user/login'); ?>">Login Admin</a></li>
                        <li><a href="<?= base_url('/user/register'); ?>">Daftar Akun</a></li>
                    </ul>
                </div>
                <div class="widget-box">
                    <h3 class="title">Widget Text</h3>
                    <p>Masih Kosong :v</p>
                </div>
            </aside>
        </section>

        <footer>
            <p>&copy; 2026 Diska Kurnia Azzahra Putra</p>
        </footer>
    </div>
</body>
</html>
