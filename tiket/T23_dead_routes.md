# [T23] Hapus 5 Dead Routes / Zombie Endpoints Berdasarkan Log

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 🟢 Mudah
* **Kategori:** 🧹 Tech Debt & Hygiene
* **Poin:** Penuh: 5 Pts | Parsial: 2.5 Pts
* **Estimasi Waktu:** 20 Menit

---

## 🚨 1. Keluhan Bisnis
File routing memuat ratusan baris route sisa promosi musiman 3 tahun lalu yang sudah tidak pernah diakses user. Hal ini membebani memori dan membingungkan developer saat melakukan audit API.

---

## 🔍 2. Lokasi Kode
* `app/public/index.php`

---

## 🧪 3. Cara Mereproduksi
```bash
./gate uji T23
```
*Hasil saat ini:* Rute zombie masih terdaftar di router publik.

---

## 🎯 4. Kriteria Kelulusan
- [ ] Mengidentifikasi dan menghapus 5 rute mati yang memiliki traffic 0 di log (misal: `/promo/harbolnas-2021`, `/beta-test`, dll).
- [ ] Rute-rute inti transaksi (`/orders`, `/checkout`, `/search`) tetap berjalan normal tanpa kendala.
- [ ] Percobaan akses ke rute yang dihapus mengembalikan status `HTTP 404 Not Found`.

---

## 📊 5. Skema Penilaian Parsial
* **Test Case 1 (Zombie Route Returns 404):** Rute mati bersih ➔ **2.5 Pts**.
* **Test Case 2 (Core Routes Remain Intact):** Rute utama tetap hidup ➔ **2.5 Pts** *(Total 5 Pts)*.

---

## 💡 6. Petunjuk Teknis
* Hapus blok `if ($uri === '...')` yang bersangkutan di `public/index.php`.
