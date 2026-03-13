<?= $this->include('template/header'); ?>

<h2><?= $title; ?></h2>
<form action="" method="post">
    <p>
        <input type="text" name="judul" value="<?= $data['judul'];?>" style="width: 100%; padding: 8px;">
    </p>
    <p>
        <textarea name="isi" cols="50" rows="10" style="width: 100%; padding: 8px;"><?= $data['isi']; ?></textarea>
    </p>
    <p>
        <input type="submit" value="Kirim" class="btn btn-large">
    </p>
</form>

<?= $this->include('template/footer'); ?>