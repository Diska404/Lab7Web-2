<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h2><?= esc($title); ?></h2>

<?php if (isset($validation)): ?>
    <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
<?php endif; ?>

<form action="" method="post">
    <?= csrf_field() ?>
    <div class="form-group">
        <label for="judul">Judul Artikel</label>
        <input id="judul" type="text" name="judul" value="<?= old('judul') ?>" placeholder="Ketik Judul Artikel">
    </div>
    <div class="form-group">
        <label for="isi">Isi Artikel</label>
        <textarea id="isi" name="isi" cols="50" rows="10" placeholder="Ketik isi artikel di sini..."><?= old('isi') ?></textarea>
    </div>
    <div class="form-group">
        <label for="id_kategori">Kategori</label>
        <select id="id_kategori" name="id_kategori" required>
            <option value="">Pilih Kategori</option>
            <?php foreach ($kategori as $k): ?>
                <option value="<?= esc((string) $k['id_kategori']); ?>" <?= old('id_kategori') == $k['id_kategori'] ? 'selected' : ''; ?>>
                    <?= esc($k['nama_kategori']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <p><button type="submit" class="btn btn-primary">Kirim</button></p>
</form>
<?= $this->endSection() ?>
