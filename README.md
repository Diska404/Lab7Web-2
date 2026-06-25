<table>
  <tr><td><strong>Nama</strong></td><td>Diska Kurnia Azzahra Putra</td></tr>
  <tr><td><strong>NIM</strong></td><td>312210369</td></tr>
  <tr><td><strong>Kelas</strong></td><td>I241E</td></tr>
  <tr><td><strong>Mata Kuliah</strong></td><td>Pemrograman Web 2</td></tr>
  <tr><td><strong>Dosen Pengajar</strong></td><td>Agung Nugroho, S.Kom., M.Kom.</td></tr>
</table>

# Lab7Web-2 - Pemrograman Web 2

Repository ini berisi hasil praktikum mata kuliah **Pemrograman Web 2** menggunakan **CodeIgniter 4** sebagai backend utama. Pengembangan dilakukan secara bertahap, mulai dari instalasi CodeIgniter, pembuatan halaman dasar, CRUD artikel, login admin, relasi tabel, upload gambar, AJAX, REST API, frontend VueJS, hingga autentikasi SPA dan pengamanan API menggunakan token.

Pada tahap akhir, project ini sudah sampai pada **Praktikum 14**, yaitu penerapan **Token-Based Authentication** pada REST API dan penggunaan **Axios Interceptors** pada frontend VueJS.

---

## Teknologi yang Digunakan

- PHP 8
- CodeIgniter 4
- MySQL / MariaDB
- XAMPP
- Visual Studio Code
- phpMyAdmin
- Postman
- VueJS 3
- Vue Router
- Axios

---

## Struktur Project Utama

```text
Lab7Web-2
├── app
│   ├── Controllers
│   │   ├── Artikel.php
│   │   ├── AjaxController.php
│   │   ├── Post.php
│   │   ├── User.php
│   │   └── Api/Auth.php
│   ├── Filters
│   │   ├── Auth.php
│   │   └── ApiAuthFilter.php
│   ├── Models
│   │   ├── ArtikelModel.php
│   │   ├── KategoriModel.php
│   │   └── UserModel.php
│   └── Views
├── file
├── lab8_vuejs
│   ├── index.html
│   └── assets
│       ├── css/style.css
│       └── js
│           ├── app.js
│           └── components
│               ├── Home.js
│               ├── Artikel.js
│               ├── About.js
│               └── Login.js
├── public
├── Screenshot
└── README.md
```

---

## Cara Menjalankan Project

### 1. Menjalankan Backend CodeIgniter

Buka terminal pada folder project:

```bash
cd /d D:\XAMPP\htdocs\lab11_ci\Lab7Web-2
php spark serve
```

Backend akan berjalan pada alamat:

```text
http://localhost:8080
```

### 2. Menjalankan Frontend VueJS SPA

Pastikan Apache XAMPP aktif, lalu buka:

```text
http://localhost/lab11_ci/Lab7Web-2/lab8_vuejs/index.html#/
```

### 3. Akun Login Lokal

Akun bawaan yang digunakan untuk pengujian lokal:

```text
Username : admin
Password : 1
```

Selain username, sistem login juga dapat menerima ID atau email yang tersimpan pada tabel `user`.

---

## Rangkuman Progress Praktikum

| Praktikum | Materi | Hasil Pengembangan |
|---|---|---|
| Praktikum 1 | PHP Framework CodeIgniter | Instalasi CI4, route, controller, view, dan layout awal |
| Praktikum 2 | CRUD Artikel | Model artikel, database, daftar artikel, detail, tambah, edit, dan hapus |
| Praktikum 3 | View Layout dan View Cell | Layout utama dan komponen artikel terkini |
| Praktikum 4 | Modul Login | Login admin, session, filter auth, dan halaman admin terlindungi |
| Praktikum 5 | Pagination dan Pencarian | Pencarian artikel dan pagination data |
| Praktikum 6 | Relasi Tabel | Relasi artikel dengan kategori menggunakan Query Builder |
| Praktikum 7 | Upload File | Upload gambar artikel dan preview gambar |
| Praktikum 8 | AJAX | Dashboard pengelolaan artikel menggunakan AJAX |
| Praktikum 10 | REST API | Endpoint `/post` untuk akses data artikel dalam format JSON |
| Praktikum 11 | VueJS Frontend API | Frontend VueJS mengambil data dari REST API CodeIgniter |
| Praktikum 12 | Vue Router SPA | Komponen Home, Artikel, About, dan routing SPA |
| Praktikum 13 | Autentikasi SPA | Login VueJS, localStorage, dan Navigation Guards |
| Praktikum 14 | Token API | ApiAuthFilter, Bearer Token, dan Axios Interceptors |

