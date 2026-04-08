<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h1><?= esc($title); ?></h1>
<hr>
<p>Selamat datang, <strong><?= esc((string) session()->get('user_name')); ?></strong> | <a href="<?= base_url('/user/logout'); ?>">Logout</a></p>
<p><a class="btn" href="<?= base_url('/admin/artikel/add'); ?>">Tambah Artikel</a></p>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Judul</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($artikel): ?>
            <?php foreach ($artikel as $row): ?>
                <tr>
                    <td><?= esc((string) $row['id']); ?></td>
                    <td>
                        <b><?= esc($row['judul']); ?></b>
                        <p><small><?= esc(substr($row['isi'], 0, 50)); ?></small></p>
                    </td>
                    <td><?= esc((string) ($row['status'] ?? 0)); ?></td>
                    <td>
                        <a class="btn btn-secondary" href="<?= base_url('/admin/artikel/edit/' . $row['id']); ?>">Ubah</a>
                        <a class="btn btn-danger" onclick="return confirm('Yakin menghapus data?');" href="<?= base_url('/admin/artikel/delete/' . $row['id']); ?>">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">Belum ada data.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?= $this->endSection() ?>
