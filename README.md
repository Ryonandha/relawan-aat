# 🌟 Sistem Informasi Manajemen Relawan - Yayasan Anak-Anak Terang (AAT)

Aplikasi berbasis web ini dibangun untuk mempermudah pengelolaan relawan, penjadwalan kegiatan pendampingan, hingga penerbitan sertifikat digital secara otomatis bagi Yayasan Anak-Anak Terang (AAT) Indonesia.

## 🚀 Fitur Utama

- **Sistem Autentikasi & Multi-Role:** Menggunakan Laravel Breeze & Spatie Permission. Terdapat 3 tingkat hak akses:
  - **Super Admin Pusat:** Mengelola seluruh data kegiatan dari semua regional dan memiliki akses penuh untuk mendaftarkan akun Admin Sekre/Regional baru.
  - **Admin Sekre (Regional):** Mengelola kegiatan (Membuat, Melihat, Menambahkan Poster Kegiatan), mengelola daftar peserta, dan melakukan presensi khusus di wilayah domisilinya.
  - **Relawan:** Mendaftar kegiatan sesuai regionalnya, mengelola profil (termasuk update domisili sekre), dan mengunduh sertifikat.
- **Filter Regional Dinamis:** Sistem secara cerdas memfilter tampilan kegiatan. Relawan dan Admin hanya akan melihat data kegiatan yang relevan dengan domisili/sekretariat mereka.
- **Manajemen Kegiatan & Presensi:** Admin dapat membuat acara baru lengkap dengan fitur *upload* poster/cover kegiatan, melihat daftar pendaftar, dan menandai kehadiran (*check-in*) peserta pada hari-H.
- **Dashboard Statistik:** Menampilkan ringkasan data secara *real-time* seperti Total Relawan, Total Sekre Aktif, dan Total Kegiatan.
- **Sertifikat Digital Otomatis (PDF):** Relawan yang ditandai "Hadir" oleh Admin dapat langsung mengunduh sertifikat PDF yang di-*generate* secara otomatis menggunakan `laravel-dompdf`.
- **UI/UX Modern & Responsif:** Desain antarmuka dikustomisasi menggunakan Tailwind CSS dengan mengusung identitas warna khas AAT (Biru Navy & Kuning Emas).

## 🛠️ Teknologi yang Digunakan

- **Framework:** Laravel 12
- **Frontend:** Tailwind CSS, Alpine.js, Blade Templates
- **Database:** MySQL
- **Library Tambahan:**
  - `spatie/laravel-permission` (Manajemen Hak Akses & Peran)
  - `barryvdh/laravel-dompdf` (Generator Sertifikat PDF)

## 📦 Panduan Instalasi (Environment Development)

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di komputer lokal (*localhost*) atau *server* pengujian:

1. **Clone Repositori:**
   ```bash
   git clone https://github.com/Ryonandha/relawan-aat.git
   cd relawan-aat

2. **Install Dependensi Backend & Frontend:**
Pastikan Anda sudah menginstal PHP, Composer, dan Node.js di komputer Anda.
```bash
composer install
npm install
```

3. **Konfigurasi Environment:**
Salin file pengaturan bawaan menjadi file `.env` aktif, lalu sesuaikan kredensial database Anda (Nama database, *username*, dan *password*).
```bash
cp .env.example .env
php artisan key:generate

```

4. **Link Folder Storage (Penting untuk Upload Foto):**
Agar file gambar/poster yang diunggah dapat diakses secara publik.
```bash
php artisan storage:link

```

5. **Migrasi dan Inisialisasi Data (Seeder):**
Jalankan perintah ini untuk membangun tabel *database* dan menyuntikkan akun *dummy* (Super Admin & Admin Sekre) ke dalam sistem.
```bash
php artisan migrate --seed

```

6. **Jalankan Aplikasi:**
Buka dua terminal (*command prompt*) dan jalankan kedua perintah ini secara bersamaan:
```bash
php artisan serve

```

```bash
npm run dev

```

Aplikasi sekarang dapat diakses melalui browser di: `http://localhost:8000`

## 🔐 Akun Default (Untuk Testing)

Gunakan kredensial berikut untuk menguji sistem pertama kali:

* **Super Admin Pusat:**
* Email: `superadmin@aat.or.id` (atau `superadmin@gmail.com` bergantung pada konfigurasi seeder Anda)
* Password: `password`


* **Admin Sekre (Contoh: Purwokerto):**
* Email: `purwokerto@aat.or.id` (atau `admin@gmail.com`)
* Password: `password`


* **Relawan:** Silakan daftar secara langsung (buat akun baru) melalui menu **Register** di halaman utama website.
