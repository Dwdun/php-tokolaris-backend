# [T19] Migrasi Transparan Password MD5 ke Bcrypt via Rehash-on-Login

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 🔴 Sulit
* **Kategori:** 🛡️ Keamanan & Kriptografi
* **Poin:** Penuh: 20 Pts | Parsial: 10 Pts
* **Estimasi Waktu:** 60 Menit

---

## 🚨 1. Keluhan Bisnis
Database menyimpan password pengguna dalam format MD5 usang (panjang 32 karakter) tanpa salt. Jika database bocor, password mudah dipecahkan menggunakan Rainbow Table. Manajemen menolak reset password massal karena takut mengganggu pengguna.

---

## 🔍 2. Lokasi Kode
* `app/Controllers/AuthController.php` (method `login()`, baris 21)
* Database MySQL: tabel `users` (kolom `password`)

---

## 🧪 3. Cara Mereproduksi
```bash
./gate uji T19
```
*Hasil saat ini:* Password di database tetap berformat MD5 setelah pengguna berhasil login.

---

## 🎯 4. Kriteria Kelulusan
- [ ] Pengguna dengan hash MD5 lama dapat login dengan password yang benar.
- [ ] Begitu login sukses, hash password di database MySQL otomatis diperbarui menjadi hash `bcrypt` modern (panjang 60 karakter, diawali `$2y$`).
- [ ] Login berikutnya otomatis menggunakan verifikasi `password_verify()` bawaan PHP.
- [ ] Password yang salah tetap ditolak (HTTP 401).

---

## 📊 5. Skema Penilaian Parsial
* **Test Case 1 (MD5 Verification & Upgrade):** Hash otomatis ter-upgrade ke bcrypt saat login ➔ **10 Pts**.
* **Test Case 2 (Subsequent Bcrypt Auth):** Login kedua kali sukses menggunakan hash baru ➔ **10 Pts** *(Total 20 Pts)*.

---

## 💡 6. Petunjuk Teknis
* 📖 Baca: `panduan/02_field_manual_materi_lengkap.md` (Modul 5.3: Pola Rehash-on-Login).
* Gunakan fungsi `password_hash($plainPassword, PASSWORD_BCRYPT)`.
