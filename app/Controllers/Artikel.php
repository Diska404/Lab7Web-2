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
        $artikel = $model->findAll();

        return view('artikel/index', [
            'title'   => $title,
            'artikel' => $artikel,
            'materi'  => $this->getMateriList(),
        ]);
    }

    public function view($slug): string
    {
        $model = new ArtikelModel();
        $artikel = $model->where(['slug' => $slug])->first();

        if (! $artikel) {
            throw PageNotFoundException::forPageNotFound();
        }

        $title = $artikel['judul'];
        return view('artikel/detail', compact('artikel', 'title'));
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

        $model = new ArtikelModel();

        if ($q !== '') {
            $model = $model->like('judul', $q);
        }

        $data = [
            'title'   => $title,
            'q'       => $q,
            'artikel' => $model->orderBy('id', 'DESC')->paginate(10),
            'pager'   => $model->pager,
        ];

        return view('artikel/admin_index', $data);
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
            throw PageNotFoundException::forPageNotFound();
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
                    [
                        'heading' => 'Manfaat untuk Praktikum',
                        'paragraphs' => [
                            'Dengan fondasi ini, mahasiswa dapat melanjutkan ke materi routing, CRUD, layout, login, hingga fitur pencarian dan pagination dengan struktur aplikasi yang tetap konsisten.',
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
                    [
                        'heading' => 'Arsitektur Client-Server',
                        'paragraphs' => [
                            'Browser berperan sebagai client yang mengirim request, kemudian server memproses request tersebut dan mengembalikan response berupa halaman web.',
                        ],
                        'points' => [
                            'Client mengakses URL.',
                            'Server menjalankan logika aplikasi.',
                            'Database menyimpan data yang dibutuhkan.',
                        ],
                    ],
                    [
                        'heading' => 'Contoh Implementasi',
                        'paragraphs' => [
                            'Sistem login, artikel, pencarian, dan CRUD adalah contoh implementasi web dinamis karena isi halaman bergantung pada data dan proses yang terjadi di server.',
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
                    [
                        'heading' => 'Jenis Route',
                        'paragraphs' => [
                            'Route dapat dibuat secara statis untuk URL tetap maupun dinamis dengan parameter seperti slug atau id.',
                        ],
                        'points' => [
                            'Static route: URL tetap seperti /about.',
                            'Dynamic route: URL dengan parameter seperti /artikel/slug.',
                            'Route group: kumpulan route dengan prefix atau filter tertentu.',
                        ],
                    ],
                    [
                        'heading' => 'Praktik pada Project',
                        'paragraphs' => [
                            'Pada project praktikum, routing dipakai untuk halaman home, artikel, about, contact, login, admin artikel, dan halaman materi kuliah.',
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
                    [
                        'heading' => 'Konsep Dasar',
                        'paragraphs' => [
                            'Materi dasar PHP meliputi variabel, operator, percabangan, perulangan, function, array, dan penanganan data dari request GET/POST.',
                        ],
                    ],
                    [
                        'heading' => 'Hubungan dengan Framework',
                        'paragraphs' => [
                            'CodeIgniter 4 dibangun di atas PHP. Karena itu, pemahaman dasar PHP sangat penting sebelum menggunakan controller, model, dan view secara efektif.',
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
                    [
                        'heading' => 'Alur Kerja',
                        'paragraphs' => [
                            'Sebuah request masuk ke route, kemudian route memanggil method pada controller, controller memproses logika, dan hasilnya dikembalikan sebagai response.',
                        ],
                        'points' => [
                            'Route menerima URL.',
                            'Controller menentukan proses.',
                            'Model menangani data.',
                            'View menampilkan hasil.',
                        ],
                    ],
                    [
                        'heading' => 'Contoh di Project',
                        'paragraphs' => [
                            'Controller Artikel mengelola daftar artikel, detail artikel, halaman admin, pencarian, pagination, dan fitur materi kuliah berbasis HTML reader.',
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
                        'heading' => 'Sintaks dan Struktur',
                        'paragraphs' => [
                            'PHP ditulis di dalam tag khusus dan dapat disisipkan ke dalam HTML untuk menampilkan output dinamis.',
                        ],
                    ],
                    [
                        'heading' => 'Pengolahan Form',
                        'paragraphs' => [
                            'Form HTML dapat mengirim data ke server menggunakan metode GET atau POST, lalu PHP membaca dan memproses datanya.',
                        ],
                        'points' => [
                            'Input text untuk nama, email, dan data lainnya.',
                            'Textarea untuk isi pesan atau konten.',
                            'Validasi data sebelum disimpan ke database.',
                        ],
                    ],
                    [
                        'heading' => 'Implementasi pada Tugas',
                        'paragraphs' => [
                            'Fitur login, tambah artikel, edit artikel, dan pencarian di project praktikum memanfaatkan dasar pemrosesan data menggunakan PHP.',
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
                    [
                        'heading' => 'View Cell',
                        'paragraphs' => [
                            'View Cell dipakai untuk komponen modular seperti daftar artikel terbaru atau widget informasi tambahan di sidebar.',
                        ],
                    ],
                    [
                        'heading' => 'Manfaat Modularitas',
                        'paragraphs' => [
                            'Dengan pendekatan ini, struktur tampilan menjadi lebih rapi, mudah dirawat, dan mudah dikembangkan saat fitur bertambah.',
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
                        'heading' => 'Konsep Keamanan Dasar',
                        'paragraphs' => [
                            'Sistem login memerlukan validasi input, penyimpanan password secara aman, dan pembatasan akses pada halaman yang sensitif.',
                        ],
                    ],
                    [
                        'heading' => 'Session dan Auth Filter',
                        'paragraphs' => [
                            'Session menyimpan status login pengguna, sedangkan auth filter memeriksa status tersebut sebelum halaman admin diakses.',
                        ],
                        'points' => [
                            'User yang belum login diarahkan ke halaman login.',
                            'Logout menghapus session agar akses admin ditutup kembali.',
                        ],
                    ],
                    [
                        'heading' => 'Penerapan di Project',
                        'paragraphs' => [
                            'Pada project ini, halaman admin artikel dilindungi oleh auth filter dan hanya bisa diakses setelah proses login berhasil.',
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
                        'points' => [
                            'Data dibatasi 10 record per halaman.',
                            'Pager digunakan untuk berpindah halaman.',
                            'Tampilan admin menjadi lebih rapi dan ringan.',
                        ],
                    ],
                    [
                        'heading' => 'Pencarian',
                        'paragraphs' => [
                            'Fitur pencarian digunakan untuk memfilter data artikel berdasarkan kata kunci tertentu, misalnya judul artikel.',
                        ],
                        'points' => [
                            'Menggunakan form GET.',
                            'Query like diterapkan pada kolom judul.',
                            'Pager tetap membawa parameter pencarian.',
                        ],
                    ],
                    [
                        'heading' => 'Implementasi',
                        'paragraphs' => [
                            'Pada project ini, halaman admin artikel sudah dilengkapi pagination dan pencarian yang bisa diuji langsung melalui menu admin.',
                        ],
                    ],
                ],
            ],
        ];
    }
}
