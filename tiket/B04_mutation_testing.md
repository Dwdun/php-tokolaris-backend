# [B04] Penaklukan Mutation Testing (Infection MSI Score $\ge$ 85%)

## 🏷️ Metadata Tiket
* **Tingkat Kesulitan:** 💀 BOSS LEVEL
* **Kategori:** 🧪 Test Rigor & Quality
* **Poin Bonus:** +25 Pts
* **Prasyarat:** Total skor tiket wajib $\ge 100$ Pts
* **Estimasi Waktu:** 45–60 Menit

---

## 🚨 1. Keluhan Bisnis
Tim QA melaporkan bahwa meskipun test coverage sudah tinggi (85%), masih banyak bug lolos ke tahap produksi karena test assertions yang ditulis developer terlalu lemah dan tidak menguji kondisi batas (*boundary conditions*).

---

## 🔍 2. Lokasi Kode
* Konfigurasi: `infection.json`
* Test suites: `uji/tests/`

---

## 🧪 3. Cara Mereproduksi
```bash
vendor/bin/infection --min-msi=85
```
*Hasil saat ini:* Mutation Score Indicator (MSI) hanya bernilai 42% (Banyak mutant lolos/escaped!).

---

## 🎯 4. Kriteria Kelulusan
- [ ] Menambahkan test assertions yang tajam pada skenario edge cases kalkulasi diskon, validasi stok, dan otorisasi invoice.
- [ ] Membunuh minimal 85% mutant yang disuntikkan secara otomatis oleh engine Mutation Testing.
- [ ] Skor akhir Infection MSI mencapai minimal **$\ge 85\%$**.

---

## 💡 Petunjuk Teknis
* 📖 Baca buku acuan: *Unit Testing: Principles, Practices, and Patterns* karya Vladimir Khorikov.
