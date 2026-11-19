# [T09] Penambahan Field `tracking_code` dengan Backward Compatibility

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 🟢 Mudah
* **Kategori:** 🔵 Fitur & Kontrak API
* **Poin:** Penuh: 5 Pts | Parsial: 2.5 Pts
* **Estimasi Waktu:** 20 Menit

---

## 🚨 1. Keluhan Bisnis
Aplikasi mobile Android versi lama mengalami crash massal setelah tim backend mengubah format payload respon order dari string sederhana menjadi object bersarang (*nested object*).

---

## 🔍 2. Lokasi Kode
* `app/Controllers/OrderController.php` (fungsi formatting response)

---

## 🧪 3. Cara Mereproduksi
```bash
./gate uji T09
```
*Hasil saat ini:* `Failed asserting that legacy address string field exists alongside new nested object`.

---

## 🎯 4. Kriteria Kelulusan
- [ ] Field lama `address: "string"` tetap dipertahankan dengan tipe data string yang sama persis.
- [ ] Field baru `address_detail: { ... }` ditambahkan sebagai opsi ekspansi untuk klien modern.
- [ ] Field baru `tracking_code` dapat dibaca tanpa mematahkan JSON parsing klien lawas.

---

## 📊 5. Skema Penilaian Parsial
* **Test Case 1 (Legacy Client Compatibility):** Field string lama tetap ada ➔ **2.5 Pts**.
* **Test Case 2 (Modern Client Payload):** Field nested baru tersedia ➔ **2.5 Pts** *(Total 5 Pts)*.

---

## 💡 6. Petunjuk Teknis
* Jangan pernah menghapus atau mengubah tipe data field yang sudah dipublikasikan ke klien publik.
* Terapkan strategi *Tolerant Reader & Additive Changes*.
