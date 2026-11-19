# [T05] Missing Index Foreign Key `user_id` & `order_id`

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 🟢 Mudah
* **Kategori:** 🟡 Database Optimization
* **Poin:** Penuh: 5 Pts | Parsial: 2.5 Pts
* **Estimasi Waktu:** 20 Menit

---

## 🚨 1. Keluhan Bisnis
Database MySQL mengalami lonjakan CPU hingga 85% setiap kali query relasi pesanan dijalankan. Analisis lambat menunjukkan database melakukan *Full Table Scan* pada seluruh tabel `orders` dan `order_items`.

---

## 🔍 2. Lokasi Kode
* `db/02_index.sql`
* Database MySQL: tabel `orders` dan tabel `order_items`

---

## 🧪 3. Cara Mereproduksi
Jalankan query analisis `EXPLAIN` di MySQL:
```sql
EXPLAIN SELECT * FROM orders WHERE user_id = 1;
EXPLAIN SELECT * FROM order_items WHERE order_id = 1;
```
*Hasil saat ini:* Kolom `type` bernilai `ALL` (Full Table Scan) dan `key` bernilai `NULL`.

---

## 🎯 4. Kriteria Kelulusan
- [ ] Menambahkan index B-Tree pada kolom `orders(user_id)`.
- [ ] Menambahkan index B-Tree pada kolom `order_items(order_id)`.
- [ ] Output `EXPLAIN` berubah dari `type: ALL` menjadi `type: ref` dengan pemanfaatan `key`.

---

## 📊 5. Skema Penilaian Parsial
* **Test Case 1 (Orders Index):** Kolom `user_id` terindeks sempurna ➔ **2.5 Pts**.
* **Test Case 2 (Order Items Index):** Kolom `order_id` terindeks sempurna ➔ **2.5 Pts** *(Total 5 Pts)*.

---

## 💡 6. Petunjuk Teknis
* Tulis perintah DDL:
  ```sql
  CREATE INDEX idx_orders_user_id ON orders(user_id);
  CREATE INDEX idx_order_items_order_id ON order_items(order_id);
  ```
* 📖 Baca: `panduan/02_field_manual_materi_lengkap.md` (Modul 2: Indexing B-Tree).
