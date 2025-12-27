# Production Deployment Guide

## Performance Optimization Commands

### Cache Commands

Run these commands before deploying to production:

```bash
# Cache routes for faster route resolution
php artisan route:cache

# Cache views for faster rendering
php artisan view:cache

# Cache config files
php artisan config:cache

# Cache events
php artisan event:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

### Database Optimization

```bash
# Run the performance indexes migration
php artisan migrate

# This will add indexes on:
# - borrowings: user_id+status, status+due_date, borrowed_at
# - payments: status, status+verified_at
# - books: times_borrowed, available_copies
```

### Clear All Caches (when updating)

```bash
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:clear
```

### Asset Minification

Assets are automatically minified when running:

```bash
npm run build
```

This uses Vite to:

-   Minify JavaScript and CSS
-   Tree-shake unused code
-   Generate hashed filenames for cache busting

### Image Optimization

For uploaded images, consider using:

-   Intervention Image package for on-the-fly compression
-   WebP conversion for modern browsers
-   Lazy loading on frontend (already implemented)

### Caching Strategy Implemented

| Component            | Cache Duration | Cache Key              |
| -------------------- | -------------- | ---------------------- |
| StatsOverview Widget | 5 minutes      | `admin_stats_overview` |
| Settings             | 1 hour         | `setting_{key}`        |

### Environment Settings for Production

```env
APP_ENV=production
APP_DEBUG=false
CACHE_DRIVER=redis  # or file
SESSION_DRIVER=redis  # or database
QUEUE_CONNECTION=redis  # or database
```

### Recommended Server Configuration

-   **PHP 8.2+** with OPcache enabled
-   **Redis** for caching and sessions
-   **MySQL 8.0+** with query cache
-   **Nginx** with gzip compression enabled
