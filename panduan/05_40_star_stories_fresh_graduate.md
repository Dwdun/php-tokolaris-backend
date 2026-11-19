# 🌟 40 STAR Stories: Fresh Graduate / Junior Backend Engineer (Legacy Systems)
> Panduan jawaban wawancara berbasis metode **STAR (Situation, Task, Action, Result)** yang dirancang realistis, kredibel, dan berdaya pikat tinggi bagi *Fresh Graduate* atau *Junior Developer*.

---

## 💡 Mengapa Formula Ini Efektif untuk Fresh Graduate?
Banyak fresh graduate gagal dalam wawancara karena:
1. **Terlalu muluk-muluk (tidak kredibel):** Mengaku "mendesain microservices berskala jutaan user" sendirian. Pewawancara senior akan langsung tahu itu dibuat-buat.
2. **Terlalu pasif:** "Saya cuma disuruh senior mengerjakan tiket kecil."

**Kunci Jawaban STAR Level Fresh Graduate Unggulan:**
* Menunjukkan **metode berpikir ilmiah** (tidak asal tebak).
* Menunjukkan **inisiatif dan kehati-hatian** (paham risiko sistem legacy).
* Menunjukkan **hasil nyata terukur** (kecepatan, stabilitas, dokumentasi, nol regresi).

---

## 📑 Daftar Isi Kategori (A — J)

