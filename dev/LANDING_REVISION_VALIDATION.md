# Validasi revisi landing page

## Tempat menarik: Delivery Gate

Implementasi mengikuti Feature B pada PRD dan DESIGN.md. Admin memakai layout, sidebar, panel putih rounded-2xl, tabel, dan form yang tersedia. Publik memakai DM Sans/Instrument Sans, moss/mist/sage, foto sebagai fokus, serta grid satu/dua/tiga kolom. ENERGY 2 / RHYTHM 2 / MOTION 1; foto memberi identitas kawasan, kategori membantu pemilihan, dan jarak memisahkan informasi. Deskripsi berupa teks yang di-escape. Ada status aktif untuk memenuhi persyaratan tempat publik aktif; featured memilih maksimal tiga tempat, diurutkan berdasarkan urutan lalu terbaru. Tidak menambahkan konten tempat fiktif ke database. Seeder contoh terpisah hanya untuk local/testing, berlabel contoh dan nonaktif, tidak dijalankan.

Storage lokal pada PRD diganti dengan disk s3 sesuai instruksi pengguna: Cloudflare R2, path bulungan/tempat-menarik, URL bertanda tangan 10 menit, foto JPG/PNG/WebP maksimal 2 MB. Uji R2 nyata berhasil upload, GET bertanda tangan 200 dengan byte gambar sama, lalu delete objek sementara. Migrasi tabel baru sudah dijalankan di database lokal. Foto lama dipertahankan jika update tanpa foto atau upload gagal; foto baru dibersihkan jika database gagal; kegagalan hapus mempertahankan record; kegagalan cleanup foto lama setelah update menampilkan warning.

Verifikasi: 49 test dan 236 assertion lulus pada TempatMenarikTest, LandingHeroTest, LandingPageTest, dan BeritaDetailTest. Pint, Vite build, Blade cache, dan diff-check lulus. Browser memeriksa index/detail publik, landing berisi tiga fixture, serta index/create/edit admin pada 320/375/768/1024/1440/1920 px tanpa overflow setelah transisi selesai. Zoom 200% di viewport 375 px tidak overflow. Fixture hanya berupa HTML sementara di /tmp. Filter kategori diklik; form dengan file valid, checkbox, fokus keyboard, sidebar/Escape, dialog cancel/confirm diuji. Konfirmasi hapus dicegat di browser agar tidak mengubah data asli; HTTP CRUD lengkap diuji dengan fake storage dan database SQLite terisolasi. State foto memuat, gagal, dan kosong terlihat. Peringatan ukuran bundle JavaScript di atas 500 kB tetap ada.

Hard Gate PASS: R-02 tidak menambah em dash pada copy; R-03 responsive/zoom lulus; R-17/R-18/R-36/R-38 tidak menambah klaim/statistik/testimoni/data publik buatan; R-23 foto hanya dari upload atau fixture berlabel; R-24 named route valid; R-25 kontras moss/mist 8.17:1, bark/sage-pale 8.55:1, border gray-500/white 4.83:1; R-26/R-27 kontrol dan state diuji; R-28 tidak menambah FAQ; R-32 fokus terlihat, Escape berfungsi; R-33 kode ditulis langsung pada source; R-34 mengikuti tema terang yang tersedia; R-35 build dan interaksi diperiksa; R-37 arah desain dinyatakan sebelum implementasi.

Purpose Gate PASS: tidak menambahkan gradient/glow/glassmorphism/grid dekoratif/ilustrasi generik/panah dekoratif/badge promosi (R-01/R-07/R-08/R-09/R-10/R-13/R-22). Ikon plus/location-dot mengikuti fungsi tambah dan tempat (R-04). Font mengikuti DESIGN.md (R-06). Shadow ringan hanya pada panel admin (R-12). Kartu foto seragam karena daftar entitas yang dibandingkan (R-14). Gerak terbatas pada transisi kontrol/sidebar/dialog (R-19).

Liveliness PASS: dials eksplisit dan sesuai; judul/foto menjadi fokus; whitespace mengelompokkan filter, foto, dan deskripsi; moss menjadi aksen; identitas berupa informasi kawasan dan foto dari admin; Design Read dinyatakan. Craftsmanship PASS C-1–C-5 dan R-05/R-11/R-15/R-16/R-20/R-21/R-29/R-30/R-31: keputusan mengikuti konten dan sistem Flora; radius bervariasi; CTA menyebut tindakan; semua fungsi baru teruji; tidak meniru produk teknologi atau menambahkan janji/angka buatan.

