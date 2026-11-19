# [T25] Perbaiki 15 Fatal Error PHPStan Level 5 pada Utility Helper

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 🟡 Sedang
* **Kategori:** 🧹 Type Safety & Static Analysis
* **Poin:** Penuh: 10 Pts | Parsial: 5 Pts
* **Estimasi Waktu:** 35 Menit

---

## 🚨 1. Keluhan Bisnis
Sistem sering memicu fatal error di server produksi saat runtime karena variabel tidak terdefinisi (*undefined variable*) atau pemanggilan method pada objek yang bernilai null.

---

## 🔍 2. Lokasi Kode
* `app/Support/Helpers.php` (atau file helper utility)

---

## 🧪 3. Cara Mereproduksi
Jalankan static analyzer PHPStan di terminal:
```bash
vendor/bin/phpstan analyse --level=5 app/Support
```
*Hasil saat ini:* 15 error tipe data terdeteksi.

---

## 🎯 4. Kriteria Kelulusan
- [ ] Menambahkan deklarasi tipe data yang ketat (*type hints*) pada parameter dan return value fungsi helper.
- [ ] Menambahkan penanganan kasus null (*null safety*).
- [ ] Perintah `phpstan analyse --level=5` menghasilkan status: **[OK] No errors**.

---

## 📊 5. Skema Penilaian Parsial
* **Test Case 1 (Error Count Reduced):** Jumlah error berkurang minimal 50% ➔ **5 Pts**.
* **Test Case 2 (PHPStan Clean L5):** Bersih 0 error pada Level 5 ➔ **5 Pts** *(Total 10 Pts)*.

---

## 💡 6. Petunjuk Teknis
* Gunakan docblock PHPDoc `@param` dan `@return` jika tipe array bersifat generik.
