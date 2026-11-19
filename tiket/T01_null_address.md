# [T01] Fix Null Pointer Exception saat User Tanpa Alamat Checkout

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 🟢 Mudah
* **Kategori:** 🔴 Bug Fix
* **Poin:** Penuh: 5 Pts | Parsial: 2.5 Pts
* **Estimasi Waktu:** 15–20 Menit

---

## 🚨 1. Keluhan Bisnis
Customer Support menerima laporan bahwa pengguna baru yang belum mengisi alamat di profil mengalami layar putih error 500 saat mencoba menekan tombol checkout. Hal ini menyebabkan konversi penjualan anjlok pada pengguna baru.

---

## 🔍 2. Lokasi Kode
* `app/Controllers/OrderController.php` (method `checkout()`, baris 32)
* Model: `app/Support/Database.php`

---

## 🧪 3. Cara Mereproduksi
Jalankan test lokal untuk melihat error:
```bash
./gate uji T01
```
*Output saat ini:* `Failed asserting that 500 is 400` atau *Attempt to read property "address" on null*.

---

## 🎯 4. Kriteria Kelulusan
- [ ] Jika user belum memiliki alamat (`$user['address']` bernilai `null` atau string kosong), sistem tidak boleh crash.
- [ ] Sistem harus mengembalikan response terstruktur: `status: error`, `code: 400`, dengan pesan `"Alamat pengiriman wajib diisi sebelum checkout"`.
- [ ] Jika user memiliki alamat lengkap, proses checkout berjalan normal (HTTP 200).

---

## 📊 5. Skema Penilaian Parsial
* **Test Case 1 (Null Safety Guard):** Menguji user tanpa alamat mengembalikan HTTP 400 rapi ➔ **2.5 Pts**.
* **Test Case 2 (Valid Address Flow):** Menguji user dengan alamat tetap sukses checkout ➔ **2.5 Pts** *(Total 5 Pts)*.

---

## 💡 6. Petunjuk Teknis
* Gunakan operator null-coalescing PHP (`??`) atau periksa nilai `trim((string)$user['address']) === ''`.
* Pasang *Guard Clause* di awal fungsi sebelum validasi produk.