## Detail berita: Delivery Gate

Halaman `home/berita/detail.blade.php` mengikuti DESIGN.md: DM Sans/Instrument Sans, moss/mist/sage-pale, radius komponen yang sudah ada. ENERGY 2 / RHYTHM 2 / MOTION 1. Judul menjadi fokus, metadata memakai relasi model, foto memakai image_url, dan isi dibatasi 72ch untuk keterbacaan. Semua data diambil dari berita publik berdasarkan slug; isi Trix dibangun kembali dengan whitelist tag dan URL aman.

`BeritaDetailTest`: 10 test dan 45 assertion lulus, meliputi data model/relasi, status draft/private dan slug tidak ada (404), gambar kosong/lokal/HTTP/S3, isi kosong, HTML aman, serta baris teks biasa. Pint, Vite build, Blade cache, dan diff-check berhasil. Chromium pada 320, 375, 768, 1440, dan 1920 px tanpa overflow; zoom 200% tanpa overflow; gambar gagal, menu mobile, dan Escape diuji tanpa JavaScript exception. Pemeriksaan klik: Beranda/brand ke `/`, Berita/breadcrumb/kembali ke daftar ke `/berita`, Tentang ke `/#tentang`, Pelestarian ke `/#koleksi`, masuk ke `/login`, daftar ke `/register`, tentang footer ke `/tentang`, skip link ke `#main-content`. Pengujian browser dilakukan sebagai pengunjung. Pasangan moss/mist 8.17:1, bark/mist 8.49:1, moss/sage-pale 8.22:1, bark/sage-pale 8.55:1 memakai hasil checker sebelumnya.

Revisi ulang landing: hero foto penuh dikembalikan dan ketidaksesuaian LandingHeroTest sudah selesai. LandingHeroTest, LandingPageTest, dan BeritaDetailTest lulus: 17 test, 84 assertion. URL gambar memakai resolver bersama dan disk lokal yang dikonfigurasi; kategori dimuat eager, ringkasan disanitasi, dan hanya tiga berita publik terbaru yang tampil. Path gambar tidak ditampilkan sebagai teks.

Antislop selama pengerjaan: arah DESIGN.md dan ENERGY 2 / RHYTHM 3 / MOTION 1 tetap dipakai. Hero, pengenalan, proses konservasi, placeholder tempat, dan komposisi berita berbeda sesuai isi. Navbar, konten, dan footer tetap melebar dengan gutter 20/32 px; CTA hero berada di ujung kanan desktop. Tidak menambahkan nama tempat, statistik, atau klaim fiktif. Pasangan kontras tetap sama dengan hasil checker di bawah. Placeholder ditandai jelas; kondisi kosong, loading, dan error gambar tetap tersedia.

Pemeriksaan ulang browser: 320–1920 px tanpa overflow horizontal; zoom 200% tanpa overflow; seluruh tautan pengunjung berhasil dibuka; fokus keyboard terlihat; menu dan video mendukung Escape; reduced-motion menonaktifkan autoplay hover; anchor berhenti di bawah navbar; footer dapat dicapai; tanpa JavaScript exception. Beberapa foto berita pada data lokal tidak berhasil dimuat, sehingga state error ditampilkan. Tinggi section minimal satu layar di bawah navbar; konten panjang pada ponsel boleh tumbuh. Pint, Vite build, Blade cache, dan diff-check berhasil. Build masih memberi peringatan ukuran bundle JavaScript di atas 500 kB.

### Hard Gate detail

- R-02 PASS: template/copy baru tidak mengandung em dash; isi model tidak ditulis ulang.
- R-03 PASS: lima ukuran layar tanpa overflow, kolom bertumpuk di mobile.
- R-17 PASS: jumlah kunjungan berasal dari field visitor; tidak dinaikkan oleh rendering.
- R-18 PASS: tidak ada testimoni.
- R-23 PASS: foto dan identitas penulis berasal dari model; tidak membuat aset orang atau kawasan.
- R-24 PASS: semua tujuan navigasi dan breadcrumb diuji klik.
- R-25 PASS: pasangan teks dan latar memakai token yang telah dihitung di atas.
- R-26 PASS: breadcrumb dan kembali ke berita adalah named route nyata; menu mobile diuji.
- R-27 PASS: isi/gambar kosong, gambar memuat, gambar gagal, dan berita 404 diuji.
- R-28 PASS: tidak menambahkan FAQ.
- R-32 PASS: kontrol native, outline global, skip link, serta Escape menu tersedia.
- R-33 PASS: fitur ditulis langsung pada Blade, CSS, controller, dan service melalui patch sumber.
- R-34 PASS: tidak menambahkan tema atau toggle tema.
- R-35 PASS: build, 10 test, inspeksi visual, dan klik kontrol pengunjung dicatat di atas.
- R-36 PASS: tidak menambahkan klaim pelanggan, keamanan, performa, atau statistik buatan.
- R-37 PASS: DESIGN.md dibaca, Design Read dinyatakan sebelum perubahan.
- R-38 PASS: judul, kategori, penulis, tanggal, isi, gambar, dan jumlah kunjungan memakai model.

