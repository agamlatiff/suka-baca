# Authentication Module

## Overview

Authentication is handled by **Laravel Breeze** with session-based authentication.

| Feature            | Status      |
| ------------------ | ----------- |
| User Registration  | ✅ Enabled  |
| Login/Logout       | ✅ Enabled  |
| Email Verification | ❌ Disabled |
| Password Reset     | ✅ Optional |
| Remember Me        | ✅ Enabled  |

---

## User Roles

| Role    | Access Level | Description                                |
| ------- | ------------ | ------------------------------------------ |
| `admin` | Full access  | Manage books, borrowings, users, settings  |
| `user`  | Limited      | Browse catalog, borrow books, view history |

---

## Installation

```bash
# Install Breeze
composer require laravel/breeze --dev

# Install Breeze with Blade
php artisan breeze:install blade

# Install dependencies & compile assets
npm install && npm run dev

# Run migrations
php artisan migrate
```

---

## Routes

### Guest Routes

| Method | URI         | Controller                     | Description            |
| ------ | ----------- | ------------------------------ | ---------------------- |
| GET    | `/login`    | AuthenticatedSessionController | Show login form        |
| POST   | `/login`    | AuthenticatedSessionController | Process login          |
| GET    | `/register` | RegisteredUserController       | Show registration form |
| POST   | `/register` | RegisteredUserController       | Process registration   |

### Authenticated Routes

| Method | URI          | Controller                     | Description    |
| ------ | ------------ | ------------------------------ | -------------- |
| POST   | `/logout`    | AuthenticatedSessionController | Logout user    |
| GET    | `/dashboard` | DashboardController            | User dashboard |

---

## Middleware

### Auth Middleware

Protects routes that require authentication:

```php
// routes/web.php
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/my-borrowings', [BorrowingController::class, 'userBorrowings']);
});
```

### Role Middleware

Custom middleware for admin-only routes:

```php
// app/Http/Middleware/AdminMiddleware.php
public function handle($request, Closure $next)
{
    if (auth()->user()->role !== 'admin') {
        abort(403, 'Unauthorized');
    }
    return $next($request);
}
```

Usage:

```php
// routes/web.php
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Admin routes
});
```

---

## Registration Fields

| Field    | Type   | Validation       | Required |
| -------- | ------ | ---------------- | -------- |
| name     | string | max:255          | ✅       |
| email    | email  | unique:users     | ✅       |
| password | string | min:8, confirmed | ✅       |
| phone    | string | max:20           | ❌       |

### Custom Registration Request

```php
// app/Http/Requests/Auth/RegisterRequest.php
public function rules(): array
{
    return [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'phone' => ['nullable', 'string', 'max:20'],
    ];
}
```

---

## Session Configuration

```php
// config/session.php
'driver' => env('SESSION_DRIVER', 'file'),
'lifetime' => env('SESSION_LIFETIME', 120), // 2 hours
'expire_on_close' => false,
```

---

## Security Best Practices

1. **Password Hashing**: Automatically handled by Breeze
2. **CSRF Protection**: Enabled by default on all POST/PUT/DELETE
3. **Rate Limiting**: Configure in `RouteServiceProvider`
4. **Session Regeneration**: On login/logout

```php
// Rate limiting for login attempts
RateLimiter::for('login', function (Request $request) {
    return Limit::perMinute(5)->by($request->email);
});
```
