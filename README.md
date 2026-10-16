# HIPERTENSI-CARE: Clinical Decision Support System (CDSS)

**Sistem Pendukung Keputusan Klinis Berbasis Web untuk Deteksi Dini Risiko Hipertensi Menggunakan Algoritma ExtraTrees Classifier dan ONNX Runtime Web di RSI Kendal**

---

## 📌 Ringkasan Proyek

HIPERTENSI-CARE adalah aplikasi web *Clinical Decision Support System* (CDSS) penapisan awal (*screening*) risiko hipertensi mandiri bagi masyarakat. Sistem ini memanfaatkan model pembelajaran mesin (*machine learning*) **ExtraTrees Classifier** berkinerja tinggi yang diekspor ke format terbuka **ONNX (Open Neural Network Exchange)** dan dieksekusi secara langsung pada peramban web pengguna (*client-side execution*) menggunakan WebAssembly (WASM).

Aplikasi ini bertujuan untuk meningkatkan kesadaran deteksi dini pada populasi berisiko tinggi tanpa memerlukan alat laboratorium invasif, serta menjembatani masyarakat untuk segera berkonsultasi dengan dokter di Rumah Sakit Islam (RSI) Kendal melalui surat rujukan digital.

---

## 🚀 Fitur Utama

1. **Skrining Mandiri Berbasis Parameter Fisik Sederhana**
   * Pengguna umum hanya perlu menginput 5 parameter fisik dasar: Nama, Jenis Kelamin, Usia, Berat Badan, dan Tinggi Badan.
   * Indeks Massa Tubuh (IMT / BMI) dihitung secara instan dengan klasifikasi standar Asia Pasifik (WHO WPRO).

2. **Rekayasa Fitur Otomatis di Sisi Klien (*Client-Side Feature Engineering*)**
   * Sebelum inferensi, JavaScript di peramban secara otomatis menghitung 13 fitur turunan (Body Surface Area, Rasio Berat-Tinggi Badan, skor risiko kumulatif, serta One-Hot Encoding usia dan kategori IMT) untuk menyusun vektor 18 fitur standar model.

3. **Inferensi ONNX Runtime Web (WASM)**
   * Eksekusi model dilakukan langsung di peramban pengguna tanpa membebani server backend, menjaga skalabilitas dan kecepatan inferensi (< 50 ms).

4. **Optimasi Ambang Batas Klinis (*Threshold Tuning*)**
   * Ambang batas probabilitas dioptimalkan pada **81.5%** (*Tuned Threshold*) untuk meminimalkan *False Negative* dan meningkatkan sensitivitas deteksi risiko hipertensi esensial.

5. **Surat Pengantar Rujukan Medis Digital**
   * Pasien dengan indikasi risiko tinggi dapat langsung mencetak lembar rujukan formal berformat resmi RSI Kendal lengkap dengan rincian parameter, interpretasi probabilitas, dan verifikasi tanda tangan digital dokter penanggung jawab pelayanan (DPJP).

6. **Dasbor Pemantauan dan Log Audit Terproteksi (*Protected Back-Office*)**
   * Modul dasbor analitik dan riwayat transaksi hanya dapat diakses oleh staf medis dan dokter melalui mekanisme autentikasi peran.
   * Dilengkapi grafik tren mingguan (Chart.js), visualisasi distribusi IMT populasi, serta fitur ekspor data ke format CSV.

---

## 🔬 Spesifikasi Model Machine Learning

* **Algoritma:** ExtraTrees Classifier (Extremely Randomized Trees)
* **Dataset Pelatihan:** 1.553 Rekam Medis Pasien RSI Kendal
* **Skor Akurasi Pengujian:** 85.21%
* **Ambang Batas Klasifikasi:** 0.815 (81.5%)
* **Format Deployment:** Open Neural Network Exchange (`model_extratrees.onnx`, 18.5 MB)
* **Engine Inferensi:** `onnxruntime-web` (WebAssembly Provider)

---

## 🛠️ Tumpukan Teknologi (Tech Stack)

