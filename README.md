# Starter Kit Bootcamp Backend: TokoLaris E-Commerce (PHP 8.2 & MySQL 8)

Selamat datang di simulasi *Developer Augmentation & Code Rescue* **TokoLaris Backend**!

Anda ditempatkan sebagai Software Engineer yang mewarisi sistem e-commerce monolitik yang dibangun beberapa tahun lalu. Sistem ini akan menghadapi kampanye **Flash Sale 12.12**. Tugas Anda adalah menstabilkan sistem, memberantas bug kritis, mengatasi kendala konkurensi stok, dan mengoptimalkan performa database tanpa melakukan *rewrite* dari nol.

---

## 1. Prasyarat Sistem

* **Docker Desktop** (versi terbaru) & Docker Compose
* **Git**
* Terminal (PowerShell di Windows, atau Bash di Linux / macOS)

---

## 2. Cara Menjalankan Lingkungan Kerja

1. Clone repositori ini ke laptop Anda:
   ```bash
   git clone https://github.com/Dwdun/php-tokolaris-backend.git
   cd php-tokolaris-backend
   ```
2. Nyalakan seluruh container service di balik layar:
   ```bash
   cd peserta
   docker-compose up -d
   cd ..
   ```
3. Verifikasi bahwa layanan API sudah online:
   Buka browser atau jalankan: `curl http://localhost:8080/`
   Output yang diharapkan:
   ```json
   { "status": "online", "service": "TokoLaris Backend API", "version": "1.0.0-legacy" }
   ```

---

## 3. Konfigurasi Identitas Peserta

Buka berkas `peserta_config.json` di root direktori, lalu sesuaikan identitas Anda:
```json
{
  "nama_peserta": "Nama Lengkap Anda",
  "meja": "Meja 01",
  "mentor_server_url": "http://web/papan"
}
```
*(Jika mentor memberikan alamat IP khusus di ruang kelas, ganti `mentor_server_url` sesuai arahan mentor).*

---

## 4. Alur Pengerjaan Tiket

Seluruh kendali pengujian dilakukan melalui perintah pembungkus `gate`:
* Di **Windows**: gunakan `.\gate.bat [perintah] [kode_tiket]`
* Di **Linux / macOS**: gunakan `./gate [perintah] [kode_tiket]`

### Perintah Utama:
* **Melihat status:**
  ```powershell
  .\gate.bat status
  ```
* **Membaca lembar spesifikasi tiket:**
  ```powershell
  .\gate.bat lihat T01
  ```
* **Memulai pengerjaan tiket (reproduksi cacat):**
  ```powershell
  .\gate.bat mulai T01
  ```
* **Menguji perbaikan kode:**
  ```powershell
  .\gate.bat uji T01
  ```
  *Sistem akan memverifikasi perbaikan Anda secara instan dan menyinkronkan statusnya ke monitor dashboard mentor di latar belakang.*
* **Mereset ulang database ke kondisi awal:**
  ```powershell
  .\gate.bat reset
  ```

---

## 5. Struktur Berkas Peserta

* **`app/`** : Kode sumber backend yang harus Anda telusuri dan perbaiki.
* **`db/`** & **`data/`** : Skema database dan seeder SQL awal.
* **`tiket/`** : Lembar tantangan misi per tiket (`T01_...md`, `T02_...md`, dst).
* **`panduan/`** : Buku pegangan mandiri (*Field Manual*), pedoman arsitektur, dan referensi metode STAR.
* **`uji/`** : Berkas automated tests (Pest PHP).
* **`skenario/`** : Skenario k6 untuk simulasi lonjakan trafik Flash Sale 12.12.
