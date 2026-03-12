# Lab14Web

## Langkah-langkah Praktikum

### Persiapan
Sebelum memulai menggunakan Framework Codeigniter, perlu dilakukan konfigurasi pada
webserver. Beberapa ekstensi PHP perlu diaktifkan untuk kebutuhan pengembangan
Codeigniter 4.
Berikut beberapa ekstensi yang perlu diaktifkan:
• **php-json** ekstension untuk bekerja dengan JSON;
• **php-mysqlnd** native driver untuk MySQL;
• **php-xml** ekstension untuk bekerja dengan XML;
• **php-intl** ekstensi untuk membuat aplikasi multibahasa;
• **libcurl** (opsional), jika ingin pakai Curl.
Untuk mengaktifkan ekstentsi tersebut, melalu **XAMPP Control Panel**, pada bagian Apache
klik **Config -> PHP.ini**
<img width="1920" height="1080" alt="Cuplikan layar 2026-02-26 105947" src="https://github.com/user-attachments/assets/fe1dfcca-cd47-4967-9ef2-7f622e475c64" />


Pada bagian extention, hilangkan tanda ; (titik koma) pada ekstensi yang akan diaktifkan.
Kemudian simpan kembali filenya dan restart Apache web server.

<img width="606" alt="2" src="https://github.com/ZahraNurhaliza/Lab14Web/assets/115614417/b0d313d8-c12f-48c0-8b59-0424d777a122">


### Instalasi Codeigniter 4
Untuk melakukan instalasi Codeigniter 4 dapat dilakukan dengan dua cara, yaitu cara manual
dan menggunakan composer. Pada praktikum ini kita menggunakan cara manual.

• Unduh **Codeigniter** dari website https://codeigniter.com/download
• Extrak file zip Codeigniter ke direktori **htdocs/lab11_ci**.
• Ubah nama direktory **framework-4.x.xx** menjadi **ci4**.
• Buka browser dengan alamat http://localhost/lab11_ci/ci4/public/

<img width="1837" height="1032" alt="Cuplikan layar 2026-02-26 110613" src="https://github.com/user-attachments/assets/0ba1ed10-cc02-4065-8ee4-fc7ceb11eaf7" />



### Menjalankan CLI (Command Line Interface)
Codeigniter 4 menyediakan CLI untuk mempermudah proses development. Untuk mengakses
CLI buka terminal/command prompt.

<img width="458" alt="4" src="https://github.com/ZahraNurhaliza/Lab14Web/assets/115614417/6337c773-927c-407f-a857-45c1e66f5270">


