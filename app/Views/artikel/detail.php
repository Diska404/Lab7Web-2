<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<article class="entry article-detail-card">
    <span class="article-category-badge detail"><?= esc($artikel['nama_kategori'] ?: 'Tanpa Kategori'); ?></span>
    <h2><?= esc($artikel['judul']); ?></h2>
    <?php if (! empty($artikel['gambar'])): ?>
        <img src="<?= base_url('/gambar/' . $artikel['gambar']); ?>" alt="<?= esc($artikel['judul']); ?>">
    <?php endif; ?>
    <p class="artikel-kategori-text">Kategori: <strong><?= esc($artikel['nama_kategori'] ?: 'Tanpa Kategori'); ?></strong></p>
    <p><?= esc($artikel['isi']); ?></p>
</article>
<?= $this->endSection() ?>
