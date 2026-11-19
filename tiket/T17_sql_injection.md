# [T17] Tutup Celah SQL Injection pada Pencarian Produk

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 🟡 Sedang
* **Kategori:** 🛡️ Keamanan Web (OWASP)
* **Poin:** Penuh: 10 Pts | Parsial: 5 Pts
* **Estimasi Waktu:** 30 Menit

---

## 🚨 1. Keluhan Bisnis
Audit keamanan mendeteksi endpoint pencarian produk `/api/v1/products/search` rentan terhadap serangan SQL Injection kritis. Penyerang dapat membaca seluruh tabel kredensial pengguna.

---

## 🔍 2. Lokasi Kode
* `app/Controllers/SearchController.php` (method `search()`, baris 14)

---

## 🧪 3. Cara Mereproduksi
```bash
./gate uji T17
```
*Hasil saat ini:* Payload `' OR '1'='1` membocorkan seluruh baris data di tabel `products`.

---

## 🎯 4. Kriteria Kelulusan
- [ ] Query pencarian menggunakan *Parameterized Prepared Statement* dengan placeholder `?` atau `:keyword`.
- [ ] Payload `' OR '1'='1` menghasilkan 0 hasil (diperlakukan sebagai string teks biasa, bukan logika boolean SQL).
- [ ] Pencarian kata kunci sah (seperti `"iPhone"`) tetap berfungsi normal.

---

## 📊 5. Skema Penilaian Parsial
* **Test Case 1 (SQLi Neutralization):** Serangan injeksi gagal membocorkan data ➔ **5 Pts**.
* **Test Case 2 (Legitimate Search Accuracy):** Pencarian produk valid tetap akurat ➔ **5 Pts** *(Total 10 Pts)*.

---

## 💡 6. Petunjuk Teknis
* 📖 Baca: `panduan/02_field_manual_materi_lengkap.md` (Modul 5.1: Prepared Statements).
* Gunakan PDO `prepare()` dan passing parameter via array execute.
