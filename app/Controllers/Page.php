<?php

namespace App\Controllers;

class Page extends BaseController
{
    public function about(): string
    {
        return view('about', [
            'title'   => 'Halaman About',
            'content' => 'Ini adalah halaman about yang menjelaskan tentang isi halaman ini.'
        ]);
    }

    public function contact(): string
    {
        return view('contact', [
            'title'   => 'Halaman Contact',
            'content' => 'Ini adalah halaman kontak. Silakan hubungi kami di sini.'
        ]);
    }

    public function faqs(): void
    {
        echo 'Ini halaman FAQ';
    }

    public function tos(): void
    {
        echo 'Ini halaman Term of Services';
    }

    public function artikel(): string
    {
        return view('artikel', [
            'title'   => 'Halaman Artikel',
            'content' => 'Ini adalah halaman yang berisi daftar artikel terbaru.'
        ]);
    }
}
