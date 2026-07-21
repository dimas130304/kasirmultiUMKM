# Kasir POS UMKM — Laravel 12

Aplikasi ini adalah migrasi dari proyek CodeIgniter (`kasir`) ke **Laravel 12**, dengan database yang sama (`kasir`).

## Persyaratan

| Komponen | Versi |
|----------|--------|
| PHP | **8.4 atau lebih baru** (wajib untuk Laravel 12.60+) |
| MySQL / MariaDB | 5.7+ |
| Composer | 2.x |
| Web server | Apache (XAMPP) dengan `mod_rewrite` |

> **Penting:** Laravel 12 membutuhkan PHP **8.4+**. Di mesin ini sudah disiapkan **`C:\xampp\php84`** (PHP 8.4.8) dan Apache diarahkan ke folder tersebut. PHP 8.0 lama ada cadangan di `C:\xampp\php80_backup`.

## Langkah instalasi

### 1. Restart Apache

Buka **XAMPP Control Panel** → Stop Apache → Start Apache agar memuat PHP 8.4.

### 2. Perintah artisan (gunakan PHP 8.4)

```bat
cd C:\xampp\htdocs\kasir-laravel
artisan.bat serve
```

Atau: `C:\xampp\php84\php.exe artisan serve`

Jangan pakai `C:\xampp\php\php.exe` (itu PHP 8.0 — akan error `readonly`).

### 2. Database

Gunakan database yang sama dengan project CI:

```sql
-- Import jika belum ada
-- File: database/sql/kasir.sql
```

### 3. Konfigurasi `.env`

File sudah diarahkan ke database `kasir`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kasir
DB_USERNAME=root
DB_PASSWORD=
```

Generate key:

```bat
cd C:\xampp\htdocs\kasir-laravel
artisan.bat key:generate
```

### 4. Jalankan aplikasi

**Opsi A — Artisan (development)**

```bash
php artisan serve
```

Buka: http://127.0.0.1:8000

**Opsi B — XAMPP Apache**

1. Pastikan DocumentRoot atau virtual host mengarah ke folder `public`:
   - `C:\xampp\htdocs\kasir-laravel\public`
2. Buka: http://localhost/kasir-laravel/public

Atur virtual host (opsional) di `httpd-vhosts.conf`:

```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/kasir-laravel/public"
    ServerName kasir-umkm.test
</VirtualHost>
```

### 5. Asset

Asset dari project lama sudah disalin ke `public/assets/` (gambar produk, plugin JS, dll.).

## Fitur yang sudah dimigrasi

- Landing page UMKM
- Login & registrasi (level Admin / Kasir)
- Dashboard admin
- **Kasir** (produk, keranjang, transaksi, cetak struk)
- Kategori, Menu/Produk (CRUD dasar)
- Customer, Order (daftar)
- Users, Profil, Pengaturan toko
- Laporan penjualan (daftar + filter tanggal)
- Keuangan ledger (daftar)
- Apriori (tampilan; perhitungan penuh butuh `php-ai/php-ml`)

## Akun login

Gunakan akun yang sudah ada di tabel `login` (database `kasir`), atau daftar akun baru lewat halaman registrasi.

## Project lama vs baru

| | CodeIgniter | Laravel 12 |
|---|-------------|--------------|
| Folder | `C:\xampp\htdocs\kasir` | `C:\xampp\htdocs\kasir-laravel` |
| URL contoh | `/kasir/` | `/kasir-laravel/public` atau `php artisan serve` |
| Database | `kasir` | `kasir` (sama) |

Kedua project bisa berjalan paralel selama development.

## Troubleshooting

**Parse error `readonly`** → Browser/CLI masih memakai PHP 8.0 (`C:\xampp\php`). Gunakan **`C:\xampp\php84`** atau `artisan.bat`. Restart Apache setelah perubahan config.

**500 / APP_KEY** → Jalankan `php artisan key:generate`.

**Gambar produk tidak muncul** → Pastikan folder `public/assets/image/produk/` berisi file gambar.

**Route not found** → Aktifkan `mod_rewrite`; pastikan `.htaccess` di folder `public` ada.
