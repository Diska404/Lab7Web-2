<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<article class="article-detail-card article-detail-modern">
    <div class="article-detail-hero">
        <div>
            <span class="article-category-badge detail"><?= esc($artikel['nama_kategori'] ?: 'Tanpa Kategori'); ?></span>
            <h1><?= esc($artikel['judul']); ?></h1>
            <p class="article-detail-meta">Kategori: <strong><?= esc($artikel['nama_kategori'] ?: 'Tanpa Kategori'); ?></strong></p>
        </div>
    </div>

    <?php if (! empty($artikel['gambar'])): ?>
        <figure class="article-detail-image">
            <img src="<?= base_url('/gambar/' . $artikel['gambar']); ?>" alt="<?= esc($artikel['judul']); ?>">
        </figure>
    <?php endif; ?>

    <div class="article-detail-content">
        <p><?= nl2br(esc($artikel['isi'])); ?></p>
    </div>

    <div class="article-detail-actions">
        <a href="<?= base_url('/artikel#daftar-artikel-web'); ?>" class="btn btn-secondary">Kembali ke Artikel</a>
    </div>
</article>
<?= $this->endSection() ?>
