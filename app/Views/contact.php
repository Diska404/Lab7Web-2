<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<small>Kontak</small>
<h1>Halaman Contact</h1>
<hr>
<p>Silakan hubungi kami melalui email atau media lain yang tersedia untuk pertanyaan dan masukan.</p>

<div class="contact-email-card">
    <div class="contact-gmail-icon" aria-hidden="true">
        <svg viewBox="0 0 64 64" role="img" aria-label="Gmail Icon">
            <path fill="#EA4335" d="M54 16H10c-2.2 0-4 1.8-4 4v24c0 2.2 1.8 4 4 4h44c2.2 0 4-1.8 4-4V20c0-2.2-1.8-4-4-4z"/>
            <path fill="#FFFFFF" d="M54 20v4L32 39 10 24v-4l22 15 22-15z"/>
            <path fill="#FBBC05" d="M10 24v20h12V32z"/>
            <path fill="#34A853" d="M54 24v20H42V32z"/>
            <path fill="#4285F4" d="M10 20l22 15L54 20v-4l-22 15L10 16z"/>
        </svg>
    </div>

    <div class="contact-email-content">
        <h2>Email Kontak</h2>
        <p>Untuk pertanyaan seputar website praktikum, saran, atau masukan, silakan hubungi melalui email berikut:</p>
        <a href="mailto:diskakurniaputra@gmail.com">diskakurniaputra@gmail.com</a>
    </div>
</div>
<?= $this->endSection() ?>