---

# Praktikum 1 - PHP Framework CodeIgniter

Pada praktikum awal, project CodeIgniter 4 disiapkan sebagai dasar pengembangan web. Tahapan yang dilakukan meliputi instalasi framework, pengaturan file `.env`, aktivasi mode development, penggunaan CLI, pembuatan route, controller, dan view dasar.

Perintah yang digunakan untuk menjalankan server lokal CodeIgniter:

```bash
php spark serve
```

Dokumentasi hasil:

> ![Instalasi CodeIgniter](Screenshot/ss_instalasi.png)

> ![CLI CodeIgniter](Screenshot/ss_cli.png)

> ![Halaman Utama](Screenshot/ss_halaman_utama.png)

---

# Praktikum 2 - Framework Lanjutan CRUD

Pada praktikum ini dibuat fitur CRUD artikel. Database `lab_ci4` digunakan untuk menyimpan data artikel. Model `ArtikelModel.php` dibuat agar proses pengambilan dan penyimpanan data lebih terstruktur.

Fitur yang dibuat pada tahap ini:

- menampilkan daftar artikel,
- menampilkan detail artikel berdasarkan slug,
- menambahkan artikel,
- mengubah artikel,
- menghapus artikel.

Dokumentasi hasil:

> ![ArtikelModel](Screenshot/ss_model.png)

> ![Halaman Artikel](Screenshot/ss_artikel.png)

---

# Praktikum 3 - View Layout dan View Cell

Pada tahap ini, tampilan dibuat lebih rapi dengan menerapkan layout utama. File `app/Views/layout/main.php` digunakan sebagai template yang berisi header, navigasi, konten utama, sidebar, dan footer.

Selain itu, dibuat juga **View Cell** untuk menampilkan daftar artikel terbaru pada bagian sidebar.

Dokumentasi hasil:

> ![Layout Main](Screenshot/ss1_main_php.png)

> ![Komponen Artikel Terkini](Screenshot/ss6_artikel_terkini_component.png)

---

# Praktikum 4 - Modul Login

Praktikum ini menambahkan sistem login admin menggunakan tabel `user`. Setelah login berhasil, data pengguna disimpan ke session. Halaman admin kemudian dilindungi menggunakan filter `auth`, sehingga pengguna yang belum login tidak bisa langsung mengakses halaman pengelolaan artikel.

File penting pada tahap ini:

- `app/Controllers/User.php`
- `app/Models/UserModel.php`
- `app/Filters/Auth.php`
- `app/Views/user/login.php`
- `app/Config/Filters.php`
- `app/Config/Routes.php`

Dokumentasi hasil:

> ![Login View](Screenshot/ss_modul4_login_view.png)

> ![Auth Filter](Screenshot/ss_modul4_auth_filter.png)

> ![Admin Artikel](Screenshot/ss_modul4_admin_artikel.png)

---

# Praktikum 5 - Pagination dan Pencarian

Pada praktikum ini, halaman artikel ditambahkan fitur pencarian dan pagination. Fitur pencarian digunakan untuk memfilter artikel berdasarkan kata kunci, sedangkan pagination digunakan agar data artikel tidak tampil terlalu panjang dalam satu halaman.

Dokumentasi hasil:

> ![Form Pencarian](Screenshot/ss_modul5_form_pencarian_code.png)

> ![Hasil Pencarian](Screenshot/ss_modul5_hasil_pencarian.png)

> ![Pagination Page 1](Screenshot/ss_modul5_page1.png)

> ![Pagination Page 2](Screenshot/ss_modul5_page2.png)

---

# Praktikum 6 - Relasi Tabel dan Query Builder

Pada tahap ini, tabel artikel dihubungkan dengan tabel kategori. Dengan adanya relasi ini, setiap artikel dapat memiliki kategori tertentu. Data artikel kemudian ditampilkan bersama nama kategorinya agar informasi lebih lengkap.

Perubahan utama:

- menambahkan tabel `kategori`,
- menambahkan field `id_kategori` pada tabel artikel,
- membuat `KategoriModel.php`,
- menampilkan kategori pada halaman artikel dan admin.

Dokumentasi hasil:

> ![Halaman Artikel Publik](Screenshot/praktikum6_01_halaman_artikel_publik.png)

> ![Dashboard Form Artikel Kategori](Screenshot/praktikum6_02_dashboard_form_artikel_kategori.png)

