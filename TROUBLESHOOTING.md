# 🔧 Troubleshooting Guide - Laravel Docker

## ✅ Masalah yang Sudah Diperbaiki

### 1. ✅ Permission Denied pada Storage/Logs
**Error:**
```
The stream or file "/var/www/html/storage/logs/laravel.log" could not be opened in append mode: Permission denied
```

**Solusi:**
```powershell
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### 2. ✅ Vite Manifest Not Found
**Error:**
```
Vite manifest not found at: /var/www/html/public/build/manifest.json
```

**Solusi:**
```powershell
# Install Node.js (sudah dilakukan)
docker-compose exec app npm install
docker-compose exec app npm run build
```

---

## 🐛 Troubleshooting Umum

### Database Connection Error
**Error:**
```
could not find driver (Connection: mysql)
```

**Solusi:**
```powershell
# Install PHP MySQL extension
docker-compose exec app docker-php-ext-install pdo pdo_mysql

# Restart container
docker-compose restart app
```

### Port Already in Use
**Error:**
```
Bind for 0.0.0.0:8000 failed: port is already allocated
```

**Solusi:**
Edit `docker-compose.yml`:
```yaml
nginx:
  ports:
    - "8001:80"  # Ganti port
```

### Clear All Caches
```powershell
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
```

### Rebuild Containers
```powershell
# Stop dan remove semua
docker-compose down

# Rebuild dari awal
docker-compose build --no-cache

# Start ulang
docker-compose up -d
```

### Reset Database
```powershell
# Drop semua tables dan migrate ulang
docker-compose exec app php artisan migrate:fresh

# Dengan seeder
docker-compose exec app php artisan migrate:fresh --seed
```

### File Permission Issues
```powershell
# Fix permissions untuk semua file Laravel
docker-compose exec app chown -R www-data:www-data /var/www/html
docker-compose exec app chmod -R 755 /var/www/html
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

### Container Won't Start
```powershell
# Lihat logs untuk error
docker-compose logs app
docker-compose logs nginx
docker-compose logs mysql

# Atau follow logs
docker-compose logs -f app
```

### Assets Not Loading (404)
```powershell
# Rebuild Vite assets
docker-compose exec app npm run build

# Atau development mode
docker-compose exec app npm run dev
```

### Composer Install Error
```powershell
# Clear composer cache
docker-compose exec app composer clear-cache

# Install ulang
docker-compose exec app composer install --no-interaction
```

---

## 🚀 Quick Commands

### Start/Stop
```powershell
# Start all containers
docker-compose up -d

# Stop all containers
docker-compose stop

# Restart specific container
docker-compose restart app
docker-compose restart nginx
```

### Laravel Commands
```powershell
# Run artisan command
docker-compose exec app php artisan [command]

# Examples:
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan route:list
docker-compose exec app php artisan tinker
```

### Database Commands
```powershell
# Access MySQL
docker-compose exec mysql mysql -u laravel -psecret tubes_pemweb

# Backup database
docker-compose exec mysql mysqldump -u laravel -psecret tubes_pemweb > backup.sql

# Restore database
docker-compose exec -T mysql mysql -u laravel -psecret tubes_pemweb < backup.sql
```

### View Logs
```powershell
# All logs
docker-compose logs

# Specific service
docker-compose logs app
docker-compose logs nginx
docker-compose logs mysql

# Follow logs (real-time)
docker-compose logs -f app

# Last 100 lines
docker-compose logs --tail=100 app
```

### Container Shell Access
```powershell
# Access app container bash
docker-compose exec app bash

# Access MySQL container
docker-compose exec mysql bash

# Run single command
docker-compose exec app ls -la storage
```

---

## 📝 File Locations

### Laravel Files (in container)
```
/var/www/html/              # Application root
/var/www/html/storage/logs/ # Log files
/var/www/html/public/       # Public files
/var/www/html/.env          # Environment config
```

### Nginx Config
```
/etc/nginx/conf.d/default.conf
```

### MySQL Data
```
/var/lib/mysql/
```

---

## 🔐 Default Credentials

### Admin Login
- Email: `admin@example.com`
- Password: `password123`

### Database
- Host: `mysql` (inside container) atau `127.0.0.1` (from host)
- Port: `3306`
- Database: `tubes_pemweb`
- Username: `laravel`
- Password: `secret`
- Root Password: `root`

---

## 📊 Performance Tips

### Production Optimization
```powershell
# Optimize autoload
docker-compose exec app composer dump-autoload -o

# Cache config
docker-compose exec app php artisan config:cache

# Cache routes
docker-compose exec app php artisan route:cache

# Cache views
docker-compose exec app php artisan view:cache
```

### Development Mode
```powershell
# Clear all caches
docker-compose exec app php artisan optimize:clear

# Run Vite in dev mode (with hot reload)
docker-compose exec app npm run dev
```

---

## 🆘 Emergency Reset

Jika semua gagal, reset total:

```powershell
# 1. Stop dan remove semua (HATI-HATI: data akan hilang)
docker-compose down -v

# 2. Remove images (optional)
docker rmi php:8.2-fpm nginx:alpine mysql:8.0

# 3. Rebuild from scratch
docker-compose build --no-cache

# 4. Start ulang
docker-compose up -d

# 5. Setup Laravel
docker-compose exec app composer install
docker-compose exec app cp .env.example .env
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
docker-compose exec app npm install
docker-compose exec app npm run build

# 6. Fix permissions
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

---

## ✅ Health Check

Untuk memastikan semua berjalan normal:

```powershell
# 1. Check containers running
docker ps

# 2. Check logs for errors
docker-compose logs --tail=50 app

# 3. Test database connection
docker-compose exec app php artisan migrate:status

# 4. Test web server
curl http://localhost:8000

# 5. Check disk space
docker system df
```

---

**🎯 Aplikasi sekarang sudah berjalan dengan baik!**

Akses: http://localhost:8000/login
