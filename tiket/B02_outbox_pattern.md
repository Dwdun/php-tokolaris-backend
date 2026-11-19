# [B02] Penerapan Transactional Outbox Pattern untuk Event Notifikasi

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 💀 BOSS LEVEL
* **Kategori:** 🌐 Distributed Data Integrity
* **Poin Bonus:** +35 Pts
* **Prasyarat:** Total skor tiket wajib $\ge 100$ Pts
* **Estimasi Waktu:** 60–90 Menit

---

## 🚨 1. Keluhan Bisnis
Saat pesanan dibuat, backend langsung memanggil message broker untuk mengirim event notifikasi. Jika jaringan broker putus sesaat setelah commit MySQL, notifikasi hilang selamanya. Jika dibalik, notifikasi terkirim tapi DB rollback (*Dual-Write Hazard*).

---

## 🔍 2. Lokasi Kode
* Database MySQL: tabel `outbox_events`
* `app/Controllers/OrderController.php`

---

## 🧪 3. Cara Mereproduksi
```bash
./gate uji B02
```
*Hasil saat ini:* Terjadi desinkronisasi data antara status order dan event notifikasi saat simulasi kegagalan jaringan.

---

## 🎯 4. Kriteria Kelulusan
- [ ] Dibuat tabel `outbox_events (id, aggregate_type, payload, status, created_at)`.
- [ ] Penyimpanan order dan event outbox berada dalam **satu database transaction lokal yang sama**.
- [ ] Dibuat background script yang membaca event outbox berstatus `PENDING` dan mempublikasikannya ke broker dengan jaminan *at-least-once delivery*.

---

## 💡 Petunjuk Teknis
* 📖 Baca buku acuan: *Designing Data-Intensive Applications* karya Martin Kleppmann.
