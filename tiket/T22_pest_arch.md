# [T22] Architecture Testing (Pest Arch) Melarang Direct DB Call di Controller

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 🟡 Sedang
* **Kategori:** 🧪 Architecture Testing
* **Poin:** Penuh: 10 Pts | Parsial: 5 Pts
* **Estimasi Waktu:** 30 Menit

---

## 🚨 1. Keluhan Bisnis
Developer baru sering kali tergoda menulis query SQL langsung di dalam lapisan Controller, menyebabkan controller kembali gemuk dan aturan bisnis bocor ke lapisan presentasi.

---

## 🔍 2. Lokasi Kode
* `uji/tests/ArchitectureTest.php`
* Namespace: `App\Controllers`

---

## 🧪 3. Cara Mereproduksi
```bash
./gate uji T22
```
*Hasil saat ini:* Belum ada aturan pengujian arsitektur yang aktif.

---

## 🎯 4. Kriteria Kelulusan
- [ ] Menulis test Pest Arch yang memverifikasi bahwa namespace `App\Controllers` dilarang memanggil `PDO` atau `App\Support\Database` secara langsung.
- [ ] Menulis test Pest Arch yang memverifikasi bahwa namespace `App\Services` tidak boleh membaca variabel global HTTP (`$_GET`, `$_POST`, `$_SERVER`).
- [ ] Test otomatis mendeteksi jika ada pelanggaran arsitektur baru.

---

## 📊 5. Skema Penilaian Parsial
* **Test Case 1 (Controller Decoupling Rule):** Aturan controller terpasang ➔ **5 Pts**.
* **Test Case 2 (Service Isolation Rule):** Aturan service terpasang ➔ **5 Pts** *(Total 10 Pts)*.

---

## 💡 6. Petunjuk Teknis
* Gunakan method Pest Arch: `expect('App\Controllers')->not->toUse('PDO')`.
