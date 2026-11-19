# 📋 Daftar 25 Tiket Tantangan E-Commerce Legacy (PHP)

| Kode | Kategori | Kesulitan | Judul Masalah | Poin Penuh | Poin Parsial | Cara Cek |
| :--- | :--- | :---: | :--- | :---: | :---: | :---: |
| **T01** | Bug Fix | 🟢 Mudah | Null Pointer Exception saat User Tanpa Alamat | 5 | 2.5 | otomatis |
| **T02** | Bug Fix | 🟢 Mudah | Division by Zero saat Diskon Voucher 100% | 5 | 2.5 | otomatis |
| **T03** | Bug Fix | 🟡 Sedang | State Machine Bypass Pembatalan Pesanan | 10 | 5 | otomatis |
| **T04** | Bug Fix | 🔴 Sulit | Race Condition Stok Minus (-28) Saat Flash Sale | 20 | 10 | campuran (k6) |
| **T05** | Database | 🟢 Mudah | Missing Index Foreign Key `user_id` & `order_id` | 5 | 2.5 | otomatis |
| **T06** | Database | 🟡 Sedang | Eliminasi N+1 Query pada Daftar Pesanan | 10 | 5 | otomatis |
| **T07** | Database | 🟡 Sedang | Optimasi Query Rekap Tanggal Menjadi Sargable | 10 | 5 | otomatis |
| **T08** | Database | 🔴 Sulit | Migrasi Skema Tabel Besar (Expand-Contract) | 20 | 10 | campuran |
| **T09** | Fitur | 🟢 Mudah | Backward Compatibility Field `tracking_code` | 5 | 2.5 | otomatis |
| **T10** | Fitur | 🟡 Sedang | Kalkulasi Pajak PPN 11% via Sprout Class | 10 | 5 | otomatis |
| **T11** | Fitur | 🟡 Sedang | Integrasi Payment Gateway Baru via Adapter | 10 | 5 | otomatis |
| **T12** | Fitur | 🔴 Sulit | Proteksi Double-Tap via X-Idempotency-Key & Redis | 20 | 10 | campuran |
| **T13** | Async | 🟢 Mudah | Ekstraksi Payload Email Menjadi DTO | 5 | 2.5 | otomatis |
| **T14** | Async | 🟡 Sedang | Pemisahan Kirim Email ke Redis Queue | 10 | 5 | otomatis |
| **T15** | Async | 🔴 Sulit | Offload Pembuatan PDF Invoice ke Background Worker | 20 | 10 | campuran |
| **T16** | Keamanan | 🟢 Mudah | Sanitasi Input XSS pada Catatan Pengiriman | 5 | 2.5 | otomatis |
| **T17** | Keamanan | 🟡 Sedang | Tutup SQL Injection pada Pencarian Produk | 10 | 5 | otomatis |
| **T18** | Keamanan | 🟡 Sedang | Proteksi Otorisasi IDOR pada Download Invoice | 10 | 5 | otomatis |
| **T19** | Keamanan | 🔴 Sulit | Migrasi Password MD5 ke Bcrypt via Rehash-on-Login | 20 | 10 | otomatis |
| **T20** | Testing | 🟢 Mudah | Snapshot Test Pertama pada Logika Keranjang | 5 | 2.5 | otomatis |
| **T21** | Testing | 🟡 Sedang | Mocking External HTTP API Kurir Logistik | 10 | 5 | otomatis |
| **T22** | Testing | 🟡 Sedang | Architecture Testing Melarang Direct DB Call | 10 | 5 | otomatis |
| **T23** | Hutang Kode | 🟢 Mudah | Hapus 5 Dead Routes Berdasarkan Access Log | 5 | 2.5 | otomatis |
| **T24** | Hutang Kode | 🟢 Mudah | Standardisasi Format Nomor Telepon E.164 | 5 | 2.5 | otomatis |
| **T25** | Hutang Kode | 🟡 Sedang | Perbaiki 15 Fatal Error PHPStan Level 5 | 10 | 5 | otomatis |

---

### 🌟 Tiket Bonus (Boss Level)
* **B01** (Resilience): Circuit Breaker Pattern pada Payment Gateway (+30 Poin)
* **B02** (Distributed): Transactional Outbox Pattern untuk Event Notifikasi (+35 Poin)
* **B03** (Automation): Automated Refactoring via Custom Rector AST Rule (+25 Poin)
* **B04** (Test Rigor): Mutation Testing Infection MSI $\ge$ 85% (+25 Poin)
* **B05** (Scaling): Database Read-Write Splitting (Primary vs Replica) (+30 Poin)
