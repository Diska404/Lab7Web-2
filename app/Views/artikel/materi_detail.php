<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="materi-reader-header">
    <span class="materi-badge"><?= esc($materi['label']); ?></span>
    <h1><?= esc($materi['judul']); ?></h1>
    <p class="materi-reader-desc"><?= esc($materi['deskripsi']); ?></p>
</div>

<div class="materi-reader-actions">
    <a href="<?= base_url('/artikel'); ?>" class="btn btn-secondary">Kembali ke Daftar Materi</a>
    <a href="<?= base_url('/artikel/download/' . $materi['slug']); ?>" class="btn">Download PDF</a>
</div>

<section class="materi-reader-card">
    <h2>Ringkasan Materi</h2>
    <p><?= esc($materi['ringkasan']); ?></p>
</section>

<?php if (! empty($materi['sections'])): ?>
    <?php foreach ($materi['sections'] as $section): ?>
        <section class="materi-reader-card">
            <h2><?= esc($section['heading']); ?></h2>

            <?php if (! empty($section['paragraphs'])): ?>
                <?php foreach ($section['paragraphs'] as $paragraph): ?>
                    <p><?= esc($paragraph); ?></p>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (! empty($section['points'])): ?>
                <ul class="materi-reader-list">
                    <?php foreach ($section['points'] as $point): ?>
                        <li><?= esc($point); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>
    <?php endforeach; ?>
<?php endif; ?>

<section class="materi-reader-card materi-reader-note">
    <h2>Catatan</h2>
    <p>Versi ini disediakan agar materi dapat dibaca langsung melalui web tanpa harus membuka PDF. Jika kamu ingin melihat dokumen aslinya, gunakan tombol <strong>Download PDF</strong> di atas.</p>
</section>
<?= $this->endSection() ?>
