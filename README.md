# Starter Kit Bootcamp Backend: TokoLaris E-Commerce (PHP 8.2 & MySQL 8)

Selamat datang di simulasi Developer Augmentation & Code Rescue TokoLaris Backend!

Anda ditempatkan sebagai Software Engineer yang mewarisi sistem e-commerce monolitik warisan yang dibangun 6 tahun lalu. Sistem ini akan menghadapi lonjakan transaksi besar pada kampanye Flash Sale 12.12. Tugas Anda adalah mengamankan sistem, menambal bug kritis, menyelesaikan kendala konkurensi stok minus, dan mengoptimalkan query database tanpa melakukan rewrite total dari nol.

---

## 1. Prasyarat Sistem

* Docker Desktop (versi terbaru aktif) & Docker Compose
* Git
* Terminal (PowerShell di Windows, atau Bash di Linux / macOS)
* Code Editor (VS Code / PHPStorm)

---

## 2. Persiapan Lingkungan Kerja (Setup Awal)

### Langkah 1: Clone Repositori
```bash
git clone https://github.com/Dwdun/php-tokolaris-backend.git
cd php-tokolaris-backend
```

### Langkah 2: Nyalakan Container Docker
Pastikan Docker Desktop sudah menyala di laptop Anda, lalu jalankan:
```bash
cd peserta
docker-compose up -d
cd ..
```
*Catatan: Perintah ini menyalakan 5 container pendukung (PHP 8.2 FPM, Nginx, MySQL 8.0, Redis 7, dan k6).*

### Langkah 3: Verifikasi API Online
Buka browser Anda di `http://localhost:8080/` atau jalankan via terminal:
```bash
curl http://localhost:8080/
```
Respons yang diharapkan:
```json
{
  "status": "online",
  "service": "TokoLaris Backend API",
  "version": "1.0.0-legacy"
}
```

### Langkah 4: Daftarkan Identitas Anda
Buka berkas `peserta_config.json` di root direktori proyek menggunakan text editor, lalu isi identitas Anda:
```json
{
  "nama_peserta": "Nama Lengkap Anda",
  "meja": "Meja 01",
  "mentor_server_url": "http://web/papan"
}
```
*(Simpan berkas tersebut. Nama ini yang akan tampil otomatis di layar monitor pemantauan mentor).*

---

## 3. Panduan Langkah Pengerjaan Tiket (Alur Lengkap Peserta)

Sistem evaluasi menggunakan model Blind Testing Investigation. Anda tidak akan disuapi daftar jawaban atau bocoran total tiket, melainkan ditantang melakukan code audit layaknya investigasi sistem produksi di dunia kerja nyata.

Ikuti siklus pengerjaan berikut untuk setiap tiket:

### Tahap 1: Cek Status Pengujian
Jalankan perintah berikut di root folder:
* Windows: `.\gate.bat status`
* Linux / macOS: `./gate status`

### Tahap 2: Membaca Lembar Tiket & Keluhan Bisnis
Baca deskripsi tiket yang ingin Anda selesaikan (contoh: tiket T01):
```powershell
.\gate.bat lihat T01
```
Terminal akan menampilkan informasi terstruktur:
* Keluhan Bisnis dari tim operasional/customer.
* Lokasi file kode sumber terkait di folder `app/`.
* Kriteria Kelulusan teknis yang harus dipenuhi.
* Petunjuk modul materi panduan.

### Tahap 3: Mereproduksi Masalah (Baseline Test)
Sebelum mengubah kode, buktikan terlebih dahulu bahwa cacat kode tersebut benar-benar ada:
```powershell
.\gate.bat mulai T01
```
Sistem akan menjalankan test awal dan menunjukkan kegagalan pada kode warisan saat ini.

### Tahap 4: Investigasi & Perbaiki Kode Sumber
1. Buka file kode yang disebutkan dalam tiket menggunakan editor Anda (misalnya: `app/Controllers/OrderController.php`).
2. Pelajari logika warisan yang bermasalah. Anda juga dapat membaca materi pengayaan di folder `panduan/` jika memerlukan penyegaran konsep (misal: konkurensi, SQL locking, atau N+1 query).
3. Lakukan perbaikan kode secara hati-hati agar tidak merusak fungsionalitas lain.

### Tahap 5: Uji dan Verifikasi Perbaikan Anda
Setelah kode selesai Anda perbaiki, jalankan pengujian:
```powershell
.\gate.bat uji T01
```
* **Jika GAGAL:** Terminal memberikan respons `[BELUM MEMENUHI SPESIFIKASI]` beserta detail kegagalan assertion test. Silakan telusuri kode Anda kembali.
* **Jika LULUS:** Terminal merespons `[LULUS]`.
  * Status kelulusan tiket ini **secara otomatis dikirim ke monitor dashboard mentor di latar belakang**. Anda tidak perlu melakukan ekspor berkas manual!

### Tahap 6: Catat Perubahan ke Git
Simpan kemajuan kerja Anda menggunakan Git:
```bash
git add .
git commit -m "fix(order): address guard implementation for T01"
```

### Tahap 7: Lanjutkan ke Tiket Berikutnya
Ulangi siklus di atas (lihat -> perbaiki -> uji) untuk tiket-tiket berikutnya (T02, T03, T04, dst).

---

## 4. Perintah Tambahan

### Reset Database ke Kondisi Awal
Jika database Anda kotor, kehabisan data stok uji coba, atau terjadi error inkonsistensi saat bereksperimen:
```powershell
.\gate.bat reset
```
Perintah ini akan menyetel ulang tabel dan data seeder ke kondisi awal dalam hitungan detik.

### Ekspor Berkas Hasil Cadangan (Opsional)
Jika sewaktu-waktu koneksi jaringan offline atau mentor meminta berkas fisik hasil pengerjaan:
```powershell
.\gate.bat export
```
Perintah ini akan menghasilkan berkas `hasil_peserta.json` di root direktori.

---

## 5. Struktur Berkas Repositori

* `app/` : Kode sumber backend aplikasi e-commerce yang harus Anda perbaiki.
  * `Controllers/` : OrderController, SearchController, InvoiceController, AuthController.
  * `Services/` : CartService, Payment Adapter, dll.
  * `Support/` : Database PDO wrapper dan helper query.
  * `public/` : Entry point routing REST API (index.php).
* `db/` & `data/` : Skema database MySQL dan seeder data awal.
* `tiket/` : Berkas spesifikasi tiket masalah (T01 s/d T25 dan B01 s/d B05).
* `panduan/` : Buku pedoman mandiri materi teknis (Field Manual) dan bank referensi STAR.
* `uji/` : Automated test suites (Pest PHP).
* `skenario/` : Skenario k6 untuk simulasi lonjakan beban konkurensi Flash Sale 12.12.
* `pemeriksa/` : Skrip CLI pendukung pengujian dan verifikasi.
* `gate.bat` : Launcher pengujian tiket untuk Windows.
* `gate` : Launcher pengujian tiket untuk Linux / macOS.
* `peserta_config.json` : Berkas konfigurasi identitas peserta.
