# lab8_vuejs

Project ini dibuat untuk Praktikum 11 dan Praktikum 12 Pemrograman Web 2.

## Praktikum 11
Frontend menggunakan VueJS 3 dan Axios untuk mengakses REST API CodeIgniter 4.

Fitur:
- Menampilkan artikel.
- Menambah artikel.
- Mengubah artikel.
- Menghapus artikel.

## Praktikum 12
Project dikembangkan menjadi Single Page Application menggunakan Vue Router.

Route:
- `/` untuk Beranda
- `/artikel` untuk Kelola Artikel
- `/about` untuk profil mahasiswa

## Cara Menjalankan
1. Jalankan backend CodeIgniter:
   ```bash
   php spark serve
   ```
2. Pastikan API aktif:
   ```text
   http://localhost:8080/post
   ```
3. Buka frontend:
   ```text
   http://localhost/lab8_vuejs/
   ```

Jika backend tidak berjalan di `http://localhost:8080`, ubah nilai `apiUrl` di:

```text
assets/js/app.js
```


## Praktikum 13

Frontend ditambahkan halaman Login dan Navigation Guards. Route `/artikel` dan `/about` hanya dapat dibuka setelah login berhasil.

## Praktikum 14

Axios Interceptors ditambahkan agar token dari `localStorage` otomatis dikirim ke backend melalui header `Authorization: Bearer <token>`. Endpoint POST, PUT, PATCH, dan DELETE pada backend dilindungi oleh filter API.
