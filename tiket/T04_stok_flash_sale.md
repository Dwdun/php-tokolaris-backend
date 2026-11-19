# Tiket T04: Race Condition Stok Minus (-28) Saat Rebutan Flash Sale

## Metadata
* **Kesulitan:** 🔴 Sulit
* **Kategori:** Bug Fix & Concurrency
* **Poin:** Penuh 20 Pts | Parsial 10 Pts
* **Cara Cek:** Campuran (`k6` load test + database check)

## Keluhan Bisnis
Pada promo flash sale iPhone (stok awal 10 unit), sistem mencatat ada 38 pesanan berhasil dan stok barang di database menjadi **-28**. Perusahaan terancam rugi ratusan juta rupiah karena harus membatalkan pesanan pelanggan yang terlanjur membayar.

## Lokasi Kode
* `app/Controllers/OrderController.php` (method `checkout()`)
* Tabel MySQL: `products` (kolom `stock`)

## Cara Cek
```bash
# Jalankan test k6 serbuan 50 request konkuren
k6 run skenario/flash_sale.js
vendor/bin/pest uji/tests/Tickets/Tkt04Test.php
```

## Kriteria Kelulusan
1. Dari 50 request serentak, tepat **10 pesanan sukses** (HTTP 200).
2. Tepat **40 pesanan ditolak** dengan respons HTTP 400 (`"Stok habis"`).
3. Sisa stok di database MySQL harus tepat **0** (tidak boleh pernah minus).

## Petunjuk Teknis
* 📖 Baca: `panduan/02_field_manual_materi_lengkap.md` (Modul 1: Concurrency).
* Pindahkan operasi pengurangan stok ke satu instruksi atomik SQL:
  ```sql
  UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?
  ```
* Evaluasi nilai `rowCount()` atau affected rows dari query update.
