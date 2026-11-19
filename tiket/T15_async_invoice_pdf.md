# [T15] Offload Pembuatan PDF Invoice Berat ke Background Worker

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 🔴 Sulit
* **Kategori:** 🟣 Asynchronous Architecture
* **Poin:** Penuh: 20 Pts | Parsial: 10 Pts
* **Estimasi Waktu:** 60 Menit

---

## 🚨 1. Keluhan Bisnis
Saat jam sibuk, ratusan pengguna mengunduh PDF invoice secara bersamaan. Proses rendering PDF yang memakan waktu 18 detik di thread HTTP menyebabkan web server kehabisan worker pool dan memicu bencana **HTTP 504 Gateway Timeout**.

---

## 🔍 2. Lokasi Kode
* `app/Controllers/OrderController.php` (method `generateInvoice()`)
* Skenario k6: `skenario/invoice_timeout.js`

---

## 🧪 3. Cara Mereproduksi
```bash
k6 run skenario/invoice_timeout.js
```
*Hasil saat ini:* 100% request mengalami timeout karena blocking HTTP thread.

---

## 🎯 4. Kriteria Kelulusan
- [ ] Endpoint `generateInvoice()` diubah menjadi asynchronous: segera mengembalikan status `HTTP 202 Accepted` beserta `job_id` dalam $< 100\text{ms}$.
- [ ] Job pembuatan PDF didorong ke Redis list `queue:pdf_invoices`.
- [ ] Uji beban k6 tidak lagi mengalami HTTP 504 Gateway Timeout.

---

## 📊 5. Skema Penilaian Parsial
* **Test Case 1 (HTTP 202 Contract):** Endpoint merespon cepat dengan job tracking ID ➔ **10 Pts**.
* **Test Case 2 (k6 Concurrency Throughput):** Lolos uji beban 20 request konkuren tanpa timeout ➔ **10 Pts** *(Total 20 Pts)*.

---

## 💡 6. Petunjuk Teknis
* 📖 Baca: `panduan/02_field_manual_materi_lengkap.md` (Modul 4.1: Dampak Blocking I/O).