> ![Tabel Artikel ID Kategori](Screenshot/praktikum6_03_tabel_artikel_id_kategori.png)

> ![Tabel Kategori](Screenshot/praktikum6_04_tabel_kategori.png)

---

# Praktikum 7 - Upload File Gambar

Pada praktikum ini, form artikel ditambahkan input upload gambar. Gambar yang diunggah akan disimpan ke folder `public/gambar`, kemudian nama file gambar disimpan ke database.

Fitur yang diterapkan:

- upload gambar saat tambah artikel,
- update gambar saat edit artikel,
- preview gambar di halaman admin,
- penghapusan file gambar saat artikel dihapus.

Dokumentasi hasil:

> ![Form Upload Gambar](Screenshot/praktikum7_01_form_upload_gambar.png)

> ![Gambar Tampil di Admin](Screenshot/praktikum7_03_gambar_tampil_di_admin.png)

---

# Praktikum 8 - AJAX

Pada praktikum ini dibuat halaman **Dashboard Pengelolaan Artikel** menggunakan AJAX. Dengan AJAX, data dapat ditampilkan, ditambah, diubah, dan dihapus tanpa memuat ulang halaman secara penuh.

Halaman AJAX tetap dilindungi oleh login admin. Data dikirim menggunakan jQuery dan `FormData`, sehingga fitur upload gambar tetap dapat digunakan.

Cara membuka halaman:

```text
http://localhost:8080/ajax
```

Dokumentasi hasil:

> ![Dashboard AJAX](Screenshot/praktikum8_01_dashboard_ajax.png)

> ![Artikel Berhasil Ditambah AJAX](Screenshot/praktikum8_02_artikel_berhasil_ditambah_ajax.png)

> ![Data Artikel Tampil AJAX](Screenshot/praktikum8_03_data_artikel_tampil_ajax.png)

---

# Praktikum 10 - REST API

Pada tahap ini ditambahkan REST API artikel menggunakan controller `app/Controllers/Post.php`. Controller ini menggunakan konsep `ResourceController` dan `ResponseTrait`, sehingga data dapat dikirim dalam format JSON dan diuji menggunakan Postman.

Route utama:

```php
$routes->resource('post');
```

Endpoint API:

| Method | Endpoint | Fungsi |
|---|---|---|
| GET | `/post` | Menampilkan seluruh artikel |
| GET | `/post/{id}` | Menampilkan detail artikel |
| POST | `/post` | Menambahkan artikel |
| PUT/PATCH | `/post/{id}` | Mengubah artikel |
| DELETE | `/post/{id}` | Menghapus artikel |

Contoh URL pengujian:

```text
GET http://localhost:8080/post
```

Dokumentasi hasil:

> ![GET Semua Artikel](Screenshot/praktikum10_01_get_semua_artikel.png.png)

> ![GET Detail Artikel](Screenshot/praktikum10_02_get_detail_artikel.png.png)

> ![POST Tambah Artikel](Screenshot/praktikum10_03_post_tambah_artikel.png)

> ![PUT Ubah Artikel](Screenshot/praktikum10_04_put_ubah_artikel.png)

> ![DELETE Artikel](Screenshot/praktikum10_05_delete_artikel.png)

> ![Validasi Artikel Tampil di Web](Screenshot/praktikum10_06_validasi_artikel_tampil_di_daftar_web.png)

> ![Validasi Detail Artikel](Screenshot/praktikum10_07_validasi_detail_artikel_hasil_put.png)

> ![Validasi Artikel Terhapus](Screenshot/praktikum10_08_validasi_artikel_terhapus_dari_web.png)

---

# Praktikum 11 - VueJS Frontend API

Pada praktikum ini dibuat frontend terpisah bernama `lab8_vuejs`. Frontend ini menggunakan VueJS dan Axios untuk mengambil data artikel dari REST API CodeIgniter.

Struktur utama frontend:

```text
lab8_vuejs
├── index.html
└── assets
    ├── css/style.css
    └── js
        ├── app.js
        └── components
            ├── Home.js
            ├── Artikel.js
            └── About.js
```

Fitur pada halaman Kelola Artikel:

- menampilkan data artikel dari REST API,
- menambahkan data artikel,
- mengubah data artikel,
- menghapus data artikel,
- menampilkan status artikel.

Dokumentasi hasil:

> ![Kelola Artikel VueJS API](Screenshot/praktikum11_01_kelola_artikel_vuejs_api.png)

---

# Praktikum 12 - VueJS Komponen dan Routing SPA

