<?php

namespace App\Controllers;

use App\Models\ArtikelModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Artikel extends BaseController
{
    public function index(): string
    {
        $title = 'Daftar Artikel';
        $model = new ArtikelModel();
        $artikel = $model->orderBy('created_at', 'DESC')->findAll();

        return view('artikel/index', compact('artikel', 'title'));
    }

    public function view(string $slug): string
    {
        $model = new ArtikelModel();
        $artikel = $model->where(['slug' => $slug])->first();

        if (!$artikel) {
            throw PageNotFoundException::forPageNotFound();
        }

        $title = $artikel['judul'];
        return view('artikel/detail', compact('artikel', 'title'));
    }

    public function admin_index(): string
    {
        $title = 'Daftar Artikel';
        $model = new ArtikelModel();
        $artikel = $model->orderBy('created_at', 'DESC')->findAll();

        return view('artikel/admin_index', compact('artikel', 'title'));
    }

    public function add()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'judul' => 'required',
            'isi'   => 'required',
        ]);

        $isDataValid = $validation->withRequest($this->request)->run();

        if ($isDataValid) {
            $artikel = new ArtikelModel();
            $judul = $this->request->getPost('judul');

            $artikel->insert([
                'judul'  => $judul,
                'isi'    => $this->request->getPost('isi'),
                'slug'   => url_title($judul, '-', true),
                'status' => 1,
            ]);

            return redirect()->to(base_url('/admin/artikel'));
        }

        $title = 'Tambah Artikel';
        return view('artikel/form_add', compact('title'));
    }

    public function edit($id)
    {
        $artikel = new ArtikelModel();

        $validation = \Config\Services::validation();
        $validation->setRules([
            'judul' => 'required',
            'isi'   => 'required',
        ]);

        $isDataValid = $validation->withRequest($this->request)->run();

        if ($isDataValid) {
            $judul = $this->request->getPost('judul');

            $artikel->update($id, [
                'judul' => $judul,
                'isi'   => $this->request->getPost('isi'),
                'slug'  => url_title($judul, '-', true),
            ]);

            return redirect()->to(base_url('/admin/artikel'));
        }

        $data = $artikel->where('id', $id)->first();
        $title = 'Edit Artikel';

        return view('artikel/form_edit', compact('title', 'data'));
    }

    public function delete($id)
    {
        $artikel = new ArtikelModel();
        $artikel->delete($id);

        return redirect()->to(base_url('/admin/artikel'));
    }
}
