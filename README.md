# 🌟 Sistem Informasi Manajemen Relawan - Yayasan Anak-Anak Terang (AAT)

Aplikasi berbasis web ini dibangun untuk mempermudah pengelolaan relawan, penjadwalan kegiatan pendampingan, hingga penerbitan sertifikat digital secara otomatis bagi Yayasan Anak-Anak Terang (AAT) Indonesia.

## 🚀 Fitur Utama

- **Sistem Autentikasi & Multi-Role:** Menggunakan Laravel Breeze & Spatie Permission. Terdapat 3 tingkat hak akses:
  - **Super Admin Pusat:** Mengelola seluruh data kegiatan dari semua regional dan memiliki akses penuh untuk mendaftarkan akun Admin Sekre/Regional baru.
  - **Admin Sekre (Regional):** Mengelola kegiatan, mengelola daftar peserta, dan melakukan presensi khusus di wilayah domisilinya.
  - **Relawan:** Mendaftar kegiatan sesuai regionalnya, mengelola profil, dan mengunduh sertifikat.
- **Dashboard Dinamis & Informatif:** Tampilan panel kontrol yang menyesuaikan peran. Admin melihat statistik skala nasional/regional, sementara relawan melihat riwayat partisipasi, sertifikat terkumpul, dan kartu pengingat jadwal kegiatan terdekat.
- **Manajemen Kegiatan Lengkap:** Pembuatan acara lengkap dengan lokasi spesifik, jam mulai/selesai, kuota pendaftar, dan poster kegiatan.
- **Filter Regional Dinamis:** Relawan dan Admin hanya akan melihat data kegiatan dan relawan yang relevan dengan domisili/sekretariat mereka.
- **Pencarian & Paginasi (Data Skala Besar):** Dilengkapi fitur pencarian *real-time* dan pembagian halaman (pagination) pada manajemen pengguna dan kegiatan untuk performa web yang cepat.
- **Export Data Absensi (CSV/Excel):** Admin dapat mengunduh daftar pendaftar (termasuk Nama, WhatsApp, dan ID SIANAS) sebagai format `.csv` untuk keperluan absensi cetak di lapangan.
- **Sertifikat Digital Otomatis (PDF):** Relawan yang ditandai "Hadir" (Check-in) pada hari-H oleh Admin, dapat langsung mengunduh sertifikat penghargaan dalam format PDF yang elegan secara otomatis.
- **UI/UX Modern & Responsif:** Desain antarmuka dikustomisasi menggunakan Tailwind CSS dengan mengusung identitas warna khas AAT (Biru & Kuning).

## 🛠️ Teknologi yang Digunakan

- **Framework:** Laravel 12
- **Frontend:** Tailwind CSS, Alpine.js, Blade Templates
- **Database:** MySQL
- **Library Tambahan:**
  - `spatie/laravel-permission` (Manajemen Hak Akses & Peran)
  - `barryvdh/laravel-dompdf` (Generator Sertifikat PDF)

## 💻 Panduan Instalasi (Environment Development)

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di komputer lokal (*localhost*):

1. **Clone Repositori:**
   ```bash
   git clone https://github.com/Ryonandha/relawan-aat.git
   cd relawan-aat

2. **Install Dependensi Backend & Frontend:**
Pastikan Anda sudah menginstal PHP, Composer, dan Node.js.
```bash
composer install
npm install
```


3. **Konfigurasi Environment:**
Salin file `.env.example` menjadi `.env`, lalu sesuaikan kredensial *database* Anda (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
```bash
cp .env.example .env
php artisan key:generate
```


4. **Link Folder Storage (Penting untuk Gambar & Sertifikat):**
Agar file gambar/poster yang diunggah dapat diakses secara publik.
```bash
php artisan storage:link
```


5. **Migrasi dan Inisialisasi Data (Seeder):**
Jalankan perintah ini untuk membangun tabel *database* dan menyuntikkan akun *dummy* komplit (termasuk Regional, Pengurus, Relawan, dan Kegiatan masa lalu/depan).
```bash
php artisan migrate:fresh --seed
```


6. **Jalankan Aplikasi:**
Buka dua terminal (*command prompt*) terpisah dan jalankan kedua perintah ini:
```bash
php artisan serve
```


```bash
npm run dev
```


Aplikasi sekarang dapat diakses melalui browser di: `http://localhost:8000`

## 🔑 Akun Default (Hasil Seeder)

Setelah menjalankan `php artisan migrate:fresh --seed`, gunakan kredensial berikut untuk menguji sistem:

* **Super Admin Pusat (Akses Semua Wilayah):**
* Email: `pusat@aat.or.id`
* Password: `password`


* **Admin Sekre Regional (Akses Terbatas Per Wilayah):**
* Email: `purwokerto@aat.or.id`  *(atau `yogyakarta@aat.or.id`)*
* Password: `password`


* **Relawan (Contoh Akun yang Sudah Terdaftar Kegiatan):**
* Email: `budi@gmail.com`
* Password: `password`