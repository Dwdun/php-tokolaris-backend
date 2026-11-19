# [T13] Ekstraksi Payload Notifikasi Email Menjadi DTO Bersih

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 🟢 Mudah
* **Kategori:** 🟣 Refactoring & Type Safety
* **Poin:** Penuh: 5 Pts | Parsial: 2.5 Pts
* **Estimasi Waktu:** 20 Menit

---

## 🚨 1. Keluhan Bisnis
Sistem notifikasi sering error fatal karena parameter email dikirim dalam bentuk array asosiatif mentah tanpa struktur pasti (`$params['emaiil']` salah ketik nama key), memicu email konfirmasi gagal terkirim tanpa jejak.

---

## 🔍 2. Lokasi Kode
* Buat kelas baru: `app/DTO/OrderNotificationDTO.php`

---

## 🧪 3. Cara Mereproduksi
```bash
./gate uji T13
```
*Hasil saat ini:* `Class OrderNotificationDTO not found`.

---

## 🎯 4. Kriteria Kelulusan
- [ ] Dibuat kelas `OrderNotificationDTO` dengan properti bertipe ketat (*strict typed properties*): `orderId (int)`, `recipientEmail (string)`, `totalAmount (float)`.
- [ ] Validasi format email dilakukan langsung saat DTO dibuat (`fromArray()`).
- [ ] Menyediakan method `toPayload(): array` untuk serialisasi json ke antrean pesan.

---

## 📊 5. Skema Penilaian Parsial
* **Test Case 1 (Type Validation):** Error saat email invalid ➔ **2.5 Pts**.
* **Test Case 2 (Serialization Output):** Struktur payload JSON akurat ➔ **2.5 Pts** *(Total 5 Pts)*.

---

## 💡 6. Petunjuk Teknis
* Gunakan fitur `readonly class` bawaan PHP 8.2 untuk menjamin *immutability*.
