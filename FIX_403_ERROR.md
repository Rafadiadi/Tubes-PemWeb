# 🔐 Fix 403 Unauthorized Error

## Masalah yang Terjadi
Error 403 "Unauthorized action" muncul saat mencoba mengakses dashboard atau reports.

## ✅ Solusi yang Sudah Diterapkan

### 1. Update Admin User Role
```powershell
docker-compose exec app php artisan db:seed --class=AdminSeeder
```

Seeder ini akan:
- ✅ Update role user menjadi 'admin'
- ✅ Update password menjadi 'password123'
- ✅ Verifikasi email

### 2. Clear All Caches
```powershell
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
```

### 3. Logout & Login Ulang

**PENTING:** Setelah update role, Anda HARUS logout dan login ulang!

1. Logout: http://localhost:8000/logout
2. Login: http://localhost:8000/login
3. Gunakan credentials:
   - Email: `admin@example.com`
   - Password: `password123`

---

## 🔍 Cara Memeriksa Role User

### Cek via Tinker:
```powershell
docker-compose exec app php artisan tinker
```

Kemudian di Tinker:
```php
$user = User::where('email', 'admin@example.com')->first();
echo "Role: " . $user->role;
```

### Cek Semua Users:
```powershell
docker-compose exec app php artisan tinker --execute="User::all(['id', 'name', 'email', 'role']);"
```

---

## 🛠️ Manual Fix (Jika Masih Error)

### Opsi 1: Update via Tinker
```powershell
docker-compose exec app php artisan tinker
```

```php
$user = User::where('email', 'admin@example.com')->first();
$user->role = 'admin';
$user->save();
echo "User role updated!";
exit;
```

### Opsi 2: Update via SQL
```powershell
docker-compose exec mysql mysql -u laravel -psecret tubes_pemweb
```

```sql
UPDATE users SET role = 'admin' WHERE email = 'admin@example.com';
SELECT id, name, email, role FROM users WHERE email = 'admin@example.com';
exit;
```

### Opsi 3: Hapus Session Files
```powershell
docker-compose exec app rm -rf storage/framework/sessions/*
docker-compose exec app php artisan session:flush
```

---

## 🎯 Verifikasi Setelah Fix

### 1. Cek Role Database
```powershell
docker-compose exec app php artisan db:seed --class=AdminSeeder
```

Output harus menunjukkan:
```
+----+-------+-------------------+-------+
| ID | Name  | Email             | Role  |
+----+-------+-------------------+-------+
| 1  | Admin | admin@example.com | admin |
+----+-------+-------------------+-------+
```

### 2. Cek Routes
```powershell
docker-compose exec app php artisan route:list --name=dashboard
```

Output harus menunjukkan middleware `auth,role:admin`

### 3. Test Akses
1. Logout: http://localhost:8000/logout
2. Login: http://localhost:8000/login
3. Dashboard: http://localhost:8000/dashboard ✅ Harus berhasil

---

## 🔐 User Roles di Sistem

### Admin
- **Role**: `admin`
- **Akses**: Dashboard, Reports, All Features
- **Email**: admin@example.com
- **Password**: password123

### Doctor
- **Role**: `doctor`
- **Akses**: Medical features (jika ada)

### Patient
- **Role**: `patient`
- **Akses**: Basic features

---

## 📝 Catatan Penting

1. **Session Issue**: Jika sudah login sebelum update role, HARUS logout dan login ulang
2. **Cache Issue**: Clear cache setelah perubahan middleware atau routes
3. **Case Sensitive**: Role harus lowercase: `admin`, `doctor`, `patient`
4. **Database**: Pastikan kolom `role` ada di tabel users

---

## 🚨 Troubleshooting Lanjutan

### Masalah: Role column tidak ada
```powershell
# Cek struktur tabel
docker-compose exec mysql mysql -u laravel -psecret tubes_pemweb -e "DESCRIBE users;"
```

Jika kolom `role` tidak ada, tambahkan migration:
```powershell
docker-compose exec app php artisan make:migration add_role_to_users_table
```

### Masalah: Middleware tidak terdaftar
Cek `bootstrap/app.php` harus ada:
```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ]);
})
```

### Masalah: Auth tidak berfungsi
```powershell
# Clear semua
docker-compose exec app php artisan optimize:clear

# Restart container
docker-compose restart app
```

---

## ✅ Checklist Penyelesaian

- [ ] Admin user memiliki role 'admin'
- [ ] Cache sudah di-clear
- [ ] Logout dari session lama
- [ ] Login ulang dengan admin credentials
- [ ] Dashboard bisa diakses tanpa error 403
- [ ] Reports bisa diakses

---

**🎉 Setelah mengikuti langkah di atas, aplikasi harus berjalan normal!**

Login: http://localhost:8000/login
Dashboard: http://localhost:8000/dashboard
