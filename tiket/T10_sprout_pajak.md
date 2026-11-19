# [T10] Kalkulasi Pajak PPN 11% Menggunakan Pola Sprout Class

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 🟡 Sedang
* **Kategori:** 🔵 Fitur & Refactoring
* **Poin:** Penuh: 10 Pts | Parsial: 5 Pts
* **Estimasi Waktu:** 35 Menit

---

## 🚨 1. Keluhan Bisnis
Regulasi baru mewajibkan penarikan pajak PPN 11% dengan aturan pembulatan khusus. Developer takut menambahkan logika baru ini ke dalam controller invoice 1.800 baris karena takut merusak kalkulasi diskon yang sudah ada.

---

## 🔍 2. Lokasi Kode
* Buat kelas baru: `app/Services/TaxCalculatorSprout.php`
* Titik panggil: `app/Controllers/OrderController.php`

---

## 🧪 3. Cara Mereproduksi
```bash
./gate uji T10
```
*Hasil saat ini:* `Class TaxCalculatorSprout not found`.

---

## 🎯 4. Kriteria Kelulusan
- [ ] Aturan pajak PPN 11% dibuat di kelas baru terpisah (*Sprout Class*).
- [ ] Unit test kelas baru mencapai coverage 100% (termasuk kasus bebas pajak & angka desimal ganjil).
- [ ] Kode lama hanya memanggil kelas baru ini pada satu titik sambung (*Seam*) tanpa merombak logika controller.

---

## 📊 5. Skema Penilaian Parsial
* **Test Case 1 (Sprout Unit Test):** Kalkulasi PPN 11% akurat ➔ **5 Pts**.
* **Test Case 2 (Seam Integration):** Terintegrasi ke alur checkout ➔ **5 Pts** *(Total 10 Pts)*.

---

## 💡 6. Petunjuk Teknis
* 📖 Baca: `panduan/02_field_manual_materi_lengkap.md` (Modul 3.2: Teknik Sprout Class).
