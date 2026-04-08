<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="home-hero">
    <div class="home-copy">
        <h1><?= esc($title); ?></h1>
        <hr>
        <p><?= esc($content); ?></p>
        <p class="home-note">Silakan gunakan tombol <strong>Login Admin</strong> di pojok kanan atas untuk masuk ke halaman admin.</p>
    </div>
    <div class="home-logo-wrap">
        <img src="<?= base_url('/LOGO.png'); ?>" alt="Logo Universitas Pelita Bangsa" class="home-logo">
    </div>
</div>
<?= $this->endSection() ?>
