<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h1><?= esc($title); ?></h1>
<hr>

<?php if ($artikel): ?>
    <?php foreach ($artikel as $row): ?>
        <article class="entry">
            <h2><a href="<?= base_url('/artikel/' . $row['slug']); ?>"><?= esc($row['judul']); ?></a></h2>
            <?php if (! empty($row['gambar'])): ?>
                <img src="<?= base_url('/gambar/' . $row['gambar']); ?>" alt="<?= esc($row['judul']); ?>">
            <?php endif; ?>
            <p><?= esc(substr($row['isi'], 0, 200)); ?></p>
        </article>
        <hr class="divider" />
    <?php endforeach; ?>
<?php else: ?>
    <article class="entry">
        <h2>Belum ada data.</h2>
    </article>
<?php endif; ?>
<?= $this->endSection() ?>
