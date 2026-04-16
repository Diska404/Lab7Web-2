<?php

namespace App\Controllers;

use App\Models\ArtikelModel;
use App\Models\KategoriModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Artikel extends BaseController
{
    public function index(): string
    {
        $title = 'Daftar Artikel';
        $kategoriSlug = trim((string) ($this->request->getVar('kategori') ?? ''));

        $model = new ArtikelModel();
        $kategoriModel = new KategoriModel();

        $artikel = $model->getArtikelDenganKategori($kategoriSlug);
        $kategoriList = $kategoriModel->orderBy('nama_kategori', 'ASC')->findAll();

        return view('artikel/index', [
            'title'         => $title,
            'artikel'       => $artikel,
            'materi'        => $this->getMateriList(),
            'kategoriList'  => $kategoriList,
            'kategoriAktif' => $kategoriSlug,
        ]);
    }

    public function view($slug): string
    {
        $model = new ArtikelModel();
        $artikel = $model->getArtikelBySlugDenganKategori($slug);

        if (! $artikel) {
            throw PageNotFoundException::forPageNotFound();
        }

        $title = $artikel['judul'];

        return view('artikel/detail', [
            'title'   => $title,
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

        $path = ROOTPATH . 'file/' . $materi['filename'];

        if (! is_file($path)) {
            throw PageNotFoundException::forPageNotFound();
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
        helper(['form']);

        $kategoriModel = new KategoriModel();
        $kategori = $kategoriModel->orderBy('nama_kategori', 'ASC')->findAll();

        if ($this->request->getMethod() === 'POST') {
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

            $artikel = new ArtikelModel();
            $artikel->insert([
                'judul'       => $data['judul'],
                'isi'         => $data['isi'],
                'slug'        => url_title($data['judul'], '-', true),
                'id_kategori' => (int) $data['id_kategori'],
            ]);

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
        helper(['form']);

        $artikelModel = new ArtikelModel();
        $data = $artikelModel->find($id);

        if (! $data) {
            throw PageNotFoundException::forPageNotFound();
        }

        $kategoriModel = new KategoriModel();
        $kategori = $kategoriModel->orderBy('nama_kategori', 'ASC')->findAll();

        if ($this->request->getMethod() === 'POST') {
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

            $artikelModel->update($id, [
                'judul'       => $payload['judul'],
                'isi'         => $payload['isi'],
                'slug'        => url_title($payload['judul'], '-', true),
                'id_kategori' => (int) $payload['id_kategori'],
            ]);

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
        $artikel->delete($id);
        session()->setFlashdata('success', 'Artikel berhasil dihapus.');
        return redirect()->to('/admin/artikel');
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

    private function getMateriList(): array
    {
        return [
            [
                'slug' => 'fondasi-codeigniter-4',
                'filename' => '01 CodeIgniter_4_Foundation.pdf',
                'judul' => 'Fondasi CodeIgniter 4',
                'label' => 'Pertemuan 1',
                'deskripsi' => 'Pengantar fondasi CodeIgniter 4, arsitektur server-side, framework, dan persiapan environment.',
                'ringkasan' => 'Materi ini membahas gambaran umum CodeIgniter 4 sebagai framework PHP modern yang ringan dan terstruktur untuk pengembangan aplikasi web.',
                'sections' => [
                    [
                        'heading' => 'Gambaran Umum',
                        'paragraphs' => [
                            'CodeIgniter 4 adalah framework PHP yang menerapkan pola pengembangan terstruktur sehingga pembuatan aplikasi web menjadi lebih cepat dan rapi.',
                            'Framework ini mendukung konsep MVC, routing, controller, view, filter, validasi, dan berbagai library bawaan yang mempermudah proses pengembangan.',
                        ],
                        'points' => [
                            'Ringan dan cepat dijalankan pada lingkungan lokal seperti XAMPP.',
                            'Mendukung CLI melalui perintah spark.',
                            'Cocok digunakan untuk pembelajaran konsep framework PHP.',
                        ],
                    ],
                    [
                        'heading' => 'Komponen Dasar',
                        'paragraphs' => [
                            'Dalam CodeIgniter 4, request dari user akan diproses melalui route, diteruskan ke controller, lalu data dapat diambil dari model dan ditampilkan melalui view.',
                        ],
                        'points' => [
                            'Route menentukan alamat URL.',
                            'Controller menangani logika aplikasi.',
                            'Model berhubungan dengan database.',
                            'View menampilkan output ke browser.',
                        ],
                    ],
                ],
            ],
            [
                'slug' => 'konsep-dasar-web-dinamis',
                'filename' => '01 Konsep Dasar Web Dinamis.pdf',
                'judul' => 'Konsep Dasar Web Dinamis',
                'label' => 'Pertemuan 1',
                'deskripsi' => 'Materi dasar tentang web dinamis, arsitektur client-server, database, serta alur kerja aplikasi web.',
                'ringkasan' => 'Web dinamis adalah website yang menampilkan data secara fleksibel berdasarkan proses di server dan data yang tersimpan pada database.',
                'sections' => [
                    [
                        'heading' => 'Perbedaan Web Statis dan Web Dinamis',
                        'paragraphs' => [
                            'Web statis menampilkan isi yang cenderung tetap, sedangkan web dinamis dapat berubah mengikuti data, input pengguna, atau proses tertentu di server.',
                        ],
                        'points' => [
                            'Web statis: konten tetap dan sederhana.',
                            'Web dinamis: konten dapat berubah berdasarkan database atau interaksi user.',
                        ],
                    ],
                ],
            ],
            [
                'slug' => 'routing-essentials-codeigniter-4',
                'filename' => '02 CI4_Routing_Essentials.pdf',
                'judul' => 'Routing Essentials di CodeIgniter 4',
                'label' => 'Pertemuan 2',
                'deskripsi' => 'Membahas konsep routing, endpoint, segment URI, static route, dynamic route, dan route grouping.',
                'ringkasan' => 'Routing digunakan untuk menghubungkan URL dengan controller atau method tertentu agar request diproses sesuai tujuan.',
                'sections' => [
                    [
                        'heading' => 'Fungsi Routing',
                        'paragraphs' => [
                            'Routing membantu developer mengatur jalur URL aplikasi secara lebih jelas dan terstruktur.',
                        ],
                        'points' => [
                            'Menentukan endpoint aplikasi.',
                            'Menghubungkan URL ke controller.',
                            'Membantu membatasi akses berdasarkan grup route.',
                        ],
                    ],
                ],
            ],
            [
                'slug' => 'dasar-php-untuk-pemrograman-web',
                'filename' => '02 PHP Dasar.pdf',
                'judul' => 'Dasar PHP untuk Pemrograman Web',
                'label' => 'Pertemuan 2',
                'deskripsi' => 'Ringkasan materi PHP dasar yang digunakan untuk membangun aplikasi web dinamis berbasis server-side.',
                'ringkasan' => 'PHP adalah bahasa server-side yang umum digunakan untuk memproses form, menampilkan data dinamis, dan berkomunikasi dengan database.',
                'sections' => [
                    [
                        'heading' => 'Peran PHP',
                        'paragraphs' => [
                            'PHP bekerja di sisi server untuk mengolah logika aplikasi sebelum hasil akhirnya dikirim ke browser pengguna.',
                        ],
                        'points' => [
                            'Mengolah input dari form.',
                            'Berinteraksi dengan database.',
                            'Membuat halaman dinamis.',
                        ],
                    ],
                ],
            ],
            [
                'slug' => 'controller-logic-flow-pada-ci4',
                'filename' => '03 CI4_Controller_Logic_Flow.pdf',
                'judul' => 'Controller Logic Flow pada CI4',
                'label' => 'Pertemuan 3',
                'deskripsi' => 'Menjelaskan controller, alur request, method, dan pengelolaan logika aplikasi pada CodeIgniter 4.',
                'ringkasan' => 'Controller adalah pusat pengendali request yang menentukan data apa yang diproses dan view apa yang ditampilkan.',
                'sections' => [
                    [
                        'heading' => 'Peran Controller',
                        'paragraphs' => [
                            'Controller menerima request dari route, memanggil model jika diperlukan, lalu mengirim hasil ke view.',
                        ],
                    ],
                ],
            ],
            [
                'slug' => 'dasar-pemrograman-web-php',
                'filename' => '03 Pemrograman Web PHP Dasar.pdf',
                'judul' => 'Dasar Pemrograman Web PHP',
                'label' => 'Pertemuan 3',
                'deskripsi' => 'Materi dasar pemrograman web berbasis PHP meliputi sintaks, variabel, form, dan proses data.',
                'ringkasan' => 'Materi ini berfokus pada penggunaan PHP dalam konteks web, terutama pemrosesan data form dan output HTML yang dinamis.',
                'sections' => [
                    [
                        'heading' => 'Pengolahan Form',
                        'paragraphs' => [
                            'Form HTML dapat mengirim data ke server menggunakan metode GET atau POST, lalu PHP membaca dan memproses datanya.',
                        ],
                    ],
                ],
            ],
            [
                'slug' => 'view-layout-dan-view-cell',
                'filename' => 'Web 2 - Modul Praktikum 3.pdf',
                'judul' => 'View Layout dan View Cell',
                'label' => 'Pertemuan 4',
                'deskripsi' => 'Materi mengenai View Layout, View Cell, dan penyusunan tampilan modular di CodeIgniter 4.',
                'ringkasan' => 'View Layout membantu konsistensi tampilan, sedangkan View Cell memudahkan pembuatan komponen kecil yang dapat dipakai ulang.',
                'sections' => [
                    [
                        'heading' => 'View Layout',
                        'paragraphs' => [
                            'Layout digunakan untuk menampung bagian umum seperti header, navigasi, sidebar, dan footer agar tidak perlu ditulis ulang di setiap halaman.',
                        ],
                    ],
                ],
            ],
            [
                'slug' => 'security-blueprint-login-dan-filter',
                'filename' => '05 CI4_Security_Blueprint.pdf',
                'judul' => 'Security Blueprint: Login dan Filter',
                'label' => 'Pertemuan 5',
                'deskripsi' => 'Membahas konsep login, filter keamanan, session, dan arsitektur autentikasi pada CodeIgniter 4.',
                'ringkasan' => 'Materi ini menyoroti bagaimana session, auth filter, dan validasi data digunakan untuk membatasi akses ke halaman tertentu.',
                'sections' => [
                    [
                        'heading' => 'Session dan Auth Filter',
                        'paragraphs' => [
                            'Session menyimpan status login pengguna, sedangkan auth filter memeriksa status tersebut sebelum halaman admin diakses.',
                        ],
                    ],
                ],
            ],
            [
                'slug' => 'pagination-dan-pencarian',
                'filename' => 'Modul Praktikum 5.pdf',
                'judul' => 'Pagination dan Pencarian',
                'label' => 'Modul Praktikum',
                'deskripsi' => 'Panduan praktikum pagination dan pencarian pada halaman admin artikel.',
                'ringkasan' => 'Materi ini membahas pembagian data ke beberapa halaman dan pencarian judul artikel agar pengelolaan data menjadi lebih efisien.',
                'sections' => [
                    [
                        'heading' => 'Pagination',
                        'paragraphs' => [
                            'Pagination membatasi jumlah data yang tampil di setiap halaman sehingga tabel admin tetap nyaman dibaca meskipun datanya banyak.',
                        ],
                    ],
                ],
            ],
        ];
    }
}
