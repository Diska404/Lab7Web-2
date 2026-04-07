<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>
<h2><?= esc($title); ?></h2>
<hr>
<form action="" method="post">
    <p>
        <input type="text" name="judul" placeholder="Ketik Judul Artikel" style="width: 100%; padding: 8px;" value="<?= old('judul'); ?>">
    </p>
    <p>
        <textarea name="isi" cols="50" rows="10" placeholder="Ketik isi artikel di sini..." style="width: 100%; padding: 8px;"><?= old('isi'); ?></textarea>
    </p>
    <p>
        <input type="submit" value="Kirim" class="btn btn-large">
    </p>
</form>
<?= $this->endSection(); ?>
