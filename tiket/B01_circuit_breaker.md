# [B01] Implementasi Circuit Breaker Pattern pada API Payment Gateway

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 💀 BOSS LEVEL
* **Kategori:** 🛡️ Resilience Engineering
* **Poin Bonus:** +30 Pts
* **Prasyarat:** Total skor tiket wajib $\ge 100$ Pts
* **Estimasi Waktu:** 60–90 Menit

---

## 🚨 1. Keluhan Bisnis
Ketika server payment gateway eksternal mengalami gangguan jaringan (latensi 30 detik), web server backend kita kehabisan worker thread karena antrean request menggantung, menyebabkan seluruh aplikasi crash (*Cascading Failure*).

---

## 🔍 2. Lokasi Kode
* `app/Services/Payment/CircuitBreaker.php`
* Redis Cache

---

## 🧪 3. Cara Mereproduksi
```bash
./gate uji B01
```
*Hasil saat ini:* Request terus mencoba menembak API yang sedang down dan memakan waktu 30 detik.

---

## 🎯 4. Kriteria Kelulusan
- [ ] Mengimplementasikan 3 state: `CLOSED` (Normal), `OPEN` (Fail-Fast), dan `HALF-OPEN` (Trial).
- [ ] Jika terjadi 5 kali kegagalan berturut-turut, sirkuit berubah menjadi `OPEN`.
- [ ] Saat status `OPEN`, request berikutnya langsung gagal dalam tempo $< 2\text{ms}$ dan mengembalikan fallback tanpa memanggil API eksternal.
- [ ] Setelah interval cooldown 30 detik, sirkuit bertransisi ke `HALF-OPEN` untuk menguji kesehatan API eksternal.

---

## 💡 Petunjuk Teknis
* 📖 Baca buku acuan: *Release It!* karya Michael T. Nygard.
* Simpan status kegagalan dan timestamp sirkuit di Redis.
