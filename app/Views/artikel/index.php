<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h1><?= esc($title); ?></h1>
<hr>

<?php if (! empty($materi)): ?>
    <section class="materi-highlight">
        <span class="materi-badge">Materi Kuliah PDF</span>
        <h2>Belajar materi Pemrograman Web langsung dari halaman artikel</h2>
        <p>Halaman ini menampilkan kumpulan materi kuliah dalam bentuk <strong>versi web</strong> agar lebih nyaman dibaca langsung dari browser. Jika ingin menyimpan file aslinya, gunakan tombol <strong>Download PDF</strong> yang tersedia pada setiap materi.</p>
    </section>

    <div class="materi-grid">
        <?php foreach ($materi as $item): ?>
            <article class="materi-card">
                <div class="materi-card-top">
                    <span class="materi-chip"><?= esc($item['label']); ?></span>
                </div>

                <h2 class="materi-title">
                    <a href="<?= base_url('/artikel/materi/' . $item['slug']); ?>" class="materi-title-link">
                        <?= esc($item['judul']); ?>
                    </a>
                </h2>

                <p class="materi-desc"><?= esc($item['deskripsi']); ?></p>
                <p class="materi-file">File PDF tersedia: <?= esc($item['filename']); ?></p>

                <div class="materi-actions">
                    <a href="<?= base_url('/artikel/materi/' . $item['slug']); ?>" class="btn btn-primary">Baca via Web</a>
                    <a href="<?= base_url('/artikel/download/' . $item['slug']); ?>" class="btn btn-secondary">Download PDF</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>

    <hr class="divider" />
<?php endif; ?>

<section class="kategori-filter-section" id="daftar-artikel-web">
    <h2 class="section-subtitle">Daftar Artikel</h2>
    <p class="section-note">Bagian ini menampilkan artikel dari database beserta kategori yang berelasi. Kamu juga bisa memfilter artikel berdasarkan kategori tertentu.</p>

    <?php if (! empty($kategoriList)): ?>
        <div class="kategori-filter-list">
            <a href="<?= base_url('/artikel'); ?>#daftar-artikel-web" class="kategori-filter-chip <?= empty($kategoriAktif) ? 'active' : ''; ?>">Semua</a>
            <?php foreach ($kategoriList as $kategori): ?>
                <a href="<?= base_url('/artikel?kategori=' . $kategori['slug_kategori']); ?>#daftar-artikel-web" class="kategori-filter-chip <?= ($kategoriAktif === $kategori['slug_kategori']) ? 'active' : ''; ?>">
                    <?= esc($kategori['nama_kategori']); ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php if ($artikel): ?>
    <?php foreach ($artikel as $row): ?>
        <article class="entry article-card">
            <div class="article-card-header">
                <h2><a href="<?= base_url('/artikel/' . $row['slug']); ?>"><?= esc($row['judul']); ?></a></h2>
                <span class="article-category-badge"><?= esc($row['nama_kategori'] ?: 'Tanpa Kategori'); ?></span>
            </div>

            <?php if (! empty($row['gambar'])): ?>
                <img src="<?= base_url('/gambar/' . $row['gambar']); ?>" alt="<?= esc($row['judul']); ?>">
            <?php endif; ?>

            <p class="artikel-kategori-text">Kategori: <strong><?= esc($row['nama_kategori'] ?: 'Tanpa Kategori'); ?></strong></p>
            <p><?= esc(substr($row['isi'], 0, 200)); ?></p>
        </article>
        <hr class="divider" />
    <?php endforeach; ?>
<?php else: ?>
    <article class="entry">
        <h2>Belum ada data artikel pada kategori ini.</h2>
    </article>
<?php endif; ?>
<?= $this->endSection() ?>
