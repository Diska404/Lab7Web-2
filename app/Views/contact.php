<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="contact-hero">
    <span class="contact-badge">Kontak</span>
    <h1><?= esc($title); ?></h1>
    <hr>
    <p class="contact-lead"><?= esc($content); ?></p>
</div>

<div class="contact-grid">
    <div class="contact-card contact-card-primary">
        <div class="contact-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" role="img" aria-hidden="true">
                <path fill="#EA4335" d="M3.75 6.75A2.25 2.25 0 0 1 6 4.5h12a2.25 2.25 0 0 1 2.25 2.25v.39L12 13.11 3.75 7.14v-.39Z"/>
                <path fill="#34A853" d="M20.25 7.14v10.11A2.25 2.25 0 0 1 18 19.5h-1.09V9.7l3.34-2.56Z"/>
                <path fill="#4285F4" d="M7.09 9.7v9.8H6a2.25 2.25 0 0 1-2.25-2.25V7.14L7.09 9.7Z"/>
                <path fill="#FBBC05" d="M16.91 9.7v9.8H7.09V9.7L12 13.33l4.91-3.63Z"/>
            </svg>
        </div>
        <div class="contact-card-body">
            <h2>Email Utama</h2>
            <p>Hubungi saya melalui email untuk pertanyaan, masukan, atau diskusi terkait tugas praktikum ini.</p>
            <a class="contact-email-link" href="mailto:diskakurniaputra@gmail.com">diskakurniaputra@gmail.com</a>
            <div class="contact-actions">
                <a class="btn contact-btn" href="mailto:diskakurniaputra@gmail.com">Kirim Email</a>
                <a class="btn btn-secondary contact-btn" href="https://mail.google.com/mail/?view=cm&amp;fs=1&amp;to=diskakurniaputra@gmail.com" target="_blank" rel="noopener noreferrer">Buka Gmail</a>
            </div>
        </div>
    </div>

    <div class="contact-card contact-card-soft">
        <h3>Info Kontak</h3>
        <ul class="contact-list">
            <li>Alamat email Masih Aktif.</li>
        </ul>
    </div>
</div>
<?= $this->endSection() ?>