### Purpose Gate detail

- R-01 PASS: tidak menambahkan gradient atau glow.
- R-04 PASS: tidak menambahkan ikon generik.
- R-06 PASS: font mengikuti identitas DESIGN.md dan kolom 72ch mendukung membaca artikel.
- R-07 PASS: tidak menambahkan pola latar.
- R-08 PASS: tidak menambahkan panah dekoratif.
- R-09 PASS: kategori berupa teks metadata, tanpa capsule promosi.
- R-10 PASS: tidak memakai glassmorphism.
- R-12 PASS: tidak menambahkan shadow komponen.
- R-13 PASS: tidak memakai glow.
- R-14 PASS: halaman berupa artikel dan metadata, tanpa kartu fitur seragam.
- R-19 PASS: tidak menambah animasi; state gambar memberi feedback pemuatan.
- R-22 PASS: foto memakai model, bukan ilustrasi generik.

### Liveliness detail

- Dials PASS: ENERGY 2 / RHYTHM 2 / MOTION 1 dinyatakan sebelum perubahan.
- Konsistensi PASS: artikel tenang dengan judul dominan dan variasi header/foto/body.
- Focal point PASS: judul artikel adalah fokus pertama.
- Whitespace PASS: header, foto, dan badan artikel dipisahkan untuk membaca bertahap.
- Aksen PASS: moss menandai judul dan navigasi, dengan latar mist netral.
- Identitas PASS: warna Flora, tipografi panduan, dan konten kebun raya berasal dari model.
- Design Read PASS: artikel publik, audiens, bahasa visual, dan dials dinyatakan sebelum perubahan.

### Craftsmanship dan Quality Locks detail

- C-1 PASS: font/palet mengikuti panduan, kolom baca 72ch dan metadata memiliki tujuan tertulis.
- C-2 PASS: breadcrumb, tautan kembali, dan kontrol shell berfungsi pada pemeriksaan pengunjung.
- C-3 PASS: komposisi mengikuti field artikel yang benar-benar tersedia.
- C-4 PASS: mobile, zoom, gambar gagal, isi kosong, dan 404 diuji.
- C-5 PASS: tidak menambahkan identitas atau angka buatan pada halaman nyata.
- R-05 PASS: header, foto, isi, dan navigasi artikel mengikuti kebutuhan membaca berita.
- R-11 PASS: memakai rounded-2xl pada foto dan rounded-xl pada empty state sesuai panduan.
- R-15 PASS: Kembali ke daftar berita menjelaskan tujuan CTA.
- R-16 PASS: copy baru tidak memakai buzzword pemasaran AI.
- R-20 PASS: warna Flora dan artikel model memberi identitas khusus kebun raya.
- R-21 PASS: tema terang mengikuti panduan, tidak memaksakan dark mode.
- R-29 PASS: moss, mist, dan sage-pale berasal dari token aplikasi.
- R-30 PASS: tidak meniru komposisi produk teknologi.
- R-31 PASS: alasan font, warna, metadata, gambar, dan lebar baca tertulis di atas.

Tanggal: 7 Oktober 2026. Antislop diterapkan selama pengerjaan.

Revisi perataan lebar: batas kontainer 1200 px dan batas footer 1100 px dihapus. Navbar, semua seksi konten, dan footer mengikuti lebar layar dengan jarak tepi 20 px pada mobile dan 32 px mulai 640 px. Footer memakai flex dengan `space-between`; tautan footer rata kanan pada tablet/desktop. Pengukuran Chromium pada 320, 375, 768, 1440, dan 1920 px membuktikan semua kontainer sejajar di kedua ujung dan tidak ada overflow. Build dan `git diff --check` kembali berhasil. Gate berikut tetap berlaku karena isi, warna, font, dan perilaku kontrol tidak berubah.

