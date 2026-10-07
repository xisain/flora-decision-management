# Flora Decision Management — Design System

Dokumen ini mendeskripsikan design system, pola komponen, dan panduan tampilan yang digunakan di seluruh aplikasi **FDM Bulungan**.

---

## Daftar Isi

1. [Tech Stack](#1-tech-stack)
2. [Color Palette](#2-color-palette)
3. [Typography](#3-typography)
4. [Layout & Struktur Halaman](#4-layout--struktur-halaman)
5. [Komponen UI](#5-komponen-ui)
   - [Sidebar](#51-sidebar)
   - [Top Navbar](#52-top-navbar)
   - [Card / Panel](#53-card--panel)
   - [Tabel Data](#54-tabel-data)
   - [Form & Input](#55-form--input)
   - [Tombol](#56-tombol)
   - [Alert / Notifikasi](#57-alert--notifikasi)
   - [Badge](#58-badge)
   - [Empty State](#59-empty-state)
   - [Auth Layout](#510-auth-layout)
6. [Pola Halaman](#6-pola-halaman)
   - [Halaman Index (Daftar)](#61-halaman-index-daftar)
   - [Halaman Form (Create / Edit)](#62-halaman-form-create--edit)
   - [Dashboard](#63-dashboard)
7. [Interaksi & JavaScript](#7-interaksi--javascript)
8. [Ikon](#8-ikon)

---

## 1. Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Backend | Laravel (PHP) |
| Templating | Blade |
| CSS Framework | Tailwind CSS v4 (`@import 'tailwindcss'`) |
| Reaktivitas UI | Alpine.js (`x-data`, `x-model`, `x-show`, `x-transition`) |
| Icon | Font Awesome 6 (kit CDN) |
| Dialog Konfirmasi | SweetAlert2 |
| Font | DM Sans (body), Instrument Sans (auth/heading) |
| Asset Bundler | Vite |
| Font CDN | bunny.net |

---

## 2. Color Palette

Semua warna didefinisikan sebagai CSS custom property di `:root` **dan** sebagai Tailwind theme token.

```css
:root {
  --flora-sage:        #3B6D11;   /* hijau gelap */
  --flora-sage-light:  #639922;   /* hijau sedang */
  --flora-sage-pale:   #EAF3DE;   /* hijau sangat pucat */
  --flora-sage-mid:    #C0DD97;   /* hijau muda */
  --flora-moss:        #085041;   /* teal-hijau gelap — warna brand utama */
  --flora-teal:        #1D9E75;   /* teal — warna interaktif/CTA */
  --flora-teal-light:  #5DCAA5;   /* teal muda */
  --flora-teal-pale:   #E1F5EE;   /* teal sangat pucat */
  --flora-earth:       #633806;   /* coklat gelap */
  --flora-amber:       #EF9F27;   /* kuning-oranye/warning */
  --flora-amber-pale:  #FAEEDA;   /* amber sangat pucat */
  --flora-bark:        #444441;   /* abu gelap / teks utama */
  --flora-mist:        #F1EFE8;   /* off-white — latar halaman */
  --flora-stone:       #B4B2A9;   /* abu muted — teks sekunder */
}
```

### Penggunaan Warna

| Peran | Token |
|-------|-------|
| Latar halaman admin | `--flora-mist` (`bg-gray-100` atau `bg-[var(--flora-mist)]`) |
| Judul halaman | `--flora-moss` |
| Teks sekunder / label | `--flora-stone` |
| Tombol utama / CTA | `--flora-teal` → hover `--flora-moss` |
| Tombol submit form | `--flora-moss` |
| Nav item aktif | `--flora-teal` (bg) + `text-white` |
| Avatar/accent | `--flora-teal-pale` (bg), `--flora-teal-light` (border), `--flora-teal` (teks) |
| Warning / amber | `--flora-amber` |

---

## 3. Typography

```css
body {
  font-family: 'DM Sans', sans-serif;
}
/* Auth pages */
head link: 'instrument-sans:400,500,600'
```

### Skala Teks (Tailwind)

| Kelas | Penggunaan |
|-------|-----------|
| `text-xs` (12px) | Label kolom tabel, badge, hint |
| `text-sm` (14px) | Konten umum, input, tombol, navigasi |
| `text-base` (16px) | Teks body auth |
| `text-2xl` (24px) | Judul halaman (`font-semibold`) |
| `text-3xl` (30px) | Angka statistik dashboard (`font-bold`) |

### Pola Judul Halaman

```html
<h1 class="text-2xl font-semibold text-[var(--flora-moss)] tracking-tight">
    Nama Halaman
</h1>
<p class="text-sm text-[var(--flora-stone)] mt-1">
    Deskripsi singkat halaman.
</p>
```

---

## 4. Layout & Struktur Halaman

```
┌──────────────────────────────────────────────────────────┐
│ SIDEBAR (fixed, collapsible)                             │
│  w-64 (expanded) / w-20 (collapsed)                      │
│  bg-white  rounded-r-2xl  shadow-xl                      │
├──────────────────────────────────────────────────────────┤
│ MAIN AREA (flex-1, ml-64 / ml-20 — transisi)            │
│                                                          │
│  ┌────────────────────────────────────────────────────┐  │
│  │ TOP NAVBAR (header)                                │  │
│  │  px-6 py-4 — hamburger kiri, user dropdown kanan  │  │
│  └────────────────────────────────────────────────────┘  │
│                                                          │
│  ┌────────────────────────────────────────────────────┐  │
│  │ KONTEN (main)                                      │  │
│  │  px-6 py-3                                        │  │
│  │  Setiap halaman membungkus konten dalam:           │  │
│  │  <div class="px-4 py-6 mx-auto">                  │  │
│  └────────────────────────────────────────────────────┘  │
└──────────────────────────────────────────────────────────┘
```

### Transisi Sidebar

Menggunakan Alpine.js + `localStorage` untuk persist state:

```html
<html x-data="{
  sidebarOpen: localStorage.getItem('sidebar_open') !== null
    ? localStorage.getItem('sidebar_open') === 'true'
    : true
}">
<!-- Main area -->
<div :class="sidebarOpen ? 'ml-64' : 'ml-20'"
     class="transition-all duration-300 ease-in-out">
```

---

## 5. Komponen UI

### 5.1 Sidebar

```html
<aside class="fixed inset-y-0 left-0 bg-white z-40 flex flex-col justify-between
              rounded-r-2xl shadow-xl border border-r-gray-200 sidebar-transition"
       :class="sidebarOpen ? 'w-64' : 'w-20'">

  <!-- Logo -->
  <div class="flex flex-col items-center py-4">
    <img src="..." class="w-12 h-12" />
    <span :class="{ 'opacity-0 hidden': !sidebarOpen }">FDM Bulungan</span>
  </div>

  <!-- Nav links -->
  <nav class="px-4 py-2 space-y-2">
    <!-- Item Aktif -->
    <a class="flex items-center px-3 py-2 rounded-xl
              bg-[var(--flora-teal)] text-white font-semibold"
       :class="{ 'justify-center': !sidebarOpen }">
      <i class="fa-solid fa-house"></i>
      <span class="ml-3" :class="{ 'opacity-0 hidden': !sidebarOpen }">Dashboard</span>
    </a>

    <!-- Item Tidak Aktif -->
    <a class="flex items-center px-3 py-2 rounded-xl
              text-gray-900 hover:bg-gray-100"
       :class="{ 'justify-center': !sidebarOpen }">
      ...
    </a>
  </nav>

  <!-- Footer: avatar + email + logout -->
  <div class="border-t border-gray-100 p-4">...</div>
</aside>
```

**Menu Admin (urutan):**

| Icon | Label |
|------|-------|
| `fa-house` | Dashboard |
| `fa-user` | User |
| `fa-user-gear` | Collector |
| `fa-user-group` | Tim |
| `fa-cog` | Kriteria & Bobot |

---

### 5.2 Top Navbar

```html
<header class="flex items-center justify-between px-6 py-4 relative z-30">
  <!-- Hamburger -->
  <button @click="toggleSidebar()" class="text-gray-700 text-xl">
    <i class="fa-solid fa-bars"></i>
  </button>

  <!-- User Dropdown -->
  <div class="relative" x-data="{ userOpen: false }">
    <button @click="userOpen = !userOpen"
            class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-gray-100">
      <!-- Avatar initial -->
      <div class="w-8 h-8 rounded-full bg-[var(--flora-teal-pale)]
                  border-2 border-[var(--flora-teal-light)]
                  flex items-center justify-center
                  text-[var(--flora-teal)] font-semibold text-sm">
        A
      </div>
      <span class="text-sm font-medium text-gray-700">Nama User</span>
      <i class="fa-solid fa-chevron-down text-xs text-gray-400"
         :class="{ 'rotate-180': userOpen }"></i>
    </button>

    <!-- Dropdown -->
    <div x-show="userOpen" x-transition
         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg
                border border-gray-100 py-1 origin-top-right">
      <div class="px-4 py-2 border-b border-gray-100">
        <p class="text-xs text-gray-400">Masuk sebagai</p>
        <p class="text-sm font-semibold text-gray-800 truncate">email@contoh.com</p>
      </div>
      <form method="POST" action="/logout">
        <button class="w-full text-left flex items-center gap-2 px-4 py-2
                       text-sm text-red-500 hover:bg-red-50">
          <i class="fa-solid fa-right-from-bracket"></i> Keluar
        </button>
      </form>
    </div>
  </div>
</header>
```

---

### 5.3 Card / Panel

Komponen dasar untuk mengelompokkan konten.

```html
<div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6">
  <!-- Header Card -->
  <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
    <h2 class="text-sm font-medium text-[var(--flora-stone)] uppercase tracking-widest">
      Judul Seksi
    </h2>
  </div>
  <!-- Body Card -->
  <div class="px-6 py-6 space-y-5">
    <!-- konten -->
  </div>
</div>
```

**Varian Header Card** (dengan tombol aksi di kanan):

```html
<div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
  <h2 class="text-sm font-medium text-[var(--flora-stone)] uppercase tracking-widest">
    Judul Seksi
  </h2>
  <button class="inline-flex items-center gap-1.5 text-sm font-medium
                 text-[var(--flora-moss)] hover:opacity-75 transition">
    <i class="fa-solid fa-plus text-xs"></i>
    Tambah Baris
  </button>
</div>
```

**Dashboard Stat Card:**

```html
<div class="group relative overflow-hidden rounded-2xl border border-slate-200
            bg-white p-5 shadow-sm transition duration-200
            hover:-translate-y-0.5 hover:shadow-md">
  <div class="flex items-start justify-between gap-3">
    <div class="flex flex-col gap-3">
      <!-- Icon box -->
      <div class="flex h-10 w-10 items-center justify-center rounded-xl {iconBg}">
        <svg class="h-5 w-5 {iconColor}">...</svg>
      </div>
      <!-- Teks -->
      <div>
        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Label</p>
        <p class="mt-1 text-3xl font-bold text-slate-900">42</p>
        <p class="mt-1 text-xs text-slate-500">Deskripsi singkat</p>
      </div>
    </div>
    <!-- Badge status -->
    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {badgeClass}">Aktif</span>
  </div>
</div>
```

---

### 5.4 Tabel Data

```html
<div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-gray-100 bg-emerald-50/70 text-left">
          <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest w-8">#</th>
          <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">Kolom</th>
          <!-- Kolom aksi — rata tengah -->
          <th class="px-5 py-3.5 text-xs font-semibold text-gray-700 uppercase tracking-widest text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        <!-- Row normal -->
        <tr class="hover:bg-emerald-50/60 transition border-b border-gray-100 group">
          <td class="px-5 py-4 text-gray-700">1</td>
          <td class="px-5 py-4 text-gray-700">Data</td>
          <!-- Aksi -->
          <td class="px-5 py-4 text-center">
            <div class="flex items-center justify-center gap-1
                        opacity-70 group-hover:opacity-100 transition-opacity">
              <!-- Edit -->
              <a class="p-1.5 rounded-md text-gray-500 hover:text-amber-600 hover:bg-amber-50 transition-colors">
                <i class="fa-solid fa-pen text-xs"></i>
              </a>
              <!-- Hapus -->
              <button class="p-1.5 rounded-md text-gray-500 hover:text-red-600 hover:bg-red-50 transition-colors">
                <i class="fa-solid fa-trash text-xs"></i>
              </button>
            </div>
          </td>
        </tr>

        <!-- Row Footer / Total -->
        <tr class="bg-gray-50 font-semibold">
          <td colspan="2" class="px-5 py-4 text-right text-gray-700">Total Bobot</td>
          <td class="px-5 py-4 text-[var(--flora-moss)]">1.00</td>
          <td colspan="N"></td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Pagination (jika ada) -->
  <div class="px-5 py-4 border-t border-gray-100">
    {{ $data->withQueryString()->links() }}
  </div>
</div>
```

**Tombol Aksi Tabel:**

| Tipe | Hover Color | Hover BG |
|------|-------------|----------|
| Detail / View | `hover:text-[var(--flora-teal)]` | `hover:bg-[var(--flora-teal)]/10` |
| Edit | `hover:text-amber-600` | `hover:bg-amber-50` |
| Hapus | `hover:text-red-600` | `hover:bg-red-50` |

---

### 5.5 Form & Input

#### Input Teks / Angka

```html
<div>
  <label for="field" class="block text-sm font-medium text-gray-700 mb-1">
    Label <span class="text-red-500">*</span>
  </label>
  <input type="text" id="field" name="field"
         placeholder="Placeholder..."
         class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800
                placeholder-gray-400 focus:outline-none focus:ring-2
                focus:ring-[var(--flora-moss)] focus:border-transparent transition
                @error('field') border-red-400 @enderror">
  <p class="mt-1.5 text-xs text-gray-400">Teks bantuan opsional</p>
  @error('field')
    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
  @enderror
</div>
```

#### Select

```html
<select name="field"
        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800
               bg-white focus:outline-none focus:ring-2 focus:ring-[var(--flora-moss)]
               focus:border-transparent transition">
  <option value="" disabled selected>-- Pilih --</option>
  <option value="a">Opsi A</option>
</select>
```

#### Grid Form (2 kolom)

```html
<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
  <div><!-- field kiri --></div>
  <div><!-- field kanan --></div>
</div>
```

#### Tabel Input Dinamis (Ordinal Scale)

Grid 12 kolom untuk baris dinamis dengan Alpine.js:

```
[#:1] [Label:3] [Nilai:1] [Operator:3] [From:2] [To+Del:2]
```

```html
<template x-for="(row, index) in rows" :key="index">
  <div class="grid grid-cols-12 gap-3 items-center">
    <div class="col-span-1 text-sm text-gray-400 text-center" x-text="index + 1"></div>
    <div class="col-span-3"><input type="text" ...></div>
    <div class="col-span-1"><input type="number" ...></div>
    <div class="col-span-3"><select ...></select></div>
    <div class="col-span-2"><input type="number" x-show="row.operator !== 'eq'"></div>
    <div class="col-span-2 flex items-center gap-2">
      <input type="number" x-show="row.operator === 'between'">
      <button type="button" @click="removeRow(index)"><!-- trash icon --></button>
    </div>
  </div>
</template>
```

---

### 5.6 Tombol

#### Tombol Utama (Tambah / CTA)

```html
<a class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white
           bg-[var(--flora-teal)] rounded-lg hover:bg-[var(--flora-moss)]
           transition-colors duration-200">
  <i class="fa-solid fa-plus text-xs"></i>
  Tambah Data
</a>
```

#### Tombol Submit Form

```html
<button type="submit"
        class="px-5 py-2.5 text-sm font-medium text-white bg-[var(--flora-moss)] rounded-lg
               hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-[var(--flora-moss)]
               focus:ring-offset-2 transition shadow-sm">
  Simpan
</button>
```

#### Tombol Batal / Secondary

```html
<a class="px-4 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-300
           rounded-lg hover:bg-gray-50 transition">
  Batal
</a>
```

#### Tombol Kembali (link)

```html
<a class="inline-flex items-center gap-2 text-sm text-[var(--flora-stone)]
           hover:text-[var(--flora-moss)] transition-colors">
  <svg class="w-4 h-4"><!-- chevron-left --></svg>
  Kembali
</a>
```

---

### 5.7 Alert / Notifikasi

#### Error (Validasi)

```html
<div class="mb-4 flex gap-3 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-sm">
  <div class="shrink-0 pt-0.5">
    <i class="fa-solid fa-circle-exclamation text-red-500 text-base"></i>
  </div>
  <div>
    <p class="font-semibold text-red-700 mb-1">Terdapat N Kesalahan</p>
    <ul class="list-disc list-inside space-y-0.5 text-red-600">
      <li>Pesan error...</li>
    </ul>
  </div>
</div>
```

#### Success (Flash)

```html
<div class="mb-4 flex gap-3 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-sm">
  <div class="shrink-0 pt-0.5">
    <i class="fa-solid fa-circle-check text-green-500 text-base"></i>
  </div>
  <p class="text-green-700 font-medium">Pesan berhasil.</p>
</div>
```

#### Info (Keterangan Form)

```html
<div class="flex items-center gap-2 px-4 py-3 rounded-lg bg-gray-50
            text-sm text-gray-500 border border-gray-200">
  <i class="fa-solid fa-circle-info text-[var(--flora-moss)]"></i>
  Keterangan info di sini.
</div>
```

---

### 5.8 Badge

```html
<!-- Umum -->
<span class="rounded-full px-2.5 py-1 text-xs font-semibold {colorClass}">
  Label
</span>

<!-- Contoh warna badge -->
<!-- Hijau aktif -->  bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200
<!-- Merah inaktif --> bg-red-50 text-red-700 ring-1 ring-red-200
<!-- Amber warning --> bg-amber-50 text-amber-700 ring-1 ring-amber-200
<!-- Slate netral -->  bg-slate-100 text-slate-600
```

---

### 5.9 Empty State

Digunakan di `@empty` dalam `@forelse` tabel:

```html
<tr>
  <td colspan="N" class="px-5 py-16 text-center">
    <div class="flex flex-col items-center gap-3 text-gray-400">
      <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center">
        <i class="fa-solid fa-{icon} text-gray-400 text-xl"></i>
      </div>
      <div>
        <p class="font-medium text-gray-500">Belum ada data</p>
        <p class="text-xs mt-1 text-gray-400">Klik tombol tambah untuk memulai.</p>
      </div>
    </div>
  </td>
</tr>
```

---

### 5.10 Auth Layout

Dua-panel: kiri branding, kanan form.

```
┌─────────────────────┬──────────────────────────┐
│  BRANDING PANEL     │  FORM PANEL              │
│  bg: sage-pale→white│  bg: white               │
│                     │                          │
│  [Logo 240px]       │  <h1>Judul Form</h1>     │
│                     │  <p>Subtitle</p>          │
│  <h2>Tagline</h2>   │                          │
│  <p>Deskripsi</p>   │  [Form fields]           │
│                     │                          │
└─────────────────────┴──────────────────────────┘
```

```html
<div class="min-h-screen flex items-center justify-center p-8 bg-[var(--flora-mist)]">
  <div class="w-full max-w-[960px] grid grid-cols-1 lg:grid-cols-[1fr_1.05fr]
              bg-white rounded-[28px] overflow-hidden
              shadow-[0_24px_80px_rgba(0,0,0,0.12)] min-h-[620px]">

    <!-- Kiri: Branding -->
    <div class="p-10 bg-gradient-to-b from-[var(--flora-sage-pale)] to-white
                flex flex-col items-center justify-center text-center">
      <img src="logo.png" class="max-w-[240px] mb-8">
      <div class="max-w-[280px] text-[var(--flora-moss)]">
        <h2 class="text-[1.75rem] font-semibold mb-3">Tagline Aplikasi</h2>
        <p class="text-base leading-[1.75] text-[rgba(68,68,65,0.85)]">Deskripsi</p>
      </div>
    </div>

    <!-- Kanan: Form -->
    <div class="p-10 lg:p-12 flex flex-col justify-center gap-4">
      <div class="mb-6">
        <h1 class="text-[2rem] font-bold text-[var(--flora-moss)] mb-1">Judul</h1>
        <p class="text-base text-[rgba(68,68,65,0.8)]">Subtitle</p>
      </div>
      <!-- slot form -->
    </div>
  </div>
</div>
```

---

## 6. Pola Halaman

### 6.1 Halaman Index (Daftar)

Struktur standar semua halaman list:

```html
@extends('layout.admin')
@section('content')

  {{-- Flash & Error Alerts --}}
  @if ($errors->any()) ... @endif
  @if (session('success')) ... @endif

  <div class="px-4 py-6 mx-auto">

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-2xl font-semibold text-[var(--flora-moss)] tracking-tight">Nama Halaman</h1>
        <p class="text-sm text-[var(--flora-stone)] mt-1">Deskripsi singkat.</p>
      </div>
      <a href="{{ route('resource.create') }}" class="... bg-[var(--flora-teal)] ...">
        <i class="fa-solid fa-plus text-xs"></i> Tambah Data
      </a>
    </div>

    {{-- Tabel --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
      <table class="w-full text-sm">...</table>
    </div>

  </div>

  @push('scripts')
    <script>/* SweetAlert delete confirmation */</script>
  @endpush

@endsection
```

### 6.2 Halaman Form (Create / Edit)

```html
@extends('layout.admin')
@section('content')

  {{-- Error Alerts --}}

  <div class="px-4 py-6 mx-auto">

    {{-- Header dengan tombol Kembali --}}
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1>Tambah / Edit Data</h1>
        <p>Deskripsi.</p>
      </div>
      <a href="{{ route('resource.index') }}">← Kembali</a>
    </div>

    <form action="..." method="POST" x-data="...">
      @csrf

      {{-- Card 1: Informasi Utama --}}
      <div class="bg-white ... rounded-2xl ... mb-6">
        <div class="px-6 py-4 border-b bg-gray-50">
          <h2 class="text-sm ... uppercase tracking-widest">Seksi 1</h2>
        </div>
        <div class="px-6 py-6 space-y-5">
          <!-- fields -->
        </div>
      </div>

      {{-- Card 2: Seksi Kondisional (x-show) --}}
      <div class="... mb-6" x-show="kondisi" x-transition x-cloak>
        ...
      </div>

      {{-- Form Footer --}}
      <div class="flex items-center justify-end gap-3">
        <a href="...">Batal</a>
        <button type="submit">Simpan</button>
      </div>
    </form>

  </div>

@endsection
```

### 6.3 Dashboard

Struktur zona dashboard:

```
1. Header Greeting + Badge "Sistem Aktif"
2. Main Stats  — grid 1→2→4 kolom (stat cards dengan icon + angka + badge)
3. Ringkasan Data Flora — grid 1→4 kolom (mini stat dengan progress bar)
4. Management Section — grid 1→2 kolom (card aksi menu utama)
5. Bottom Section — grid 1→3 kolom:
   │ Criteria Status (1 col) │ Aktivitas Terbaru (2 col) │
```

---

## 7. Interaksi & JavaScript

### Alpine.js Patterns

**Toggle konten kondisional:**
```html
<div x-data="{ mode: '' }">
  <select x-model="mode">...</select>
  <div x-show="mode === 'ordinal'" x-transition x-cloak>...</div>
  <div x-show="mode === 'numerik'" x-transition x-cloak>...</div>
</div>
```

**Dynamic rows (tambah/hapus baris):**
```js
function criteriaForm() {
  return {
    rows: [{ label: '', nilai: '', operator: 'eq', range_from: '', range_to: '' }],
    skala: '',
    preferenceFunction: '',

    addRow() {
      this.rows.push({ label: '', nilai: '', operator: 'eq', range_from: '', range_to: '' });
    },
    removeRow(index) {
      if (this.rows.length > 1) this.rows.splice(index, 1);
    }
  }
}
```

**Aturan `x-cloak`:**  
Tambahkan CSS global:
```css
[x-cloak] { display: none !important; }
```

### SweetAlert2 Delete Confirmation

Pola standar untuk konfirmasi hapus:

```js
document.querySelectorAll('.form-delete').forEach(form => {
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    Swal.fire({
      title: 'Hapus data ini?',
      text: 'Data yang dihapus tidak dapat dikembalikan.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, hapus',
      cancelButtonText: 'Batal',
      confirmButtonColor: '#ef4444',
      cancelButtonColor: '#6b7280',
    }).then((result) => {
      if (result.isConfirmed) form.submit();
    });
  });
});
```

### Sidebar Sync ke Server

```js
fetch('/sidebar/toggle', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
  body: JSON.stringify({ open: this.sidebarOpen })
});
```

---

## 8. Ikon

Semua ikon menggunakan **Font Awesome 6** via kit CDN. Kelas yang umum dipakai:

| Konteks | Kelas FA |
|---------|----------|
| Dashboard | `fa-house` |
| User | `fa-user` |
| Collector | `fa-user-gear` |
| Tim | `fa-user-group` |
| Kriteria | `fa-cog` / `fa-sliders` |
| Tambah | `fa-plus` |
| Edit | `fa-pen` |
| Hapus | `fa-trash` |
| Detail | `fa-eye` |
| Logout | `fa-right-from-bracket` |
| Error | `fa-circle-exclamation` |
| Success | `fa-circle-check` |
| Info | `fa-circle-info` |
| Hamburger | `fa-bars` |
| Chevron | `fa-chevron-down` |
| Kembali | SVG chevron-left |

---

## Ringkasan Design Principles

1. **Konsistensi warna** — selalu gunakan token `var(--flora-*)` untuk warna brand, bukan hex langsung.
2. **Rounded** — UI menggunakan sudut membulat: `rounded-lg` (input), `rounded-xl` (dropdown/alert), `rounded-2xl` (card/table), `rounded-[28px]` (auth card).
3. **Shadow ringan** — `shadow-sm` sebagai default; `shadow-md` saat hover.
4. **Hover subtle** — baris tabel: `hover:bg-emerald-50/60`; tombol aksi: opacity + bg warna.
5. **Transisi halus** — `transition`, `duration-200`, `ease-in-out` pada semua elemen interaktif.
6. **Responsif** — sidebar collapse untuk layar kecil, form grid `grid-cols-1 md:grid-cols-2`.
7. **Aksesibilitas** — label terhubung ke input via `for`/`id`, required field ditandai `*` merah.