* **Backend Framework:** Laravel 13 (PHP 8.3+)
* **Basis Data:** MySQL (Laragon) / SQLite
* **Frontend:** Blade Templating, Tailwind CSS CDN, Font Awesome 6
* **Visualisasi Data:** Chart.js
* **Machine Learning Runtime:** ONNX Runtime Web (WASM)

---

## 💻 Panduan Instalasi dan Menjalankan Sistem

### 1. Prasyarat Sistem
* PHP >= 8.2 (dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `curl`)
* Composer
* MySQL / MariaDB (misal: Laragon atau XAMPP)
* Web Browser modern yang mendukung WebAssembly (Google Chrome, Microsoft Edge, Mozilla Firefox)

### 2. Kloning Repositori
```bash
git clone https://github.com/rezaandika142/project_skripsi.git
cd project_skripsi
```

### 3. Instalasi Dependensi PHP
```bash
composer install
```

### 4. Konfigurasi Lingkungan (.env)
Salin berkas contoh konfigurasi lingkungan dan sesuaikan kredensial basis data:
```bash
cp .env.example .env
php artisan key:generate
```

Pastikan pengaturan database pada berkas `.env` sesuai dengan konfigurasi MySQL lokal Anda:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_hipertensi
DB_USERNAME=root
DB_PASSWORD=
SESSION_DRIVER=file
```

### 5. Migrasi dan Inisialisasi Data Awal (*Database Migration & Seeding*)
Jalankan migrasi tabel beserta data awal model klinis dan akun dokter:
```bash
php artisan migrate:fresh --seed
```

### 6. Menjalankan Server Lokal
```bash
php artisan serve
```
Akses aplikasi melalui peramban di: **`http://127.0.0.1:8000`**

---

## 🔐 Akun Akses Demonstrasi Staf Medis

Untuk keperluan demonstrasi sidang tugas akhir, sistem menyediakan fitur **1-Click Quick Demo Login** pada rute `/quick-login` atau melalui kredensial berikut:

* **URL Login:** `http://127.0.0.1:8000/login`
* **Email:** `dokter@rsikendal.co.id`
* **Kata Sandi:** `password123`
* **Peran:** Dokter Spesialis Jantung & Pembuluh Darah (DPJP)

---

## 📁 Struktur Direktori Utama

```text
project/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php        # Autentikasi staf medis & demo sidang
│   │   ├── DashboardController.php   # Analitik dasbor & grafik pemantauan
│   │   ├── RiwayatController.php     # Log audit transaksi & ekspor CSV
│   │   └── SkriningController.php    # Form skrining & cetak rujukan
│   └── Models/
│       ├── ModelVersion.php          # Metadata versi model ONNX
│       ├── Prediction.php            # Catatan riwayat skrining pasien
│       └── User.php                  # Data staf medis / dokter
├── database/
│   ├── migrations/                   # Skema tabel users, models, predictions
│   └── seeders/DatabaseSeeder.php    # Inisialisasi data awal
├── public/
│   └── models/
│       ├── model_extratrees.onnx     # Model komputasi ONNX ExtraTrees (18.5 MB)
│       └── model_metadata.json       # Metadata konfigurasi & scaler fitur
├── resources/views/
│   ├── layouts/app.blade.php         # Master layout navigasi dan skrip
│   ├── skrining.blade.php            # Tampilan skrining & inferensi WASM
│   ├── dashboard.blade.php           # Tampilan dasbor analitik pemantauan
│   ├── riwayat.blade.php             # Tampilan log audit riwayat pasien
│   ├── rujukan_print.blade.php       # Template cetak surat pengantar rujukan
│   └── auth_login.blade.php          # Formulir masuk portal dokter
└── routes/web.php                    # Definisi rute publik dan terproteksi
```

---

## 📄 Hak Cipta dan Lisensi

Dikembangkan sebagai bagian dari Tugas Akhir Sarjana Terapan / Program Studi Teknik Informatika, bekerjasama dengan Rumah Sakit Islam (RSI) Kendal.  
Dilisensikan di bawah lisensi [MIT](LICENSE).
