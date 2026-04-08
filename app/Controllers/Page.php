<?php

namespace App\Controllers;

class Page extends BaseController
{
    public function about(): string
    {
        return view('about', [
            'title'   => 'Halaman About',
            'content' => 'Halaman ini berisi informasi singkat mengenai website praktikum yang dibangun menggunakan CodeIgniter 4.',
        ]);
    }

    public function contact(): string
    {
        return view('contact', [
            'title'   => 'Halaman Contact',
            'content' => 'Silakan hubungi kami melalui email atau media lain yang tersedia untuk pertanyaan dan masukan.',
        ]);
    }

    public function faqs()
    {
        return 'Ini halaman FAQ';
    }

    public function tos()
    {
        return 'Ini halaman Term of Services';
    }
}
