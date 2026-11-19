# [T16] Sanitasi Input XSS pada Catatan Pengiriman Pesanan

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 🟢 Mudah
* **Kategori:** 🛡️ Keamanan Web (OWASP)
* **Poin:** Penuh: 5 Pts | Parsial: 2.5 Pts
* **Estimasi Waktu:** 20 Menit

---

## 🚨 1. Keluhan Bisnis
Laporan penetration test menemukan celah Stored Cross-Site Scripting (XSS). Penyerang bisa menyisipkan skrip berbahaya pada field catatan pengiriman yang akan tereksekusi di browser admin gudang saat mencetak label.

---

## 🔍 2. Lokasi Kode
* `app/Controllers/OrderController.php` (atau template rendering invoice)

---

## 🧪 3. Cara Mereproduksi
```bash
./gate uji T16
```
*Hasil saat ini:* Payload `<script>alert('XSS')</script>` tereksekusi mentah tanpa karakter escape.

---

## 🎯 4. Kriteria Kelulusan
- [ ] Seluruh karakter berbahaya HTML (`<`, `>`, `"`, `'`, `&`) dikonversi menjadi HTML entities yang aman.
- [ ] Payload serangan XSS tidak tereksekusi di browser klien.
- [ ] Teks catatan normal tetap tampil bersih dan terbaca manusia.

---

## 📊 5. Skema Penilaian Parsial
* **Test Case 1 (XSS Payload Escape):** Script tag berhasil di-escape ➔ **2.5 Pts**.
* **Test Case 2 (Regular Text Integrity):** Teks biasa tetap utuh ➔ **2.5 Pts** *(Total 5 Pts)*.

---

## 💡 6. Petunjuk Teknis
* Gunakan fungsi PHP `htmlspecialchars($input, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')`.
