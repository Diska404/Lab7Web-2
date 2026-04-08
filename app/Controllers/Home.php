<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('home', [
            'title'   => 'Halaman Home',
            'content' => 'Selamat datang di website praktikum Web 2. Silakan jelajahi artikel, pelajari halaman lain, atau masuk ke panel admin untuk mengelola konten.',
        ]);
    }
}
