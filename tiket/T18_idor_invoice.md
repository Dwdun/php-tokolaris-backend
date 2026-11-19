# [T18] Proteksi Otorisasi IDOR pada Download Invoice

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 🟡 Sedang
* **Kategori:** 🛡️ Keamanan Web (OWASP)
* **Poin:** Penuh: 10 Pts | Parsial: 5 Pts
* **Estimasi Waktu:** 30 Menit

---

## 🚨 1. Keluhan Bisnis
Ditemukan kebocoran privasi fatal: Pengguna A dapat mengunduh invoice pembelian milik Pengguna B hanya dengan mengganti nomor ID invoice pada parameter URL `/api/v1/invoices/1002`.

---

## 🔍 2. Lokasi Kode
* `app/Controllers/InvoiceController.php` (method `download()`, baris 16)

---

## 🧪 3. Cara Mereproduksi
```bash
./gate uji T18
```
*Hasil saat ini:* User ID 1 sukses mengunduh invoice ID 1002 milik User ID 2 dengan status `HTTP 200 OK`.

---

## 🎯 4. Kriteria Kelulusan
- [ ] Sistem memvalidasi kepemilikan invoice dengan user yang sedang login (`invoice.user_id === current_user.id`).
- [ ] Jika invoice milik pengguna lain, sistem mengembalikan status `HTTP 403 Forbidden` atau `HTTP 404 Not Found`.
- [ ] Pengguna tetap dapat mengunduh invoice miliknya sendiri secara normal.

---

## 📊 5. Skema Penilaian Parsial
* **Test Case 1 (Cross-User Block):** Akses lintas akun ditolak ➔ **5 Pts**.
* **Test Case 2 (Owner Access Permitted):** Akses pemilik sah disetujui ➔ **5 Pts** *(Total 10 Pts)*.

---

## 💡 6. Petunjuk Teknis
* 📖 Baca: `panduan/02_field_manual_materi_lengkap.md` (Modul 5.2: Pencegahan IDOR).
* Tambahkan klausa `AND user_id = ?` pada query database.
