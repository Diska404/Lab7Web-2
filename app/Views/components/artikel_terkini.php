<h3 class="title">Artikel Terkini</h3>
<ul class="latest-article-list">
    <?php if (! empty($artikel)): ?>
        <?php foreach ($artikel as $row): ?>
            <li>
                <a href="<?= base_url('/artikel/' . $row['slug']) ?>">
                    <span class="latest-dot"></span>
                    <span><?= esc($row['judul']) ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    <?php else: ?>
        <li class="empty-widget-state">Belum ada artikel.</li>
    <?php endif; ?>
</ul>
