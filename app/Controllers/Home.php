<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('home', [
            'title'   => 'Halaman Home',
            'content' => 'Ini adalah halaman utama website praktikum CodeIgniter 4.'
        ]);
    }
}
