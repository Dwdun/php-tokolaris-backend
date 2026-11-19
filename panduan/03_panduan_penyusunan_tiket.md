# 📑 Panduan Penyusunan Tiket: Format, Anatomi & Template
> **Pedoman Praktis:** Cara menyusun lembar misi tiket agar peserta bootcamp dapat bekerja 100% mandiri (*self-service*), memahami masalah bisnis, mengeksekusi pengujian, dan mendapatkan feedback instan tanpa perlu bertanya ke mentor.

---

## 📂 1. Struktur Folder Tiket di Repositori Proyek

Agar sistem modular dan rapi, setiap tiket memiliki 2 komponen utama di dalam repositori:
1. **Lembar Misi Markdown:** Disimpan di `docs/tickets/TKT-XX.md` (dibaca peserta).
2. **File Automated Test:** Disimpan di `tests/Tickets/TktXXTest.php` (dieksekusi peserta untuk verifikasi).

```text
shop-legacy-app/
├── docs/
│   ├── manuals/                    <-- Teori & referensi teknis
│   │   ├── 01-concurrency.md
│   │   └── 02-database-n-plus-1.md
│   └── tickets/                    <-- 25 Lembar panduan tiket
│       ├── TKT-01.md
│       ├── TKT-04.md
│       └── TKT-06.md
├── tests/
│   └── Tickets/                    <-- 25 Automated test per tiket
│       ├── Tkt01Test.php
│       ├── Tkt04Test.php
│       └── Tkt06Test.php
└── ...
```

---

## 📐 2. Anatomi Standar Sebuah Tiket (Template Wajib)

Setiap file tiket markdown (`TKT-XX.md`) **wajib mengikuti struktur standar 7 bagian** berikut:

```markdown
# [ID-TIKET] Judul Masalah yang Jelas & Spesifik

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 🟢 EASY / 🟡 MEDIUM / 🔴 HARD
* **Kategori:** Bug Fix / Database / Feature / Security / Async / Testing / Tech Debt
* **Poin:** Penuh: XX Pts | Parsial (Setengah): YY Pts
* **Estimasi Waktu:** XX Menit

---

## 🚨 1. Keluhan Bisnis / Gejala Masalah (The Bug Story)
Ceritakan skenario nyata dari sudut pandang pengguna atau laporan Customer Support/Bisnis.
Kenapa bug ini merugikan perusahaan?

---

## 🔍 2. Lokasi Kode yang Terlibat (Target Files)
Sebutkan file dan baris yang perlu diperiksa agar peserta tidak tersesat di kode ribuan baris:
* `app/Controllers/OrderController.php` (sekitar baris 140–180)
* `app/Models/Product.php`

---

## 🧪 3. Cara Mereproduksi Masalah (How to Reproduce)
Perintah terminal yang dijalankan peserta untuk melihat kondisi GAGAL (merah) saat ini:
```bash
vendor/bin/pest tests/Tickets/TktXXTest.php
```

---

## 🎯 4. Kriteria Kelulusan (Acceptance Criteria)
Kondisi spesifik yang harus dipenuhi agar tiket dianggap selesai:
- [ ] Kondisi A terpenuhi...
- [ ] Kondisi B terpenuhi...
- [ ] Test otomatis menghasilkan status **PASSED (Hijau)**.

---

## 📊 5. Skema Penilaian Parsial (Scoring Breakdown)
Jelaskan bagaimana poin diberikan:
* **Lolos Test 1 (Unit Logic):** Mendapatkan 50% Poin.
* **Lolos Test 2 (Integration / Edge Case):** Mendapatkan 100% Poin Penuh.

---

## 💡 6. Petunjuk Teknis & Bahan Bacaan (Hints & Clues)
Arahkan peserta ke dokumen manual yang relevan di repositori:
* 📖 Baca panduan: `docs/manuals/XX-nama-panduan.md`
* 💡 *Hint:* Perhatikan fungsi `xxx()` pada database engine.

---

## 🚀 7. Langkah Penyelesaian (Action Steps)
Langkah taktis yang disarankan untuk dikerjakan:
1. Buka file `tests/Tickets/TktXXTest.php` untuk memahami ekspektasi output.
2. Edit kode di file target.
3. Jalankan kembali test sampai hijau.
```

---

## 🔍 3. Tiga Contoh Nyata Lengkap Penyusunan Tiket

Berikut adalah contoh konkret bagaimana menyusun tiket level **Easy**, **Medium**, dan **Hard**:

