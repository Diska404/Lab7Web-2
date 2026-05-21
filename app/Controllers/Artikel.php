<?php

namespace App\Controllers;

use App\Models\ArtikelModel;
use App\Models\KategoriModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Files\UploadedFile;
use RuntimeException;

class Artikel extends BaseController
{
    private const MATERI_DIR = 'file';
    private const IMAGE_DIR = 'gambar';

    public function index(): string
    {
        $title = 'Daftar Artikel';
        $kategoriSlug = trim((string) ($this->request->getVar('kategori') ?? ''));
        $materiKategori = trim((string) ($this->request->getVar('materi_kategori') ?? ''));
        $materiTipe = trim((string) ($this->request->getVar('materi_tipe') ?? ''));

        if (! in_array($materiTipe, ['pertemuan', 'praktikum'], true)) {
            $materiTipe = '';
        }

        $model = new ArtikelModel();
        $kategoriModel = new KategoriModel();

        $artikel = $model->getArtikelDenganKategori($kategoriSlug);
        $kategoriList = $kategoriModel->orderBy('nama_kategori', 'ASC')->findAll();

        $materi = $this->getMateriList();

        if ($materiTipe !== '') {
            $materi = array_values(array_filter($materi, function ($item) use ($materiTipe) {
                return $this->getMateriType($item['label'] ?? '') === $materiTipe;
            }));
        }

        $materiKategoriList = $this->buildMateriKategoriList($materi);

        if ($materiKategori !== '') {
            $materi = array_values(array_filter($materi, static function ($item) use ($materiKategori) {
                return url_title($item['label'], '-', true) === $materiKategori;
            }));
        }

        return view('artikel/index', [
            'title'               => $title,
            'artikel'             => $artikel,
            'materi'              => $materi,
            'kategoriList'        => $kategoriList,
            'kategoriAktif'       => $kategoriSlug,
            'materiKategoriList'  => $materiKategoriList,
            'materiKategoriAktif' => $materiKategori,
            'materiTipeAktif'     => $materiTipe,
        ]);
    }

    public function view($slug): string
    {
        $model = new ArtikelModel();
        $artikel = $model->getArtikelBySlugDenganKategori($slug);

        if (! $artikel) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('artikel/detail', [
            'title'   => $artikel['judul'],
            'artikel' => $artikel,
        ]);
    }

    public function materi($slug): string
    {
        $materi = $this->findMateri($slug);

        if (! $materi) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('artikel/materi_detail', [
            'title'  => $materi['judul'],
            'materi' => $materi,
        ]);
    }

    public function downloadMateri($slug)
    {
        $materi = $this->findMateri($slug);

        if (! $materi) {
            throw PageNotFoundException::forPageNotFound();
        }

        $path = $this->materiPath($materi['filename']);

        if (! is_file($path)) {
            session()->setFlashdata('error', 'File PDF belum tersedia. Pindahkan file "' . $materi['filename'] . '" ke folder /file terlebih dahulu.');
            return redirect()->to('/artikel/materi/' . $slug);
        }

        return $this->response->download($path, null);
    }

    public function admin_index(): string
    {
        $title = 'Daftar Artikel';
        $q = trim((string) ($this->request->getVar('q') ?? ''));
        $kategori_id = trim((string) ($this->request->getVar('kategori_id') ?? ''));

        $model = new ArtikelModel();
        $kategoriModel = new KategoriModel();

        $model->select('artikel.*, kategori.nama_kategori, kategori.slug_kategori')
            ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left');

        if ($q !== '') {
            $model->like('artikel.judul', $q);
        }

        if ($kategori_id !== '') {
            $model->where('artikel.id_kategori', (int) $kategori_id);
        }

        $artikel = $model->orderBy('artikel.id', 'DESC')->paginate(10);

        return view('artikel/admin_index', [
            'title'       => $title,
            'q'           => $q,
            'kategori_id' => $kategori_id,
            'kategori'    => $kategoriModel->orderBy('nama_kategori', 'ASC')->findAll(),
            'artikel'     => $artikel,
            'pager'       => $model->pager,
        ]);
    }

