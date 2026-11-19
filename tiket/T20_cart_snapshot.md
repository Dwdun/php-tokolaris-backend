# [T20] Karakterisasi Snapshot Test Pertama pada Logika Keranjang

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 🟢 Mudah
* **Kategori:** 🧪 Testing & Safety Net
* **Poin:** Penuh: 5 Pts | Parsial: 2.5 Pts
* **Estimasi Waktu:** 20 Menit

---

## 🚨 1. Keluhan Bisnis
Modul kalkulasi keranjang belanja `CartService` tidak memiliki automated test sama sekali. Developer takut melakukan refactoring karena tidak memiliki acuan apakah output hasil hitungan berubah atau tidak.

---

## 🔍 2. Lokasi Kode
* `uji/tests/Tickets/Tkt20Test.php`
* `app/Services/CartService.php`

---

## 🧪 3. Cara Mereproduksi
```bash
./gate uji T20
```
*Hasil saat ini:* File snapshot test belum ada.

---

## 🎯 4. Kriteria Kelulusan
- [ ] Dibuat snapshot test menggunakan Pest PHP yang mengunci minimal 3 variasi keranjang:
  1. Keranjang belanja standar tanpa diskon.
  2. Keranjang belanja dengan voucher diskon 10%.
  3. Keranjang belanja dengan banyak item kuantitas berbeda.
- [ ] Test menghasilkan file snapshot acuan (*golden master*) di folder `__snapshots__`.

---

## 📊 5. Skema Penilaian Parsial
* **Test Case 1 (Snapshot Setup):** Test snapshot berhasil dijalankan ➔ **2.5 Pts**.
* **Test Case 2 (Assertion Match):** Seluruh snapshot cocok dengan output acuan ➔ **2.5 Pts** *(Total 5 Pts)*.

---

## 💡 6. Petunjuk Teknis
* Gunakan method Pest: `expect($result)->toMatchSnapshot()`.
* 📖 Baca: `panduan/02_field_manual_materi_lengkap.md` (Modul 3.1: Characterization Test).
