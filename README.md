# Bootcamp Backend Legacy: E-Commerce TokoLaris (PHP 8.2 & MySQL 8)

Repositori ini untuk **penyelenggara & mentor**. Isinya membangun sistem e-commerce monolitik warisan (*legacy system*) yang sengaja memiliki cacat arsitektur, bug konkurensi, dan celah keamanan sesuai tiket **T01–T25**, menghasilkan paket mandiri untuk peserta, dan menyediakan pengujian serta kunci jawaban referensi.

Peserta menerima paket dari `paket_peserta.sh` atau cloning dari GitHub: kode aplikasi, docker-compose (termasuk k6 & Redis), 25 tiket tantangan + 5 bonus, lembar panduan mandiri (*Field Manual*), dan skrip pemeriksa `gate` (`.bat` / `.sh`).

---

## ⚡ Mode Operasional Bootcamp (Fleksibel: Offline vs Online Proyektor)

Bootcamp ini didesain sangat fleksibel dan dapat berjalan dalam 2 mode:

### 1. 📴 Mode 100% Offline (Mandiri / Tanpa Wi-Fi / Tanpa Proyektor / Tanpa Flashdisk) — REKOMENDASI TERMUDAH
* **Distribusi:** Peserta sudah meng-clone repo dari GitHub atau mengunduh file `.zip` H-1 di rumah sebelum datang ke lokasi.
* **Tidak Butuh Wi-Fi Lokal:** Seluruh pengujian Pest PHP, k6 uji beban, Redis, dan MySQL berjalan 100% di dalam Docker di laptop masing-masing peserta.
* **Tanpa Proyektor:** Peserta memantau progres dan total poin sendiri secara langsung di terminal menggunakan:
  * Windows: `gate.bat status`
  * Linux / macOS: `./gate status`
* **Peran Mentor:** Pasif & murni *on-demand*. Mentor tidak perlu repot setup server, router, atau colok flashdisk. Mentor hanya duduk santai dan menjawab peserta yang bertanya saat buntu.

### 2. 🌐 Mode Terpusat (Wi-Fi Lokal + Layar Proyektor) — OPSIONAL
* Laptop mentor menjalankan `php -S 0.0.0.0:3000 papan/server.php`.
* Layar proyektor menampilkan matriks CTF live leaderboard untuk kompetisi visual.
* Peserta mengirim skor via `./gate kirim` atau `gate.bat kirim`.

---

## 📂 Struktur Direktori Master

```text
build.sh                      membangun lingkungan dari nol dan memverifikasi cacat awal
paket_peserta.sh              mengemas paket mandiri peserta ke dist/paket_peserta_ecommerce_php.zip
gate                          launcher CLI Linux/macOS untuk root direktori
gate.bat                      launcher CLI Windows untuk root direktori
README.md                     panduan master repositori

db/                           skema database awal (MySQL 8.0)
  01_skema.sql                5 tabel monolitik (users, products, orders, order_items, invoices)
  02_index.sql                index awal yang sengaja minim (memicu table scan)

data/                         data benih (seeder) awal dan data cacat
  10_master.sql               data pengguna (MD5) dan katalog produk
  11_transaksi.sql            data pesanan dan relasi order items (memicu N+1)
  12_cacat_stok.sql           item promo Flash Sale (stok 10) untuk memicu race condition

app/                          kode sumber aplikasi monolitik PHP 8.2 yang harus diperbaiki
  Controllers/                controller gemuk (OrderController, SearchController, InvoiceController, AuthController)
  Services/                   CartService, dll
  Support/                    Database PDO wrapper dengan query counter
  public/                     entry point REST API (index.php)
  composer.json               dependensi Pest PHP, PHPStan, dan Rector
  Dockerfile                  image PHP 8.2-FPM Alpine dengan ekstensi pdo & redis

tiket/                        lembar tantangan misi peserta (T01 s/d T25 + B01 s/d B05)
  00_daftar_tiket.md          ringkasan 25 tiket wajib + 5 bonus, bobot poin, dan estimasi waktu
  01_panduan_gate_tiket.md    aturan Gate Masuk (M1-M5) dan Gate Keluar (K1-K5)
  T01_null_address.md         misi T01: null pointer address checkout
  T02_division_zero.md        misi T02: division by zero saat voucher diskon 100%
  T03_state_machine.md        misi T03: state machine invalid status order cancel
  T04_stok_flash_sale.md      misi T04: race condition stok minus flash sale
  ... s/d T25 dan B01 s/d B05

kunci/                        25 kunci jawaban resmi referensi mentor (T01 s/d T25)
  01_T01_null_address.php     solusi null-safe operator
  02_T04_atomic_update.php    solusi atomic conditional update di MySQL
  03_T06_eager_loading.php    solusi batch eager loading (dari 152 ke 3 query)
  04_T17_prepared_stmt.php    solusi parameterized query
  05_T18_idor_check.php       solusi authorization policy check
  06_T19_rehash_login.php     solusi mekanisme Rehash-on-Login
  07_T02_division_zero.php    solusi fallback division by zero
  ... s/d 25_T25_phpstan_fix.php

skenario/                     skenario uji beban (k6) terintegrasi container
  flash_sale.js               serbuan 50 user serentak dalam 10 detik (uji T04)
  invoice_timeout.js          serbuan 20 user cetak PDF invoice (uji T15)

uji/                          pembuktian cacat & automated test suite
  verifikasi_cacat.php        skrip pembuktian 5 cacat utama ada di awal
  tests/Tickets/              test suite Pest PHP lengkap (Tkt01Test s/d Tkt25Test + Bns01 s/d Bns05)

pemeriksa/                    alat otomatis peserta & mentor
  cek_cepat.php               pemeriksa cepat status seluruh tiket di terminal (< 5 detik)
  gate.sh                     antarmuka CLI peserta: status, lihat, mulai, uji, cek, kirim
  kirim.php                   mengirim hasil pengujian lokal ke papan pusat mentor (jika online)

papan/                        papan pantau pusat di laptop mentor (opsional jika ada proyektor)
  01_skema.sql                skema database papan progres
  server.php                  server mini HTTP penerima telemetri peserta
  tampil.html                 tampilan visual dashboard CTF Scoreboard untuk proyektor
  docker-compose.yml          container server papan

peserta/                      template lingkungan kerja peserta
  docker-compose.yml          lingkungan lokal peserta (PHP 8.2 + MySQL 8 + Redis + k6)
  gate.bat                    launcher batch Windows untuk peserta
  .env.contoh                 template konfigurasi IP papan mentor dan identitas peserta

panduan/                      dokumentasi kurikulum & materi mandiri peserta
  01_spesifikasi_kurikulum.md silabus lengkap, aturan scoring, dan opsi 1-2 hari
  02_field_manual_materi.md   buku pegangan teknis mandiri 5 modul (Zero Lecture)
  03_panduan_penyusunan.md    pedoman anatomi & pembuatan tiket mandiri
  04_todo_persiapan.md        checklist teknis & logistik H-30 s/d Hari-H
  05_40_star_stories.md       koleksi 40 STAR methods untuk persiapan wawancara kerja
  06_panduan_fundamental.md   matrix kompetensi A-J & kurasi buku standar industri
  07_panduan_gate_tiket.md    standar gerbang kendali mutu (Quality Gates M1-M5 & K1-K5)
```