## Arah dan alasan desain

Landing page pengenalan Kebun Raya Bundayati untuk pengunjung umum. ENERGY 2 / RHYTHM 3 / MOTION 1. Arah mengikuti DESIGN.md: warna moss, sage, mist, DM Sans untuk teks, Instrument Sans untuk heading. Warna moss menghubungkan navigasi, judul, dan tindakan; hero memakai foto kawasan dan teks putih sesuai permintaan pengembalian hero lama.

Hero memakai kembali foto latar penuh, judul di kiri bawah, dan CTA Jelajahi Kebun Raya. Scrim gelap menjaga keterbacaan teks pada foto maupun video; putih pada latar terburuk #595959 memiliki kontras 7.00:1. Video lama dimuat saat hover pada perangkat yang mendukung hover dan tidak meminta reduced-motion. Tombol native memberi akses putar/hentikan pada touch dan keyboard; Escape menghentikan video. Pengenalan memakai dua kolom, pelestarian memakai daftar berurutan pada bidang moss, berita menonjolkan artikel paling baru. Susunan berita menyesuaikan jumlah artikel, termasuk satu artikel. Tidak ada animasi masuk yang menyembunyikan konten.

Statistik tanpa sumber, nomor spesimen buatan, galeri blok warna, tautan sosial kosong, dan informasi kunjungan tanpa rincian dihapus. Isi pengenalan dan proses konservasi berasal dari halaman sebelumnya; berita memakai data `$berita` yang sudah ada. Foto dan logo asli tetap dipakai bila tersedia. Tidak ada perubahan database atau backend.

## Pemeriksaan

Pemulihan hero: `tests/Feature/LandingHeroTest.php` lulus, dua test dan 12 assertion, tanpa ketergantungan database. Test memeriksa foto latar, identitas kawasan, CTA anchor, nama aksesibel kontrol video, serta keberadaan section lain dan kondisi berita kosong. Pint dijalankan sesuai AGENTS.md, build berhasil. Pemeriksaan Chromium pada 320, 375, 768, 1440, dan 1920 px menunjukkan hero memenuhi area layar di bawah navbar (806 px pada viewport 900 px), tanpa overflow. Toggle video memasang iframe dan mengubah aria-pressed; Escape melepas iframe dan mengembalikan aria-pressed menjadi false. Kegagalan gambar menampilkan teks fallback. Roda mouse dan anchor section masih berhenti tepat di bawah navbar, menu mobile berfungsi, footer dapat dicapai, dan tidak ada JavaScript exception. Pemeriksaan kontrol video mencakup lifecycle iframe, bukan verifikasi pemutaran media di layanan YouTube.

Revisi layar penuh dan scroll per section: atas permintaan pengguna, setiap section landing memakai minimum `100svh` dikurangi tinggi navbar sticky. Tinggi navbar diukur dengan ResizeObserver sehingga anchor dan posisi snap tetap di bawah navbar pada perubahan ukuran/font. CSS scroll snap pada root hanya aktif untuk landing; halaman publik lain tidak diberi scroll snap. Gulir native tetap digunakan, tanpa intersepsi roda mouse atau touch. Section boleh lebih tinggi saat isinya membutuhkan ruang; aturan tinggi layar mengikuti permintaan pengguna, dengan konten tetap tidak dipotong. Preferensi reduced-motion memakai gulir langsung dan snap proximity.

PASS R-03/R-35/C-4: Chromium pada 320, 375, 768, 1440, dan 1920 px tanpa overflow; minimum section 806 px pada viewport 900 px dengan navbar 94 px. Semua section tepat 806 px pada desktop 1440 dan 1920 px dengan data aktual. Bagian tempat menarik lebih panjang pada mobile karena kedua placeholder ditumpuk. Roda mouse dari hero berhenti di pengenalan, posisi section dan batas bawah navbar sama-sama 94 px. Anchor tentang, koleksi, tempat-menarik, dan berita sejajar di bawah navbar. Menu mobile dan Escape tetap bekerja; footer dapat dicapai hingga bagian akhir. Tidak ada JavaScript exception. PASS R-19: smooth scroll khusus navigasi section; reduced-motion menghasilkan `scroll-behavior: auto`. Build, kompilasi Blade, dan `git diff --check` berhasil. Gate lain di bawah tetap PASS; isi dan pasangan warna tidak diubah.

