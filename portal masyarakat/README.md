# Portal Masyarakat

Portal Masyarakat adalah aplikasi pengaduan berbasis Laravel. Warga bisa mengirim dan memantau laporan, sedangkan admin dapat meninjau laporan serta melihat akun yang terdaftar.

## Cara kerja singkat

Saat pengguna membuka halaman, Laravel mencari alamatnya di `routes/web.php`. Route meneruskan permintaan ke controller, controller mengolah data melalui model, lalu halaman ditampilkan menggunakan Blade.

Contohnya, laporan dibuat dari form di `resources/views/complaints/create.blade.php`, diproses oleh `ComplaintController`, lalu disimpan ke database.

## File yang sering digunakan

- `routes/web.php` — mengatur alamat halaman dan siapa yang boleh membukanya.
- `app/Http/Controllers/AuthController.php` — menangani login, pendaftaran, dan logout.
- `app/Http/Controllers/ComplaintController.php` — menangani dashboard serta proses pengaduan warga dan admin.
- `app/Http/Controllers/AdminUserController.php` — menampilkan dan mencari akun pengguna untuk admin.
- `app/Http/Middleware/EnsureUserHasRole.php` — membatasi halaman berdasarkan role.
- `app/Models/User.php` — data pengguna dan relasinya dengan laporan.
- `app/Models/Complaint.php` — data laporan, termasuk kategori, status, dan prioritas.
- `resources/views/layouts/app.blade.php` — tampilan utama, sidebar, dan tema aplikasi.
- `resources/views/auth/` — halaman login dan pendaftaran.
- `resources/views/complaints/` — dashboard warga, form laporan, riwayat, dan dashboard admin.
- `resources/views/admin/users/index.blade.php` — daftar akun yang dapat dilihat admin.
- `database/migrations/` — membuat dan mengubah struktur tabel.
- `database/seeders/DatabaseSeeder.php` — menyiapkan akun demo untuk lokal dan testing.
- `tests/Feature/AuthTest.php` — menguji fitur login, role, laporan, dan halaman admin.
- `composer.json` dan `package.json` — mencatat dependency PHP dan JavaScript.

## Role dan halaman

Pendaftaran baru selalu membuat akun **user**. Role admin tidak dapat dipilih dari form pendaftaran.

- **User:** ringkasan di `/user/dashboard`, membuat laporan di `/user/complaints/create`, dan melihat laporan sendiri di `/user/complaints`.
- **Admin:** mengelola laporan di `/admin/dashboard` dan melihat akun di `/admin/users`.
- Setelah login, `/dashboard` mengarahkan pengguna ke halaman sesuai rolenya.

Halaman admin hanya bisa dibuka oleh admin, begitu juga halaman warga hanya untuk user yang sudah login. Laporan warga juga hanya dapat dilihat oleh pemiliknya; admin dapat melihat semua laporan.

## Menjalankan aplikasi

Pastikan PHP 8.1+ dan MySQL/MariaDB tersedia. Siapkan `.env` lokal dengan konfigurasi database, lalu buat database yang sesuai. Jangan membagikan isi `.env` karena dapat berisi informasi sensitif.

Jalankan perintah berikut dari folder proyek:

```bash
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Seeder membuat akun demo berikut dan hanya dapat dijalankan pada environment `local` atau `testing`:

| Role | Email | Password |
| --- | --- | --- |
| User | `user@example.com` | `12345678` |
| Admin | `admin@example.com` | `12345678` |

Akun ini hanya untuk keperluan lokal, jangan digunakan di server produksi.

Untuk menjalankan test:

```bash
php artisan test
```

Test menggunakan SQLite. Pastikan extension PHP `pdo_sqlite` dan `sqlite3` aktif jika perintah test mengeluhkan driver SQLite.