---

### Contoh 1: Tiket 🟢 EASY (`TKT-01.md`)

```markdown
# [TKT-01] Fix Null Pointer Exception saat User Tanpa Alamat Checkout

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 🟢 EASY
* **Kategori:** 🔴 Bug Fix
* **Poin:** Penuh: 5 Pts | Parsial: 2.5 Pts
* **Estimasi Waktu:** 15–20 Menit

## 🚨 1. Keluhan Bisnis
Customer Support menerima komplain bahwa pengguna baru yang belum mengisi alamat di profil mengalami layar putih error 500 saat mencoba menekan tombol checkout. Hal ini menyebabkan konversi penjualan anjlok.

## 🔍 2. Lokasi Kode
* `app/Services/CheckoutService.php` (pada method `prepareShipping()`)

## 🧪 3. Cara Mereproduksi
Jalankan test berikut di terminal. Saat ini test menghasilkan error fatal:
```bash
vendor/bin/pest tests/Tickets/Tkt01Test.php
```
*Output saat ini:* `Error: Attempt to read property "city" on null`.

## 🎯 4. Kriteria Kelulusan
- [ ] Jika user belum memiliki alamat (`$user->address` bernilai `null`), sistem tidak boleh crash.
- [ ] Sistem harus mengembalikan response valid: `status: 400` dengan pesan `"Alamat pengiriman wajib diisi sebelum checkout"`.
- [ ] Jika user memiliki alamat lengkap, proses pengiriman berjalan normal seperti biasa.

## 📊 5. Skema Penilaian Parsial
* **Test Case 1 (Null Safety):** Menguji user tanpa alamat tidak crash $\rightarrow$ **2.5 Pts**.
* **Test Case 2 (Valid Address Flow):** Menguji user dengan alamat tetap sukses $\rightarrow$ **2.5 Pts** *(Total 5 Pts)*.

## 💡 6. Petunjuk Teknis
* Gunakan operator Nullsafe PHP 8 (`?->`) atau Null Coalescing (`??`).
* Pastikan melakukan pengecekan di awal method (*Guard Clause*).
```

---

### Contoh 2: Tiket 🟡 MEDIUM (`TKT-06.md`)

```markdown
# [TKT-06] Eliminasi N+1 Query pada Daftar 20 Transaksi Terakhir

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 🟡 MEDIUM
* **Kategori:** 🟡 Database Optimization
* **Poin:** Penuh: 10 Pts | Parsial: 5 Pts
* **Estimasi Waktu:** 30–45 Menit

## 🚨 1. Keluhan Bisnis
Halaman riwayat transaksi pengguna (`/api/v1/orders`) memakan waktu loading **4.8 detik** dan sering menyebabkan server database kehabisan koneksi saat jam makan siang.

## 🔍 2. Lokasi Kode
* `app/Controllers/OrderController.php` (method `index()`)
* `app/Models/Order.php`

## 🧪 3. Cara Mereproduksi
```bash
vendor/bin/pest tests/Tickets/Tkt06Test.php
```
*Hasil saat ini:* `Failed asserting that 152 queries is less than 5 queries`.

## 🎯 4. Kriteria Kelulusan
- [ ] Jumlah query SQL yang dieksekusi untuk memuat 20 order **maksimal 3 query SQL** (tidak boleh ada query di dalam looping `foreach`).
- [ ] Struktur respon JSON yang dikembalikan ke frontend tidak boleh berubah sedikit pun (nama field, relasi items, dan toko harus identik).
- [ ] Durasi eksekusi endpoint turun di bawah **200 milidetik**.

## 📊 5. Skema Penilaian Parsial
* **Test Case 1 (Query Count Check):** Jumlah query berkurang di bawah 5 query $\rightarrow$ **5 Pts**.
* **Test Case 2 (Payload Integrity):** Seluruh data relasi toko & barang tetap termuat lengkap $\rightarrow$ **5 Pts** *(Total 10 Pts)*.

## 💡 6. Petunjuk Teknis
* 📖 Baca manual: `docs/manuals/02-database-indexing-and-explain.md`.
* Ganti pemanggilan model lazy loading `$order->items` di dalam loop menjadi batch Eager Loading: `Order::with(['items', 'store'])->get()`.
```

---

### Contoh 3: Tiket 🔴 HARD (`TKT-04.md`)

