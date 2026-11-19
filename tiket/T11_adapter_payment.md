# [T11] Integrasi Payment Gateway Baru via Adapter Pattern

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 🟡 Sedang
* **Kategori:** 🔵 Desain Arsitektur & Fitur
* **Poin:** Penuh: 10 Pts | Parsial: 5 Pts
* **Estimasi Waktu:** 45 Menit

---

## 🚨 1. Keluhan Bisnis
Bisnis meminta integrasi gateway pembayaran baru (Xendit) dalam 3 hari. Saat ini, kode Midtrans lama tertanam kaku (*tightly coupled*) di dalam ratusan baris kode checkout.

---

## 🔍 2. Lokasi Kode
* `app/Services/Payment/PaymentGatewayInterface.php`
* `app/Services/Payment/MidtransAdapter.php`
* `app/Services/Payment/XenditAdapter.php`

---

## 🧪 3. Cara Mereproduksi
```bash
./gate uji T11
```
*Hasil saat ini:* `Failed asserting that checkout uses PaymentGatewayInterface abstraction`.

---

## 🎯 4. Kriteria Kelulusan
- [ ] Dibuat interface bersama `PaymentGatewayInterface` dengan method standar `charge()`.
- [ ] Provider lama (Midtrans) dibungkus ke dalam `MidtransAdapter`.
- [ ] Provider baru (Xendit) dibungkus ke dalam `XenditAdapter`.
- [ ] Checkout controller hanya bergantung pada interface abstraksi, bukan kelas konkret.

---

## 📊 5. Skema Penilaian Parsial
* **Test Case 1 (Adapter Interface Compliance):** Kedua adapter mematuhi kontrak ➔ **5 Pts**.
* **Test Case 2 (Dynamic Gateway Resolution):** Checkout memilih adapter berdasarkan payload ➔ **5 Pts** *(Total 10 Pts)*.

---

## 💡 6. Petunjuk Teknis
* 📖 Baca: `panduan/02_field_manual_materi_lengkap.md` (Modul 3.3: Adapter Pattern).