Tambahan Tempat menarik: dua placeholder sebelum berita, atas permintaan pengguna. Foto, nama, dan deskripsi diberi label placeholder; tidak ada nama lokasi, klaim, atau tautan detail buatan. Kedua slot berukuran sama karena belum ada konten yang menetapkan prioritas (R-14). Satu kolom pada mobile, dua mulai 640 px, tetap mengisi lebar kontainer. Chromium pada 320, 375, 768, 1440, dan 1920 px menunjukkan dua slot dan tanpa overflow. Build, kompilasi Blade, render fixture, dan `git diff --check` berhasil. Tidak ada kontrol baru untuk diuji klik; pasangan warna memakai token yang telah diuji. Hard Gate, Purpose Gate, Liveliness, dan Quality Locks di bawah tetap PASS dengan alasan placeholder ini dicatat.

- `npm run build`: berhasil. Vite masih melaporkan ukuran bundle JavaScript aplikasi lebih dari 500 kB.
- `php artisan view:cache`: berhasil. Halaman langsung menghasilkan HTTP 200.
- `git diff --check`: berhasil.
- Chromium: lebar 320, 375, 640, 768, 960, 1280, dan 1440 px tidak menghasilkan overflow horizontal. Kontrol halaman memenuhi tinggi minimum 44 px; kontrol Laravel Debugbar pengembangan dikecualikan dari pemeriksaan produk.
- Menu mobile membuka, Escape menutup dan mengembalikan fokus ke tombol Menu. Skip link menerima fokus dengan outline solid. Zoom 200% tidak menghasilkan overflow pada pemeriksaan desktop.
- Semua tautan landing page diklik, dengan tujuan tercatat di bawah. Tidak ada JavaScript exception selama pemeriksaan.
- Data aktual: satu berita, foto berhasil dimuat. Fixture Blade sementara di `/tmp` memeriksa nol dan tiga berita, gambar kosong, serta dua gambar gagal dimuat. Semua fixture tetap tanpa overflow pada 320, 768, dan 1440 px. Fixture tidak disimpan ke database.
- Kontras teks: moss/mist 8.17:1; sage/mist 5.40:1; bark/mist 8.49:1; bark/sage-pale 8.55:1; moss/sage-pale 8.22:1; putih/moss 9.40:1; sage-mid/moss 6.28:1.

## Rekaman interaksi

- Lewati navigasi → `/#main-content`, target ada.
- Brand header dan footer → `/`.
- Tentang dan Kenali kebun raya → `/#tentang`, target ada.
- Pelestarian dan Pelestarian flora → `/#koleksi`, target ada.
- Baca kabar terbaru → `/#berita`, target ada.
- Berita, Lihat semua berita, dan Berita & kegiatan → `/berita`.
- Masuk → `/login`.
- Daftar akun → `/register`.
- Baca tentang Kebun Raya Bundayati dan Tentang kebun raya → `/tentang`.
- Judul berita aktual dan Baca berita → `/berita/indonesia-maju`.
- Menu → terbuka; Escape → tertutup, fokus kembali ke tombol.

Pemeriksaan klik dilakukan sebagai pengunjung. Cabang pengguna terautentikasi diperiksa melalui kode: tombol Keluar tetap memakai form POST ke route `logout` dan token CSRF. Sesi login serta pengiriman logout tidak diuji lewat browser.

## Delivery Gate antislop

### Hard Gate

- R-02 PASS: teks revisi tidak memakai em dash.
- R-03 PASS: tujuh lebar viewport tanpa overflow; susunan satu dan dua kolom mengikuti ruang tersedia.
- R-17 PASS: statistik tanpa sumber dihapus; angka proses hanya urutan daftar.
- R-18 PASS: tidak ada testimoni.
- R-23 PASS: logo dan foto memakai aset sebelumnya; aset hilang menampilkan teks fallback yang jelas.
- R-24 PASS: seluruh tujuan navigasi diklik dan ada.
- R-25 PASS: seluruh pasangan warna teks utama dihitung, minimum 5.40:1.
- R-26 PASS: anchor nyata, route nyata, menu Alpine, serta form logout POST dengan CSRF.
- R-27 PASS: kondisi berita kosong dirender; pemuatan, gambar kosong, dan kegagalan gambar memiliki teks yang terlihat.
- R-28 PASS: tidak ada FAQ buatan.
- R-32 PASS: tautan dan tombol native; skip link, outline, serta Escape diuji.
- R-33 PASS: perubahan langsung pada Blade dan CSS melalui patch sumber.
- R-34 PASS: tidak ada toggle tema atau mode tambahan yang dikirim.
- R-35 PASS: build, rendering langsung, pemeriksaan viewport, dan klik seluruh kontrol pengunjung tercatat; batas pemeriksaan autentikasi dijelaskan di atas.
- R-36 PASS: tidak ada klaim keamanan, performa, pelanggan, atau angka buatan.
- R-37 PASS: DESIGN.md dibaca dan Design Read dinyatakan sebelum perubahan.
- R-38 PASS: isi memakai sumber halaman sebelumnya dan data berita; fallback foto ditandai sebagai tidak tersedia.