```markdown
# [TKT-04] Fix Race Condition Stok Minus (-28) Saat Rebutan Flash Sale

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 🔴 HARD
* **Kategori:** 🔴 Concurrency / Bug Fix
* **Poin:** Penuh: 20 Pts | Parsial: 10 Pts
* **Estimasi Waktu:** 60–75 Menit

## 🚨 1. Keluhan Bisnis
Pada promo flash sale iPhone (stok promo: 10 unit), sistem mencatat ada 38 pesanan berhasil dan stok barang di database menjadi **-28**. Perusahaan terancam rugi ratusan juta rupiah karena harus membatalkan pesanan pelanggan yang terlanjur membayar.

## 🔍 2. Lokasi Kode
* `app/Controllers/CheckoutController.php` (method `processOrder()`)
* Tabel MySQL: `products` (kolom `stock`) dan tabel `orders`

## 🧪 3. Cara Mereproduksi
Jalankan simulasi serbuan 50 pengguna serentak menggunakan script k6:
```bash
k6 run load-tests/flash-sale.js
php tests/verify-flash-sale.php
```
*Hasil saat ini:* `CRITICAL BUG: Final stock is -28. Total sold: 38`.

## 🎯 4. Kriteria Kelulusan
- [ ] 50 user menembak bersamaan ke barang yang memiliki stok 10 unit.
- [ ] Tepat **10 pesanan sukses** (HTTP 200).
- [ ] Tepat **40 pesanan ditolak** dengan respons HTTP 400 (`"Stok habis"`).
- [ ] Sisa stok di database MySQL harus tepat **0** (tidak boleh pernah minus).

## 📊 5. Skema Penilaian Parsial
* **Test Case 1 (Unit Boundary Check):** Validasi logika stok habis di level unit test $\rightarrow$ **10 Pts**.
* **Test Case 2 (k6 Concurrency Rush):** Lolos uji serbuan 50 request konkuren k6 $\rightarrow$ **10 Pts** *(Total 20 Pts)*.

## 💡 6. Petunjuk Teknis
* 📖 Baca manual: `docs/manuals/01-concurrency-and-locking.md`.
* **JANGAN** lakukan `SELECT stock` lalu `UPDATE stock = stock - 1` di level kode PHP! Di lingkungan multi-request, nilai memori akan saling menimpa.
* Gunakan **Atomic Database Operation**:
  ```sql
  UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?
  ```
* Periksa *affected rows* dari query update untuk menentukan apakah user berhasil merebut stok atau kehabisan.
```

---

## ⚡ 4. Hubungan Tiket dengan File Test Pest PHP

Setiap file test di `tests/Tickets/TktXXTest.php` dirancang secara elegan untuk memfasilitasi penilaian parsial:

```php
<?php
// tests/Tickets/Tkt04Test.php

it('prevents overselling at the unit validation level', function () {
    // Test Case 1: Nilai Parsial (10 Poin)
    $product = createProduct(['stock' => 0]);
    $response = postJson('/api/v1/orders/checkout', ['product_id' => $product->id, 'quantity' => 1]);
    
    expect($response->status())->toBe(400)
        ->and($response->json('message'))->toContain('Stok habis');
});

it('maintains strict zero stock under high concurrency flash sale', function () {
    // Test Case 2: Nilai Penuh (+10 Poin)
    // Menjalankan sub-process k6 load test lokal
    exec('k6 run --quiet load-tests/flash-sale.js', $output, $exitCode);
    expect($exitCode)->toBe(0);

    $finalStock = getDatabaseStock(productId: 101);
    expect($finalStock)->toBe(0); // Mutlak 0, tidak boleh minus!
});
```

---

## ✅ 5. Checklist Validasi Kualitas Tiket Sebelum Rilis ke Peserta

Sebelum event bootcamp dimulai, pastikan setiap tiket memenuhi checklist ini:
- [ ] Apakah judul tiket mencerminkan masalah bisnis nyata?
- [ ] Apakah lokasi file target disebutkan secara spesifik (agar peserta tidak membuang waktu mencari file)?
- [ ] Apakah perintah test untuk reproduksi error sudah diuji dan menghasilkan status **MERAH** di starter kit?
- [ ] Apakah kriteria kelulusan jelas dan terukur secara biner (LOLOS vs GAGAL)?
- [ ] Apakah ada petunjuk teknis (*hints*) yang mengarahkan ke file manual yang tepat?
- [ ] Apakah sudah diverifikasi di branch solusi master bahwa tiket ini menghasilkan status **HIJAU**?
