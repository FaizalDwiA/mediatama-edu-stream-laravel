# 🚀 EduStream - Platform Streaming Video Edukasi & Manajemen Akses Aman

<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-v12.0-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel Version">
  <img src="https://img.shields.io/badge/Filament-v3.0-EBB308?style=for-the-badge&logo=laravel" alt="Filament Version">
  <img src="https://img.shields.io/badge/PHP-%3E%3D_8.2-777BB4?style=for-the-badge&logo=php" alt="PHP Version">
  <img src="https://img.shields.io/badge/TailwindCSS-v3.0-06B6D4?style=for-the-badge&logo=tailwindcss" alt="Tailwind Version">
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
</p>

---

## 📝 Tentang EduStream

**EduStream** adalah platform berbasis web untuk streaming video pembelajaran secara aman dan terkontrol. Aplikasi ini dirancang menggunakan **Laravel 12**, **Filament v3**, dan **TailwindCSS**. 

Dengan EduStream, administrator dapat mengelola video, kategori, dan hak akses pengguna secara efisien, sedangkan pengguna (customer) dapat menjelajah materi pembelajaran serta mengajukan permohonan akses video pembelajaran dengan batas waktu menonton tertentu.

---

## ⚡ Fitur Utama

### 👥 Portal Customer (User Dashboard)
*   **Video Hub:** Dashboard modern untuk mencari dan menjelajah video edukasi berdasarkan kategori atau pencarian judul.
*   **Sistem Request Akses:** Pengguna dapat meminta akses untuk video yang terkunci. Status permohonan akan diperbarui secara real-time.
*   **Pemutar Video Interaktif:** Player bawaan untuk memutar video pembelajaran setelah disetujui.
*   **Batas Waktu Menonton (Expiry System):** Akses menonton dibatasi waktu (misal: 24 jam) dan otomatis berubah status menjadi *Expired* ketika masanya habis.

### 🎨 Fitur Profil & Registrasi Premium
*   **Interactive Avatar Uploader:** Form input file dengan desain modern yang memiliki efek hover glowing, overlay tombol, preview real-time, dan status file yang interaktif (di halaman registrasi dan pengaturan profil).
*   **Kompresi Gambar Otomatis:** Sistem secara otomatis memotong (*crop*) dan mengompres foto profil menjadi format WebP berukuran 300x300 px menggunakan library **Intervention Image** (mengurangi beban storage dan mempercepat waktu muat).
*   **Integrasi Navigasi & Dashboard:** Foto profil terintegrasi penuh di pojok kanan atas navbar portal customer serta di Dashboard Admin Filament (melalui kontrak `HasAvatar` pada model User).
*   **Pengaman Registrasi Baru:** Formulir registrasi dilengkapi dengan indikator kekuatan password *real-time* untuk membantu pengguna memilih kata sandi yang aman dan checklist persetujuan Syarat & Ketentuan.

### 🛡️ Secure Streaming Engine (Keamanan Video)
*   **URL Video Tersembunyi:** Path asli video di server tidak dibocorkan di sisi client.
*   **Chunk-Based File Streaming:** Video dikirimkan dalam potongan data (chunk stream) untuk menghemat bandwidth, mendukung buffering cepat, dan mencegah pengunduhan langsung secara ilegal.
*   **Real-time Validation:** Setiap potongan stream divalidasi keaktifan hak aksesnya secara terus-menerus.

### ⚙️ Panel Admin Premium (Filament v3)
*   **User Management:** Pengelolaan data pengguna dan penetapan peran (Admin / Customer).
*   **Category Management:** Pengelompokan video berdasarkan topik/mata pelajaran.
*   **Video Manager:** Fitur upload video baru, pengaturan deskripsi, dan upload thumbnail gambar.
*   **Auto File Cleanup (Storage Management):** Saat admin mengganti thumbnail atau file video pada halaman edit, file lama di storage akan otomatis dihapus **setelah data berhasil disimpan**. Begitu pula saat record video dihapus, seluruh file terkait (thumbnail & video) ikut terhapus secara otomatis — mencegah penumpukan file sampah di server.
*   **Request Approval Panel:** Persetujuan permohonan akses video lengkap dengan pengaturan durasi kedaluwarsa akses (`valid_until`).
*   **Activity Logs:** Audit trail otomatis yang mencatat riwayat aktivitas login, request, dan penayangan video.

---

## 🛠️ Stack Teknologi

*   **Framework Utama:** Laravel 12
*   **Database:** MySQL
*   **Admin Dashboard:** Filament v3
*   **CSS Styling:** TailwindCSS & Tailwind Nesting
*   **Frontend Tools:** Vite & Blade Templates
*   **Authentication:** Laravel Breeze
*   **Image Processing:** Intervention Image v3 & Intervention Image Laravel

---

## 📋 Persyaratan Sistem

Sebelum memulai instalasi, pastikan sistem Anda memenuhi persyaratan berikut:
*   **PHP** `>= 8.2` (dengan ekstensi `pdo_mysql`, `bcmath`, `ctype`, `fileinfo`, `openssl`, `token`, `xml`)
*   **MySQL / MariaDB**
*   **Composer** `^2.0`
*   **Node.js** `>= 18.0` & **NPM**

---

## 🚀 Langkah Instalasi & Konfigurasi

