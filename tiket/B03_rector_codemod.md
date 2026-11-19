# [B03] Automated Large-Scale Refactoring dengan Custom Rector AST Rule

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 💀 BOSS LEVEL
* **Kategori:** 🤖 DevEx & Large-Scale Changes
* **Poin Bonus:** +25 Pts
* **Prasyarat:** Total skor tiket wajib $\ge 100$ Pts
* **Estimasi Waktu:** 45–60 Menit

---

## 🚨 1. Keluhan Bisnis
Terdapat 50 file legacy yang masih menggunakan sintaks array lawas `array(...)` dan konkatenasi query manual yang tidak aman. Melakukan refactoring satu per satu secara manual memakan waktu berhari-hari dan rawan kesalahan manusia.

---

## 🔍 2. Lokasi Kode
* Konfigurasi: `rector.php`

---

## 🧪 3. Cara Mereproduksi
```bash
vendor/bin/rector process --dry-run
```
*Hasil saat ini:* Puluhan file legacy ditandai memerlukan perbaikan sintaks.

---

## 🎯 4. Kriteria Kelulusan
- [ ] Menulis rule set di `rector.php` untuk mentransformasi Abstract Syntax Tree (AST) secara otomatis:
  1. Konversi array lama `array()` menjadi short syntax `[]`.
  2. Penambahan type hints otomatis pada method private.
  3. Konversi fungsi string usang ke fungsi PHP 8 modern.
- [ ] Menjalankan `vendor/bin/rector process` dan memverifikasi seluruh test suite tetap hijau 100%.

---

## 💡 Petunjuk Teknis
* 📖 Baca buku acuan: *Software Engineering at Google* (Bab: Large-Scale Changes).
