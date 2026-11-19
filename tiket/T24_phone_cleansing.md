# [T24] Standardisasi Data Format Nomor Telepon E.164 via Script

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 🟢 Mudah
* **Kategori:** 🧹 Data Cleansing
* **Poin:** Penuh: 5 Pts | Parsial: 2.5 Pts
* **Estimasi Waktu:** 25 Menit

---

## 🚨 1. Keluhan Bisnis
Integrasi SMS OTP baru gagal mengirim kode verifikasi ke 18% pengguna karena format nomor telepon di database acak-acakan (`0812...`, `+628...`, `62-812...`, spasi, dan tanda strip).

---

## 🔍 2. Lokasi Kode
* `kunci/24_T24_phone_cleansing.sql`
* Database MySQL: tabel `users` (kolom `phone`)

---

## 🧪 3. Cara Mereproduksi
```bash
./gate uji T24
```
*Hasil saat ini:* Ditemukan ratusan format nomor telepon non-standar di tabel `users`.

---

## 🎯 4. Kriteria Kelulusan
- [ ] Seluruh nomor telepon aktif terstandarisasi menjadi format internasional E.164 (`+628...`).
- [ ] Karakter pemisah seperti spasi, tanda kurung, dan tanda strip tereliminasi bersih.
- [ ] Awalan `08` dan `628` terkonversi secara idempoten menjadi `+628`.

---

## 📊 5. Skema Penilaian Parsial
* **Test Case 1 (Prefix Standardization):** Awalan nomor seragam `+62` ➔ **2.5 Pts**.
* **Test Case 2 (Character Stripping):** Bebas dari karakter non-numerik ➔ **2.5 Pts** *(Total 5 Pts)*.

---

## 💡 6. Petunjuk Teknis
* Gunakan ekspresi reguler MySQL: `REGEXP_REPLACE(phone, '[^0-9+]', '')`.
