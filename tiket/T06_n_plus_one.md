# Tiket T06: Eliminasi Masalah N+1 Query pada Daftar Pesanan

## Metadata
* **Kesulitan:** 🟡 Sedang
* **Kategori:** Database Optimization
* **Poin:** Penuh 10 Pts | Parsial 5 Pts
* **Cara Cek:** Otomatis (`vendor/bin/pest uji/tests/Tickets/Tkt06Test.php`)

## Keluhan Bisnis
Halaman riwayat transaksi pengguna (`GET /api/v1/orders`) memakan waktu loading **4.8 detik** dan sering menyebabkan server database kehabisan connection pool saat jam sibuk.

## Lokasi Kode
* `app/Controllers/OrderController.php` (method `listOrders()`)

## Cara Cek
```bash
vendor/bin/pest uji/tests/Tickets/Tkt06Test.php
```

## Kriteria Kelulusan
1. Jumlah query SQL yang dieksekusi untuk memuat 20 order **maksimal 3 query SQL**.
2. Struktur respon JSON tetap identik (termasuk relasi user dan order items).
3. Waktu respons di bawah 200ms.

## Petunjuk Teknis
* 📖 Baca: `panduan/02_field_manual_materi_lengkap.md` (Modul 2: Database Performance).
* Jangan panggil query di dalam looping `foreach`.
* Kumpulkan seluruh `user_id` dan `order_id`, lalu ambil data menggunakan klausul `WHERE id IN (...)` secara batch.
