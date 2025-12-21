# Sukabaca - Setup & Deployment Guide

## Local Development Setup

### Prerequisites

-   PHP 8.2+
-   Composer 2.x
-   MySQL 8.0+ / MariaDB 10.6+
-   Node.js 18+ & npm
-   Git

### Installation Steps

```bash
# 1. Clone repository
git clone <repo-url> sukabaca
cd sukabaca

# 2. Install PHP dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Configure database in .env
# Edit DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 6. Run migrations
php artisan migrate

# 7. Seed database (optional)
php artisan db:seed

# 8. Install frontend dependencies
npm install

# 9. Build assets
npm run dev

# 10. Start development server
php artisan serve
```

---

## Environment Configuration

### .env File

```env
APP_NAME=Sukabaca
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sukabaca
DB_USERNAME=root
DB_PASSWORD=

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Queue (optional)
QUEUE_CONNECTION=sync

# Mail (optional)
MAIL_MAILER=log
```

---

## Database Setup

### Create Database (MySQL)

```sql
CREATE DATABASE sukabaca CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'sukabaca'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON sukabaca.* TO 'sukabaca'@'localhost';
FLUSH PRIVILEGES;
```

### Run Migrations

```bash
# Fresh migration (drop all tables)
php artisan migrate:fresh

# Fresh migration with seeding
php artisan migrate:fresh --seed

# Regular migration (add new tables)
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Check migration status
php artisan migrate:status
```

---

## Development Commands

### Artisan Commands

```bash
# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# List routes
php artisan route:list

# Create model with migration, factory, seeder
php artisan make:model ModelName -mfs

# Create controller
php artisan make:controller Admin/BookController --resource

# Create request
php artisan make:request StoreBookRequest

# Create service
php artisan make:class Services/BookService

# Create middleware
php artisan make:middleware CheckRole

# Run tests
php artisan test
```

### npm Commands

```bash
# Development (with hot reload)
npm run dev

# Production build
npm run build
```

---

## Production Deployment

### Server Requirements

-   PHP 8.2+ with extensions: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
-   MySQL 8.0+ / MariaDB 10.6+
-   Nginx / Apache
-   Composer
-   Node.js (for build only)

### Deployment Steps

```bash
# 1. Upload files to server

# 2. Install dependencies (production)
composer install --optimize-autoloader --no-dev

# 3. Configure .env
cp .env.example .env
php artisan key:generate
# Edit .env with production settings

# 4. Set permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 5. Run migrations
php artisan migrate --force

# 6. Build frontend assets
npm install
npm run build

# 7. Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 8. Restart queue workers (if using)
php artisan queue:restart
```

### Nginx Configuration

```nginx
server {
    listen 80;
    server_name sukabaca.com;
    root /var/www/sukabaca/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## Troubleshooting

### Common Issues

**1. 500 Server Error**

```bash
# Check storage permissions
chmod -R 775 storage bootstrap/cache

# Check logs
tail -f storage/logs/laravel.log
```

**2. Class not found**

```bash
composer dump-autoload
php artisan clear-compiled
```

**3. Config changes not applied**

```bash
php artisan config:clear
php artisan cache:clear
```

**4. Migration failed**

```bash
# Check database connection
php artisan db:show

# Reset and remigrate
php artisan migrate:fresh
```

**5. Asset changes not showing**

```bash
npm run build
php artisan view:clear
```

---

## Backup & Restore

### Database Backup

```bash
# Backup
mysqldump -u root -p sukabaca > backup_$(date +%Y%m%d).sql

# Restore
mysql -u root -p sukabaca < backup_20241221.sql
```

### Full Application Backup

```bash
# Exclude vendor, node_modules, .git
tar -czvf sukabaca_backup.tar.gz \
    --exclude='vendor' \
    --exclude='node_modules' \
    --exclude='.git' \
    /var/www/sukabaca
```