Project `lab8_vuejs` kemudian dikembangkan menjadi **Single Page Application (SPA)** menggunakan Vue Router. Navigasi dilakukan melalui `router-link`, sedangkan konten halaman ditampilkan melalui `router-view`.

Komponen yang digunakan:

```text
Home.js
Artikel.js
About.js
```

Route yang dibuat:

```javascript
const routes = [
  { path: '/', component: Home },
  { path: '/artikel', component: Artikel },
  { path: '/about', component: About }
];
```

Pada route `/about`, ditampilkan profil mahasiswa sebagai bagian dari tugas praktikum.

Dokumentasi hasil:

> ![Beranda SPA](Screenshot/praktikum12_01_beranda_single_page_application.png)

> ![Route About Vue Router](Screenshot/praktikum12_02_route_about_vue_router.png)

---

# Praktikum 13 - Autentikasi SPA dan Navigation Guards

Pada praktikum ini, frontend VueJS ditambahkan halaman login. Login dilakukan melalui endpoint API CodeIgniter, kemudian status login dan token disimpan ke `localStorage`.

File baru yang digunakan:

```text
lab8_vuejs/assets/js/components/Login.js
```

Endpoint login:

```text
POST http://localhost:8080/api/login
```

Data yang disimpan setelah login berhasil:

```text
isLoggedIn
userToken
username
```

Route `/artikel` dan `/about` diberi proteksi menggunakan `meta: { requiresAuth: true }`. Jika pengguna belum login, Vue Router akan mengarahkan pengguna ke halaman `/login`.

Dokumentasi hasil:

> ![Halaman Login SPA](Screenshot/praktikum13_01_halaman_login_spa.png)

> ![Kelola Artikel Setelah Login](Screenshot/praktikum13_03_kelola_artikel_setelah_login.png)

---

# Praktikum 14 - Keamanan API, Token, dan Axios Interceptors

Pada praktikum terakhir, keamanan API ditingkatkan dengan token. Backend CodeIgniter menambahkan filter `ApiAuthFilter.php` untuk memeriksa header:

```text
Authorization: Bearer <token>
```

Jika request tidak membawa token atau token tidak valid, maka server akan mengembalikan response `401 Unauthorized`.

Route API yang dilindungi:

| Method | Endpoint | Status Proteksi |
|---|---|---|
| POST | `/post` | Wajib Bearer Token |
| PUT | `/post/{id}` | Wajib Bearer Token |
| PATCH | `/post/{id}` | Wajib Bearer Token |
| DELETE | `/post/{id}` | Wajib Bearer Token |

Endpoint `GET /post` dan `GET /post/{id}` tetap dibuat terbuka agar data masih dapat dibaca oleh frontend.

Pada sisi frontend, `Axios Interceptors` ditambahkan pada `lab8_vuejs/assets/js/app.js`. Interceptor ini akan mengambil token dari `localStorage`, lalu mengirimkannya secara otomatis pada header request. Jika server membalas `401`, frontend akan menghapus data login dan mengarahkan pengguna kembali ke halaman login.

Dokumentasi hasil:

> ![API Ditolak Tanpa Token](Screenshot/praktikum14_02_api_ditolak_tanpa_token.png)

---

# Integrasi Materi PDF pada Halaman Web

Selain fitur utama, project ini juga menyediakan halaman materi versi web. File PDF praktikum dan materi kuliah diletakkan pada folder:

```text
Lab7Web-2/file/
```

Materi tersebut ditampilkan pada halaman artikel melalui daftar materi, shortcut pertemuan, dan tombol unduh PDF.

Contoh file materi yang sudah tersedia:

```text
Modul Praktikum 10.pdf
Modul Praktikum 11.pdf
Modul Praktikum 12.pdf
Modul Praktikum 13.pdf
Modul Praktikum 14.pdf
CI4_REST_API_Development.pdf
Interactive_VueJS_3_Frontends.pdf
Arsitektur_SPA_VueJS.pdf
```

---

# Kesimpulan

Berdasarkan seluruh proses praktikum, project **Lab7Web-2** sudah berkembang dari aplikasi CodeIgniter sederhana menjadi aplikasi web dengan fitur yang cukup lengkap. Fitur yang sudah selesai meliputi halaman artikel, CRUD admin, login, relasi kategori, upload gambar, AJAX dashboard, REST API, frontend VueJS, SPA routing, login SPA, dan pengamanan API menggunakan token.

Dengan demikian, project ini sudah berada pada tahap akhir sesuai praktikum terakhir, yaitu **Praktikum 14: Keamanan API, Token, dan Axios Interceptors**.
