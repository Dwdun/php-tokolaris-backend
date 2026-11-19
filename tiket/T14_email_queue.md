# [T14] Pemisahan Pengiriman Email dari HTTP Thread ke Redis Queue

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 🟡 Sedang
* **Kategori:** 🟣 Asynchronous Architecture
* **Poin:** Penuh: 10 Pts | Parsial: 5 Pts
* **Estimasi Waktu:** 35 Menit

---

## 🚨 1. Keluhan Bisnis
Proses checkout terasa lambat (3.2 detik) karena backend menunggu respon koneksi server SMTP email eksternal sebelum membalas respon HTTP ke aplikasi pengguna.

---

## 🔍 2. Lokasi Kode
* `app/Controllers/OrderController.php` (method `checkout()`)
* Redis Queue: key `queue:emails`

---

## 🧪 3. Cara Mereproduksi
```bash
./gate uji T14
```
*Hasil saat ini:* `Failed asserting that email job is pushed to Redis list queue:emails`.

---

## 🎯 4. Kriteria Kelulusan
- [ ] Endpoint checkout tidak lagi memanggil fungsi kirim email secara synchronous.
- [ ] Payload email didorong (*LPUSH*) ke antrean Redis `queue:emails` dalam hitungan milidetik.
- [ ] Waktu eksekusi checkout terpangkas dari 3.2 detik menjadi di bawah 200 milidetik.

---

## 📊 5. Skema Penilaian Parsial
* **Test Case 1 (Redis Push Check):** Payload DTO tersimpan di list Redis ➔ **5 Pts**.
* **Test Case 2 (HTTP Latency Reduction):** Response checkout $< 200\text{ms}$ ➔ **5 Pts** *(Total 10 Pts)*.

---

## 💡 6. Petunjuk Teknis
* 📖 Baca: `panduan/02_field_manual_materi_lengkap.md` (Modul 4.2: Pola Queue Producer-Consumer).
