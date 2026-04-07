<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>
<h2><?= esc($title); ?></h2>
<hr>
<form action="" method="post">
    <p>
        <input type="text" name="judul" value="<?= esc($data['judul']); ?>" style="width: 100%; padding: 8px;">
    </p>
    <p>
        <textarea name="isi" cols="50" rows="10" style="width: 100%; padding: 8px;"><?= esc($data['isi']); ?></textarea>
    </p>
    <p>
        <input type="submit" value="Kirim" class="btn btn-large">
    </p>
</form>
<?= $this->endSection(); ?>