Arahkan lokasi direktori sesuai dengan direktori kerja project dibuat
(xampp/**htdocs/lab11_ci/ci4/**)

Perintah yang dapat dijalankan untuk memanggil CLI Codeigniter adalah:
`php spark`

<img width="552" alt="5" src="https://github.com/ZahraNurhaliza/Lab14Web/assets/115614417/89c95cf3-aefc-49b2-b4b8-7282580f966f">


### Mengaktifkan Mode Debugging
Codeigniter 4 menyediakan fitur **debugging** untuk memudahkan developer untuk mengetahui
pesan error apabila terjadi kesalahan dalam membuat kode program.
Secara default fitur ini belum aktif. Ketika terjadi error pada aplikasi akan ditampilkan pesan
kesalahan seperti berikut.

<img width="960" alt="6" src="https://github.com/ZahraNurhaliza/Lab14Web/assets/115614417/00bc02f8-34d1-4035-94c9-db9c24cd5e4b">


Semua jenis error akan ditampilkan sama. Untuk memudahkan mengetahui jenis errornya,
maka perlu diaktifkan mode debugging dengan mengubah nilai konfigurasi pada environment
variable **CI_ENVIRINMENT** menjadi **development**.
Ubah nama file **env** menjadi **.env** kemudian buka file tersebut dan ubah nilai variable
**CI_ENVIRINMENT** menjadi **development**.
Contoh error yang terjadi. Untuk mencoba error tersebut, ubah kode pada file
**app/Controller/Home.php** hilangkan titik koma pada akhir kode.


### Membuat Route Baru.
Pada Codeigniter, request yang diterima oleh file index.php akan diarahkan ke Router untuk
meudian oleh router tesebut diarahkan ke Controller.
Router terletak pada file **app/config/Routes.php**
Pada file tersebut kita dapat mendefinisikan route untuk aplikasi yang kita buat.
Contoh:
```php
$routes->get('/', 'Home::index');
```
Kode tersebut akan mengarahkan rute untuk halaman home.
Tambahkan kode berikut di dalam Routes.php
```php
$routes->get('/about', 'Page::about');
$routes->get('/contact', 'Page::contact');
$routes->get('/faqs', 'Page::faqs');
```
Untuk mengetahui route yang ditambahkan sudah benar, buka CLI dan jalankan perintah
berikut.

`php spark routes`

<img width="594" alt="7" src="https://github.com/ZahraNurhaliza/Lab14Web/assets/115614417/b67ebb75-9e8f-4c1d-b58b-bbf75e1b1a61">


Selanjutnya coba akses route yang telah dibuat dengan mengakses alamat url
http://localhost:8080/about


### Membuat Controller
Selanjutnya adalah membuat Controller Page. Buat file baru dengan nama page.php pada
direktori Controller kemudian isi kodenya seperti berikut.
```php
<?php

namespace App\Controllers;

class Page extends BaseController
{
    public function about()
    {
    echo "Ini halaman About";
    }
    public function contact()
    {
    echo "Ini halaman Contact";
    }
    public function faqs()
    {
    echo "Ini halaman FAQ";
    }
}
```


### Auto Routing
Secara default fitur autoroute pada Codeiginiter sudah aktif. Untuk mengubah status autoroute
dapat mengubah nilai variabelnya. Untuk menonaktifkan ubah nilai true menjadi false.
`$routes->setAutoRoute(true);`

Tambahkan method baru pada **Controller Page** seperti berikut.
```php
public function tos()
{
echo "ini halaman Term of Services";
}
```

Method ini belum ada pada **routing**, sehingga cara mengaksesnya dengan menggunakan
alamat: http://localhost:8080/page/tos


### Membuat View
Selanjutnya adalam membuat view untuk tampilan web agar lebih menarik. Buat file baru
dengan nama about.php pada direktori view **(app/view/about.php)** kemudian isi kodenya seperti berikut.

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title; ?></title>
</head>
<body>
    <h1><?= $title; ?></h1>
    <hr>
    <p><?= $content; ?></p>
</body>
</html>
```

Ubah **method about** pada class **Controller Page** menjadi seperti berikut:

```php
public function about()
{
    return view('about', [
        'title' => 'Halaman Abot',
        'content' => 'Ini adalah halaman abaut yang menjelaskan tentang isi halaman ini.'
    ]);
}
```

Lakukan refresh pada halaman tersebut.

Kemudian buat folder **template** pada direktori **view** kemudian buat file **header.php** dan
**footer.php**
File **app/view/template/header.php**

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title; ?></title>
    <link rel="stylesheet" href="<?= base_url('/style.css');?>">
</head>
<body>
    <div id="container">
    <header>
        <h1>Layout Sederhana</h1>
    </header>
    <nav>
        <a href="<?= base_url('/');?>" class="active">Home</a>
        <a href="<?= base_url('/artikel');?>">Artikel</a>
        <a href="<?= base_url('/about');?>">About</a>
        <a href="<?= base_url('/contact');?>">Kontak</a>
    </nav>
    <section id="wrapper">
        <section id="main">
```

File **app/view/template/footer.php**

```html
        </section>
        <aside id="sidebar">
            <div class="widget-box">
                <h3 class="title">Widget Header</h3>
                <ul>
                    <li><a href="#">Widget Link</a></li>
                    <li><a href="#">Widget Link</a></li>
                </ul>
            </div>
            <div class="widget-box">
                <h3 class="title">Widget Text</h3>
                <p>Vestibulum lorem elit, iaculis in nisl volutpat,
                malesuada tincidunt arcu. Proin in leo fringilla, vestibulum mi porta,
                faucibus felis. Integer pharetra est nunc, nec pretium nunc pretium ac.</p>
            </div>
        </aside>
    </section>
    <footer>
        <p>&copy; 2021 - Universitas Pelita Bangsa</p>
    </footer>
    </div>
</body>
</html>
```

Kemudian ubah file **app/view/about.php** seperti berikut.
```php
<?= $this->include('template/header'); ?>
<h1><?= $title; ?></h1>
<hr>
<p><?= $content; ?></p>
<?= $this->include('template/footer'); ?>
```

Selanjutnya refresh tampilan pada alamat http://localhost:8080/about

<img width="960" alt="8" src="https://github.com/ZahraNurhaliza/Lab14Web/assets/115614417/04a70275-f855-4e96-a75d-413db35dc637">


# SELESAI

