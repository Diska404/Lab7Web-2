<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php
$jumlahMateri = is_countable($materi ?? []) ? count($materi) : 0;
$jumlahArtikel = is_countable($artikel ?? []) ? count($artikel) : 0;
$jumlahKategori = is_countable($kategoriList ?? []) ? count($kategoriList) : 0;
$materiTipeAktif = $materiTipeAktif ?? '';
$materiKategoriAktif = $materiKategoriAktif ?? '';

$materiFilterUrl = static function (array $params = []): string {
    $params = array_filter($params, static fn ($value) => $value !== '' && $value !== null);
    $query = http_build_query($params);

    return base_url('/artikel' . ($query !== '' ? '?' . $query : '')) . '#materi-kuliah-web';
};

$judulMateriSection = 'Materi belajar versi web';
$deskripsiMateriSection = 'Daftar materi ini sudah diurutkan rapi. Gunakan shortcut Pertemuan atau Praktikum agar materi lebih mudah ditemukan.';

if ($materiTipeAktif === 'pertemuan') {
    $judulMateriSection = 'Materi pertemuan kuliah';
    $deskripsiMateriSection = 'Kumpulan materi pertemuan diurutkan dari pertemuan awal sampai materi terbaru.';
} elseif ($materiTipeAktif === 'praktikum') {
    $judulMateriSection = 'Materi praktikum';
    $deskripsiMateriSection = 'Kumpulan modul praktikum diurutkan dari praktikum awal sampai praktikum terbaru.';
}
?>

<section class="content-hero article-landing-hero">
    <div class="hero-copy">
        <span class="hero-eyebrow">Portal Pembelajaran</span>
        <h1>Pusat Artikel & Materi Pemrograman Web</h1>
        <p>Jelajahi materi kuliah, modul praktikum, dan artikel pembelajaran dalam satu halaman yang lebih rapi, mudah dicari, dan nyaman dibaca.</p>
        <div class="hero-actions">
            <a href="#materi-kuliah-web" class="btn btn-primary">Lihat Materi</a>
            <a href="#daftar-artikel-web" class="btn btn-secondary">Lihat Artikel</a>
        </div>
    </div>
    <div class="hero-stats" aria-label="Ringkasan konten">
        <div class="stat-card">
            <strong><?= esc((string) $jumlahMateri); ?></strong>
            <span>Materi</span>
        </div>
        <div class="stat-card">
            <strong><?= esc((string) $jumlahArtikel); ?></strong>
            <span>Artikel</span>
        </div>
        <div class="stat-card">
            <strong><?= esc((string) $jumlahKategori); ?></strong>
            <span>Kategori</span>
        </div>
    </div>
</section>

<section class="content-toolbar" aria-label="Pencarian konten">
    <div>
        <span class="toolbar-label">Cari cepat</span>
        <h2>Temukan materi atau artikel</h2>
    </div>
    <div class="search-control">
        <input type="search" id="contentSearch" placeholder="Cari judul, kategori, atau topik..." autocomplete="off">
        <button type="button" id="clearContentSearch" class="btn btn-secondary">Reset</button>
    </div>
</section>

<?php if (! empty($materi)): ?>
    <section class="content-section" id="materi-kuliah-web">
        <div class="section-heading-row">
            <div>
                <span class="section-kicker">Materi Kuliah PDF</span>
                <h2><?= esc($judulMateriSection); ?></h2>
                <p><?= esc($deskripsiMateriSection); ?> Jika file PDF sudah dipindahkan ke folder <code>file</code>, tombol unduh akan aktif.</p>
            </div>
        </div>

        <div class="materi-filter-panel" aria-label="Filter jenis materi">
            <div class="filter-row filter-row-primary">
                <a href="<?= $materiFilterUrl(); ?>" class="kategori-filter-chip filter-chip-main <?= empty($materiTipeAktif) && empty($materiKategoriAktif) ? 'active' : ''; ?>">Semua Materi</a>
                <a href="<?= $materiFilterUrl(['materi_tipe' => 'pertemuan']); ?>" class="kategori-filter-chip filter-chip-main <?= $materiTipeAktif === 'pertemuan' ? 'active' : ''; ?>">Materi Pertemuan</a>
                <a href="<?= $materiFilterUrl(['materi_tipe' => 'praktikum']); ?>" class="kategori-filter-chip filter-chip-main <?= $materiTipeAktif === 'praktikum' ? 'active' : ''; ?>">Materi Praktikum</a>
            </div>

            <?php if (! empty($materiKategoriList)): ?>
                <div class="filter-row filter-row-secondary">
                    <span class="filter-row-caption">Shortcut:</span>
                    <a href="<?= $materiFilterUrl(['materi_tipe' => $materiTipeAktif]); ?>" class="kategori-filter-chip filter-chip-sub <?= empty($materiKategoriAktif) ? 'active' : ''; ?>">
                        <?= $materiTipeAktif === 'pertemuan' ? 'Semua Pertemuan' : ($materiTipeAktif === 'praktikum' ? 'Semua Praktikum' : 'Semua Label'); ?>
                    </a>
                    <?php foreach ($materiKategoriList as $slug => $label): ?>
                        <a href="<?= $materiFilterUrl(['materi_tipe' => $materiTipeAktif, 'materi_kategori' => $slug]); ?>" class="kategori-filter-chip filter-chip-sub <?= ($materiKategoriAktif === $slug) ? 'active' : ''; ?>">
                            <?= esc($label); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="materi-grid modern-card-grid" data-search-group="content">
            <?php foreach ($materi as $item): ?>
                <?php
                $searchText = strtolower(($item['label'] ?? '') . ' ' . ($item['judul'] ?? '') . ' ' . ($item['deskripsi'] ?? '') . ' ' . ($item['filename'] ?? ''));
                ?>
                <article class="materi-card modern-materi-card searchable-card" data-search-text="<?= esc($searchText); ?>">
                    <div class="card-topline">
                        <span class="materi-chip"><?= esc($item['label']); ?></span>
                        <?= ! empty($item['available']) ? '<span class="file-status ok">PDF tersedia</span>' : '<span class="file-status missing">PDF belum ada</span>'; ?>
                    </div>

                    <h3 class="materi-title">
                        <a href="<?= base_url('/artikel/materi/' . $item['slug']); ?>" class="materi-title-link">
                            <?= esc($item['judul']); ?>
                        </a>
                    </h3>

                    <p class="materi-desc clamp-3"><?= esc($item['deskripsi']); ?></p>
                    <p class="materi-file">File: <span><?= esc($item['filename']); ?></span></p>

                    <div class="materi-actions">
                        <a href="<?= base_url('/artikel/materi/' . $item['slug']); ?>" class="btn btn-primary">Buka Materi</a>
                        <?php if (! empty($item['available'])): ?>
                            <a href="<?= base_url('/artikel/download/' . $item['slug']); ?>" class="btn btn-secondary">Unduh PDF</a>
                        <?php else: ?>
                            <span class="btn btn-disabled">PDF belum dipindahkan</span>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

