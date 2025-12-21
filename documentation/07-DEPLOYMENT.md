# Deployment Guide

> **Note**: This guide is written for beginners using shared hosting. For production at scale, consider VPS or managed services.

## Recommended Hosting Options

| Option               | Monthly Cost | Difficulty  | Best For         |
| -------------------- | ------------ | ----------- | ---------------- |
| **Hostinger**        | ~$3-5        | ⭐ Easy     | MVP, small scale |
| Niagahoster          | ~$3-5        | ⭐ Easy     | Indonesia-based  |
| DigitalOcean + Forge | ~$12+        | ⭐⭐ Medium | Scaling up       |
| Manual VPS           | ~$5-10       | ⭐⭐⭐ Hard | Full control     |

---

## Shared Hosting Deployment (Hostinger)

### Prerequisites

-   Hostinger Business/Premium plan (PHP 8.1+, MySQL)
-   Domain pointed to Hostinger
-   FileZilla or similar FTP client

### Step 1: Prepare for Production

```bash
# 1. Set environment to production
APP_ENV=production
APP_DEBUG=false

# 2. Generate production key
php artisan key:generate

# 3. Optimize application
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Build frontend assets
npm run build
```

### Step 2: Create Production .env

```env
APP_NAME=Sukabaca
APP_ENV=production
APP_KEY=base64:YOUR_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

### Step 3: Upload Files to Hostinger

**Structure on Hostinger:**

```
public_html/           <- Contents of Laravel's /public folder
├── index.php          (modified)
├── .htaccess
├── css/
├── js/
└── storage/           (symlink to ../app/storage/app/public)

your_app_folder/       <- Everything else (above public_html)
├── app/
├── bootstrap/
├── config/
├── database/
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env
└── artisan
```

### Step 4: Modify index.php

Edit `public/index.php` to point to the correct paths:

```php
<?php

// Change these paths
require __DIR__.'/../your_app_folder/vendor/autoload.php';
$app = require_once __DIR__.'/../your_app_folder/bootstrap/app.php';
```

### Step 5: Create Database

1. Login to Hostinger hPanel
2. Go to Databases → MySQL Databases
3. Create new database
4. Create database user
5. Add user to database with all privileges
6. Update `.env` with credentials

### Step 6: Run Migrations

Via SSH (if available):

```bash
cd ~/your_app_folder
php artisan migrate --force
php artisan db:seed --force
```

Or via Hostinger's PHP terminal.

### Step 7: Set Permissions

```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### Step 8: Create Storage Symlink

```bash
php artisan storage:link
```

---

## Checklist Before Go-Live

| Task                      | Status |
| ------------------------- | ------ |
| APP_DEBUG = false         | ⬜     |
| APP_ENV = production      | ⬜     |
| Database migrated         | ⬜     |
| Admin user created        | ⬜     |
| SSL certificate installed | ⬜     |
| Storage symlink created   | ⬜     |
| Cache optimized           | ⬜     |
| Error logging configured  | ⬜     |

---

## Troubleshooting

### Error 500

1. Enable debug temporarily to see error
2. Check `storage/logs/laravel.log`
3. Verify file permissions

### Database Connection Failed

1. Verify `.env` credentials
2. Check if database exists
3. Ensure user has proper privileges

### Assets Not Loading

1. Run `npm run build`
2. Check asset paths in blade files
3. Verify Vite manifest exists

### Storage Issues

1. Run `php artisan storage:link`
2. Check folder permissions
3. Verify symlink path in `filesystems.php`

---

## Backup Strategy

| What         | Frequency      | How                                  |
| ------------ | -------------- | ------------------------------------ |
| Database     | Daily          | Hostinger auto-backup or `mysqldump` |
| User uploads | Weekly         | FTP download `/storage/app/public`   |
| Full app     | Before updates | Download entire folder               |

---

## Upgrading

```bash
# 1. Enable maintenance mode
php artisan down

# 2. Pull/upload new code
git pull origin main
# or FTP upload

# 3. Update dependencies
composer install --no-dev --optimize-autoloader

# 4. Run new migrations
php artisan migrate --force

# 5. Clear caches
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Disable maintenance mode
php artisan up
```