### 1. Kloning Repositori
Kloning repositori ke komputer lokal Anda:
```bash
git clone https://github.com/FaizalDwiA/mediatama-edu-stream-laravel.git edustream
cd edustream
```

### 2. Konfigurasi Environment & Database MySQL
1. Copy file environment dari template `.env.example`:
   ```bash
   copy .env.example .env
   ```
2. Buat database baru bernama **`edustream`** pada server MySQL Anda (melalui phpMyAdmin, MySQL CLI, atau DBeaver).
3. Buka file `.env` yang baru dibuat dan sesuaikan konfigurasi database Anda jika berbeda dengan default:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=edustream
   DB_USERNAME=root
   DB_PASSWORD=
   ```

### 3. Instalasi Dependensi
Jalankan perintah berikut untuk menginstal dependensi PHP dan Node:
```bash
composer install
npm install
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Jalankan Migrasi Database (php artisan migrate)
Perintah ini wajib dijalankan untuk membuat seluruh tabel database (users, videos, categories, access_requests, activity_logs) pada database MySQL Anda:
```bash
php artisan migrate
```

### 6. Isi Data Demo (Seeding)
Jalankan seeder untuk mengisi database dengan kategori pelajaran, beberapa contoh video, serta akun uji coba bawaan (Admin dan Customer):
```bash
php artisan db:seed
```

> 💡 **Tips Praktis:** Anda dapat menggabungkan langkah migrasi dan seed di atas dengan satu perintah:
> ```bash
> php artisan migrate --seed
> ```

### 7. Generate Link Storage (PENTING)
Agar file video dan thumbnail yang diunggah melalui Filament Admin Panel dapat diakses di portal customer, Anda **wajib** membuat link storage ke public folder:
```bash
php artisan storage:link
```

### 8. Build Frontend Assets
Compile aset frontend menggunakan Vite:
```bash
npm run build
```

---

## 🔑 Akun Demo (Uji Coba)

Setelah proses seeding selesai, Anda dapat langsung masuk menggunakan akun default berikut untuk mencoba fungsionalitas sistem:

### 🛡️ Akun Administrator (Akses Dashboard Admin)
*   **Halaman Login:** [http://127.0.0.1:174/login](http://127.0.0.1:174/login)
*   **Email:** `admin@edustream.com`
*   **Password:** `password`

### 👥 Portal Customer (Akses Portal Dashboard Belajar)
*   **Halaman Login:** [http://127.0.0.1:174/login](http://127.0.0.1:174/login)
*   **Email:** `faizal@edustream.com`
*   **Password:** `password`

---

## 💻 Cara Menjalankan Aplikasi di Lokal

Untuk memulai server pengembangan lokal (local development server), jalankan perintah terintegrasi berikut:

```bash
composer dev
```

Perintah `composer dev` di atas akan menjalankan beberapa service secara bersamaan menggunakan `concurrently`:
1.  **Server Laravel:** Menjalankan `php artisan serve` pada `http://127.0.0.1:174`.
2.  **Vite Server:** Menjalankan `npm run dev` untuk hot reload CSS & JS (otomatis membuka browser).
3.  **Queue Listener:** Menjalankan `php artisan queue:listen` untuk memproses job di latar belakang.

*Catatan: Pastikan server database MySQL (seperti XAMPP / Laragon) Anda sudah dalam keadaan aktif sebelum menjalankan aplikasi.*

---

## 📂 Struktur Folder Utama

*   [CustomerVideoController.php](file:///app/Http/Controllers/CustomerVideoController.php): Berisi logika routing video, sistem request akses, validasi kedaluwarsa waktu menonton secara real-time, dan streaming engine video aman.
*   [Resources](file:///app/Filament/Resources): Kumpulan resource Filament v3 untuk panel admin (pengelolaan Users, Videos, Categories, Access Requests, dan Activity Logs).
*   [Models](file:///app/Models): Model database (`User`, `Video`, `Category`, `AccessRequest`, `ActivityLog`).
*   [web.php](file:///routes/web.php): Rute web utama untuk portal belajar customer.
*   [auth.php](file:///routes/auth.php): Rute login & registrasi bawaan Laravel Breeze.
*   [views](file:///resources/views): Berkas tampilan Blade untuk user interface dashboard, pemutar video (`watch`), dan profil.

---

## 📝 Catatan Tambahan (Pengembangan)

*   **Penyimpanan Video:** Semua file video diunggah ke storage lokal di folder `storage/app/public/`. Streaming engine membaca file dari direktori tersebut sehingga video dilindungi dan tidak dapat diputar langsung tanpa autentikasi/akses aktif.
*   **Pengaturan Waktu:** Waktu kedaluwarsa video dihitung menggunakan pustaka Carbon dengan membandingkan waktu saat ini dengan kolom `valid_until` pada tabel `access_requests`. Ketika user membuka dashboard, status request yang melewati masa berlaku akan diperbarui menjadi `expired`.
*   **Auto File Cleanup:** Ketika admin mengganti thumbnail atau file video melalui halaman edit, file lama di server **hanya dihapus setelah data berhasil tersimpan** ke database. Jika admin membatalkan sebelum menyimpan, file lama tetap aman. Ketika record video dihapus, file thumbnail dan video di storage ikut terhapus secara otomatis untuk menjaga kebersihan server.

