# 🚀 Setup Instructions - Dashboard & Analytics Project

## 📋 Status Proyek

### ✅ Yang Sudah Selesai:
- **Controllers**: `DashboardController.php`, `ReportController.php`
- **Views**: Dashboard, Sales Report, Users Report
- **Routes**: Sudah dikonfigurasi dengan middleware admin
- **Features**: Charts (Chart.js), Filters, Export, Dummy Data

### ❌ Yang Perlu Dilakukan:
- Install PHP
- Install Composer
- Setup Database
- Install Dependencies
- Jalankan Server

---

## 🎯 Pilihan Setup (Pilih Salah Satu)

### **OPSI 1: LARAGON (Paling Mudah & Recommended)** ⭐

Laragon adalah solusi all-in-one untuk development PHP di Windows.

#### Download & Install:
1. Download dari: https://laragon.org/download/
2. Pilih **"Laragon Full"** (sudah include PHP, MySQL, Composer, Node.js)
3. Install dengan default settings
4. Buka Laragon → Klik **"Start All"**

#### Setup Project:
1. Di Laragon, klik kanan → **Terminal**
2. Jalankan perintah berikut:

```powershell
# Masuk ke folder project
cd "C:\Users\NAUFAL\Documents\GitHub\Tubes-PemWeb#"

# Install dependencies
composer install

# Copy environment file
copy .env.example .env

# Generate application key
php artisan key:generate

# Setup database
php artisan migrate

# (Optional) Seed data dummy
php artisan db:seed

# Jalankan server
php artisan serve
```

#### Akses Aplikasi:
- Dashboard: http://localhost:8000/dashboard
- Sales Report: http://localhost:8000/reports/sales
- Users Report: http://localhost:8000/reports/users

---

### **OPSI 2: Manual Installation**

#### 1. Install PHP
- Download: https://windows.php.net/download/
- Pilih **PHP 8.2** (Thread Safe)
- Extract ke `C:\php`
- Tambahkan `C:\php` ke **System PATH**

#### 2. Install Composer
- Download: https://getcomposer.org/Composer-Setup.exe
- Run installer (akan auto-detect PHP)

#### 3. Install Node.js (untuk npm & Vite)
- Download: https://nodejs.org/
- Pilih **LTS version**
- Install dengan default settings

#### 4. Setup Project (sama seperti di atas)

---

### **OPSI 3: Docker** 🐳

Jika Anda familiar dengan Docker:

```powershell
# Install Docker Desktop for Windows
# Download: https://www.docker.com/products/docker-desktop

# Di terminal project:
docker run -d -p 8000:8000 -v ${PWD}:/var/www/html php:8.2-cli php artisan serve --host=0.0.0.0
```

---

## 🗄️ Konfigurasi Database

Edit file `.env` (setelah copy dari `.env.example`):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tubes_pemweb
DB_USERNAME=root
DB_PASSWORD=
```

### Buat Database:
**Jika menggunakan Laragon:**
- Klik kanan Laragon → MySQL → Create Database
- Nama: `tubes_pemweb`

**Jika menggunakan phpMyAdmin:**
- Buka: http://localhost/phpmyadmin
- Create database: `tubes_pemweb`

---

## 👤 Membuat User Admin

Setelah migrate database, buat user admin:

```powershell
php artisan tinker
```

Lalu di tinker:

```php
$admin = new App\Models\User();
$admin->name = 'Admin';
$admin->email = 'admin@example.com';
$admin->password = bcrypt('password123');
$admin->role = 'admin';
$admin->save();
```

Atau tekan `Ctrl+C` dan jalankan seeder jika sudah ada.

---

## 📊 Fitur Dashboard & Analytics

### 1. **Admin Dashboard** (`/dashboard`)
- Total Users, Doctors, Patients, Monthly Sales
- User Registration Trend Chart
- Monthly Sales Chart
- User Role Distribution Chart
- Top 5 Products
- Recent Activities Timeline

### 2. **Sales Report** (`/reports/sales`)
- Filter by Period (Day, Week, Month, Year)
- Date Range Filter
- Total Revenue, Orders, Completion Rate
- Sales by Category Chart
- Payment Methods Chart
- Top Products Table
- Transaction List with Status

### 3. **Users Report** (`/reports/users`)
- Filter by Role (All, Admin, Doctor, Patient)
- User Statistics Cards
- Registration Trend Chart
- Age Distribution Chart
- User Activity Metrics
- Role Distribution Progress Bars
- Gender Distribution Chart
- Paginated User List

---

## 🎨 Technologies Used

- **Backend**: Laravel 11
- **Frontend**: Blade Templates, Tailwind CSS
- **Charts**: Chart.js (CDN)
- **Icons**: SVG Icons (inline)
- **Data**: Dummy data for testing

---

## 🔐 Middleware & Security

Routes dilindungi dengan:
- `auth` - User harus login
- `role:admin` - Hanya admin yang bisa akses

Pastikan `RoleMiddleware` sudah terdaftar di `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ]);
})
```

---

## 🐛 Troubleshooting

### Error: "Class not found"
```powershell
composer dump-autoload
```

### Error: "No application encryption key"
```powershell
php artisan key:generate
```

### Error: "Could not find driver"
- Enable extension `php_pdo_mysql` di `php.ini`

### Port 8000 sudah digunakan
```powershell
php artisan serve --port=8001
```

---

## 📞 Support

Jika ada error atau masalah:
1. Pastikan PHP >= 8.1
2. Pastikan semua extensions PHP aktif (PDO, mbstring, openssl, dll)
3. Jalankan `composer install` ulang
4. Clear cache: `php artisan cache:clear`

---

## 🎯 Next Steps

Setelah aplikasi berjalan:
1. ✅ Login sebagai admin
2. ✅ Akses dashboard untuk melihat statistics
3. ✅ Cek Sales Report dengan filter
4. ✅ Cek Users Report dengan pagination
5. ✅ Test semua charts dan export functionality

---

**Selamat Coding! 🚀**
