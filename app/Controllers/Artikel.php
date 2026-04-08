<?php

namespace App\Controllers;

use App\Models\ArtikelModel;

class Artikel extends BaseController
{
    public function index(): string
    {
        $title = 'Daftar Artikel';
        $model = new ArtikelModel();
        $artikel = $model->findAll();

        return view('artikel/index', compact('artikel', 'title'));
    }

    public function view($slug): string
    {
        $model = new ArtikelModel();
        $artikel = $model->where(['slug' => $slug])->first();

        if (! $artikel) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $title = $artikel['judul'];
        return view('artikel/detail', compact('artikel', 'title'));
    }

    public function admin_index(): string
    {
        $title = 'Daftar Artikel';
        $model = new ArtikelModel();
        $artikel = $model->findAll();

        return view('artikel/admin_index', compact('artikel', 'title'));
    }

    public function add()
    {
        helper(['form']);

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'judul' => 'required|min_length[3]',
                'isi'   => 'required|min_length[10]',
            ];

            $data = [
                'judul' => $this->request->getPost('judul'),
                'isi'   => $this->request->getPost('isi'),
            ];

            if (! $this->validateData($data, $rules)) {
                return view('artikel/form_add', [
                    'title'      => 'Tambah Artikel',
                    'validation' => $this->validator,
                ]);
            }

            $artikel = new ArtikelModel();
            $artikel->insert([
                'judul' => $data['judul'],
                'isi'   => $data['isi'],
                'slug'  => url_title($data['judul'], '-', true),
            ]);

            session()->setFlashdata('success', 'Artikel berhasil ditambahkan.');
            return redirect()->to('/admin/artikel');
        }

        return view('artikel/form_add', ['title' => 'Tambah Artikel']);
    }

    public function edit($id)
    {
        helper(['form']);

        $artikelModel = new ArtikelModel();
        $data = $artikelModel->where('id', $id)->first();

        if (! $data) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'judul' => 'required|min_length[3]',
                'isi'   => 'required|min_length[10]',
            ];

            $payload = [
                'judul' => $this->request->getPost('judul'),
                'isi'   => $this->request->getPost('isi'),
            ];

            if (! $this->validateData($payload, $rules)) {
                return view('artikel/form_edit', [
                    'title'      => 'Edit Artikel',
                    'data'       => $data,
                    'validation' => $this->validator,
                ]);
            }

            $artikelModel->update($id, [
                'judul' => $payload['judul'],
                'isi'   => $payload['isi'],
                'slug'  => url_title($payload['judul'], '-', true),
            ]);

            session()->setFlashdata('success', 'Artikel berhasil diperbarui.');
            return redirect()->to('/admin/artikel');
        }

        return view('artikel/form_edit', [
            'title' => 'Edit Artikel',
            'data'  => $data,
        ]);
    }

    public function delete($id)
    {
        $artikel = new ArtikelModel();
        $artikel->delete($id);
        session()->setFlashdata('success', 'Artikel berhasil dihapus.');
        return redirect()->to('/admin/artikel');
    }
}