    public function add()
    {
        $kategoriModel = new KategoriModel();
        $kategori = $kategoriModel->orderBy('nama_kategori', 'ASC')->findAll();

        if (strtolower($this->request->getMethod()) === 'post') {
            $rules = [
                'judul'       => 'required|min_length[3]',
                'isi'         => 'required|min_length[10]',
                'id_kategori' => 'required|integer',
            ];

            $data = [
                'judul'       => $this->request->getPost('judul'),
                'isi'         => $this->request->getPost('isi'),
                'id_kategori' => $this->request->getPost('id_kategori'),
            ];

            if (! $this->validateData($data, $rules)) {
                return view('artikel/form_add', [
                    'title'      => 'Tambah Artikel',
                    'validation' => $this->validator,
                    'kategori'   => $kategori,
                ]);
            }

            try {
                $gambar = $this->uploadGambar($this->request->getFile('gambar'));
            } catch (RuntimeException $e) {
                return view('artikel/form_add', [
                    'title'       => 'Tambah Artikel',
                    'uploadError' => $e->getMessage(),
                    'kategori'    => $kategori,
                ]);
            }

            $payload = [
                'judul'       => $data['judul'],
                'isi'         => $data['isi'],
                'slug'        => url_title($data['judul'], '-', true),
                'id_kategori' => (int) $data['id_kategori'],
            ];

            if ($gambar !== null) {
                $payload['gambar'] = $gambar;
            }

            $artikel = new ArtikelModel();
            $artikel->insert($payload);

            session()->setFlashdata('success', 'Artikel berhasil ditambahkan.');
            return redirect()->to('/admin/artikel');
        }

        return view('artikel/form_add', [
            'title'    => 'Tambah Artikel',
            'kategori' => $kategori,
        ]);
    }

    public function edit($id)
    {
        $artikelModel = new ArtikelModel();
        $data = $artikelModel->find($id);

        if (! $data) {
            throw PageNotFoundException::forPageNotFound();
        }

        $kategoriModel = new KategoriModel();
        $kategori = $kategoriModel->orderBy('nama_kategori', 'ASC')->findAll();

        if (strtolower($this->request->getMethod()) === 'post') {
            $rules = [
                'judul'       => 'required|min_length[3]',
                'isi'         => 'required|min_length[10]',
                'id_kategori' => 'required|integer',
            ];

            $payload = [
                'judul'       => $this->request->getPost('judul'),
                'isi'         => $this->request->getPost('isi'),
                'id_kategori' => $this->request->getPost('id_kategori'),
            ];

            if (! $this->validateData($payload, $rules)) {
                return view('artikel/form_edit', [
                    'title'      => 'Edit Artikel',
                    'data'       => $data,
                    'kategori'   => $kategori,
                    'validation' => $this->validator,
                ]);
            }

            try {
                $gambar = $this->uploadGambar($this->request->getFile('gambar'));
            } catch (RuntimeException $e) {
                return view('artikel/form_edit', [
                    'title'       => 'Edit Artikel',
                    'data'        => $data,
                    'kategori'    => $kategori,
                    'uploadError' => $e->getMessage(),
                ]);
            }

            $update = [
                'judul'       => $payload['judul'],
                'isi'         => $payload['isi'],
                'slug'        => url_title($payload['judul'], '-', true),
                'id_kategori' => (int) $payload['id_kategori'],
            ];

            if ($gambar !== null) {
                $update['gambar'] = $gambar;
                $this->hapusGambarLama($data['gambar'] ?? null);
            }

            $artikelModel->update($id, $update);

            session()->setFlashdata('success', 'Artikel berhasil diperbarui.');
            return redirect()->to('/admin/artikel');
        }

        return view('artikel/form_edit', [
            'title'    => 'Edit Artikel',
            'data'     => $data,
            'kategori' => $kategori,
        ]);
    }

    public function delete($id)
    {
        $artikel = new ArtikelModel();
        $data = $artikel->find($id);

        if ($data) {
            $this->hapusGambarLama($data['gambar'] ?? null);
            $artikel->delete($id);
            session()->setFlashdata('success', 'Artikel berhasil dihapus.');
        }

        return redirect()->to('/admin/artikel');
    }