- [Kategori A — Bug Fix di Sistem Rapuh (#1 - #4)](#kategori-a--bug-fix-di-sistem-rapuh)
- [Kategori B — Memahami & Mendokumentasikan Sistem (#5 - #8)](#kategori-b--memahami--mendokumentasikan-sistem)
- [Kategori C — Menambah Fitur Tanpa Merusak (#9 - #12)](#kategori-c--menambah-fitur-tanpa-merusak)
- [Kategori D — Database & Migrasi Data (#13 - #16)](#kategori-d--database--migrasi-data)
- [Kategori E — Testing di Sistem Tanpa Test (#17 - #20)](#kategori-e--testing-di-sistem-tanpa-test)
- [Kategori F — Upgrade Framework, Bahasa & Dependency (#21 - #24)](#kategori-f--upgrade-framework-bahasa--dependency)
- [Kategori G — Modernisasi Bertahap & Arsitektur (#25 - #28)](#kategori-g--modernisasi-bertahap--arsitektur)
- [Kategori H — Keamanan di Sistem Lama (#29 - #32)](#kategori-h--keamanan-di-sistem-lama)
- [Kategori I — Incident & Production (#33 - #36)](#kategori-i--incident--production)
- [Kategori J — Kolaborasi, Komunikasi & Kepemimpinan (#37 - #40)](#kategori-j--kolaborasi-komunikasi--kepemimpinan)

---

### Kategori A — Bug Fix di Sistem Rapuh

#### STAR #1: Mengatasi Bug Race Condition pada Pengurangan Kuota Kupon
* **Situation:** Pada event promo bulanan, sistem sering mengalami over-claim kupon diskon (kupon terbatas 100 digunakan oleh 108 pengguna). Kode legacy menggunakan `SELECT quota` lalu `UPDATE quota = quota - 1` di level aplikasi tanpa locking.
* **Task:** Mengidentifikasi penyebab selisih data dan memperbaikinya tanpa mengorbankan performa transaksi saat traffic tinggi.
* **Action:**
  1. Melakukan load test lokal dengan concurrency 50 request/detik untuk mereproduksi race condition secara deterministik.
  2. Menganalisis bahwa masalah terletak pada ketiadaan atomisitas transaksi di database.
  3. Mengubah update query menjadi atomic increment: `UPDATE coupons SET quota = quota - 1 WHERE id = ? AND quota > 0`.
  4. Menambahkan pengecekan baris yang terpengaruh (*affected rows*) di backend untuk memvalidasi keberhasilan klaim.
* **Result:** Bug over-claim teratasi 100% pada flash sale berikutnya dengan 0 selisih kupon dan tanpa lonjakan latensi API.

#### STAR #2: Melacak Memory Leak pada Background Worker Pemroses Email
* **Situation:** Worker asinkron pemroses antrean email sering mati mendadak (*OutOfMemory / exit code 137*) setiap 4–5 jam, memaksa tim ops me-restart service secara manual.
* **Task:** Mencari akar penyebab kebocoran memori dan membuat worker stabil berjalan 24/7.
* **Action:**
  1. Memasang memory profiler sederhana untuk mencatat heap memory setiap interval pemrosesan 500 job.
  2. Menemukan bahwa ORM menyimpan histori query di dalam memori internal (*in-memory query log*) dan koneksi attachment file tidak di-close setelah dikirim.
  3. Menonaktifkan query logger pada background process dan menambahkan blok `try-finally` untuk memastikan stream buffer file selalu dibebaskan (*released*).
* **Result:** Penggunaan memori stabil di angka 120MB konstan selama 7 hari berturut-turut tanpa restart manual lagi.

#### STAR #3: Mengatasi Bug Sporadis "Ghost Timeout" pada Webhook Pihak Ketiga
* **Situation:** Pelanggan mengeluhkan pembayaran sukses tetapi status pesanan kadang tidak berubah (*pending*), terjadi sekitar 3% dari total transaksi secara acak.
* **Task:** Menemukan kenapa notifikasi webhook payment gateway gagal diproses pada kondisi tertentu.
* **Action:**
  1. Memfilter access log dan structured log mencari transaksi dengan status gagal.
  2. Menemukan bahwa webhook controller memanggil API eksternal pihak ketiga (verifikasi logistik) secara synchronous dengan timeout default 60 detik tanpa penanganan error.
  3. Ketika API logistik lambat, koneksi payment gateway terkena HTTP timeout 10 detik dan memicu pembatalan.
  4. Mengisolasi pemanggilan logistik ke dalam asynchronous queue terpisah dan segera mengembalikan respons `HTTP 200 OK` ke payment gateway begitu signature tervalidasi.
* **Result:** Tingkat kegagalan pembaruan status pembayaran turun dari 3% menjadi 0.01%, serta waktu respons webhook berkurang dari 3.2 detik ke 85 milidetik.

#### STAR #4: Menemukan Sumber Inkonsistensi Data Saldo Pengguna
* **Situation:** Audit keuangan menemukan anomali saldo pengguna yang bertambah ganda saat top-up gagal jaringan. Kode lama sudah berusia 6 tahun dengan ribuan baris tanpa dokumentasi.
* **Task:** Menginvestigasi aliran mutasi data dan menutup celah manipulasi state saldo.
* **Action:**
  1. Menelusuri seluruh codebase untuk mencari query yang memodifikasi tabel `user_balances`.
  2. Menggunakan Git Blame untuk membaca histori commit saat fitur tersebut dibuat.
  3. Menemukan bahwa retry mechanism di sisi mobile client memanggil endpoint top-up berulang kali tanpa verifikasi transaksi sebelumnya.
  4. Mengimplementasikan pengecekan `idempotency_key` dan membungkus mutasi saldo di dalam database transaction dengan isolation level *Repeatable Read*.
* **Result:** Anomali saldo tereliminasi sepenuhnya; finance audit berikutnya lolos dengan akurasi rekonsiliasi 100%.

---

### Kategori B — Memahami & Mendokumentasikan Sistem

#### STAR #5: Reverse-Engineering Endpoint Legacy Menjadi Diagram Alir
* **Situation:** Tim ingin memperbarui modul Checkout, namun developer asli sudah resign dan tidak ada dokumen arsitektur sama sekali. Kode berupa controller 2.500 baris dengan puluhan kondisi nested `if-else`.
* **Task:** Memetakan seluruh alur logika, validasi, dan dependensi eksternal modul Checkout agar tim paham batasan fitur lama.
* **Action:**
  1. Membaca kode dari route entry point, mencatat setiap percabangan state dan side-effects (DB write, email, API call).
  2. Memeriksa access log produksi untuk melihat format payload nyata yang dikirimkan klien.
  3. Menggambar diagram sequence dan state machine interaktif menggunakan Mermaid.js.
  4. Menyelenggarakan sesi walk-through 30 menit kepada tim dan Product Manager untuk memvalidasi alur bisnis.
* **Result:** Dokumentasi disetujui tim dan dijadikan referensi resmi proyek, mempercepat estimasi pengerjaan tim dari 6 minggu menjadi 3 minggu.

#### STAR #6: Melakukan Git Forensics untuk Menjawab Kode "Aneh"
* **Situation:** Ditemukan baris kode di logic diskon: `if (cart.id == 9921) return 0;`. Banyak developer baru mengira ini kode kotor sisa uji coba dan berniat menghapusnya.
* **Task:** Memastikan apakah baris kode tersebut aman dihapus atau memiliki tujuan bisnis tersembunyi.
* **Action:**
  1. Menjalankan `git log -S "9921" --stat` dan `git blame` untuk menemukan commit awal serta commit message-nya.
  2. Menemukan referensi tiket Jira lama dari 4 tahun lalu: cart tersebut adalah akun dummy monitoring milik tim infrastructure untuk health-check otomatis.
  3. Berdiskusi dengan tim Infra untuk memodifikasi mekanisme health check agar menggunakan header khusus (`X-Health-Check`), bukan hardcode ID.
  4. Membersihkan hardcoded ID tersebut dengan aman setelah pengujian bersama.
* **Result:** Menyelamatkan sistem monitoring infra dari kegagalan palsu (*false alert*) dan membersihkan technical debt secara bertanggung jawab.

#### STAR #7: Membuat Kamus Data dan Relasi Tabel yang Tidak Memiliki Foreign Key
* **Situation:** Database aplikasi memiliki 120 tabel, namun tidak satu pun memiliki relasi *Foreign Key* fisik di level MySQL karena alasan performa historis. Hal ini membuat developer baru kesulitan memahami hubungan antar tabel.
* **Task:** Memetakan relasi implisit dan mendokumentasikan skema database inti.
* **Action:**
  1. Menulis script parser sederhana untuk mencari pola penamaan kolom seperti `*_id` di seluruh skema.
  2. Melakukan cross-check dengan kode ORM/model untuk memvalidasi fungsi `belongsTo` atau `hasMany`.
  3. Menghasilkan diagram Entity-Relationship (ERD) visual dan kamus data (*data dictionary*) format Markdown di repositori tim.
* **Result:** Waktu adaptasi (*onboarding*) developer baru dalam memahami database terpangkas dari rata-rata 2 minggu menjadi hanya 3 hari.

#### STAR #8: Mengidentifikasi dan Memusnahkan Endpoint Zombie (Dead Code)
* **Situation:** Repositori monolit memiliki lebih dari 350 route/endpoint API. Tim curiga banyak endpoint yang sudah tidak terpakai namun tetap membebani proses maintenance.
* **Task:** Memverifikasi rute mana saja yang benar-benar aktif vs rute mati (*dead code*) tanpa mematikan fitur user.
* **Action:**
  1. Mengekstrak access log Nginx selama 90 hari terakhir dan menulis script Python untuk mencocokkan URL hit dengan daftar rute di kode.
  2. Menemukan 42 endpoint yang memiliki traffic 0 selama 3 bulan.
  3. Mengonfirmasi ke tim produk apakah rute tersebut milik kampanye musiman atau fitur deprecated.
  4. Menandai rute tersebut dengan logging warning terlebih dahulu selama 2 minggu, sebelum akhirnya membuat Pull Request untuk menghapus 3.000+ baris kode mati.
* **Result:** Mengurangi kompleksitas repositori sebesar 12% dan menghilangkan beban mental tim saat melakukan review kode.

---

### Kategori C — Menambah Fitur Tanpa Merusak

#### STAR #9: Integrasi Payment Gateway Baru dengan Adapter Pattern
* **Situation:** Aplikasi hanya mendukung satu payment gateway (Midtrans) yang kodenya tertanam langsung (*tightly coupled*) di dalam ratusan baris controller. Bisnis meminta integrasi gateway kedua (Xendit) dalam 2 minggu.
* **Task:** Menambahkan provider baru tanpa mengacak-acak logika checkout yang sudah berjalan stabil.
* **Action:**
  1. Mengekstrak interface umum `PaymentGatewayInterface` dengan method standar: `charge()`, `verify()`, dan `handleNotification()`.
  2. Membuat `MidtransAdapter` membungkus kode lama tanpa mengubah perilakunya (refactor murni).
  3. Mengimplementasikan `XenditAdapter` baru yang mematuhi kontrak interface yang sama.
  4. Menggunakan factory pattern sederhana untuk menentukan adapter berdasarkan pilihan user di payload.
* **Result:** Gateway baru sukses rilis tepat waktu tanpa memunculkan 1 pun bug regresi pada pengguna gateway lama.

#### STAR #10: Menambah Validasi Pajak Baru dengan Teknik Sprout Class
* **Situation:** Aturan pemerintah mewajibkan perhitungan pajak PPN baru 11% dengan aturan pembulatan khusus pada transaksi invoice. File invoice generator lama berukuran 1.800 baris dan tidak memiliki unit test.
* **Task:** Menerapkan aturan pajak baru tanpa menyentuh ratusan baris kalkulasi finansial lama.
* **Action:**
  1. Menerapkan teknik *Sprout Class* dari buku Michael Feathers: membuat kelas terisolasi baru `TaxCalculator2024`.
  2. Menulis unit test komprehensif 100% coverage untuk seluruh skenario pembulatan di kelas baru tersebut.
  3. Memanggil kelas baru tersebut hanya pada satu titik sambung (*seam*) di invoice generator dengan conditional date check.
* **Result:** Fitur lolos audit kepatuhan regulasi tepat waktu dan kode lama tetap utuh tanpa risiko efek samping.

#### STAR #11: Rilis Fitur Algoritma Rekomendasi dengan Feature Flag
* **Situation:** Tim data membuat algoritma scoring pencarian baru, namun tim produk khawatir algoritma ini menurunkan angka konversi jika langsung dirilis 100%.
* **Task:** Mengimplementasikan rilis bertahap (*canary release*) di level aplikasi backend yang aman.
* **Action:**
  1. Mengintegrasikan mekanisme Feature Flag sederhana berbasis konfigurasi Redis dan parameter hashing ID pengguna.
  2. Memisahkan eksekusi: 90% pengguna tetap memakai query pencarian lama, 10% dialihkan ke algoritma baru.
  3. Menambahkan metrik durasi query dan klik pengguna di logging.
* **Result:** Ketika terjadi lonjakan latensi di hari ke-2 pada algoritma baru, kami mematikan toggle dalam 5 detik via Redis tanpa perlu redeploy/rollback kode.

#### STAR #12: Menambah Field Baru pada Response API Tanpa Merusak Aplikasi Mobile Lama
* **Situation:** Fitur profil pengguna memerlukan struktur data alamat baru yang bersarang (*nested object*), namun 40% pengguna aplikasi mobile Android masih memakai build versi lama yang akan crash jika tipe data field berubah (*type mismatch*).
* **Task:** Menyediakan data baru untuk web frontend tanpa mematahkan kontrak respon aplikasi mobile lawas.
* **Action:**
  1. Menganalisis *backward-compatibility* payload JSON.
  2. Membiarkan field lama `address: "string"` tetap ada dan terisi data lama.
  3. Menambahkan field baru terpisah `address_detail: { ... }` sebagai ekspansi payload (*expand phase*).
  4. Menguji respon menggunakan automated schema validator untuk memastikan payload diterima baik oleh kedua versi klien.
* **Result:** Fitur web sukses meluncur dan tidak ada laporan crash dari pengguna mobile app versi lama.

---

### Kategori D — Database & Migrasi Data

#### STAR #13: Mengeliminasi N+1 Query pada Endpoint Daftar Transaksi
* **Situation:** Halaman riwayat transaksi pengguna memakan waktu loading 4.5 detik saat memuat 20 transaksi. Server database mengalami spike query hingga 200 query per request.
* **Task:** Mengoptimalkan query database agar endpoint dapat merespons di bawah 500 milidetik.
* **Action:**
  1. Memeriksa query log melalui ORM debug tool dan menemukan bahwa aplikasi melakukan query terpisah untuk mengambil detail toko dan barang di dalam looping `foreach`.
  2. Mengubah pemanggilan lazy loading menjadi eager loading (`with(['store', 'items'])`) dan menyederhanakan data yang di-select.
  3. Menambahkan composite index pada kolom `(user_id, created_at)` pada tabel transaksi.
* **Result:** Jumlah query berkurang dari 201 query menjadi hanya 3 query; waktu respons endpoint turun drastis dari 4.5 detik ke 180 milidetik (peningkatan kecepatan 96%).

#### STAR #14: Optimasi Query Laporan Bulanan Menggunakan EXPLAIN
* **Situation:** Query rekapitulasi data penjualan bulanan untuk dashboard merchant sering mengalami *lock timeout* dan membebani server saat jam kerja.
* **Task:** Mendiagnosis eksekusi query dan mempercepat waktu eksekusi tanpa mengubah hasil agregasi.
* **Action:**
  1. Menjalankan perintah `EXPLAIN ANALYZE` pada query bersangkutan di database staging yang memiliki volume data mirip produksi.
  2. Menemukan bahwa MySQL melakukan *Full Table Scan* pada tabel berukuran 2 juta baris karena penggunaan fungsi tanggal: `WHERE YEAR(created_at) = 2024`.
  3. Menulis ulang query menjadi rentang tanggal langsung: `WHERE created_at >= '2024-01-01' AND created_at < '2025-01-01'` agar index dapat dimanfaatkan (*sargable query*).
* **Result:** Query plan beralih dari *ALL (Full Scan)* ke *Range Index Scan*; durasi query berkurang dari 18 detik menjadi 42 milidetik.

#### STAR #15: Menambahkan Kolom Baru pada Tabel Berisi 5 Juta Baris Tanpa Downtime
* **Situation:** Dibutuhkan kolom `uuid` baru pada tabel `orders` yang memiliki 5 juta baris dan traffic transaksi konstan. Menjalankan `ALTER TABLE` langsung berisiko me-lock tabel selama bermenit-menit dan menghentikan operasional.
* **Task:** Menjalankan migrasi skema dan pengisian data tanpa membuat transaksi pelanggan macet.
* **Action:**
  1. Merancang strategi migrasi bertahap (*Expand-Contract Pattern*).
  2. Menambahkan kolom baru dengan konfigurasi `NULLable` terlebih dahulu tanpa default value yang berat.
  3. Mengupdate kode aplikasi untuk mulai menulis ke kolom lama dan kolom baru secara bersamaan (*dual-write*).
  4. Menjalankan background script chunking (500 baris per batch dengan jeda 200ms) untuk melakukan *backfill* data lama di jam sepi (pukul 02.00 dini hari).
* **Result:** Tabel 5 juta baris berhasil diperbarui 100% tanpa ada downtime, error lock, atau komplain dari pengguna.

#### STAR #16: Membersihkan dan Menstandarkan Data Format Nomor Telepon yang Anomali
* **Situation:** Tabel pengguna menyimpan nomor telepon dalam berbagai format historis yang tidak konsisten (`0812...`, `+62812...`, `62-812...`, spasi, strip). Hal ini menggagalkan integrasi gateway SMS OTP baru.
* **Task:** Melakukan audit, cleansing, dan standardisasi format E.164 pada 300.000 data pengguna.
* **Action:**
  1. Menulis query agregasi regex untuk memetakan distribusi pola anomali data di database.
  2. Membuat script migrasi yang bersifat idempoten dan menjalankan dry-run pada database tiruan staging.
  3. Mengidentifikasi 45 data yang benar-benar rusak (misal: "tidak ada") dan mengarantinanya ke tabel terpisah untuk ditindaklanjuti tim Customer Support.
  4. Menjalankan script update secara bertahap dan memasang validasi ketat (*regex constraint*) di level API untuk data baru.
* **Result:** 99.98% data telepon terstandarisasi dengan sukses, dan tingkat keberhasilan pengiriman SMS OTP meningkat menjadi 99.4%.

---

### Kategori E — Testing di Sistem Tanpa Test

#### STAR #17: Membuat Characterization Test Pertama pada Modul Keranjang Belanja
* **Situation:** Tim berencana merombak logika diskon di modul keranjang belanja (`CartService`), namun tidak ada automated test sama sekali. Developer takut melakukan refactor karena takut merusak aturan diskon lama.
* **Task:** Membangun jaring pengaman (*safety net*) sebelum refactor dimulai.
* **Action:**
  1. Menerapkan konsep *Characterization Testing*: menulis test bukan untuk menguji apakah kodenya benar, tetapi untuk mengunci output yang dihasilkan saat ini.
  2. Memberikan berbagai kombinasi input (keranjang kosong, 1 barang, barang promo, voucher expired, campuran barang fisik & digital).
  3. Merekam output kalkulasi saat ini sebagai snapshot acuan (*ground truth*).
  4. Menjalankan test suite ini di pipeline setiap kali ada baris kode yang diubah.
* **Result:** Saat proses refactor dilakukan, test berhasil menangkap 2 potensi regresi tersembunyi sebelum sempat masuk ke tahap staging.

#### STAR #18: Membangun Setup Integration Test Otomatis Menggunakan Docker Testcontainers
* **Situation:** Pengujian integrasi selama ini dilakukan manual oleh developer di laptop masing-masing dengan database lokal yang sering kali berbeda versinya dengan server produksi.
* **Task:** Membuat lingkungan testing otomatis yang bersih, deterministik, dan mudah dijalankan dengan satu perintah.
* **Action:**
  1. Mengonfigurasi container database sementara (*ephemeral database*) menggunakan Docker / Testcontainers di dalam suite test.
  2. Menerapkan mekanisme database transaction rollback: setiap test case dijalankan di dalam transaksi DB yang langsung di-rollback setelah assert selesai.
  3. Mengintegrasikan perintah `npm test` atau `phpunit` ke dalam pre-commit hook dan GitHub Actions.
* **Result:** Waktu eksekusi pengujian berkurang dari 1 jam uji manual menjadi hanya 45 detik otomatis, dan seluruh anggota tim dapat menjalankan test yang identik di laptop masing-masing.

#### STAR #19: Mengisolasi Ketergantungan Eksternal dengan Mocking dan Fixtures
* **Situation:** Test suite sering gagal (*flaky test*) di pipeline CI/CD karena salah satu test case memanggil API pihak ketiga (layanan kurir logistik) yang sering mengalami timeout atau limitasi rate.
* **Task:** Menghilangkan ketergantungan jaringan eksternal pada test runner agar test suite 100% deterministik.
* **Action:**
  1. Mengaudit test yang memicu HTTP call nyata ke luar server.
  2. Mengimplementasikan test double (*Mock/Stub*) menggunakan HTTP mocking library untuk memalsukan respon API kurir.
  3. Membuat file fixture JSON berisi contoh payload sukses, respon gagal, dan payload timeout.
* **Result:** Durasi pipeline CI berkurang 40% dan tingkat keberhasilan test build menjadi 100% hijau stabil tanpa kegagalan palsu.

#### STAR #20: Menargetkan Test Coverage pada Jalur Finansial Kritis
* **Situation:** Manajemen menargetkan test coverage 80% untuk seluruh aplikasi monolit tua. Target ini tidak realistis dan membuat developer tertekan sehingga hanya membuat test pura-pura pada fungsi helper sepele.
* **Task:** Mengarahkan strategi testing agar fokus pada nilai bisnis dan mitigasi risiko tertinggi.
* **Action:**
  1. Berdiskusi secara pragmatis dengan tech lead untuk mengubah fokus dari "angka kuantitas coverage" ke "kualitas area berisiko".
  2. Mengidentifikasi alur transaksi paling bernilai tinggi: mutasi saldo, kalkulasi refund, dan pemotongan stok.
  3. Mengalokasikan waktu sprint untuk menulis unit & integration test mendalam khusus pada ketiga modul tersebut hingga mencapai 95% branch coverage.
* **Result:** Terhindar dari insiden finansial selama 6 bulan berikutnya dan tim memiliki rasa percaya diri tinggi saat mendeploy perubahan pada modul pembayaran.

---

### Kategori F — Upgrade Framework, Bahasa & Dependency

#### STAR #21: Menambal Kerentanan Keamanan Kritis (CVE) pada Library Usang
* **Situation:** Laporan audit keamanan menemukan dependensi library XML parsing yang memiliki kerentanan Remote Code Execution (RCE) dengan skor keparahan kritis (CVSS 9.8). Library tersebut sudah tidak diupdate selama 4 tahun.
* **Task:** Mengganti library rentan tersebut ke versi aman tanpa merusak integrasi data mitra B2B yang mengirim payload XML.
* **Action:**
  1. Membaca CVE advisory teknis untuk memahami vektor serangan dari kerentanan tersebut.
  2. Mengidentifikasi seluruh file di proyek yang mengimpor library tersebut.
  3. Mengganti dependensi ke library parser modern yang aktif dirawat dan mengonfigurasi opsi anti-XXE (*external entity injection disabled*).
  4. Menguji serialisasi dan deserialisasi 100 sampel file XML mitra lama.
* **Result:** Kerentanan berhasil ditutup total dalam waktu 48 jam dan integrasi data B2B tetap berjalan normal tanpa kendala.

#### STAR #22: Menerapkan Analisis Statis (PHPStan / mypy) untuk Menemukan Bug Tersembunyi
* **Situation:** Bahasa pemrograman dinamis yang digunakan proyek sering menimbulkan fatal error di produksi akibat *calling method on null* atau salah ketik nama variabel.
* **Task:** Mencegah bug runtime mendasar sebelum kode masuk ke proses review.
* **Action:**
  1. Memasang static analysis tool (level dasar terlebih dahulu agar tim tidak kewalahan dengan ribuan error lama).
  2. Menyiapkan file *baseline*: mengunci error warisan masa lalu agar tim bisa fokus menjaga agar *kode baru* bebas error 100%.
  3. Menambahkan command static analysis ke dalam pipeline CI/CD sebagai syarat wajib sebelum pull request dapat di-merge.
* **Result:** Menemukan dan memperbaiki 14 potensi fatal error tipe data sebelum rilis ke staging, serta meningkatkan standar penulisan kode tim secara bertahap.

#### STAR #23: Otomasi Refactoring Ratusan File Menggunakan Rector / Codemod
* **Situation:** Proyek backend harus dinaikkan versi runtime-nya karena versi lama sudah End-of-Life (EOL). Terdapat lebih dari 400 file yang masih menggunakan fungsi yang di-deprecate di versi baru.
* **Task:** Memperbarui sintaks deprecated secara cepat dan konsisten tanpa membuang waktu berminggu-minggu untuk edit manual.
* **Action:**
  1. Mempelajari aturan migrasi sintaks resmi dari migration guide runtime target.
  2. Mengonfigurasi automated refactoring tool (Rector / codemod AST transformation) dengan rule set spesifik versi baru.
  3. Menjalankan refactoring secara bertahap: 1 modul per commit, lalu menjalankan automated test suite di setiap tahap.
* **Result:** Memangkas estimasi pengerjaan manual dari 3 minggu menjadi hanya 2 hari, dengan 0 kesalahan manusia (*typo/syntax error*).

#### STAR #24: Menyiapkan Dual-Runtime Environment di CI Matrix
* **Situation:** Tim merencanakan upgrade versi mayor runtime aplikasi dalam 3 bulan ke depan, namun takut kode yang ditulis developer saat ini akan tidak kompatibel dengan runtime masa depan.
* **Task:** Memastikan setiap kode baru yang di-merge hari ini dapat berjalan lancar di kedua versi runtime (lama dan baru).
* **Action:**
  1. Mengedit konfigurasi GitHub Actions workflow untuk menambahkan matriks build.
  2. Menjalankan test suite pada runtime versi eksisting (versi produksi) dan runtime versi target secara paralel.
  3. Menandai build target baru sebagai non-blocking pada awalnya, dan secara bertahap memperbaiki peringatan *deprecation warning* yang muncul.
* **Result:** Pada saat hari peluncuran upgrade runtime produksi dilakukan, proses transisi berjalan mulus dalam 1 sprint tanpa ada penolakan kode.

---

### Kategori G — Modernisasi Bertahap & Arsitektur

#### STAR #25: Menerapkan Pola Strangler Fig untuk Memisahkan Endpoint Auth
* **Situation:** Monolit backend lambat saat menangani jutaan permintaan verifikasi token JWT. Manajemen ingin memulai modernisasi ke layanan terpisah tanpa merombak total monolit sekaligus.
* **Task:** Memindahkan endpoint `/api/v1/auth/verify` ke microservice baru yang ringan tanpa disadari oleh klien mobile/web.
* **Action:**
  1. Membangun microservice autentikasi baru yang terisolasi dan efisien.
  2. Mengonfigurasi Nginx reverse proxy di depan monolit sebagai routing splitter (*Strangler Fig Pattern*).
  3. Mengalihkan 5% traffic ke service baru terlebih dahulu (*canary*), memantau log error dan latensi, lalu menaikkannya bertahap menjadi 100%.
  4. Menyiapkan mekanisme fallback instan kembali ke monolit jika service baru mengalami kendala.
* **Result:** Beban CPU monolit berkurang 25%, latensi verifikasi auth turun dari 120ms ke 15ms, dan proses migrasi berjalan transparan bagi pengguna.

#### STAR #26: Mengimplementasikan Idempotency Key pada Endpoint Transaksi Finansial
* **Situation:** Koneksi internet seluler pengguna yang tidak stabil sering memicu aplikasi mengirim request checkout berulang kali saat layar loading, mengakibatkan pengguna terpotong saldo 2 kali.
* **Task:** Menjamin bahwa request yang sama tidak akan pernah dieksekusi lebih dari 1 kali di sisi backend.
* **Action:**
  1. Merancang mekanisme header `X-Idempotency-Key` (UUID unik yang dibuat oleh aplikasi mobile per aksi checkout).
  2. Menggunakan Redis dengan atomic operation `SETNX` (Set if Not Exists) dan TTL 120 detik untuk mengunci key tersebut saat request pertama diproses.
  3. Jika request dengan key yang sama masuk kembali saat proses berlangsung, server mengembalikan status *in-progress* atau menyajikan cached response transaksi pertama.
* **Result:** Komplain transaksi ganda turun drastis menjadi nol, melindungi bisnis dari kerugian refund operasional.

#### STAR #27: Memindahkan Pembuatan Dokumen PDF ke Background Worker
* **Situation:** Fitur cetak invoice PDF dijalankan secara synchronous di dalam HTTP request thread. Ketika invoice memiliki ratusan item, request memakan waktu 25 detik dan sering menyebabkan web server kehabisan worker pool (*HTTP 504 Gateway Timeout*).
* **Task:** Mengubah arsitektur pembuatan dokumen dari blocking synchronous menjadi asynchronous event-driven.
* **Action:**
  1. Menyiapkan message queue (RabbitMQ / Redis Queue) dan background worker terpisah.
  2. Mengubah endpoint HTTP agar hanya membuat record job bertatus *queued*, lalu mengembalikan respon instan `HTTP 202 Accepted` beserta ID tracking dalam 50 milidetik.
  3. Background worker memproses PDF secara terisolasi dan mengunggah hasilnya ke cloud storage (S3).
  4. Mengirimkan notifikasi push / websocket atau polling status ke browser pengguna saat file siap diunduh.
* **Result:** Menghilangkan insiden server timeout 100% dan membebaskan kapasitas web server untuk melayani transaksi utama.

#### STAR #28: Mencegah Kegagalan Dual-Write dengan Outbox Pattern Sederhana
* **Situation:** Aplikasi lama menyimpan data order ke MySQL lalu langsung memanggil API notifikasi pihak ketiga di dalam fungsi yang sama. Jika jaringan ke API pihak ketiga putus, transaksi DB ikut gagal atau data order tersimpan tapi notifikasi hilang selamanya (*dual-write hazard*).
* **Task:** Menjamin data tersimpan dan pesan notifikasi pasti terkirim (*at-least-once delivery*) tanpa kegagalan transaksi DB.
* **Action:**
  1. Menerapkan pola *Transactional Outbox Pattern* versi praktis.
  2. Membuat tabel baru `outbox_events` di database MySQL yang sama dengan tabel order.
  3. Menyimpan data order dan event notifikasi di dalam satu transaksi database lokal yang ACID.
  4. Menjalankan background scheduler/cron tiap 10 detik yang bertugas membaca event di tabel outbox, mengirimkannya ke pihak ketiga, dan menandai statusnya sebagai *sent*.
* **Result:** Integritas data terjamin 100%; tidak ada lagi pesanan sukses yang kehilangan notifikasi pengiriman.

---

### Kategori H — Keamanan di Sistem Lama

#### STAR #29: Remediasi Celah SQL Injection pada Modul Pencarian Legacy
* **Situation:** Modul pencarian data inventaris lama dibangun dengan merangkai string SQL secara langsung (`$query = "SELECT * FROM items WHERE name LIKE '%" . $input . "%'"`), yang menimbulkan celah fatal SQL Injection.
* **Task:** Mengamankan seluruh query pencarian tanpa merusak fungsionalitas filter data yang kompleks.
* **Action:**
  1. Memindai seluruh codebase menggunakan static analysis keamanan untuk menemukan pola konkatenasi raw SQL serupa.
  2. Menulis ulang query menggunakan *Parameterized Queries / Prepared Statements* dengan PDO/query builder.
  3. Menambahkan sanitasi karakter wildcard (`%`, `_`) pada input pengguna agar pencarian tetap akurat.
  4. Menulis automated test case khusus dengan payload SQL injection klasik (`' OR '1'='1`) untuk memastikan query kebal serangan.
* **Result:** Celah keamanan kategori Critical tertutup sempurna dan lolos pengujian *Penetration Testing* eksternal.

#### STAR #30: Menutup Celah IDOR (Insecure Direct Object Reference) pada File Invoice
* **Situation:** Pengguna dapat mengunduh invoice milik pengguna lain hanya dengan mengubah angka parameter di URL: `/invoices/download?id=1042`.
* **Task:** Memperbaiki sistem otorisasi agar data privat pengguna terlindungi secara ketat.
* **Action:**
  1. Mengaudit seluruh endpoint yang menerima parameter ID objek secara langsung.
  2. Membuat middleware / authorization policy yang memeriksa kepemilikan data: `invoice.user_id == current_authenticated_user.id`.
  3. Mengganti ID integer auto-increment yang mudah ditebak (*enumerable*) menjadi identifier unik (UUID v4) untuk akses publik.
  4. Menambahkan logging keamanan jika terdeteksi upaya akses ID lintas akun untuk memantau potensi penyerang.
* **Result:** Celah privasi berhasil diperbaiki dan sistem memiliki sistem audit trail yang mampu mendeteksi upaya manipulasi data secara real-time.

#### STAR #31: Migrasi Algoritma Password Hashing (MD5 ke bcrypt) via Rehash-on-Login
* **Situation:** Sistem warisan tahun 2015 masih menyimpan password pengguna menggunakan hashing MD5 satu lapis tanpa salt yang sangat rentan dipecahkan dengan Rainbow Table.
* **Task:** Memperbarui keamanan password seluruh pengguna ke standar modern (bcrypt/Argon2) tanpa mereset password atau mengganggu kenyamanan pengguna.
* **Action:**
  1. Menerapkan pola *Rehash-on-Login*.
  2. Mengubah logic autentikasi: ketika pengguna memasukkan password saat login, sistem memeriksa apakah hash di DB masih berformat MD5.
  3. Jika ya, verifikasi password dengan MD5; jika cocok, buat hash baru menggunakan `bcrypt` dengan cost factor 12 dan timpa hash lama di database secara transparan.
  4. Untuk akun yang tidak aktif login selama 6 bulan, siapkan alur wajib *Forgot Password* dengan token aman.
* **Result:** Dalam 3 bulan, 85% akun pengguna aktif telah bermigrasi ke algoritma hashing modern tanpa satu pun komplain dari pengguna.

#### STAR #32: Membersihkan Hardcoded API Keys dari Repositori Git
* **Situation:** Ditemukan API key AWS S3 dan kredensial database tertulis mentah (*hardcoded*) di dalam file konfigurasi repositori Git internal perusahaan.
* **Task:** Menghapus secret dari kode sumber dan memastikan kredensial yang bocor dinonaktifkan.
* **Action:**
  1. Berkoordinasi dengan tech lead untuk segera melakukan rotasi (*revoke and re-generate*) kredensial lama di dashboard AWS dan server DB.
  2. Memindahkan seluruh secret ke file `.env` lokal dan memasukkan `.env` ke dalam `.gitignore`.
  3. Menggunakan tool `git-filter-repo` untuk membersihkan riwayat commit lama yang memuat teks secret tersebut.
  4. Memasang pre-commit hook (TruffleHog / GitGuardian) di repositori agar developer tidak bisa lagi melakukan commit jika terdapat secret terdeteksi.
* **Result:** Repositori bebas dari kredensial sensitif dan sistem terlindungi dari risiko kebocoran data di masa mendatang.

---

### Kategori I — Incident & Production

#### STAR #33: Triase Cepat Lonjakan CPU 100% di Server Produksi
* **Situation:** Di hari Senin pagi, CPU server backend melonjak hingga 100%, menyebabkan seluruh request mengalami timeout dan antrean pengguna membludak.
* **Task:** Mengidentifikasi penyebab lonjakan dan memulihkan kestabilan sistem dalam waktu sesingkat-singkatnya.
* **Action:**
  1. Masuk ke server via SSH dan menjalankan perintah `top` serta `htop` untuk menemukan process ID yang mengonsumsi CPU tertinggi.
  2. Menemukan query database tertentu yang memicu recursive regex pada data teks yang sangat besar.
  3. Berkoordinasi dengan tim ops untuk me-kill query lambat tersebut di database dan memasang rate-limit sementara pada IP pengirim request.
  4. Setelah sistem stabil, menginvestigasi kode sumber dan menambahkan limitasi panjang karakter input pada endpoint tersebut.
* **Result:** Sistem pulih dalam waktu 15 menit, dan perbaikan permanen di-deploy di hari yang sama untuk mencegah masalah terulang.

#### STAR #34: Menangani Deadlock Database saat Transaksi Puncak
* **Situation:** Database mencatat lonjakan error *Deadlock found when trying to get lock; try restarting transaction* sebanyak ratusan kali per jam saat promo berlangsung.
* **Task:** Menemukan transaksi yang saling mengunci (*conflicting locks*) dan menstabilkan alur penulisan database.
* **Action:**
  1. Menjalankan perintah `SHOW ENGINE INNODB STATUS` di database untuk membaca rekaman transaksi terakhir yang mengalami deadlock.
  2. Menemukan bahwa dua proses bisnis berbeda memperbarui tabel `orders` dan `inventories` dengan urutan yang terbalik (Proses A mengunci order lalu inventory; Proses B mengunci inventory lalu order).
  3. Menstandarkan urutan penguncian (*lock ordering*) di seluruh codebase agar selalu mengunci tabel dengan urutan yang sama secara konsisten.
  4. Menambahkan retry mechanism dengan *exponential backoff and jitter* di level aplikasi untuk menangani deadlock sporadis yang tersisa.
* **Result:** Angka deadlock di database turun drastis hingga 99% pada event promo berikutnya.

#### STAR #35: Menyusun Runbook Rollback Pasca-Deployment Gagal
* **Situation:** Suatu deployment malam hari mengalami error tak terduga, dan tim panik karena developer yang mendeploy tidak tahu langkah pasti untuk mengembalikan ke versi stabil sebelumnya (*rollback*), menyebabkan downtime berlangsung 45 menit.
* **Task:** Merancang prosedur operasi standar (runbook) dan script rollback otomatis agar insiden serupa dapat diatasi dalam hitungan menit.
* **Action:**
  1. Menganalisis tahapan deployment yang ada dan titik-titik rawan kegagalan.
  2. Menulis dokumen Runbook langkah demi langkah yang jelas, terstruktur, dan mudah dibaca oleh siapa pun yang bertugas *on-call*.
  3. Mengonfigurasi script deployment agar selalu menyimpan tag rilis sebelumnya dan menyediakan one-click rollback script di CI/CD pipeline.
  4. Mengadakan simulasi rollback bersama tim di lingkungan staging untuk menguji efektivitas runbook.
* **Result:** Durasi pemulihan insiden rilis (*Mean Time to Recovery / MTTR*) terpangkas dari 45 menit menjadi kurang dari 3 menit.

#### STAR #36: Memimpin Sesi Blameless Post-Mortem Pasca Insiden Downtime
* **Situation:** Terjadi insiden downtime selama 30 menit akibat salah satu developer junior salah menjalankan script migrasi database di produksi. Suasana tim sempat tegang dan saling menyalahkan.
* **Task:** Mengalihkan fokus tim dari menyalahkan individu menuju perbaikan kelemahan sistem dan prosedur.
* **Action:**
  1. Menginisiasi sesi *Blameless Post-Mortem* dengan mendokumentasikan kronologi insiden berdasarkan timeline log yang objektif.
  2. Menekankan pertanyaan: *"Mengapa sistem kita mengizinkan seorang engineer menjalankan script berbahaya secara langsung ke produksi tanpa pengaman?"*, bukan *"Siapa yang salah pencet?"*.
  3. Mengidentifikasi kelemahan sistemik: ketiadaan pemisahan hak akses akun DB dan belum adanya validasi otomatis sebelum migrasi berjalan.
  4. Menetapkan 3 action item konkret: mencabut akses write langsung dari koneksi personal, memasang database approval gate di pipeline, dan menambahkan dry-run check.
* **Result:** Hubungan psikologis tim kembali sehat dan celah keamanan operasional berhasil ditutup secara permanen.

---

### Kategori J — Kolaborasi, Komunikasi & Kepemimpinan

#### STAR #37: Menerjemahkan Technical Debt Menjadi Bahasa Nilai Bisnis ke Product Manager
* **Situation:** Sistem sudah sangat lambat untuk ditambah fitur baru karena kode autentikasi yang rumit, namun Product Manager (PM) selalu menolak usulan refactoring karena menganggapnya hanya membuang waktu dan tidak menghasilkan fitur baru.
* **Task:** Meyakinkan PM agar bersedia mengalokasikan 20% kapasitas sprint untuk refactoring teknis.
* **Action:**
  1. Mengumpulkan data konkret: mencatat bahwa dalam 3 bulan terakhir, tim menghabiskan 80 jam kerja hanya untuk memperbaiki bug di modul autentikasi tersebut.
  2. Menghitung opportunity cost: 80 jam tersebut setara dengan waktu untuk menyelesaikan 2 fitur bisnis baru yang tertunda.
  3. Menyusun proposal kompromi: tidak meminta sprint khusus refactor total, melainkan mencicil perbaikan dengan alokasi 1 hari per sprint (model 20% kapasitas debt).
  4. Menunjukkan metrik keberhasilan yang akan dicapai (penurunan tiket bug dan estimasi pengerjaan fitur login sosial yang lebih cepat).
* **Result:** PM menyetujui proposal tersebut dengan antusias; setelah 2 bulan, estimasi pengerjaan fitur terkait autentikasi menjadi 50% lebih cepat.

#### STAR #38: Memangkas Waktu Onboarding Developer Baru Lewat Pembaruan README dan Docker
* **Situation:** Saat saya pertama kali bergabung sebagai fresh graduate, butuh waktu 6 hari kerja hanya untuk menyalakan aplikasi di komputer lokal karena dokumentasi README usang dan versi library yang bentrok.
* **Task:** Memperbaiki proses setup lingkungan lokal agar developer berikutnya tidak mengalami penderitaan yang sama.
* **Action:**
  1. Mencatat secara detail setiap error, konfigurasi tersembunyi, dan langkah solusi yang saya lalui selama proses onboarding.
  2. Mengemas dependensi lingkungan lokal (database, caching, runtime, queue) ke dalam file `docker-compose.yml` terpadu.
  3. Menulis ulang file `README.md` dengan instruksi 3 langkah mudah: clone, copy env, dan jalankan satu perintah `docker compose up`.
  4. Menguji panduan tersebut kepada satu orang rekan magang lain yang baru bergabung untuk memvalidasi kemudahannya.
* **Result:** Waktu setup lokal untuk developer baru terpangkas dari 6 hari kerja menjadi hanya 45 menit, mendapatkan apresiasi langsung dari Engineering Manager.

#### STAR #39: Proaktif Menggali Tacit Knowledge Lewat Pair Programming dengan Senior
* **Situation:** Saya ditugaskan mengerjakan perubahan kecil pada modul kalkulasi komisi mitra yang terkenal sangat rumit dan penuh "aturan gaib" yang tidak tertulis di mana pun.
* **Task:** Menyelesaikan tiket tanpa salah menginterpretasikan aturan bisnis yang rumit.
* **Action:**
  1. Menyiapkan daftar pertanyaan terstruktur dan diagram alur pemahaman awal saya sebelum menemui tech lead senior.
  2. Mengajukan sesi *Pair Programming* singkat selama 45 menit untuk meninjau bagian-bagian kode yang ambigu bersama-sama.
  3. Secara aktif mencatat alasan historis di balik keputusan desain yang tampak janggal.
  4. Setelah sesi selesai, merangkum hasil diskusi ke dalam dokumen wiki tim (*Architecture Decision Record / ADR*) agar pengetahuan tersebut tidak hilang saat senior tersebut cuti.
* **Result:** Tugas selesai tepat waktu tanpa revisi, dan dokumentasi baru tersebut sangat membantu anggota tim lain yang menyentuh modul yang sama.

#### STAR #40: Membangun Standar Pull Request Template untuk Mengurangi Debat Review
* **Situation:** Proses code review sering berlarut-larut selama berhari-hari karena reviewer bingung konteks perubahan yang dibuat, dan perdebatan sering kali berkutat pada format spasi dan penamaan variabel.
* **Task:** Mempercepat siklus code review dan membuat proses review lebih fokus pada logika bisnis.
* **Action:**
  1. Mengusulkan dan membuat template Pull Request (PR) otomatis di GitHub yang mencakup: *Konteks Masalah*, *Solusi yang Dilakukan*, *Cara Mengetes*, dan *Screenshot/Bukti Log*.
  2. Memasang linter otomatis di CI untuk menangani masalah gaya penulisan kode (*code style*) secara otomatis agar manusia tidak perlu memperdebatkannya lagi di kolom komentar.
  3. Membiasakan diri memecah PR besar menjadi PR-PR kecil (*atomic commits*) yang mudah direview (maksimal 200–300 baris perubahan per PR).
* **Result:** Waktu tunggu persetujuan (*PR turnaround time*) berkurang dari rata-rata 3 hari menjadi kurang dari 1 hari kerja, serta meningkatkan kehangatan kolaborasi antar developer di tim.
