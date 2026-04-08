<h3>Artikel Terkini</h3>
<ul>
    <?php if (! empty($artikel)): ?>
        <?php foreach ($artikel as $row): ?>
            <li>
                <a href="<?= base_url('/artikel/' . $row['slug']) ?>"><?= esc($row['judul']) ?></a>
            </li>
        <?php endforeach; ?>
    <?php else: ?>
        <li>Belum ada artikel.</li>
    <?php endif; ?>
</ul>
