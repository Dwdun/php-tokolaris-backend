# [T03] Fix State Machine Bypass Pembatalan Pesanan

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 🟡 Sedang
* **Kategori:** 🔴 Bug Fix / State Invariants
* **Poin:** Penuh: 10 Pts | Parsial: 5 Pts
* **Estimasi Waktu:** 30–40 Menit

---

## 🚨 1. Keluhan Bisnis
Ditemukan celah di mana pesanan yang barangnya sudah diterima pelanggan (`status = 'DELIVERED'`) masih bisa dibatalkan (`status = 'CANCELLED'`) melalui endpoint pembatalan. Hal ini memicu klaim pengembalian dana sepihak (*fraud*).

---

## 🔍 2. Lokasi Kode
* `app/Controllers/OrderController.php` (method `cancelOrder()`, baris 115)

---

## 🧪 3. Cara Mereproduksi
```bash
./gate uji T03
```
*Output saat ini:* `Failed asserting that order with DELIVERED status returns 422 Unprocessable Entity`.

---

## 🎯 4. Kriteria Kelulusan
- [ ] Pesanan dengan status `'DELIVERED'` atau `'CANCELLED'` dilarang dibatalkan ulang.
- [ ] Sistem mengembalikan status `HTTP 422 Unprocessable Entity` jika transisi status tidak sah.
- [ ] Pesanan dengan status `'PENDING'` atau `'PAID'` tetap dapat dibatalkan secara sah.

---

## 📊 5. Skema Penilaian Parsial
* **Test Case 1 (Illegal State Guard):** Menolak pembatalan order `DELIVERED` ➔ **5 Pts**.
* **Test Case 2 (Legal Cancellation):** Mengizinkan pembatalan order `PENDING` ➔ **5 Pts** *(Total 10 Pts)*.

---

## 💡 6. Petunjuk Teknis
* Periksa status pesanan saat ini: `in_array($order['status'], ['DELIVERED', 'CANCELLED'])`.
* Terapkan prinsip *State Invariants* sebelum mengeksekusi perintah SQL `UPDATE`.
