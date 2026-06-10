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
  <img src="https://img.shields.io/badge/SQLite-Database-003B57?style=for-the-badge&logo=sqlite" alt="SQLite">
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

### 🛡️ Secure Streaming Engine (Keamanan Video)
*   **URL Video Tersembunyi:** Path asli video di server tidak dibocorkan di sisi client.
*   **Chunk-Based File Streaming:** Video dikirimkan dalam potongan data (chunk stream) untuk menghemat bandwidth, mendukung buffering cepat, dan mencegah pengunduhan langsung secara ilegal.
*   **Real-time Validation:** Setiap potongan stream divalidasi keaktifan hak aksesnya secara terus-menerus.

### ⚙️ Panel Admin Premium (Filament v3)
*   **User Management:** Pengelolaan data pengguna dan penetapan peran (Admin / Customer).
*   **Category Management:** Pengelompokan video berdasarkan topik/mata pelajaran.
*   **Video Manager:** Fitur upload video baru, pengaturan deskripsi, dan upload thumbnail gambar.
*   **Request Approval Panel:** Persetujuan permohonan akses video lengkap dengan pengaturan durasi kedaluwarsa akses (`valid_until`).
*   **Activity Logs:** Audit trail otomatis yang mencatat riwayat aktivitas login, request, dan penayangan video.

---

## 🛠️ Stack Teknologi

*   **Framework Utama:** Laravel 12
*   **Database:** SQLite (Default, portable & tidak membutuhkan instalasi server SQL tambahan)
*   **Admin Dashboard:** Filament v3
*   **CSS Styling:** TailwindCSS & Tailwind Nesting
*   **Frontend Tools:** Vite & Blade Templates
*   **Authentication:** Laravel Breeze

---

## 📋 Persyaratan Sistem

Sebelum memulai instalasi, pastikan sistem Anda memenuhi persyaratan berikut:
*   **PHP** `>= 8.2` (dengan ekstensi `pdo_sqlite`, `bcmath`, `ctype`, `fileinfo`, `openssl`, `token`, `xml`)
*   **Composer** `^2.0`
*   **Node.js** `>= 18.0` & **NPM**

---

## 🚀 Langkah Instalasi & Konfigurasi

EduStream dilengkapi dengan otomatisasi script pada `composer.json` sehingga proses instalasi dapat diselesaikan dengan sangat mudah.

### 1. Kloning Repositori
Kloning repositori ke komputer lokal Anda:
```bash
git clone https://github.com/FaizalDwiA/mediatama-edu-stream-laravel.git edustream
cd edustream
```

### 2. Setup Otomatis (Sangat Direkomendasikan)
Jalankan perintah berikut untuk menginstal dependensi PHP, membuat file `.env`, membuat database SQLite kosong, generate application key, menjalankan migrasi database, menginstal paket Node, dan membuild aset frontend secara otomatis:
```bash
composer setup
```

*Jika Anda ingin melakukan langkah tersebut secara manual, silakan ikuti petunjuk berikut:*
<details>
<summary><b>Klik untuk melihat langkah instalasi manual</b></summary>

*   **Copy file environment:**
    ```bash
    copy .env.example .env
    ```
*   **Install PHP dependencies:**
    ```bash
    composer install
    ```
*   **Generate Application Key:**
    ```bash
    php artisan key:generate
    ```
*   **Buat File Database SQLite:**
    *   Buat file kosong di folder `database/database.sqlite` (untuk Windows PowerShell):
        ```powershell
        New-Item -Path "database/database.sqlite" -ItemType File
        ```
*   **Jalankan Migrasi Database:**
    ```bash
    php artisan migrate
    ```
*   **Install & Build Frontend Assets:**
    ```bash
    npm install
    npm run build
    ```
</details>

### 3. Generate Link Storage (PENTING)
Agar file video dan thumbnail yang diunggah melalui Filament Admin Panel dapat diakses di portal customer, Anda **wajib** membuat link storage ke public folder:
```bash
php artisan storage:link
```

### 4. Seed Database (Mengisi Data Demo)
Jalankan seeder untuk mengisi database dengan kategori pelajaran, beberapa contoh video, serta akun uji coba (Admin dan Customer):
```bash
php artisan db:seed
```

---

## 🔑 Akun Demo (Uji Coba)

Setelah proses seeding selesai, Anda dapat langsung masuk menggunakan akun default berikut untuk mencoba fungsionalitas sistem:

### 🛡️ Akun Administrator (Akses Dashboard Admin)
*   **Halaman Login:** [http://localhost:8000/admin/login](http://localhost:8000/admin/login)
*   **Email:** `admin@edustream.com`
*   **Password:** `password`

### 👥 Akun Customer (Akses Portal Dashboard Belajar)
*   **Halaman Login:** [http://localhost:8000/login](http://localhost:8000/login)
*   **Email:** `faizal@edustream.com`
*   **Password:** `password`

---

## 💻 Cara Menjalankan Aplikasi di Lokal

Untuk memulai server pengembangan lokal (local development server), jalankan perintah terintegrasi berikut:

```bash
composer dev
```

Perintah `composer dev` di atas akan menjalankan beberapa service secara bersamaan menggunakan `concurrently`:
1.  **Server Laravel:** Menjalankan `php artisan serve` pada `http://localhost:8000`.
2.  **Vite Server:** Menjalankan `npm run dev` untuk hot reload CSS & JS.
3.  **Queue Listener:** Menjalankan `php artisan queue:listen` untuk memproses job di latar belakang.
4.  **Log Tracker:** Menjalankan `php artisan pail` untuk memonitor error log secara langsung di terminal Anda.

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