    private function uploadGambar(?UploadedFile $file): ?string
    {
        if (! $file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if (! $file->isValid()) {
            throw new RuntimeException($file->getErrorString());
        }

        $allowedMime = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (! in_array($file->getMimeType(), $allowedMime, true)) {
            throw new RuntimeException('File gambar harus berformat JPG, JPEG, PNG, GIF, atau WEBP.');
        }

        if ($file->getSizeByUnit('kb') > 2048) {
            throw new RuntimeException('Ukuran gambar maksimal 2 MB.');
        }

        $targetDir = FCPATH . self::IMAGE_DIR;
        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0775, true);
        }

        $newName = $file->getRandomName();
        $file->move($targetDir, $newName);

        return $newName;
    }

    private function hapusGambarLama(?string $filename): void
    {
        if (! $filename) {
            return;
        }

        $path = FCPATH . self::IMAGE_DIR . DIRECTORY_SEPARATOR . $filename;
        if (is_file($path)) {
            @unlink($path);
        }
    }

    private function findMateri(string $slug): ?array
    {
        foreach ($this->getMateriList() as $item) {
            if ($item['slug'] === $slug) {
                return $item;
            }
        }

        return null;
    }

    private function materiPath(string $filename): string
    {
        return ROOTPATH . self::MATERI_DIR . DIRECTORY_SEPARATOR . $filename;
    }

    private function getMateriList(): array
    {
        $items = [
            [
                'slug' => 'fondasi-codeigniter-4',
                'filename' => '01 CodeIgniter_4_Foundation.pdf',
                'judul' => 'Fondasi CodeIgniter 4',
                'label' => 'Pertemuan 1',
                'deskripsi' => 'Arsitektur server-side, framework, instalasi environment, dan gambaran semester Pemrograman Web 2.',
                'ringkasan' => 'Materi ini mengenalkan CodeIgniter 4 sebagai framework PHP untuk membangun aplikasi web terstruktur berbasis MVC.',
                'sections' => [
                    [
                        'heading' => 'Pokok Bahasan',
                        'paragraphs' => ['Request dari browser diproses oleh route, controller, model, dan view hingga menghasilkan respon HTML.'],
                        'points' => ['Server-side programming', 'MVC', 'instalasi environment', 'struktur project CodeIgniter 4'],
                    ],
                ],
            ],
            [
                'slug' => 'konsep-dasar-web-dinamis',
                'filename' => '01 Konsep Dasar Web Dinamis.pdf',
                'judul' => 'Konsep Dasar Web Dinamis',
                'label' => 'Pertemuan 1',
                'deskripsi' => 'Konsep web dinamis, client-side, server-side, database, web server, dan API.',
                'ringkasan' => 'Web dinamis dapat berubah berdasarkan interaksi pengguna dan data yang diproses di server.',
                'sections' => [[
                    'heading' => 'Pokok Bahasan',
                    'paragraphs' => ['Web dinamis memanfaatkan backend dan database untuk menghasilkan konten yang berubah sesuai kebutuhan pengguna.'],
                    'points' => ['HTML, CSS, JavaScript', 'PHP dan server-side scripting', 'database', 'web server'],
                ]],
            ],
            [
                'slug' => 'routing-essentials-codeigniter-4',
                'filename' => '02 CI4_Routing_Essentials.pdf',
                'judul' => 'Routing Essentials di CodeIgniter 4',
                'label' => 'Pertemuan 2',
                'deskripsi' => 'Konsep routing, endpoint, URL segment, static route, dynamic route, dan route grouping.',
                'ringkasan' => 'Routing menerjemahkan permintaan URL menjadi perintah menuju controller dan method yang sesuai.',
                'sections' => [[
                    'heading' => 'Pokok Bahasan',
                    'paragraphs' => ['Setiap URL diarahkan melalui file route agar request masuk ke controller yang tepat.'],
                    'points' => ['Routes.php', 'endpoint', 'dynamic route', 'route group'],
                ]],
            ],
            [
                'slug' => 'dasar-php-untuk-pemrograman-web',
                'filename' => '02 PHP Dasar.pdf',
                'judul' => 'Dasar PHP untuk Pemrograman Web',
                'label' => 'Pertemuan 2',
                'deskripsi' => 'Dasar PHP sebagai bahasa server-side untuk memproses data dan menghasilkan halaman dinamis.',
                'ringkasan' => 'PHP digunakan untuk membaca input, memproses logika aplikasi, dan berkomunikasi dengan database.',
                'sections' => [[
                    'heading' => 'Pokok Bahasan',
                    'paragraphs' => ['PHP berjalan pada server dan hasilnya dikirim ke browser dalam bentuk HTML.'],
                    'points' => ['Sintaks PHP', 'variabel', 'form', 'server-side scripting'],
                ]],
            ],
            [
                'slug' => 'controller-logic-pada-ci4',
                'filename' => '03 CI4_Controller_Logic.pdf',
                'judul' => 'Controller Logic pada CodeIgniter 4',
                'label' => 'Pertemuan 3',
                'deskripsi' => 'Peran controller dalam menerima request, memproses logika, memanggil model, dan mengembalikan response.',
                'ringkasan' => 'Controller menjadi pengatur utama alur request di dalam pola MVC.',
                'sections' => [[
                    'heading' => 'Pokok Bahasan',
                    'paragraphs' => ['Controller menghubungkan route, model, dan view agar aplikasi dapat merespons aksi pengguna.'],
                    'points' => ['Request lifecycle', 'BaseController', 'input/output', 'response'],
                ]],
            ],
            [
                'slug' => 'view-layout-dan-view-cell',
                'filename' => 'Web 2 - CodeIgniter_4_View_Architecture.pdf',
                'judul' => 'Arsitektur View: Layout dan View Cell',
                'label' => 'Pertemuan 4',
                'deskripsi' => 'Membangun UI modular dengan View Layout, View Partial, dan View Cell.',
                'ringkasan' => 'View Layout menghindari penulisan HTML berulang, sedangkan View Cell membantu membuat komponen reusable.',
                'sections' => [[
                    'heading' => 'Pokok Bahasan',
                    'paragraphs' => ['Tampilan aplikasi dipisahkan ke layout utama dan komponen kecil agar lebih mudah dirawat.'],
                    'points' => ['layout/main.php', 'renderSection', 'View Cell', 'komponen artikel terkini'],
                ]],
            ],
            [
                'slug' => 'praktikum-1-php-framework-codeigniter',
                'filename' => 'Modul Praktikum 1.pdf',
                'judul' => 'Modul Praktikum 1: PHP Framework CodeIgniter',
                'label' => 'Praktikum 1',
                'deskripsi' => 'Instalasi CodeIgniter 4, menjalankan CLI, debugging, routing, controller, dan view awal.',
                'ringkasan' => 'Praktikum pertama membangun pondasi project CodeIgniter 4 dari instalasi sampai tampilan dasar.',
                'sections' => [[
                    'heading' => 'Target Praktikum',
                    'paragraphs' => ['Mahasiswa menyiapkan project CodeIgniter 4 dan memahami struktur awal MVC.'],
                    'points' => ['Aktifkan extension PHP', 'jalankan php spark', 'buat route dan controller', 'buat view awal'],
                ]],
            ],
            [
                'slug' => 'praktikum-2-crud-artikel',
                'filename' => 'Web 2 - Modul Praktikum 2.pdf',
                'judul' => 'Modul Praktikum 2: CRUD Artikel',
                'label' => 'Praktikum 2',
                'deskripsi' => 'Membuat database artikel, model, controller, view, detail, tambah, ubah, dan hapus data.',
                'ringkasan' => 'Praktikum ini mengubah project menjadi aplikasi dinamis berbasis database artikel.',
                'sections' => [[
                    'heading' => 'Target Praktikum',
                    'paragraphs' => ['Data artikel dikelola melalui Model dan halaman admin CRUD.'],
                    'points' => ['Database lab_ci4', 'tabel artikel', 'ArtikelModel', 'CRUD admin'],
                ]],
            ],
            [
                'slug' => 'praktikum-3-view-layout-view-cell',
                'filename' => 'Web 2 - Modul Praktikum 3.pdf',
                'judul' => 'Modul Praktikum 3: View Layout dan View Cell',
                'label' => 'Praktikum 3',
                'deskripsi' => 'Mengubah tampilan agar memakai layout utama dan komponen View Cell.',
                'ringkasan' => 'Praktikum ini membuat tampilan lebih modular dan konsisten di banyak halaman.',
                'sections' => [[
                    'heading' => 'Target Praktikum',
                    'paragraphs' => ['Setiap halaman memakai layout utama dan sidebar menampilkan komponen artikel terbaru.'],
                    'points' => ['extend layout', 'section content', 'View Cell', 'komponen sidebar'],
                ]],
            ],
            [
                'slug' => 'praktikum-4-login-dan-auth-filter',
                'filename' => 'Web 2 - Modul Praktikum 4.pdf',
                'judul' => 'Modul Praktikum 4: Login dan Auth Filter',
                'label' => 'Praktikum 4',
                'deskripsi' => 'Membangun login admin, session, seeder user, dan filter proteksi halaman admin.',
                'ringkasan' => 'Halaman admin dibatasi agar hanya bisa diakses oleh pengguna yang sudah login.',
                'sections' => [[
                    'heading' => 'Target Praktikum',
                    'paragraphs' => ['Login menggunakan tabel user, session, dan filter auth untuk mengamankan halaman admin.'],
                    'points' => ['UserModel', 'User controller', 'session logged_in', 'Auth filter'],
                ]],
            ],
            [
                'slug' => 'security-blueprint-login-dan-filter',
                'filename' => '05 CI4_Security_Blueprint.pdf',
                'judul' => 'Security Blueprint: Login dan Filter',
                'label' => 'Pertemuan 5',
                'deskripsi' => 'Arsitektur autentikasi, session, model user, controller user, dan filter keamanan.',
                'ringkasan' => 'Materi ini memperjelas peran setiap komponen MVC dan filter dalam sistem login.',
                'sections' => [[
                    'heading' => 'Pokok Bahasan',
                    'paragraphs' => ['Sistem login menggunakan view, controller, model, database, session, dan auth filter.'],
                    'points' => ['Login view', 'UserController', 'UserModel', 'Auth filter'],
                ]],
            ],
            [
                'slug' => 'praktikum-5-pagination-dan-pencarian',
                'filename' => 'Modul Praktikum 5.pdf',
                'judul' => 'Modul Praktikum 5: Pagination dan Pencarian',
                'label' => 'Praktikum 5',
                'deskripsi' => 'Membatasi daftar artikel per halaman dan menambahkan pencarian judul artikel.',
                'ringkasan' => 'Pagination dan pencarian membuat halaman admin tetap rapi walaupun data artikel bertambah banyak.',
                'sections' => [[
                    'heading' => 'Target Praktikum',
                    'paragraphs' => ['Data artikel dikelola dengan paginate, pager, dan filter query q.'],
                    'points' => ['paginate(10)', 'pager links', 'form pencarian', 'query q'],
                ]],
            ],
            [
                'slug' => 'praktikum-6-relasi-tabel-query-builder',
                'filename' => 'Modul Praktikum 6.pdf',
                'judul' => 'Modul Praktikum 6: Relasi Tabel dan Query Builder',
                'label' => 'Praktikum 6',
                'deskripsi' => 'Relasi One-to-Many antara kategori dan artikel, foreign key, join, dan filter kategori.',
                'ringkasan' => 'Praktikum ini menambahkan tabel kategori dan menghubungkannya dengan artikel melalui id_kategori.',
                'sections' => [[
                    'heading' => 'Target Praktikum',
                    'paragraphs' => ['Artikel ditampilkan bersama nama kategori menggunakan Query Builder join.'],
                    'points' => ['Tabel kategori', 'id_kategori pada artikel', 'KategoriModel', 'getArtikelDenganKategori', 'filter kategori'],
                ]],
            ],
            [
                'slug' => 'blueprint-relasi-query-builder',
                'filename' => '07 Blueprint_Relasi_CI4.pdf',
                'judul' => 'Blueprint Relasi CI4: Query Builder dan Relasi Tabel',
                'label' => 'Pertemuan 7',
                'deskripsi' => 'Panduan visual relasi tabel kategori-artikel, query builder, validasi, dan integrasi antarmuka.',
                'ringkasan' => 'Materi ini memperkuat konsep relasi data dan Query Builder dari database sampai tampilan web.',
                'sections' => [[
                    'heading' => 'Pokok Bahasan',
                    'paragraphs' => ['Relasi One-to-Many membuat satu kategori dapat memiliki banyak artikel, lalu data relasi ditampilkan melalui join.'],
                    'points' => ['Foreign key id_kategori', 'Query Builder join', 'validasi id_kategori', 'dropdown kategori dinamis'],
                ]],
            ],
            [
                'slug' => 'praktikum-7-upload-file-gambar',
                'filename' => 'Modul Praktikum 7.pdf',
                'judul' => 'Modul Praktikum 7: Upload File Gambar',
                'label' => 'Praktikum 7',
                'deskripsi' => 'Menambahkan upload gambar pada form tambah dan ubah artikel.',
                'ringkasan' => 'Praktikum ini menambahkan input file, enctype multipart/form-data, penyimpanan gambar ke public/gambar, dan tampilan gambar artikel.',
                'sections' => [[
                    'heading' => 'Target Praktikum',
                    'paragraphs' => ['Admin dapat mengunggah gambar artikel saat menambah atau mengubah data.'],
                    'points' => ['input type file', 'multipart/form-data', 'validasi file gambar', 'folder public/gambar'],
                ]],
            ],
            [
                'slug' => 'mastering-ci4-ajax',
                'filename' => 'Mastering_CI4_AJAX.pdf',
                'judul' => 'Mastering CI4 AJAX',
                'label' => 'Pertemuan 8',
                'deskripsi' => 'Konsep AJAX, arsitektur CI4 + AJAX, JSON response, loading state, dan delete asinkron.',
                'ringkasan' => 'AJAX memungkinkan browser mengambil atau memperbarui data dari server tanpa reload halaman penuh.',
                'sections' => [[
                    'heading' => 'Pokok Bahasan',
                    'paragraphs' => ['jQuery mengirim request ke controller, controller mengembalikan JSON, lalu JavaScript memperbarui DOM.'],
                    'points' => ['GET ajax/getData', 'JSON response', 'loading state', 'delete asinkron'],
                ]],
            ],
            [
                'slug' => 'praktikum-8-ajax',
                'filename' => 'Modul Praktikum 8.pdf',
                'judul' => 'Modul Praktikum 8: AJAX',
                'label' => 'Praktikum 8',
                'deskripsi' => 'Implementasi AJAX pada CodeIgniter 4 untuk menampilkan, menambah, mengubah, dan menghapus artikel tanpa reload penuh.',
                'ringkasan' => 'Praktikum ini memakai jQuery AJAX, AjaxController, respon JSON, dan tabel data dinamis.',
                'sections' => [[
                    'heading' => 'Target Praktikum',
                    'paragraphs' => ['Aplikasi memiliki halaman AJAX untuk CRUD artikel secara asinkron.'],
                    'points' => ['AjaxController', 'getData JSON', 'AJAX create', 'AJAX update', 'AJAX delete', 'reload table tanpa reload halaman'],
                ]],
            ],
            [
                'slug' => 'rest-api-development-codeigniter-4',
                'filename' => 'CI4_REST_API_Development.pdf',
                'judul' => 'REST API Development di CodeIgniter 4',
                'label' => 'Pertemuan 10',
                'deskripsi' => 'Konsep REST API, arsitektur client-server, HTTP method, JSON response, dan pengujian endpoint menggunakan Postman.',
                'ringkasan' => 'REST API membuat data artikel dapat diakses oleh aplikasi lain melalui endpoint berbasis JSON.',
                'sections' => [[
                    'heading' => 'Pokok Bahasan',
                    'paragraphs' => ['REST API memisahkan client dan server. Client mengirim HTTP request ke endpoint, lalu server mengembalikan response dalam format JSON.'],
                    'points' => ['REST client dan REST server', 'format JSON', 'GET /post', 'POST /post', 'PUT /post/{id}', 'DELETE /post/{id}', 'pengujian dengan Postman'],
                ], [
                    'heading' => 'Endpoint yang Tersedia',
                    'paragraphs' => ['Pada update ini route resource post sudah ditambahkan sehingga endpoint API artikel bisa langsung diuji.'],
                    'points' => ['GET /post untuk semua artikel', 'GET /post/{id} untuk artikel spesifik', 'POST /post untuk tambah artikel', 'PUT /post/{id} untuk ubah artikel', 'DELETE /post/{id} untuk hapus artikel'],
                ]],
            ],
            [
                'slug' => 'praktikum-10-api',
                'filename' => 'Modul Praktikum 10.pdf',
                'judul' => 'Modul Praktikum 10: API',
                'label' => 'Praktikum 10',
                'deskripsi' => 'Implementasi REST API pada CodeIgniter 4 menggunakan ResourceController, ResponseTrait, route resource, dan pengujian CRUD dengan Postman.',
                'ringkasan' => 'Praktikum ini menambahkan controller API untuk menampilkan, menambah, mengubah, dan menghapus data artikel melalui HTTP request.',
                'sections' => [[
                    'heading' => 'Target Praktikum',
                    'paragraphs' => ['Aplikasi memiliki REST Controller Post yang mengembalikan data artikel dalam format JSON dan dapat diuji melalui Postman.'],
                    'points' => ['Membuat Post.php', 'menggunakan ResourceController', 'menggunakan ResponseTrait', 'menambahkan $routes->resource("post")', 'uji GET, POST, PUT, dan DELETE di Postman'],
                ], [
                    'heading' => 'Format Uji Coba',
                    'paragraphs' => ['Gunakan x-www-form-urlencoded atau JSON body pada Postman untuk mengirim data artikel.'],
                    'points' => ['judul: judul artikel', 'isi: isi artikel', 'id_kategori: opsional', 'status: 0 atau 1'],
                ]],
            ],
        ];

        foreach ($items as &$item) {
            $item['available'] = is_file($this->materiPath($item['filename']));
        }
        unset($item);

        return $this->sortMateriList($items);
    }

    private function buildMateriKategoriList(array $materi): array
    {
        $labels = [];

        foreach ($materi as $item) {
            $label = (string) ($item['label'] ?? '');
            if ($label === '') {
                continue;
            }

            $labels[url_title($label, '-', true)] = $label;
        }

        return $labels;
    }

    private function sortMateriList(array $items): array
    {
        usort($items, function ($first, $second) {
            $firstType = $this->getMateriType($first['label'] ?? '');
            $secondType = $this->getMateriType($second['label'] ?? '');

            $typeOrder = [
                'pertemuan' => 1,
                'praktikum' => 2,
                'lainnya'   => 3,
            ];

            $firstRank = $typeOrder[$firstType] ?? 3;
            $secondRank = $typeOrder[$secondType] ?? 3;

            if ($firstRank !== $secondRank) {
                return $firstRank <=> $secondRank;
            }

            $numberCompare = $this->getMateriNumber($first['label'] ?? '') <=> $this->getMateriNumber($second['label'] ?? '');
            if ($numberCompare !== 0) {
                return $numberCompare;
            }

            return strcasecmp((string) ($first['judul'] ?? ''), (string) ($second['judul'] ?? ''));
        });

        return $items;
    }

    private function getMateriType(string $label): string
    {
        $label = strtolower($label);

        if (str_contains($label, 'pertemuan')) {
            return 'pertemuan';
        }

        if (str_contains($label, 'praktikum')) {
            return 'praktikum';
        }

        return 'lainnya';
    }

    private function getMateriNumber(string $label): int
    {
        if (preg_match('/(\d+)/', $label, $matches)) {
            return (int) $matches[1];
        }

        return 999;
    }
}
