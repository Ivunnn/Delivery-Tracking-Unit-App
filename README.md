# Sistem Delivery Tracking Unit

Aplikasi web untuk mengelola pengiriman unit kendaraan dari sisi administrator, driver, dan customer. Aplikasi ini menyediakan master data unit, customer, driver, order, invoice, pengiriman, serta halaman dashboard untuk memantau data operasional.

## Fitur Utama

- Dashboard admin dengan ringkasan customer, order, unit, driver, dan pendapatan yang sudah dibayar.
- Manajemen data unit kendaraan.
- Manajemen customer dan status aktif customer.
- Manajemen driver beserta status ketersediaannya.
- Pengelolaan order customer.
- Invoice dan status pembayaran.
- Pengelolaan pengiriman unit.
- Tracking status pengiriman, lokasi, koordinat, dan catatan.
- Bukti pengiriman.
- Login berbasis role: admin, driver, dan customer.
- Dark mode dan layout responsive.

## Teknologi

- PHP 8.2+
- Laravel 12
- Blade Components
- Tailwind CSS 4
- Alpine.js 3
- Vite
- MySQL 8 atau database Laravel lain yang kompatibel
- Laravel Sail untuk lingkungan Docker

## Persyaratan

Pastikan perangkat sudah memiliki:

- PHP >= 8.2
- Composer
- Node.js 22 dan npm
- MySQL jika menjalankan aplikasi secara native
- Docker Desktop jika menggunakan Sail

Periksa instalasi dengan:

```bash
php -v
composer -V
node -v
npm -v
```

## Instalasi Lokal

### 1. Clone repository

```bash
git clone <url-repository>
cd LIVE-TRACKING
```

### 2. Install dependency

```bash
composer install
npm install
```

### 3. Buat file environment

Linux/macOS:

```bash
cp .env.example .env
```

Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

### 4. Konfigurasi database

Atur koneksi database di `.env`. Contoh menggunakan MySQL lokal:

```env
APP_NAME="Sistem Delivery Tracking Unit"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=delivery_tracking
DB_USERNAME=root
DB_PASSWORD=
```

Buat database `delivery_tracking`, lalu jalankan migration dan seeder:

```bash
php artisan migrate --seed
```

Untuk mengulang database dari awal:

```bash
php artisan migrate:fresh --seed
```

### 5. Jalankan aplikasi

Gunakan dua terminal:

Terminal 1:

```bash
php artisan serve
```

Terminal 2:

```bash
npm run dev
```

Buka [http://localhost:8000](http://localhost:8000).

Alternatif, gunakan script Composer:

```bash
composer run dev
```

Script tersebut menjalankan server Laravel, Vite, queue worker, dan log viewer secara bersamaan.

## Menjalankan dengan Docker / Sail

Pastikan Docker Desktop aktif dan dependency Composer sudah terpasang.

```bash
cp .env.example .env
php artisan key:generate
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate --seed
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

Windows PowerShell dapat menggunakan perintah berikut:

```powershell
Copy-Item .env.example .env
php artisan key:generate
vendor/bin/sail up -d
vendor/bin/sail artisan migrate --seed
vendor/bin/sail npm install
vendor/bin/sail npm run dev
```

Alamat layanan default:

- Aplikasi: [http://localhost](http://localhost)
- Vite: [http://localhost:5173](http://localhost:5173)
- Mailpit: [http://localhost:8025](http://localhost:8025)

## Akun Demo

Akun berikut dibuat oleh `UserSeeder`:

| Role     | Email                 | Password   |
| -------- | --------------------- | ---------- |
| Admin    | `admin@anugerah.com`  | `password` |
| Driver   | `budi@anugerah.com`   | `password` |
| Driver   | `agus@anugerah.com`   | `password` |
| Driver   | `roni@anugerah.com`   | `password` |
| Customer | `lamongan@dealer.com` | `password` |
| Customer | `tuban@dealer.com`    | `password` |
| Customer | `cepu@dealer.com`     | `password` |

Jangan gunakan password demo untuk lingkungan production.

## Struktur Modul

```text
app/
├── Http/Controllers/       Controller aplikasi dan admin
├── Http/Middleware/        Middleware autentikasi dan role
├── Models/                 Model User, Unit, Order, Invoice, Driver, dan lainnya
└── View/Components/        Class-based Blade components

database/
├── migrations/             Struktur tabel aplikasi
└── seeders/                Data awal untuk pengembangan

resources/
├── css/                    Tailwind CSS
├── js/                     Alpine.js, ApexCharts, dan modul frontend
└── views/                  Layout, halaman, dan Blade components

routes/
└── web.php                 Route autentikasi, admin, driver, dan customer
```

## Route Utama

| Route                 | Akses    | Keterangan              |
| --------------------- | -------- | ----------------------- |
| `/`                   | Guest    | Halaman login           |
| `/signup`             | Guest    | Registrasi customer     |
| `/admin/dashboard`    | Admin    | Dashboard administrator |
| `/driver/dashboard`   | Driver   | Dashboard driver        |
| `/customer/dashboard` | Customer | Dashboard customer      |
| `/admin/units`        | Admin    | Data unit               |
| `/admin/customers`    | Admin    | Data customer           |
| `/admin/drivers`      | Admin    | Data driver             |

Lihat seluruh route dengan:

```bash
php artisan route:list
```

## Build Production

Build asset frontend:

```bash
npm run build
```

Optimasi Laravel:

```bash
php artisan optimize
```

Contoh environment production:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://example.com
```

## Testing dan Validasi

Jalankan test suite:

```bash
composer test
```

Atau:

```bash
php artisan test
```

Validasi Blade:

```bash
php artisan view:cache
```

Clear cache aplikasi:

```bash
php artisan optimize:clear
```

## Catatan Pengembangan

- Gunakan logical CSS utilities Tailwind untuk menjaga dukungan RTL.
- Simpan komponen reusable di `resources/views/components`.
- Tambahkan migration baru untuk perubahan schema database.
- Jalankan `php artisan migrate --seed` setelah menyiapkan database development.
- Jangan menyimpan file `.env`, credential, atau data production ke repository.

## Lisensi

Proyek ini menggunakan lisensi yang tercantum pada file `LICENSE`.
