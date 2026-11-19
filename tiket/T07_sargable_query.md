# [T07] Optimasi Query Rekap Tanggal Menjadi Sargable Date Range

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 🟡 Sedang
* **Kategori:** 🟡 Database Optimization
* **Poin:** Penuh: 10 Pts | Parsial: 5 Pts
* **Estimasi Waktu:** 30 Menit

---

## 🚨 1. Keluhan Bisnis
Dashboard laporan keuangan tahunan sering mengalami *Lock Wait Timeout* karena query rekap transaksi membaca 2 juta baris selama 18 detik. Index pada kolom `created_at` tidak terbaca oleh MySQL.

---

## 🔍 2. Lokasi Kode
* `app/Controllers/ReportController.php` (method `getSalesRekapByYear()`)

---

## 🧪 3. Cara Mereproduksi
```bash
./gate uji T07
```
*Hasil saat ini:* `Failed asserting that query duration (18000ms) is less than 100ms`.

---

## 🎯 4. Kriteria Kelulusan
- [ ] Query tidak lagi membungkus kolom ke dalam fungsi kalkulasi (`WHERE YEAR(created_at) = 2024`).
- [ ] Query menggunakan rentang nilai langsung (*Sargable Range Query*): `WHERE created_at >= '2024-01-01 00:00:00' AND created_at < '2025-01-01 00:00:00'`.
- [ ] Waktu eksekusi query turun di bawah 100 milidetik.

---

## 📊 5. Skema Penilaian Parsial
* **Test Case 1 (Sargable Syntax Check):** Tidak ada pemanggilan fungsi tanggal pada kolom `WHERE` ➔ **5 Pts**.
* **Test Case 2 (Execution Time Threshold):** Durasi query $< 100\text{ms}$ ➔ **5 Pts** *(Total 10 Pts)*.

---

## 💡 6. Petunjuk Teknis
* 📖 Baca: `panduan/02_field_manual_materi_lengkap.md` (Modul 2.3: Aturan Sargable Query).
