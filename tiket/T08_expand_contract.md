# [T08] Migrasi Skema Tabel Besar dengan Pola Expand-Contract

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 🔴 Sulit
* **Kategori:** 🟡 Database Migration
* **Poin:** Penuh: 20 Pts | Parsial: 10 Pts
* **Estimasi Waktu:** 60 Menit

---

## 🚨 1. Keluhan Bisnis
Tim logistik membutuhkan kolom `tracking_code` pada tabel `orders` yang memiliki jutaan baris. Menjalankan perintah `ALTER TABLE` langsung di jam kerja akan mengunci seluruh tabel dan menghentikan proses belanja pelanggan (*downtime*).

---

## 🔍 2. Lokasi Kode
* `kunci/11_T08_expand_contract.sql`
* Database MySQL: tabel `orders`

---

## 🧪 3. Cara Mereproduksi
```bash
./gate uji T08
```
*Hasil saat ini:* `Failed asserting that zero-downtime migration strategy exists`.

---

## 🎯 4. Kriteria Kelulusan
- [ ] Fase Expand: Menambahkan kolom baru bersifat `NULLABLE` tanpa default value berat.
- [ ] Fase Backfill: Script batch pengisian data lama berjalan per chunk (500 baris) agar tidak membebani IOPS disk.
- [ ] Fase Contract: Index ditambahkan setelah data terisi penuh.

---

## 📊 5. Skema Penilaian Parsial
* **Test Case 1 (Expand Phase Check):** Kolom baru ditambahkan secara non-blocking ➔ **10 Pts**.
* **Test Case 2 (Chunked Backfill Verification):** Seluruh baris lama terisi tracking code tanpa table lock ➔ **10 Pts** *(Total 20 Pts)*.

---

## 💡 6. Petunjuk Teknis
* 📖 Baca: `panduan/02_field_manual_materi_lengkap.md` (Modul 5.4: Pola Expand-Contract).
