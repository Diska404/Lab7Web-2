<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>
<h1><?= esc($title); ?></h1>
<hr>
<p>
    Selamat datang, <strong><?= esc(session()->get('user_name') ?? 'Admin'); ?></strong>
    | <a href="<?= base_url('/user/logout'); ?>">Logout</a>
</p>
<p>
    <a class="btn btn-primary" href="<?= base_url('/admin/artikel/add'); ?>">Tambah Artikel</a>
</p>

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
                    <td><?= esc($row['id']); ?></td>
                    <td>
                        <b><?= esc($row['judul']); ?></b>
                        <p><small><?= esc(substr(strip_tags($row['isi']), 0, 50)); ?></small></p>
                    </td>
                    <td><?= esc($row['status'] ?? ''); ?></td>
                    <td>
                        <a class="btn" href="<?= base_url('/admin/artikel/edit/' . $row['id']); ?>">Ubah</a>
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
<?= $this->endSection(); ?>
