<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('home', [
            'title'   => 'Halaman Home',
            'content' => 'Merupakan Halaman Utama Dari Halaman Home.',
        ]);
    }
}
