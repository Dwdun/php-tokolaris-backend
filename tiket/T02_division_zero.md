# [T02] Fix Division by Zero saat Diskon Voucher 100%

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 🟢 Mudah
* **Kategori:** 🔴 Bug Fix
* **Poin:** Penuh: 5 Pts | Parsial: 2.5 Pts
* **Estimasi Waktu:** 15–20 Menit

---

## 🚨 1. Keluhan Bisnis
Pada peluncuran promo voucher giveaway `"GRATIS100"`, aplikasi keranjang belanja mengalami fatal crash `DivisionByZeroError`. Pelanggan tidak bisa menyelesaikan transaksi barang gratis.

---

## 🔍 2. Lokasi Kode
* `app/Services/CartService.php` (method `calculateTotal()`, baris 26)

---

## 🧪 3. Cara Mereproduksi
```bash
./gate uji T02
```
*Output saat ini:* `DivisionByZeroError: Division by zero at CartService.php:26`.

---

## 🎯 4. Kriteria Kelulusan
- [ ] Voucher diskon 100% menghasilkan `final_total = 0`.
- [ ] Rasio penghematan (`savings_ratio`) harus bernilai `100.0` (bukan error pembagian nol).
- [ ] Diskon normal (seperti `DISKON10`) tetap terhitung akurat sesuai proporsi.

---

## 📊 5. Skema Penilaian Parsial
* **Test Case 1 (Zero Div Guard):** Menguji kalkulasi voucher 100% tanpa crash ➔ **2.5 Pts**.
* **Test Case 2 (Standard Ratio):** Menguji akurasi kalkulasi diskon reguler ➔ **2.5 Pts** *(Total 5 Pts)*.

---

## 💡 6. Petunjuk Teknis
* Periksa apakah `$finalTotal == 0` sebelum menghitung pembagian persentase.
* Jika `$finalTotal == 0` dan `$discount > 0`, maka persentase penghematan adalah mutlak `100.0%`.