<section class="content-section" id="daftar-artikel-web">
    <div class="section-heading-row">
        <div>
            <span class="section-kicker">Artikel Web</span>
            <h2>Daftar artikel pembelajaran</h2>
            <p>Artikel dari database ditampilkan bersama kategori yang berelasi. Gunakan filter kategori atau pencarian untuk menemukan konten tertentu.</p>
        </div>
    </div>

    <?php if (! empty($kategoriList)): ?>
        <div class="kategori-filter-list modern-filter">
            <a href="<?= base_url('/artikel'); ?>#daftar-artikel-web" class="kategori-filter-chip <?= empty($kategoriAktif) ? 'active' : ''; ?>">Semua Artikel</a>
            <?php foreach ($kategoriList as $kategori): ?>
                <a href="<?= base_url('/artikel?kategori=' . $kategori['slug_kategori']); ?>#daftar-artikel-web" class="kategori-filter-chip <?= ($kategoriAktif === $kategori['slug_kategori']) ? 'active' : ''; ?>">
                    <?= esc($kategori['nama_kategori']); ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($artikel): ?>
        <div class="article-list-modern" data-search-group="content">
            <?php foreach ($artikel as $row): ?>
                <?php
                $kategoriArtikel = $row['nama_kategori'] ?: 'Tanpa Kategori';
                $searchText = strtolower(($row['judul'] ?? '') . ' ' . ($row['isi'] ?? '') . ' ' . $kategoriArtikel);
                $ringkasan = trim(strip_tags((string) ($row['isi'] ?? '')));
                if (strlen($ringkasan) > 170) {
                    $ringkasan = substr($ringkasan, 0, 170) . '...';
                }
                ?>
                <article class="article-card article-card-modern searchable-card" data-search-text="<?= esc($searchText); ?>">
                    <a href="<?= base_url('/artikel/' . $row['slug']); ?>" class="article-thumb" aria-label="Buka artikel <?= esc($row['judul']); ?>">
                        <?php if (! empty($row['gambar'])): ?>
                            <img src="<?= base_url('/gambar/' . $row['gambar']); ?>" alt="<?= esc($row['judul']); ?>">
                        <?php else: ?>
                            <span><?= esc(strtoupper(substr((string) $row['judul'], 0, 1))); ?></span>
                        <?php endif; ?>
                    </a>

                    <div class="article-card-body">
                        <div class="article-meta-row">
                            <span class="article-category-badge"><?= esc($kategoriArtikel); ?></span>
                            <span class="article-meta-text">Artikel Pembelajaran</span>
                        </div>

                        <h3>
                            <a href="<?= base_url('/artikel/' . $row['slug']); ?>"><?= esc($row['judul']); ?></a>
                        </h3>
                        <p class="article-excerpt"><?= esc($ringkasan !== '' ? $ringkasan : 'Belum ada ringkasan artikel.'); ?></p>

                        <div class="article-card-footer">
                            <a href="<?= base_url('/artikel/' . $row['slug']); ?>" class="read-more-link">Baca Selengkapnya</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <article class="empty-state-card">
            <h3>Belum ada data artikel pada kategori ini.</h3>
            <p>Silakan tambahkan artikel melalui dashboard atau pilih kategori lain.</p>
        </article>
    <?php endif; ?>
</section>

<div id="noSearchResult" class="empty-state-card" hidden>
    <h3>Konten tidak ditemukan</h3>
    <p>Coba gunakan kata kunci lain atau reset pencarian.</p>
</div>

<script>
(function () {
    const input = document.getElementById('contentSearch');
    const reset = document.getElementById('clearContentSearch');
    const cards = Array.from(document.querySelectorAll('.searchable-card'));
    const empty = document.getElementById('noSearchResult');

    function filterCards() {
        const keyword = (input.value || '').toLowerCase().trim();
        let visibleCount = 0;

        cards.forEach(function (card) {
            const text = (card.getAttribute('data-search-text') || '').toLowerCase();
            const match = keyword === '' || text.includes(keyword);
            card.hidden = !match;
            if (match) visibleCount++;
        });

        if (empty) {
            empty.hidden = keyword === '' || visibleCount > 0;
        }
    }

    if (input) {
        input.addEventListener('input', filterCards);
    }

    if (reset) {
        reset.addEventListener('click', function () {
            input.value = '';
            filterCards();
            input.focus();
        });
    }
})();
</script>
<?= $this->endSection() ?>
