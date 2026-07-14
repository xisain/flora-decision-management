# Flora Decision Management

Flora Decision Management (FDM) adalah aplikasi berbasis web yang dikembangkan untuk membantu pengelolaan koleksi tanaman di Kebun Raya Bundayati Bulungan. Selain mendukung proses administrasi koleksi tanaman, aplikasi ini juga mengimplementasikan Sistem Pendukung Keputusan (SPK) menggunakan metode **PROMETHEE II (Preference Ranking Organization Method for Enrichment Evaluation II)** untuk menghasilkan rekomendasi prioritas tanaman konservasi berdasarkan kriteria yang telah ditentukan oleh ahli.

## Latar Belakang

Pengelolaan koleksi tanaman konservasi memerlukan proses pencatatan yang terstruktur serta pengambilan keputusan yang terukur. Proses penentuan prioritas tanaman yang masih dilakukan secara manual berpotensi menimbulkan inkonsistensi dan membutuhkan waktu yang cukup lama.

Flora Decision Management dikembangkan untuk membantu proses tersebut dengan menyediakan sistem informasi yang mampu mengelola data koleksi tanaman sekaligus menghasilkan rekomendasi prioritas konservasi menggunakan metode PROMETHEE II.

## Fitur

Sistem memiliki beberapa modul utama, yaitu:

- Dashboard
- Manajemen pengguna
- Manajemen role dan hak akses
- Manajemen data tanaman
- Manajemen data spesies
- Manajemen data genus
- Manajemen data famili
- Manajemen lokasi
- Manajemen kriteria
- Penerimaan tanaman
- Penyemaian tanaman
- Inspeksi tanaman
- Sistem pendukung keputusan menggunakan metode PROMETHEE II
- Laporan hasil rekomendasi tanaman

## Tahapan Inspeksi

Tahapan inspeksi tanaman pada sistem terdiri dari:

- Checkup
- Labeling
- Aklimatisasi
- Evaluasi
- Siap Tanam

## Metode SPK

Metode yang digunakan pada sistem adalah PROMETHEE II dengan tahapan sebagai berikut.

1. Menentukan alternatif tanaman.
2. Menentukan kriteria penilaian.
3. Menentukan bobot setiap kriteria.
4. Menghitung nilai preferensi antar alternatif.
5. Menghitung Leaving Flow.
6. Menghitung Entering Flow.
7. Menghitung Net Flow.
8. Menghasilkan peringkat tanaman berdasarkan nilai Net Flow.

## Teknologi yang Digunakan

### Backend

- Laravel 13
- PHP 8.3

### Frontend

- Blade
- Tailwind CSS
- Alpine.js

### Database

- MySQL

### Build Tools

- Vite
- Composer
- npm

## Struktur Direktori

```
app/
├── Http/
├── Models/
├── Services/
├── Repositories/
└── Policies/

database/
├── migrations/
├── seeders/
└── factories/

resources/
├── css/
├── js/
└── views/

routes/
├── web.php
└── auth.php
```

## Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/xisain/flora-decision-management.git

cd flora-decision-management
```

### 2. Install Dependency

```bash
composer install

npm install
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env

php artisan key:generate
```

Sesuaikan konfigurasi database pada file `.env`.

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=flora_decision_management
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrasi Database

```bash
php artisan migrate --seed
```

### 5. Membuat Symbolic Link Storage

```bash
php artisan storage:link
```

### 6. Menjalankan Aplikasi

Menjalankan server Laravel.

```bash
php artisan serve
```

Menjalankan Vite.

```bash
npm run dev
```

## Peran Pengguna

Sistem memiliki tiga jenis pengguna.

| Role | Deskripsi |
|-------|-----------|
| Administrator | Mengelola data master, pengguna, role, kriteria, dan konfigurasi sistem. |
| Teknisi Registrasi | Mengelola proses penerimaan tanaman beserta data pendukungnya. |
| Teknisi Pembibitan | Mengelola penyemaian, inspeksi tanaman, serta proses rekomendasi tanaman. |



## Pengembangan Selanjutnya

Beberapa pengembangan yang dapat dilakukan pada sistem antara lain:

- Pengembangan aplikasi mobile menggunakan Flutter.
- Integrasi pemindaian barcode atau QR Code.
- Penyediaan REST API.
- Integrasi dengan Sistem Informasi Geografis (SIG).
- Penambahan fitur pelaporan dan analisis data.
- Penambahan sistem notifikasi.

## Penelitian

Aplikasi ini dikembangkan sebagai implementasi penelitian dengan judul:

**Sistem Pendukung Keputusan Tanaman Konservasi di Kebun Raya Bundayati Bulungan Berbasis Web dengan Metode PROMETHEE II**

## Lisensi

Repositori ini menggunakan lisensi MIT. Silakan sesuaikan apabila menggunakan lisensi lain.
