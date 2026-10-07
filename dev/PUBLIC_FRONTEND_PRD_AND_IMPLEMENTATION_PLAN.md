# PRD & Implementation Plan — Flora Public Frontend
## Fitur: Tempat Menarik, Berita, dan Lokasi Tanaman

**Versi:** 1.0.0  
**Tanggal:** 2026-09-26  
**Aplikasi:** Flora Decision Management — Kebun Raya Bundayati  
**Stack:** Laravel 12 · Blade · Alpine.js · Tailwind CSS · Vite

---

## Daftar Isi

1. [Latar Belakang & Konteks](#1-latar-belakang--konteks)
2. [PRD — Product Requirements](#2-prd--product-requirements)
   - 2.1 Goals & Non-Goals
   - 2.2 User Personas
   - 2.3 Fitur Requirements
   - 2.4 Success Metrics
3. [Analisis Kondisi Saat Ini](#3-analisis-kondisi-saat-ini)
4. [Arsitektur Solusi](#4-arsitektur-solusi)
5. [Database Layer](#5-database-layer)
6. [Backend Layer](#6-backend-layer)
7. [Routing & Middleware](#7-routing--middleware)
8. [Frontend Layer](#8-frontend-layer)
9. [Dependensi Frontend](#9-dependensi-frontend)
10. [Rencana Pengerjaan](#10-rencana-pengerjaan)
11. [Verification Plan](#11-verification-plan)
12. [Open Questions](#12-open-questions)

---

## 1. Latar Belakang & Konteks

Aplikasi **Flora Decision Management** saat ini berfungsi sebagai sistem internal untuk mengelola siklus hidup tanaman di Kebun Raya Bundayati — mulai dari penerimaan (eksplorasi/introduksi), penyemaian, inspeksi, hingga seleksi koleksi dengan metode PROMETHEE II.

Sistem internal sudah berjalan, namun **tidak ada antarmuka publik** yang bisa diakses masyarakat umum. Halaman `/` saat ini langsung meng-*redirect* ke halaman login, sehingga pengunjung tidak bisa melihat informasi apapun tentang kebun raya.

Tiga fitur baru diusulkan untuk membangun **portal publik** Kebun Raya Bundayati:

| Fitur | Tujuan |
|---|---|
| **Tempat Menarik** | Showcase zona/area menarik di kebun raya untuk menarik pengunjung |
| **Berita** | Publikasi artikel, event, dan pengumuman seputar kebun raya |
| **Lokasi Tanaman** | Peta interaktif yang menunjukkan lokasi fisik tanaman koleksi di kebun raya |

---

## 2. PRD — Product Requirements

### 2.1 Goals & Non-Goals

#### ✅ Goals
- Membangun **landing page publik** yang bisa diakses tanpa login
- Menampilkan **Tempat Menarik** dengan foto, deskripsi, dan kategori area kebun raya
- Menampilkan **Berita** berupa artikel, event, dan pengumuman yang bisa di-filter dan dibaca lengkap
- Menampilkan **Lokasi Tanaman** sebagai peta interaktif berdasarkan data koleksi yang sudah ada di sistem (KebunRayaKoleksi)
- Menyediakan panel **Admin CRUD** untuk mengelola ketiga konten tersebut
- Memperbaiki **bug & kelemahan middleware** yang ada saat ini

#### ❌ Non-Goals (tidak dicakup dalam scope ini)
- Sistem komentar atau interaksi pengunjung
- Autentikasi pengunjung / sistem member publik
- Multi-bahasa (i18n)
- Push notification atau email newsletter
- Mobile app (Android/iOS)
- Sistem pembayaran tiket masuk
- Integrasi GPS real-time petugas lapangan

### 2.2 User Personas

```
┌─────────────────────────────────────────────────────────────┐
│  PERSONA 1: Pengunjung Umum (Guest)                         │
│  ─────────────────────────────────────────────────────────  │
│  • Tidak login, mengakses dari browser/HP                   │
│  • Ingin tahu: ada apa di kebun raya, tanaman apa saja,     │
│    berita terbaru, lokasi tanaman favorit                   │
│  • Journey: Landing → Tempat Menarik → Berita → Peta        │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│  PERSONA 2: Admin Konten (role: admin)                      │
│  ─────────────────────────────────────────────────────────  │
│  • Login sebagai admin                                      │
│  • Membuat/edit/hapus Tempat Menarik, Berita, Lokasi        │
│  • Publish/unpublish berita sebelum tampil ke publik        │
│  • Upload foto untuk tempat menarik & cover berita          │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│  PERSONA 3: Peneliti / Teknisi (role: teknisi registrasi    │
│             / teknisi pembibitan)                           │
│  ─────────────────────────────────────────────────────────  │
│  • Login seperti biasa                                      │
│  • TIDAK mengelola konten publik                            │
│  • Bisa mengakses landing page setelah login (redirect ke   │
│    dashboard masing-masing)                                 │
└─────────────────────────────────────────────────────────────┘
```

### 2.3 Fitur Requirements

---

#### Feature A — Landing Page (Halaman Utama Publik)

| ID | Requirement | Prioritas |
|---|---|---|
| LP-01 | Route `/` menampilkan landing page, bukan redirect ke login | 🔴 Wajib |
| LP-02 | Navbar menampilkan link ke Tempat Menarik, Berita, Peta Tanaman dengan `href` nyata | 🔴 Wajib |
| LP-03 | Hero section dengan foto & tagline kebun raya | 🟡 Penting |
| LP-04 | Section Tempat Menarik — tampilkan 3 item featured terbaru dari DB | 🔴 Wajib |
| LP-05 | Section Berita — tampilkan 3 berita terpublish terbaru dari DB | 🔴 Wajib |
| LP-06 | Section Koleksi Unggulan / Peta — mini peta atau grid tanaman koleksi | 🟡 Penting |
| LP-07 | Tombol "Lihat Semua" pada tiap section mengarah ke halaman masing-masing | 🔴 Wajib |
| LP-08 | User yang sudah login: tombol "Go to Dashboard" di hero mengarah sesuai role | 🟡 Penting |
| LP-09 | Halaman fully responsive (mobile-first) | 🟡 Penting |

---

#### Feature B — Tempat Menarik

**B.1 — Halaman Publik**

| ID | Requirement | Prioritas |
|---|---|---|
| TM-01 | Halaman `/jelajah/tempat-menarik` menampilkan semua tempat menarik aktif | 🔴 Wajib |
| TM-02 | Setiap card menampilkan: foto, nama, kategori (badge), dan deskripsi singkat | 🔴 Wajib |
| TM-03 | Filter by kategori (Taman, Greenhouse, Danau, Jalan, Fasilitas) | 🟡 Penting |
| TM-04 | Halaman detail `/jelajah/tempat-menarik/{id}` dengan foto penuh dan deskripsi lengkap | 🟢 Nice-to-have |
| TM-05 | Halaman terintegrasi dengan section landing page (ambil 3 item `is_featured = true`) | 🔴 Wajib |

**B.2 — Admin Panel**

| ID | Requirement | Prioritas |
|---|---|---|
| TM-A01 | Tabel index semua tempat menarik dengan kolom: nama, kategori, featured, urutan, aksi | 🔴 Wajib |
| TM-A02 | Form tambah/edit: nama, deskripsi, kategori, foto upload, toggle featured, urutan | 🔴 Wajib |
| TM-A03 | Upload foto — disimpan ke `storage/public/tempat-menarik/` | 🔴 Wajib |
| TM-A04 | Hapus item beserta fotonya | 🔴 Wajib |
| TM-A05 | Validasi: nama wajib, foto wajib saat create, max 2MB per foto | 🔴 Wajib |

---

#### Feature C — Berita

**C.1 — Halaman Publik**

| ID | Requirement | Prioritas |
|---|---|---|
| BR-01 | Halaman `/jelajah/berita` menampilkan semua berita published, newest first | 🔴 Wajib |
| BR-02 | Setiap card menampilkan: foto cover, judul, kategori, ringkasan, tanggal publish | 🔴 Wajib |
| BR-03 | Filter by kategori: Berita, Event, Pengumuman | 🟡 Penting |
| BR-04 | Halaman detail `/jelajah/berita/{slug}` dengan konten rich text penuh | 🔴 Wajib |
| BR-05 | Halaman detail: tampilkan penulis, tanggal, kategori, foto cover | 🔴 Wajib |
| BR-06 | Breadcrumb navigasi di halaman detail | 🟢 Nice-to-have |
| BR-07 | "Berita Terkait" (same kategori, limit 3) di halaman detail | 🟢 Nice-to-have |

**C.2 — Admin Panel**

| ID | Requirement | Prioritas |
|---|---|---|
| BR-A01 | Tabel index: judul, kategori, penulis, status (draft/published), tanggal, aksi | 🔴 Wajib |
| BR-A02 | Form tambah/edit: judul, slug (auto-generate, editable), ringkasan, konten (rich text), foto cover, kategori, penulis, status | 🔴 Wajib |
| BR-A03 | Rich text editor (Quill.js) untuk kolom `konten` | 🔴 Wajib |
| BR-A04 | Tombol "Publish / Unpublish" toggle langsung dari tabel index | 🟡 Penting |
| BR-A05 | Slug auto-generate dari judul saat user mengetik, tapi bisa di-override manual | 🟡 Penting |
| BR-A06 | Upload foto cover — simpan ke `storage/public/berita/` | 🔴 Wajib |
| BR-A07 | Preview halaman berita sebelum publish | 🟢 Nice-to-have |
| BR-A08 | Validasi: judul, konten, kategori wajib; slug unique; foto max 5MB | 🔴 Wajib |

---

#### Feature D — Lokasi Tanaman (Peta Interaktif)

**D.1 — Halaman Publik**

| ID | Requirement | Prioritas |
|---|---|---|
| LT-01 | Halaman `/jelajah/peta-tanaman` menampilkan peta interaktif (Leaflet.js + OpenStreetMap) | 🔴 Wajib |
| LT-02 | Setiap marker di peta mewakili satu `tanaman_penerimaan` yang punya lokasi tercatat | 🔴 Wajib |
| LT-03 | Klik marker → popup berisi: nama ilmiah, nama lokal, zona/blok, nomor plot | 🔴 Wajib |
| LT-04 | Filter by kategori habitus (Tree / Shrub) | 🟡 Penting |
| LT-05 | Search tanaman by nama di peta | 🟢 Nice-to-have |
| LT-06 | Data peta diambil via endpoint JSON (`/api/lokasi-tanaman`) | 🔴 Wajib |
| LT-07 | Mini peta (tidak interactive) embed di landing page sebagai preview | 🟡 Penting |

**D.2 — Admin Panel**

| ID | Requirement | Prioritas |
|---|---|---|
| LT-A01 | Tabel index lokasi terdaftar: nama tanaman, zona, nomor plot, koordinat, aksi | 🔴 Wajib |
| LT-A02 | Form tambah: pilih `tanaman_penerimaan` dari dropdown (autocomplete), input koordinat lat/lng, zona, nomor plot, keterangan | 🔴 Wajib |
| LT-A03 | Mini peta klik-untuk-ambil-koordinat di form tambah/edit | 🟡 Penting |
| LT-A04 | Validasi koordinat: latitude -90 s/d 90, longitude -180 s/d 180 | 🔴 Wajib |
| LT-A05 | Satu `tanaman_penerimaan` hanya boleh punya satu record lokasi (unique constraint) | 🔴 Wajib |

---

#### Feature E — Perbaikan Middleware & Infrastructure

| ID | Requirement | Prioritas |
|---|---|---|
| MW-01 | Route `/` render landing page publik (ganti dari redirect ke login) | 🔴 Wajib |
| MW-02 | Semua middleware menggunakan `optional()` untuk akses `roles` — cegah crash null | 🔴 Wajib |
| MW-03 | Link navbar publik menggunakan `href="{{ route(...) }}"` bukan Alpine.js `navTo()` | 🔴 Wajib |
| MW-04 | Admin sidebar menambahkan menu untuk Tempat Menarik, Berita, Lokasi Tanaman | 🔴 Wajib |
| MW-05 | Redirect post-login sesuai role: admin → `/admin`, peneliti → `/registrasi` atau `/pembibitan` | 🟡 Penting |
| MW-06 | File upload disimpan di `storage/public/` dan di-symlink via `php artisan storage:link` | 🔴 Wajib |

### 2.4 Success Metrics

| Metric | Target |
|---|---|
| Pengunjung bisa membuka landing page tanpa login | ✅ Route `/` returns 200 |
| Semua section landing page render data dari database (bukan dummy) | ✅ LandingPageController inject data |
| CRUD admin Tempat Menarik berfungsi end-to-end | ✅ Manual test CRUD |
| CRUD admin Berita berfungsi end-to-end termasuk publish/unpublish | ✅ Manual test + toggle test |
| Peta Leaflet render minimal 1 marker dari data DB | ✅ API endpoint `/api/lokasi-tanaman` returns GeoJSON |
| Tidak ada crash middleware akibat `roles` null | ✅ `optional()` dipakai di semua middleware |
| Semua link navbar berfungsi (bukan dead link) | ✅ Browser navigation test |

---

## 3. Analisis Kondisi Saat Ini

### 3.1 Yang Sudah Ada (Existing Assets)

| Asset | Lokasi | Status |
|---|---|---|
| Skeleton landing page | `resources/views/home.blade.php` | ✅ Ada, tapi data dummy statis |
| Layout publik | `resources/views/layout/app.blade.php` | ✅ Fungsional |
| Navbar publik | `resources/views/components/navigation/app.blade.php` | ⚠️ Link `navTo()` belum wired ke route |
| Footer | `resources/views/components/footer.blade.php` | ✅ Ada |
| Layout admin | `resources/views/layout/admin.blade.php` | ✅ Fungsional dengan sidebar |
| Middleware auth | 4 file middleware | ⚠️ Perlu defensive check `optional()` |
| Model `KebunRayaKoleksi` | `app/Models/KebunRayaKoleksi.php` | ✅ Bisa jadi sumber "Koleksi Unggulan" |
| Model `TanamanInfo` | `app/Models/TanamanInfo.php` | ✅ Scientific name, nama lokal, dll |
| Model `PenerimaanTanaman` | `app/Models/PenerimaanTanaman.php` | ✅ Sudah ada `vak_no`, `locality` |

### 3.2 Masalah Yang Harus Diperbaiki

```
BUG-01: Route '/' → redirect ke login
        Impact: Pengunjung publik tidak bisa melihat apapun
        File: routes/web.php L23-25

BUG-02: Navbar navTo() tidak terhubung ke route Laravel
        Impact: Klik "Tanaman", "Koleksi", "Berita" tidak berpindah halaman
        File: resources/views/components/navigation/app.blade.php L12-28

BUG-03: Middleware bisa crash jika user.roles = null
        Impact: Server 500 error jika data role corrupt/missing
        File: semua *Middleware.php — $request->user()->roles->name

BUG-04: home.blade.php masih data dummy (@for loop statis)
        Impact: Section Tempat Menarik, Berita, Koleksi tidak menampilkan data nyata
        File: resources/views/home.blade.php
```

### 3.3 Pemetaan Role ID

Dari `resources/views/layout/admin.blade.php`:
```
roles_id == 1  → admin
roles_id == 2  → teknisi pembibitan  (pembibitanMiddleware)
roles_id == 3  → teknisi registrasi  (RegistrasiMiddleware)
```

---

## 4. Arsitektur Solusi

### 4.1 Diagram Arsitektur Keseluruhan

```
┌──────────────────────────────────────────────────────────────┐
│                    BROWSER / CLIENT                          │
└──────────────────┬───────────────────────────────────────────┘
                   │ HTTP Request
┌──────────────────▼───────────────────────────────────────────┐
│                    ROUTES (web.php + api.php)                │
│                                                              │
│  PUBLIC (no auth)          ADMIN (auth + adminMiddleware)    │
│  ─────────────────         ──────────────────────────────    │
│  GET /                     GET  /admin/tempat-menarik        │
│  GET /jelajah/tempat-      POST /admin/tempat-menarik        │
│       menarik              GET  /admin/berita                │
│  GET /jelajah/berita       POST /admin/berita                │
│  GET /jelajah/berita/{slug}PATCH /admin/berita/{id}/publish  │
│  GET /jelajah/peta-        GET  /admin/lokasi-tanaman        │
│       tanaman              POST /admin/lokasi-tanaman        │
│                                                              │
│  API PUBLIC                                                  │
│  GET /api/lokasi-tanaman   (GeoJSON, no auth)                │
└──────────┬──────────────────────────────────────────────────-┘
           │
┌──────────▼───────────────────────────────────────────────────┐
│                 MIDDLEWARE STACK                              │
│                                                              │
│  Public routes: [tanpa middleware]                           │
│  Admin routes:  [auth → registeredAccount → adminMiddleware] │
│  API route:     [tanpa middleware / throttle saja]           │
└──────────┬───────────────────────────────────────────────────┘
           │
┌──────────▼───────────────────────────────────────────────────┐
│                    CONTROLLERS                               │
│                                                              │
│  app/Http/Controllers/                                       │
│  ├── Public/                                                 │
│  │   ├── LandingPageController.php                          │
│  │   ├── TempatMenarikPublicController.php                  │
│  │   ├── BeritaPublicController.php                         │
│  │   └── LokasiTanamanController.php  (juga serve API JSON) │
│  └── Admin/                                                  │
│      ├── TempatMenarikAdminController.php                   │
│      ├── BeritaAdminController.php                          │
│      └── LokasiTanamanAdminController.php                   │
└──────────┬───────────────────────────────────────────────────┘
           │
┌──────────▼───────────────────────────────────────────────────┐
│                    MODELS & DATABASE                         │
│                                                              │
│  tempat_menarik  →  TempatMenarik.php                        │
│  berita          →  Berita.php                               │
│  lokasi_tanaman  →  LokasiTanaman.php  (FK: tanaman_         │
│                                         penerimaan_id)       │
│                                                              │
│  Existing relations yang dipakai:                            │
│  KebunRayaKoleksi → Tanaman → PenerimaanTanaman → TanamanInfo│
└──────────────────────────────────────────────────────────────┘
```

### 4.2 Flow Navigasi Publik

```
Landing Page (/)
     │
     ├─── [Tempat Menarik] ──→ /jelajah/tempat-menarik
     │                               │
     │                               └──→ /jelajah/tempat-menarik/{id}  (detail)
     │
     ├─── [Berita] ──────────→ /jelajah/berita
     │                               │
     │                               └──→ /jelajah/berita/{slug}  (artikel penuh)
     │
     └─── [Peta Tanaman] ────→ /jelajah/peta-tanaman
                                     │
                                     └──→ fetch /api/lokasi-tanaman  (GeoJSON)
```

---

## 5. Database Layer

### 5.1 Tabel Baru: `tempat_menarik`

**File:** `database/migrations/YYYY_MM_DD_xxxxxx_create_tempat_menarik_table.php`

```php
Schema::create('tempat_menarik', function (Blueprint $table) {
    $table->id();
    $table->string('nama');
    $table->text('deskripsi');
    $table->string('foto');                          // path relatif di storage/public
    $table->enum('kategori', [
        'taman', 'greenhouse', 'danau', 'jalan', 'fasilitas', 'lainnya'
    ])->default('lainnya');
    $table->boolean('is_featured')->default(false);  // tampil di landing page
    $table->integer('urutan')->default(0);           // urutan tampil di grid
    $table->timestamps();
});
```

**Indeks yang diperlukan:**
- `index('is_featured')` — query landing page hanya ambil yang featured
- `index('urutan')` — sorting

---

### 5.2 Tabel Baru: `berita`

**File:** `database/migrations/YYYY_MM_DD_xxxxxx_create_berita_table.php`

```php
Schema::create('berita', function (Blueprint $table) {
    $table->id();
    $table->string('judul');
    $table->string('slug')->unique();
    $table->text('ringkasan');
    $table->longText('konten');                     // HTML dari rich text editor
    $table->string('foto_cover')->nullable();       // path relatif di storage/public
    $table->enum('kategori', ['berita', 'event', 'pengumuman'])->default('berita');
    $table->string('penulis');
    $table->boolean('is_published')->default(false);
    $table->timestamp('published_at')->nullable();  // diisi saat pertama kali di-publish
    $table->timestamps();
});
```

**Indeks yang diperlukan:**
- `unique('slug')` — untuk URL SEO-friendly
- `index(['is_published', 'published_at'])` — query publik: berita published, newest first
- `index('kategori')` — filter by kategori

---

### 5.3 Tabel Baru: `lokasi_tanaman`

**File:** `database/migrations/YYYY_MM_DD_xxxxxx_create_lokasi_tanaman_table.php`

**Rationale desain FK:** FK ke `tanaman_penerimaans` (bukan ke `tanaman`), karena dalam konteks kebun raya, satu nomor akses (species batch) biasanya ditanam di satu zona/blok yang sama. Data spesies (nama ilmiah, nama lokal) tersedia via `tanaman_penerimaan → tanaman_info`.

```php
Schema::create('lokasi_tanaman', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tanaman_penerimaan_id')
          ->unique()                               // satu batch = satu lokasi
          ->constrained('tanaman_penerimaans')
          ->cascadeOnDelete();
    $table->decimal('latitude', 10, 8);            // -90.00000000 s/d 90.00000000
    $table->decimal('longitude', 11, 8);           // -180.00000000 s/d 180.00000000
    $table->string('zona')->nullable();             // misal: "Zona A", "Blok III"
    $table->string('nomor_plot')->nullable();       // misal: "A-12", "III-07"
    $table->text('keterangan')->nullable();
    $table->timestamps();
});
```

**Catatan:** Field `vak_no` sudah ada di `tanaman_penerimaans`, namun tidak mengandung koordinat GPS. Tabel baru ini menambahkan koordinat agar bisa ditampilkan di Leaflet map.

---

### 5.4 ERD Lengkap (Existing + New)

```
penerimaans ─────────────────────┐
    │                            │
    │ hasMany                    │ belongsTo
    ▼                            │
tanaman_penerimaans ◄────────────┘
    │ │                 \
    │ │ hasMany          \── hasOne ──→ [NEW] lokasi_tanaman
    │ │                              (lat, lng, zona, nomor_plot)
    │ ▼
    │ tanaman_infos
    │ (scientific_name, nama_lokal, ...)
    │
    │ hasMany
    ▼
tanaman
    │
    │ hasMany
    ▼
kebun_raya_koleksi
(ranking, net_flow, ...)


[NEW] tempat_menarik
(nama, foto, kategori, is_featured, urutan)

[NEW] berita
(judul, slug, konten, foto_cover, is_published, ...)
```

---

## 6. Backend Layer

### 6.1 Models Baru

#### `app/Models/TempatMenarik.php`

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table('tempat_menarik')]
#[Fillable('nama', 'deskripsi', 'foto', 'kategori', 'is_featured', 'urutan')]
class TempatMenarik extends Model
{
    protected function casts(): array
    {
        return ['is_featured' => 'boolean'];
    }

    // Accessor: URL lengkap foto
    public function getFotoUrlAttribute(): string
    {
        return asset('storage/' . $this->foto);
    }

    // Scope untuk landing page
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->orderBy('urutan');
    }
}
```

#### `app/Models/Berita.php`

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable('judul', 'slug', 'ringkasan', 'konten', 'foto_cover', 'kategori',
           'penulis', 'is_published', 'published_at')]
class Berita extends Model
{
    protected function casts(): array
    {
        return [
            'is_published'  => 'boolean',
            'published_at'  => 'datetime',
        ];
    }

    // Auto-generate slug dari judul
    public static function generateSlug(string $judul): string
    {
        $base = Str::slug($judul);
        $count = static::where('slug', 'like', $base . '%')->count();
        return $count ? "{$base}-{$count}" : $base;
    }

    // Scope: hanya yang published dan tanggalnya sudah lewat
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                     ->whereNotNull('published_at')
                     ->orderByDesc('published_at');
    }

    // Accessor: URL foto cover
    public function getFotoCoverUrlAttribute(): ?string
    {
        return $this->foto_cover ? asset('storage/' . $this->foto_cover) : null;
    }
}
```

#### `app/Models/LokasiTanaman.php`

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('lokasi_tanaman')]
#[Fillable('tanaman_penerimaan_id', 'latitude', 'longitude', 'zona', 'nomor_plot', 'keterangan')]
class LokasiTanaman extends Model
{
    public function tanamanPenerimaan(): BelongsTo
    {
        return $this->belongsTo(PenerimaanTanaman::class, 'tanaman_penerimaan_id');
    }

    // Helper: return sebagai GeoJSON Feature
    public function toGeoJsonFeature(): array
    {
        $info = $this->tanamanPenerimaan->TanamanInfo;
        return [
            'type' => 'Feature',
            'geometry' => [
                'type'        => 'Point',
                'coordinates' => [(float) $this->longitude, (float) $this->latitude],
            ],
            'properties' => [
                'id'              => $this->id,
                'nomor_akses'     => $this->tanamanPenerimaan->nomor_akses,
                'scientific_name' => $info?->scientific_name,
                'nama_lokal'      => $info?->nama_lokal,
                'habitus'         => $this->tanamanPenerimaan->habitus,
                'zona'            => $this->zona,
                'nomor_plot'      => $this->nomor_plot,
                'keterangan'      => $this->keterangan,
            ],
        ];
    }
}
```

---

### 6.2 Controllers Baru

#### Public Controllers — `app/Http/Controllers/Public/`

**`LandingPageController.php`**
```
Method: index()
Query:
  - $tempatMenarik = TempatMenarik::featured()->limit(3)->get()
  - $beritaTerbaru = Berita::published()->limit(3)->get()
  - $koleksiUnggulan = KebunRayaKoleksi::with('tanaman.tanamanPenerimaan.TanamanInfo')
                        ->orderByDesc('ranking')->limit(6)->get()
Return: view('public.landing', compact(...))
```

**`TempatMenarikPublicController.php`**
```
Method: index()  → view('public.tempat-menarik.index') + semua TempatMenarik aktif
Method: show($id) → view('public.tempat-menarik.show') + 1 item
```

**`BeritaPublicController.php`**
```
Method: index()       → view('public.berita.index') + Berita::published()->paginate(9)
Method: show($slug)   → view('public.berita.show') + Berita::where('slug',$slug)->published()
```

**`LokasiTanamanController.php`** (dual purpose: view + API)
```
Method: index()   → view('public.peta.index')
Method: geojson() → response()->json(GeoJSON FeatureCollection dari semua LokasiTanaman)
```

---

#### Admin Controllers — `app/Http/Controllers/Admin/`

**`TempatMenarikAdminController.php`** — Resource Controller penuh
```
index()   → tabel semua record
create()  → form kosong
store()   → validasi + simpan foto ke storage + insert DB
edit()    → form prefill data existing
update()  → validasi + replace foto jika ada upload baru + update DB
destroy() → hapus record + hapus file foto dari storage
```

**`BeritaAdminController.php`** — Resource Controller + extra method
```
index()        → tabel dengan kolom status
create()       → form dengan Quill.js editor
store()        → auto-generate slug + simpan
edit()         → form prefill
update()       → update + handle foto baru
destroy()      → hapus record + foto
togglePublish() → toggle is_published + set/clear published_at
```

**`LokasiTanamanAdminController.php`** — Resource Controller
```
index()   → tabel dengan join ke tanaman_penerimaans
create()  → form + Leaflet mini map klik-koordinat
store()   → validasi + insert
edit()    → form prefill + peta dengan marker existing
update()  → update koordinat / zona / plot
destroy() → hapus record
```

---

### 6.3 Form Requests Baru

**`app/Http/Requests/StoreTempatMenarikRequest.php`**
```php
'nama'      => 'required|string|max:255',
'deskripsi' => 'required|string',
'foto'      => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
'kategori'  => 'required|in:taman,greenhouse,danau,jalan,fasilitas,lainnya',
'urutan'    => 'nullable|integer|min:0',
'is_featured' => 'boolean',
```

**`app/Http/Requests/UpdateTempatMenarikRequest.php`**
```php
// Sama, hanya 'foto' → 'nullable|image|...' (tidak wajib jika tidak ganti foto)
```

**`app/Http/Requests/StoreBeritaRequest.php`**
```php
'judul'      => 'required|string|max:255',
'slug'       => 'nullable|string|unique:berita,slug|max:255',
'ringkasan'  => 'required|string|max:500',
'konten'     => 'required|string',
'foto_cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
'kategori'   => 'required|in:berita,event,pengumuman',
'penulis'    => 'required|string|max:255',
'is_published' => 'boolean',
```

**`app/Http/Requests/StoreLokasiTanamanRequest.php`**
```php
'tanaman_penerimaan_id' => 'required|exists:tanaman_penerimaans,id|unique:lokasi_tanaman',
'latitude'              => 'required|numeric|between:-90,90',
'longitude'             => 'required|numeric|between:-180,180',
'zona'                  => 'nullable|string|max:100',
'nomor_plot'            => 'nullable|string|max:50',
'keterangan'            => 'nullable|string',
```

---

## 7. Routing & Middleware

### 7.1 Perubahan `routes/web.php`

```php
<?php
// ============================================================
// IMPORT BARU yang perlu ditambahkan
// ============================================================
use App\Http\Controllers\Public\LandingPageController;
use App\Http\Controllers\Public\TempatMenarikPublicController;
use App\Http\Controllers\Public\BeritaPublicController;
use App\Http\Controllers\Public\LokasiTanamanController;
use App\Http\Controllers\Admin\TempatMenarikAdminController;
use App\Http\Controllers\Admin\BeritaAdminController;
use App\Http\Controllers\Admin\LokasiTanamanAdminController;

// ============================================================
// [UBAH] Route home — dari redirect ke rendering view
// ============================================================
// SEBELUM:
// Route::get('/', fn() => redirect()->route('login'))->name('home');

// SESUDAH:
Route::get('/', [LandingPageController::class, 'index'])->name('home');

// ============================================================
// [BARU] Public Routes — tidak perlu auth
// ============================================================
Route::prefix('jelajah')->name('public.')->group(function () {
    Route::get('tempat-menarik', [TempatMenarikPublicController::class, 'index'])
         ->name('tempat-menarik.index');
    Route::get('tempat-menarik/{tempatMenarik}', [TempatMenarikPublicController::class, 'show'])
         ->name('tempat-menarik.show');

    Route::get('berita', [BeritaPublicController::class, 'index'])
         ->name('berita.index');
    Route::get('berita/{slug}', [BeritaPublicController::class, 'show'])
         ->name('berita.show');

    Route::get('peta-tanaman', [LokasiTanamanController::class, 'index'])
         ->name('peta.index');
});

// ============================================================
// [TAMBAH DALAM GRUP ADMIN YANG ADA]
// Di dalam: Route::prefix('admin')->middleware([...])->group(...)
// ============================================================
Route::resource('tempat-menarik', TempatMenarikAdminController::class)
     ->names('admin.tempat-menarik');

Route::resource('berita', BeritaAdminController::class)
     ->names('admin.berita');
Route::patch('berita/{berita}/publish', [BeritaAdminController::class, 'togglePublish'])
     ->name('admin.berita.togglePublish');

Route::resource('lokasi-tanaman', LokasiTanamanAdminController::class)
     ->names('admin.lokasi-tanaman');
```

### 7.2 `routes/api.php` — Endpoint GeoJSON

```php
use App\Http\Controllers\Public\LokasiTanamanController;

// Endpoint publik untuk Leaflet map
Route::get('lokasi-tanaman', [LokasiTanamanController::class, 'geojson'])
     ->name('api.lokasi-tanaman');
```

---

### 7.3 Perbaikan Middleware

#### Fix 1 — `adminMiddleware.php`

```php
// SEBELUM (crash jika roles = null):
if ($request->user()->roles->name === 'admin') { ... }

// SESUDAH:
public function handle(Request $request, Closure $next): Response
{
    if (optional($request->user()->roles)->name === 'admin') {
        return $next($request);
    }
    abort(403, 'Tidak Memiliki Akses');
}
```

#### Fix 2 — `RegistrasiMiddleware.php`

```php
// SEBELUM:
if ($request->user()->roles->name === 'teknisi registrasi') { ... }

// SESUDAH:
if (optional($request->user()->roles)->name === 'teknisi registrasi') {
    return $next($request);
}
abort(403, 'Tidak Memiliki Akses');
```

#### Fix 3 — `pembibitanMiddleware.php`

```php
// SEBELUM:
if ($request->user()->roles->name === 'teknisi pembibitan') { ... }

// SESUDAH:
if (optional($request->user()->roles)->name === 'teknisi pembibitan') {
    return $next($request);
}
abort(403, 'Tidak Memiliki Akses');
```

#### Fix 4 — Navbar: Ganti `navTo()` dengan `href` Route

```html
<!-- SEBELUM — di components/navigation/app.blade.php -->
<a @click="navTo('tanaman')">Tanaman</a>
<a @click="navTo('koleksi')">Koleksi</a>
<a @click="navTo('berita')">Berita</a>

<!-- SESUDAH -->
<a href="{{ route('public.peta.index') }}"
   class="{{ request()->routeIs('public.peta.*') ? 'active' : '' }}">
    Tanaman
</a>
<a href="{{ route('public.tempat-menarik.index') }}"
   class="{{ request()->routeIs('public.tempat-menarik.*') ? 'active' : '' }}">
    Tempat Menarik
</a>
<a href="{{ route('public.berita.index') }}"
   class="{{ request()->routeIs('public.berita.*') ? 'active' : '' }}">
    Berita
</a>
```

#### Fix 5 — Post-Login Redirect Sesuai Role

**File:** `app/Http/Controllers/Auth/AuthenticatedSessionController.php`  
Cari method `store()` dan tambahkan logika redirect:

```php
// Setelah authenticate($request):
$user = $request->user();
$role = optional($user->roles)->name;

return match($role) {
    'admin'                => redirect()->intended(route('admin.home')),
    'teknisi registrasi'   => redirect()->intended(route('peneliti.registrasi.home')),
    'teknisi pembibitan'   => redirect()->intended(route('peneliti.pembibitan.home')),
    default                => redirect()->intended(route('home')),
};
```

---

## 8. Frontend Layer

### 8.1 Struktur Folder Views Lengkap

```
resources/views/
│
├── layout/
│   ├── app.blade.php          ✅ existing — layout publik
│   ├── admin.blade.php        ✅ existing — layout admin
│   └── auth.blade.php         ✅ existing
│
├── components/
│   ├── navigation/
│   │   ├── app.blade.php      ⚠️ UBAH — ganti navTo() dengan href route
│   │   ├── admin.blade.php    ⚠️ UBAH — tambah menu Konten Publik di sidebar
│   │   └── peneliti.blade.php ✅ tidak berubah
│   └── footer.blade.php       ✅ existing (minor update teks)
│
├── public/                    🆕 BARU — semua halaman publik
│   ├── landing.blade.php      🆕 refactor dari home.blade.php (dengan data real)
│   ├── tempat-menarik/
│   │   ├── index.blade.php    🆕 grid semua tempat menarik + filter kategori
│   │   └── show.blade.php     🆕 halaman detail satu tempat
│   ├── berita/
│   │   ├── index.blade.php    🆕 list berita + filter + pagination
│   │   └── show.blade.php     🆕 artikel full (render HTML dari Quill)
│   └── peta/
│       └── index.blade.php    🆕 Leaflet.js map full-screen + filter
│
├── admin/
│   ├── dashboard.blade.php    ⚠️ UBAH — tambah stat cards konten publik
│   ├── tempat-menarik/        🆕 BARU
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   ├── berita/                🆕 BARU
│   │   ├── index.blade.php
│   │   ├── create.blade.php   (dengan Quill.js)
│   │   └── edit.blade.php     (dengan Quill.js)
│   └── lokasi-tanaman/        🆕 BARU
│       ├── index.blade.php
│       ├── create.blade.php   (dengan Leaflet mini map)
│       └── edit.blade.php     (dengan Leaflet mini map)
│
└── home.blade.php             ⚠️ DEPRECATE → pindahkan ke public/landing.blade.php
```

---

### 8.2 Landing Page (`public/landing.blade.php`)

**Data yang diterima dari `LandingPageController`:**
```php
$tempatMenarik   // Collection<TempatMenarik> — 3 featured
$beritaTerbaru   // Collection<Berita>        — 3 published terbaru
$koleksiUnggulan // Collection<KebunRayaKoleksi> — 6 koleksi ranking teratas
```

**Wireframe Section-by-Section:**
```
┌─────────────────────────────────────────────────────┐
│  NAVBAR (components/navigation/app.blade.php)       │
│  Logo | Tanaman | Tempat Menarik | Berita | Login   │
└─────────────────────────────────────────────────────┘
┌─────────────────────────────────────────────────────┐
│  HERO SECTION                                       │
│  ┌─────────────────────┐  ┌──────────────────────┐ │
│  │ "Kebun Raya         │  │                      │ │
│  │  Bundayati"         │  │   [header.png]       │ │
│  │                     │  │                      │ │
│  │ [Jelajahi Sekarang] │  │                      │ │
│  │ [Go to Dashboard]   │  │                      │ │
│  └─────────────────────┘  └──────────────────────┘ │
└─────────────────────────────────────────────────────┘
┌─────────────────────────────────────────────────────┐
│  SECTION: TEMPAT MENARIK                           │
│  "Tempat Menarik"                    [Lihat Semua →]│
│  ┌─────────┐  ┌─────────┐  ┌─────────┐            │
│  │  [foto] │  │  [foto] │  │  [foto] │            │
│  │  Nama   │  │  Nama   │  │  Nama   │            │
│  │ [badge] │  │ [badge] │  │ [badge] │            │
│  └─────────┘  └─────────┘  └─────────┘            │
└─────────────────────────────────────────────────────┘
┌─────────────────────────────────────────────────────┐
│  SECTION: BERITA TERBARU                           │
│  "Berita Seputar Kebun Raya"         [Lihat Semua →]│
│  ┌──────────────────────┐ ┌──────────────────────┐ │
│  │ [foto cover]         │ │ [foto cover]         │ │
│  │ [BADGE KATEGORI]     │ │ [BADGE KATEGORI]     │ │
│  │ Judul Berita...      │ │ Judul Berita...      │ │
│  │ Ringkasan singkat    │ │ Ringkasan singkat    │ │
│  │ 26 Sep 2026 · Penulis│ │ ...                  │ │
│  └──────────────────────┘ └──────────────────────┘ │
└─────────────────────────────────────────────────────┘
┌─────────────────────────────────────────────────────┐
│  SECTION: KOLEKSI UNGGULAN & PETA                  │
│  ┌──────────────────────┐  ┌──────────────────────┐│
│  │ Grid 3x2 tanaman     │  │                      ││
│  │ koleksi ranking      │  │   [LEAFLET MAP       ││
│  │ teratas              │  │    EMBED - preview]  ││
│  │                      │  │                      ││
│  │ [Lihat Semua →]      │  │ [Buka Peta Lengkap →]││
│  └──────────────────────┘  └──────────────────────┘│
└─────────────────────────────────────────────────────┘
┌─────────────────────────────────────────────────────┐
│  FOOTER                                             │
└─────────────────────────────────────────────────────┘
```

---

### 8.3 Halaman Peta Tanaman (`public/peta/index.blade.php`)

Menggunakan **Leaflet.js** dengan OpenStreetMap (tidak butuh API key).

```html
@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Koordinat center kebun raya (ganti dengan koordinat nyata)
    const map = L.map('peta-tanaman').setView([-2.9, 117.4], 15);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Ambil data dari API endpoint
    fetch('{{ route("api.lokasi-tanaman") }}')
        .then(r => r.json())
        .then(geojson => {
            L.geoJSON(geojson, {
                pointToLayer: function(feature, latlng) {
                    return L.circleMarker(latlng, {
                        radius: 8,
                        fillColor: feature.properties.habitus === 'tree' ? '#2d6a4f' : '#74c69d',
                        color: '#fff',
                        weight: 2,
                        fillOpacity: 0.9
                    });
                },
                onEachFeature: function(feature, layer) {
                    const p = feature.properties;
                    layer.bindPopup(`
                        <div class="popup-tanaman">
                            <b>${p.scientific_name ?? 'Nama tidak tersedia'}</b>
                            <p>${p.nama_lokal ?? ''}</p>
                            <hr>
                            <small>No. Akses: ${p.nomor_akses}</small><br>
                            <small>Zona: ${p.zona ?? '-'} | Plot: ${p.nomor_plot ?? '-'}</small>
                        </div>
                    `);
                }
            }).addTo(map);
        });
});
</script>
@endpush
```

---

### 8.4 Admin — Halaman Berita (dengan Quill.js)

```html
{{-- di admin/berita/create.blade.php dan edit.blade.php --}}
<div id="quill-editor" style="height: 400px;"></div>
<input type="hidden" name="konten" id="konten-input">

@push('scripts')
<link rel="stylesheet" href="https://cdn.quilljs.com/1.3.7/quill.snow.css">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
    const quill = new Quill('#quill-editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                [{ 'header': [1, 2, 3, false] }],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    // Pre-fill saat edit
    @if(isset($berita))
        quill.root.innerHTML = {!! json_encode($berita->konten) !!};
    @endif

    // Sync ke hidden input sebelum form submit
    document.querySelector('form').addEventListener('submit', function() {
        document.getElementById('konten-input').value = quill.root.innerHTML;
    });

    // Auto-generate slug dari judul
    document.getElementById('judul').addEventListener('input', function() {
        const slugField = document.getElementById('slug');
        if (!slugField.dataset.manual) {
            slugField.value = this.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-');
        }
    });
    document.getElementById('slug').addEventListener('input', function() {
        this.dataset.manual = 'true';
    });
</script>
@endpush
```

---

### 8.5 Admin — Sidebar Update (`components/navigation/admin.blade.php`)

Tambahkan grup menu baru "Konten Publik" di sidebar admin:

```html
{{-- Tambahkan setelah menu Management yang ada --}}
<div class="sidebar-group">
    <p class="sidebar-group-label">Konten Publik</p>

    <a href="{{ route('admin.tempat-menarik.index') }}"
       class="sidebar-link {{ request()->routeIs('admin.tempat-menarik.*') ? 'active' : '' }}">
        <i class="fa-solid fa-map-pin"></i>
        <span>Tempat Menarik</span>
    </a>

    <a href="{{ route('admin.berita.index') }}"
       class="sidebar-link {{ request()->routeIs('admin.berita.*') ? 'active' : '' }}">
        <i class="fa-solid fa-newspaper"></i>
        <span>Berita</span>
    </a>

    <a href="{{ route('admin.lokasi-tanaman.index') }}"
       class="sidebar-link {{ request()->routeIs('admin.lokasi-tanaman.*') ? 'active' : '' }}">
        <i class="fa-solid fa-location-dot"></i>
        <span>Lokasi Tanaman</span>
    </a>
</div>
```

---

### 8.6 Admin Dashboard — Tambahan Stat Cards

Di `admin/dashboard.blade.php`, tambahkan data baru ke array `$stats`:
```
Tempat Menarik  → $tempatMenarikCount (dari TempatMenarik::count())
Berita Published → $beritaCount       (dari Berita::published()->count())
Lokasi Tercatat → $lokasiCount        (dari LokasiTanaman::count())
```

---

## 9. Dependensi Frontend

### 9.1 Libraries Yang Diperlukan

| Library | Versi | Kegunaan | Cara Install | Butuh API Key? |
|---|---|---|---|---|
| **Leaflet.js** | 1.9.4 | Peta interaktif Lokasi Tanaman | CDN atau `npm install leaflet` | ❌ Tidak |
| **Quill.js** | 1.3.7 | Rich text editor untuk konten Berita | CDN atau `npm install quill` | ❌ Tidak |
| **OpenStreetMap** | — | Tile map untuk Leaflet | Otomatis via Leaflet | ❌ Tidak |

### 9.2 Cara Integrasi via NPM (Rekomendasi)

```bash
npm install leaflet quill
```

Tambahkan ke `resources/js/app.js`:
```js
import L from 'leaflet';
import Quill from 'quill';
import 'leaflet/dist/leaflet.css';
import 'quill/dist/quill.snow.css';

window.L = L;
window.Quill = Quill;
```

Atau via CDN langsung di view yang membutuhkan saja (lebih sederhana, cocok untuk scope kecil).

### 9.3 Storage Symlink

Wajib dijalankan sekali agar file upload bisa diakses publik:
```bash
php artisan storage:link
```

---

## 10. Rencana Pengerjaan

### Prioritas & Urutan

```
Phase 1 — Perbaikan Critical (Bug Fixes)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
[ ] MW-01: Ganti route '/' dari redirect ke LandingPageController
[ ] MW-02: Fix optional() di semua 3 middleware
[ ] MW-03: Ganti navTo() di navbar dengan href route nyata

Estimasi: 0.5 hari kerja

Phase 2 — Database & Models
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
[ ] Migration: create_tempat_menarik_table
[ ] Migration: create_berita_table
[ ] Migration: create_lokasi_tanaman_table
[ ] Model: TempatMenarik.php
[ ] Model: Berita.php
[ ] Model: LokasiTanaman.php
[ ] php artisan migrate
[ ] php artisan storage:link

Estimasi: 1 hari kerja

Phase 3 — Backend: Feature Tempat Menarik
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
[ ] Controller: TempatMenarikPublicController
[ ] Controller: TempatMenarikAdminController
[ ] Form Requests: Store + Update
[ ] Routes: public + admin
[ ] Views: public/tempat-menarik/index + show
[ ] Views: admin/tempat-menarik/index + create + edit

Estimasi: 1.5 hari kerja

Phase 4 — Backend: Feature Berita
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
[ ] Controller: BeritaPublicController
[ ] Controller: BeritaAdminController (+ togglePublish)
[ ] Form Requests: Store + Update
[ ] Routes: public + admin
[ ] Views: public/berita/index + show
[ ] Views: admin/berita/index + create + edit (dengan Quill.js)

Estimasi: 2 hari kerja

Phase 5 — Backend: Feature Lokasi Tanaman
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
[ ] Controller: LokasiTanamanController (view + GeoJSON endpoint)
[ ] Controller: LokasiTanamanAdminController
[ ] Form Requests: Store + Update
[ ] Routes: public + api + admin
[ ] Views: public/peta/index (Leaflet.js)
[ ] Views: admin/lokasi-tanaman/index + create + edit (mini map)

Estimasi: 2 hari kerja

Phase 6 — Landing Page & Polish
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
[ ] LandingPageController dengan query data nyata
[ ] Refactor home.blade.php → public/landing.blade.php
[ ] Update admin dashboard stat cards
[ ] Update admin sidebar menu
[ ] Fix footer teks brand
[ ] Post-login redirect sesuai role
[ ] Responsiveness test

Estimasi: 1.5 hari kerja

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
TOTAL ESTIMASI: ~8.5 hari kerja
```

### Dependency Map

```
Phase 1 (Bug Fixes)
    └──→ bisa jalan paralel dengan Phase 2

Phase 2 (DB + Models)
    └──→ harus selesai sebelum Phase 3, 4, 5

Phase 3 (Tempat Menarik) ─┐
Phase 4 (Berita)          ├──→ bisa paralel, semua butuh Phase 2
Phase 5 (Lokasi Tanaman)  ─┘

Phase 6 (Landing Page)
    └──→ butuh Phase 3, 4, 5 selesai (data harus ada di DB)
```

---

## 11. Verification Plan

### 11.1 Checklist Manual Testing

#### Landing Page
```
[ ] GET /  → returns 200, menampilkan layout public (bukan redirect login)
[ ] Section Tempat Menarik tampil data dari DB (bukan dummy for-loop)
[ ] Section Berita tampil data dari DB (bukan dummy for-loop)
[ ] Tombol "Lihat Semua" tiap section mengarah ke halaman yang benar
[ ] Login sebagai admin → hero button "Go to Dashboard" → /admin
[ ] Login sebagai teknisi → hero button → /registrasi atau /pembibitan
[ ] Guest user: tidak ada button Dashboard, hanya "Jelajahi Sekarang"
```

#### Tempat Menarik
```
[ ] GET /jelajah/tempat-menarik → 200, tampil semua data
[ ] Filter kategori bekerja (URL query ?kategori=taman)
[ ] GET /jelajah/tempat-menarik/{id} → 200, tampil detail
[ ] Admin: CREATE tempat menarik → form submit → redirect index dengan flash sukses
[ ] Admin: foto tersimpan di storage/public/tempat-menarik/
[ ] Admin: UPDATE tanpa ganti foto → foto lama tidak hilang
[ ] Admin: DELETE → record terhapus + file foto terhapus dari storage
[ ] Validasi: submit form kosong → error message tampil
```

#### Berita
```
[ ] GET /jelajah/berita → hanya tampilkan is_published = true
[ ] GET /jelajah/berita/{slug} → 200 jika published, 404 jika draft
[ ] Admin: CREATE berita → slug auto-generate dari judul
[ ] Admin: konten Quill.js tersimpan sebagai HTML di DB
[ ] Admin: PUBLISH → is_published = true + published_at terisi timestamp
[ ] Admin: UNPUBLISH → is_published = false (published_at tidak berubah)
[ ] Admin: EDIT slug manual → tidak tumpang tindih dengan slug lain
[ ] Validasi: slug duplicate → error "slug sudah digunakan"
```

#### Lokasi Tanaman
```
[ ] GET /jelajah/peta-tanaman → 200, peta Leaflet ter-render
[ ] GET /api/lokasi-tanaman → response JSON format GeoJSON FeatureCollection
[ ] Marker muncul di peta sesuai koordinat yang tersimpan di DB
[ ] Klik marker → popup muncul dengan info tanaman yang benar
[ ] Admin: CREATE lokasi → tanaman_penerimaan_id + koordinat tersimpan
[ ] Admin: satu tanaman_penerimaan tidak bisa punya 2 lokasi (unique constraint error)
[ ] Admin: koordinat di luar range → validasi error
```

#### Middleware
```
[ ] GET /admin/ sebagai guest → redirect ke login
[ ] GET /admin/ sebagai teknisi → 403 Tidak Memiliki Akses
[ ] GET /registrasi/ sebagai admin → 403
[ ] GET / sebagai guest → 200 (bukan redirect login)
[ ] User tanpa role → middleware tidak crash (optional() bekerja)
[ ] User dengan account_status != 1 → logout + flash error
```

### 11.2 Artisan Commands Untuk Testing

```bash
# Jalankan setelah migration
php artisan migrate --pretend       # dry-run, cek SQL yang akan dijalankan
php artisan migrate                 # jalankan migration

# Storage symlink
php artisan storage:link

# Route list — verifikasi semua route terdaftar
php artisan route:list --path=jelajah
php artisan route:list --path=admin
php artisan route:list --path=api

# Clear cache setelah perubahan route/config
php artisan route:clear
php artisan view:clear
php artisan config:clear
```

---

## 12. Open Questions

> **OQ-01 — Koordinat Kebun Raya**  
> Berapa koordinat GPS pusat Kebun Raya Bundayati yang akan dijadikan center map Leaflet?  
> Sementara menggunakan `-2.9, 117.4` (estimasi Bulungan, Kalimantan Utara).

> **OQ-02 — Role Konten Editor**  
> Apakah perlu menambahkan role baru `konten_editor` agar ada user yang bisa kelola Berita/Tempat Menarik tanpa punya akses ke manajemen data tanaman penelitian?  
> Saat ini rencana: hanya `admin` yang bisa kelola konten publik.

> **OQ-03 — Rich Text Gambar**  
> Apakah konten Berita boleh embed gambar inline dalam konten (via Quill image upload)?  
> Jika ya, perlu endpoint upload gambar inline terpisah. Jika tidak, foto hanya di field `foto_cover`.

> **OQ-04 — Koleksi Unggulan di Landing Page**  
> Section "Koleksi Unggulan" di landing page: apakah menampilkan tanaman dari `kebun_raya_koleksi` (ranking PROMETHEE) atau dari `tanaman_penerimaans` yang punya `lokasi_tanaman`?  
> Saat ini diasumsikan dari `kebun_raya_koleksi` (hasil ranking).

> **OQ-05 — `vak_no` vs `zona`**  
> Field `vak_no` sudah ada di `tanaman_penerimaans`. Apakah `zona` di tabel `lokasi_tanaman` baru adalah hal yang berbeda, atau seharusnya `lokasi_tanaman.zona` diisi otomatis dari `tanaman_penerimaan.vak_no`?

---

*Dokumen ini dibuat pada 2026-09-26. Update dokumen setiap ada perubahan scope yang signifikan.*