### Purpose Gate

- R-01 PASS: gradient scrim hanya pada hero untuk menjaga kontras teks di atas foto/video; bidang lain memakai token solid tanpa glow.
- R-04 PASS: tidak menambahkan ikon generik.
- R-06 PASS: DM Sans untuk keterbacaan teks, Instrument Sans untuk judul, sesuai DESIGN.md.
- R-07 PASS: tidak ada pola latar atau grid dekoratif.
- R-08 PASS: tidak memakai panah dekoratif pada tombol.
- R-09 PASS: tidak memakai badge promosi atau capsule eyebrow.
- R-10 PASS: tidak memakai glassmorphism.
- R-12 PASS: tidak memakai shadow sebagai dekorasi tiap komponen.
- R-13 PASS: tidak memakai glow.
- R-14 PASS: berita terbaru lebih dominan; proses konservasi berupa daftar, bukan kartu identik.
- R-19 PASS: video hover berasal dari hero lama yang diminta pengguna; reduced-motion mematikan pemutaran otomatis, tombol menyediakan kontrol eksplisit.
- R-22 PASS: foto kawasan nyata; tidak ada ilustrasi generik.

### Liveliness

- Dials PASS: ENERGY 2 / RHYTHM 3 / MOTION 1 dinyatakan sebelum pengerjaan.
- Konsistensi dials PASS: hero lapang, susunan seksi berbeda, gerak hanya pada kontrol.
- Focal point PASS: nama kebun pada hero, judul pengenalan, judul pelestarian, lalu artikel utama.
- Whitespace PASS: jarak memisahkan narasi; ukuran mobile lebih rapat daripada desktop.
- Aksen PASS: moss menonjolkan tindakan utama di luar foto, hero memakai teks putih untuk keterbacaan.
- Motif identitas PASS: foto kawasan asli, warna tumbuhan, dan alur pelestarian menyambung dengan topik kebun raya.
- Design Read PASS: audiens, gaya visual, dan dials dinyatakan sebelum perubahan.

### Craftsmanship dan Quality Locks

- C-1 PASS: alasan font, warna, komposisi, radius, dan transisi dicatat di atas.
- C-2 PASS: semua kontrol pengunjung memiliki tindakan yang telah diuji.
- C-3 PASS: pengenalan, pelestarian, dan berita memakai isi produk yang tersedia.
- C-4 PASS: kondisi kosong dan gagal gambar diuji, menu Escape berfungsi, zoom 200% tanpa overflow.
- C-5 PASS: tidak menambahkan statistik, testimoni, atau klaim buatan.
- R-05 PASS: komposisi hero, pengenalan, daftar konservasi, dan berita berbeda sesuai kebutuhan konten.
- R-11 PASS: CTA hero mengembalikan radius pill sebelumnya; tombol lain rounded-lg dan area berita rounded-xl, tanpa pill seragam.
- R-15 PASS: CTA menyebut tindakan: Jelajahi Kebun Raya menuju pengenalan, baca berita, daftar akun.
- R-16 PASS: tidak memakai buzzword pemasaran AI.
- R-20 PASS: nama kawasan, foto asli, palet Flora, dan alur pelestarian membentuk identitas halaman.
- R-21 PASS: latar mist dan pale mengikuti DESIGN.md; tidak memaksakan dark mode.
- R-29 PASS: seluruh warna berasal dari token Flora, dengan moss, mist, dan sage sebagai palet inti.
- R-30 PASS: susunan mengikuti cerita kebun raya, tanpa meniru produk teknologi.
- R-31 PASS: alasan keputusan visual utama dijelaskan di bagian arah desain.
