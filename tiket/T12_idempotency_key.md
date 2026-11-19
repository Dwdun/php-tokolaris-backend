# [T12] Proteksi Double-Tap Checkout dengan X-Idempotency-Key

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 🔴 Sulit
* **Kategori:** 🔵 Concurrency & Distributed Systems
* **Poin:** Penuh: 20 Pts | Parsial: 10 Pts
* **Estimasi Waktu:** 60 Menit

---

## 🚨 1. Keluhan Bisnis
Pelanggan dengan koneksi seluler tidak stabil sering menekan tombol bayar berulang kali saat layar memuat (*double-tap*), mengakibatkan saldo terpotong ganda dan pesanan terbuat dua kali.

---

## 🔍 2. Lokasi Kode
* `app/Controllers/OrderController.php` (middleware atau guard idempotency)
* Redis Cache (`REDIS_HOST: 6379`)

---

## 🧪 3. Cara Mereproduksi
```bash
./gate uji T12
```
*Hasil saat ini:* `Failed asserting that two duplicate requests with same X-Idempotency-Key only execute once`.

---

## 🎯 4. Kriteria Kelulusan
- [ ] Endpoint checkout membaca header `X-Idempotency-Key`.
- [ ] Menggunakan Redis operasi atomik `SETNX` dengan TTL 60 detik untuk mengunci key saat eksekusi pertama.
- [ ] Request kedua dengan key yang sama dalam 60 detik mengembalikan respon cached transaksi pertama atau `HTTP 409 Conflict`.
- [ ] Tidak ada pesanan ganda yang terbuat di database MySQL.

---

## 📊 5. Skema Penilaian Parsial
* **Test Case 1 (Duplicate Request Block):** Request kedua ditolak/disajikan dari cache ➔ **10 Pts**.
* **Test Case 2 (Database State Integrity):** Tepat 1 record pesanan yang tersimpan ➔ **10 Pts** *(Total 20 Pts)*.

---

## 💡 6. Petunjuk Teknis
* 📖 Baca: `panduan/02_field_manual_materi_lengkap.md` (Modul 4.3: Idempotency Key Pattern).
