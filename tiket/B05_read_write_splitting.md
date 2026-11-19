# [B05] Database Read-Write Splitting (Routing Primary vs Replica)

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 💀 BOSS LEVEL
* **Kategori:** ⚖️ Database Scaling & High Availability
* **Poin Bonus:** +30 Pts
* **Prasyarat:** Total skor tiket wajib $\ge 100$ Pts
* **Estimasi Waktu:** 60 Menit

---

## 🚨 1. Keluhan Bisnis
CPU database server utama melonjak 95% di jam sibuk karena jutaan query pembacaan katalog produk (`SELECT`) membebani mesin yang sama dengan transaksi penulisan order (`INSERT`/`UPDATE`).

---

## 🔍 2. Lokasi Kode
* `app/Support/Database.php`

---

## 🧪 3. Cara Mereproduksi
```bash
./gate uji B05
```
*Hasil saat ini:* Seluruh query `SELECT` dan `INSERT` dieksekusi ke koneksi host database yang sama.

---

## 🎯 4. Kriteria Kelulusan
- [ ] Mengonfigurasi dual-connection pool di kelas `Database`: koneksi `read` (Replica) dan koneksi `write` (Primary).
- [ ] Seluruh query `SELECT` otomatis diarahkan ke koneksi Read-Replica.
- [ ] Seluruh query mutasi (`INSERT`, `UPDATE`, `DELETE`) dan transaksi ACID tetap mengalir ke Primary DB.
- [ ] Mendukung opsi *Sticky Connection* (pembacaan sesaat setelah penulisan tetap membaca dari Primary untuk menghindari replika lag).

---

## 💡 Petunjuk Teknis
* 📖 Baca buku acuan: *Database Reliability Engineering* karya Charity Majors & Laine Campbell.