---

## 💻 Alur Kerja Peserta di Hari-H (100% Solo & Hands-on)

### 1. Ekstrak & Nyalakan Lingkungan
```bash
# Di Windows:
cd peserta
copy .env.contoh .env
docker compose up -d

# Di Linux / macOS:
cd peserta
cp .env.contoh .env
docker compose up -d
```

### 2. Cek Status Progres di Terminal
```bash
# Windows:
gate.bat status

# Linux / macOS:
./gate status
```
Terminal akan menampilkan papan skor tabel 25 tiket + 5 tiket bonus, total poin saat ini, serta tier akreditasi (Junior / Mid / Senior).

### 3. Pilih & Pelajari Tiket
Misalnya peserta memilih tiket **T04** (Race Condition Stok Flash Sale):
```bash
# Windows:
gate.bat lihat T04

# Linux / macOS:
./gate lihat T04
```
Peserta membaca keluhan bisnis, letak kode di `app/Controllers/OrderController.php`, dan membaca panduan teknis di `panduan/02_field_manual_materi_lengkap.md`.

### 4. Jalankan Tes Awal (Membuktikan Cacat / Gate M4)
```bash
# Windows:
gate.bat mulai T04

# Linux / macOS:
./gate mulai T04
```
Pest PHP akan menjalankan test dan menunjukkan kegagalan (Red Phase) karena bug masih ada.

### 5. Lakukan Refactoring / Bugfix
Peserta membuka file `app/Controllers/OrderController.php` dan memperbaiki kode (misal menerapkan atomic conditional update SQL).

### 6. Uji Solusi Mandiri (Gate K2 & K3)
```bash
# Windows:
gate.bat uji T04

# Linux / macOS:
./gate uji T04
```
Jika lolos, Pest PHP akan menampilkan warna hijau `PASS`!

Untuk menguji uji beban konkurensi dengan k6 (tanpa perlu install k6 di Windows/Mac):
```bash
docker compose -f peserta/docker-compose.yml run --rm k6 run /scripts/flash_sale.js
```

### 7. Uji Anti-Regresi Seluruh Tiket
Sebelum mengklaim tiket selesai, jalankan cek cepat untuk memastikan perubahan tidak merusak tiket lain:
```bash
# Windows:
gate.bat cek

# Linux / macOS:
./gate cek
```
Skor poin peserta di terminal akan otomatis bertambah!

### 8. Reset Database ke Kondisi Awal Bersih (Kapan Saja)
Jika database kotor saat bereksperimen (misal: stok produk habis atau status transaksi berantakan):
```bash
# Windows:
gate.bat reset

# Linux / macOS:
./gate reset
```
Dalam 2 detik, seluruh tabel dan seeder awal otomatis di-reset ke kondisi awal bawaan (*Fresh State*).

### 9. Konsultasi Mentor (Hanya Jika Buntu)
Peserta yang stuck lebih dari 30 menit memanggil mentor di meja untuk konsultasi tanya-jawab secara personal.
