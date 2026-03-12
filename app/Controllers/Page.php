<?php

namespace App\Controllers;

class Page extends BaseController
{
    public function about()
    {
        return view('about', [
            'title' => 'Halaman About',
            'content' => 'Ini adalah halaman about yang menjelaskan tentang isi halaman ini.'
        ]);
    }

    public function contact()
    {
        return view('contact', [
            'title' => 'Halaman Contact',
            'content' => 'Ini adalah halaman kontak. Silakan hubungi kami di sini.'
        ]);
    }

    public function faqs()
    {
        echo "Ini halaman FAQ";
    }
    
    public function tos()
    {
        echo "ini halaman Term of Services";
    }

    public function artikel()
    {
        return view('artikel', [
            'title' => 'Halaman Artikel',
            'content' => 'Ini adalah halaman yang berisi daftar artikel terbaru.'
        ]);
    }
}