# [T21] Mocking External HTTP API Kurir Logistik pada Test Suite

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 🟡 Sedang
* **Kategori:** 🧪 Testing & Reliability
* **Poin:** Penuh: 10 Pts | Parsial: 5 Pts
* **Estimasi Waktu:** 35 Menit

---

## 🚨 1. Keluhan Bisnis
Pipeline CI/CD sering gagal merah (*flaky test*) secara sporadis karena salah satu integration test memanggil server HTTP API kurir eksternal yang sering mengalami timeout atau limitasi rate.

---

## 🔍 2. Lokasi Kode
* `app/Services/Shipping/ShippingApiClientInterface.php`
* `kunci/21_T21_mock_api.php`

---

## 🧪 3. Cara Mereproduksi
```bash
./gate uji T21
```
*Hasil saat ini:* Test suite memanggil network riil ke luar server.

---

## 🎯 4. Kriteria Kelulusan
- [ ] Dibuat abstraksi interface untuk pemanggilan tarif kurir.
- [ ] Dibuat kelas `MockShippingApiClient` yang menyajikan respon statis terisolasi tanpa memicu request HTTP nyata ke luar server.
- [ ] Pipeline test berjalan 100% deterministik dan bebas dari ketergantungan koneksi internet eksternal.

---

## 📊 5. Skema Penilaian Parsial
* **Test Case 1 (Mock Isolation):** Tidak ada request network eksternal ➔ **5 Pts**.
* **Test Case 2 (Deterministic Output):** Tarif ongkir terhitung konsisten ➔ **5 Pts** *(Total 10 Pts)*.

---

## 💡 6. Petunjuk Teknis
* Terapkan pola *Test Double (Mock/Stub)*.
