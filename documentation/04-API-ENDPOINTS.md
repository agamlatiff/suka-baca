# API Endpoints

## Overview

All API endpoints are RESTful and return JSON responses. Authentication uses **Laravel Sanctum** tokens.

**Base URL:** `/api`

---

## Authentication

### Headers

All authenticated endpoints require:

```
Authorization: Bearer {token}
```

### Endpoints

| Method | Endpoint             | Description           | Auth |
| ------ | -------------------- | --------------------- | ---- |
| POST   | `/api/auth/login`    | Login & get token     | ❌   |
| POST   | `/api/auth/register` | Register new user     | ❌   |
| POST   | `/api/auth/logout`   | Logout & revoke token | ✅   |
| GET    | `/api/auth/user`     | Get current user      | ✅   |

#### POST `/api/auth/login`

**Request:**

```json
{
    "email": "user@example.com",
    "password": "password"
}
```

**Response (200):**

```json
{
    "user": {
        "id": 1,
        "name": "User Name",
        "email": "user@example.com",
        "role": "user",
        "phone": "081234567890"
    },
    "token": "1|abc123..."
}
```

#### POST `/api/auth/register`

**Request:**

```json
{
    "name": "New User",
    "email": "newuser@example.com",
    "password": "password",
    "password_confirmation": "password",
    "phone": "081234567890"
}
```

---

## Books

| Method | Endpoint                 | Description            | Auth | Role  |
| ------ | ------------------------ | ---------------------- | ---- | ----- |
| GET    | `/api/books`             | List books (paginated) | ✅   | Any   |
| GET    | `/api/books/{id}`        | Get book detail        | ✅   | Any   |
| POST   | `/api/books`             | Create book            | ✅   | Admin |
| PUT    | `/api/books/{id}`        | Update book            | ✅   | Admin |
| DELETE | `/api/books/{id}`        | Delete book            | ✅   | Admin |
| POST   | `/api/books/{id}/borrow` | Borrow book            | ✅   | User  |

#### GET `/api/books`

**Query Parameters:**
| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| search | string | null | Search title/author |
| category_id | int | null | Filter by category |
| available | bool | null | Only available books |
| sort | string | title | Sort field |
| order | string | asc | Sort order |
| page | int | 1 | Page number |
| per_page | int | 12 | Items per page |

**Response (200):**

```json
{
    "data": [
        {
            "id": 1,
            "title": "Laskar Pelangi",
            "author": "Andrea Hirata",
            "image": "/storage/books/1_1703345678.jpg",
            "category": {
                "id": 1,
                "name": "Fiksi"
            },
            "description": "Novel inspiratif...",
            "rental_fee": 5000,
            "total_copies": 3,
            "available_copies": 2,
            "times_borrowed": 25
        }
    ],
    "meta": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 12,
        "total": 60
    }
}
```

#### POST `/api/books` (Admin)

**Request (multipart/form-data):**
| Field | Type | Required |
|-------|------|----------|
| title | string | ✅ |
| author | string | ✅ |
| category_id | int | ✅ |
| image | file | ❌ |
| description | string | ❌ |
| rental_fee | number | ✅ |
| copies_count | int | ✅ |

#### POST `/api/books/{id}/borrow`

**Request:**

```json
{
    "duration": 7
}
```

**Response (201):**

```json
{
    "message": "Buku berhasil dipinjam",
    "borrowing": {
        "id": 1,
        "borrowing_code": "BRW-20241223-001",
        "due_date": "2024-12-30",
        "rental_fee": 5000
    }
}
```

---

## Categories

| Method | Endpoint               | Description         | Auth | Role  |
| ------ | ---------------------- | ------------------- | ---- | ----- |
| GET    | `/api/categories`      | List all categories | ✅   | Any   |
| POST   | `/api/categories`      | Create category     | ✅   | Admin |
| PUT    | `/api/categories/{id}` | Update category     | ✅   | Admin |
| DELETE | `/api/categories/{id}` | Delete category     | ✅   | Admin |

#### GET `/api/categories`

**Response (200):**

```json
{
    "data": [
        {
            "id": 1,
            "name": "Fiksi",
            "description": "Novel, cerita pendek...",
            "books_count": 15
        }
    ]
}
```

---

## Borrowings

| Method | Endpoint                      | Description          | Auth | Role  |
| ------ | ----------------------------- | -------------------- | ---- | ----- |
| GET    | `/api/borrowings`             | List borrowings      | ✅   | Any\* |
| GET    | `/api/borrowings/{id}`        | Get borrowing detail | ✅   | Any\* |
| POST   | `/api/borrowings/{id}/return` | Process return       | ✅   | Admin |
| PATCH  | `/api/borrowings/{id}/paid`   | Mark as paid         | ✅   | Admin |

\*User sees only own borrowings, Admin sees all.

#### GET `/api/borrowings`

**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| status | string | active/returned/overdue |
| is_paid | bool | Filter by payment |
| page | int | Page number |

**Response (200):**

```json
{
    "data": [
        {
            "id": 1,
            "borrowing_code": "BRW-20241223-001",
            "user": {
                "id": 1,
                "name": "Budi Santoso"
            },
            "book_copy": {
                "id": 1,
                "copy_code": "BK001-C01",
                "book": {
                    "id": 1,
                    "title": "Laskar Pelangi",
                    "image": "/storage/books/1_1703345678.jpg"
                }
            },
            "borrowed_at": "2024-12-23",
            "due_date": "2024-12-30",
            "returned_at": null,
            "rental_fee": 5000,
            "late_fee": 0,
            "total_fee": 5000,
            "is_paid": false,
            "status": "active",
            "days_remaining": 7
        }
    ]
}
```

#### POST `/api/borrowings/{id}/return`

**Response (200):**

```json
{
    "message": "Buku berhasil dikembalikan",
    "borrowing": {
        "id": 1,
        "returned_at": "2024-12-30",
        "days_late": 0,
        "late_fee": 0,
        "total_fee": 5000,
        "status": "returned"
    }
}
```

---

## Users (Admin)

| Method | Endpoint          | Description     | Auth | Role  |
| ------ | ----------------- | --------------- | ---- | ----- |
| GET    | `/api/users`      | List all users  | ✅   | Admin |
| GET    | `/api/users/{id}` | Get user detail | ✅   | Admin |
| PUT    | `/api/users/{id}` | Update user     | ✅   | Admin |

---

## Settings (Admin)

| Method | Endpoint              | Description      | Auth | Role  |
| ------ | --------------------- | ---------------- | ---- | ----- |
| GET    | `/api/settings`       | Get all settings | ✅   | Admin |
| PUT    | `/api/settings/{key}` | Update setting   | ✅   | Admin |

#### GET `/api/settings`

**Response (200):**

```json
{
    "data": [
        {
            "key": "late_fee_per_day",
            "value": "2000",
            "description": "Denda keterlambatan per hari"
        },
        {
            "key": "max_borrow_days",
            "value": "14",
            "description": "Maksimal hari peminjaman"
        }
    ]
}
```

---

## Dashboard

| Method | Endpoint               | Description           | Auth | Role  |
| ------ | ---------------------- | --------------------- | ---- | ----- |
| GET    | `/api/dashboard/user`  | User dashboard stats  | ✅   | User  |
| GET    | `/api/dashboard/admin` | Admin dashboard stats | ✅   | Admin |

#### GET `/api/dashboard/user`

**Response (200):**

```json
{
  "active_borrowings": 2,
  "due_soon": 1,
  "overdue": 0,
  "total_fees_unpaid": 10000,
  "recent_borrowings": [...]
}
```

#### GET `/api/dashboard/admin`

**Response (200):**

```json
{
  "total_books": 100,
  "total_copies": 350,
  "available_copies": 280,
  "borrowed_copies": 70,
  "total_users": 50,
  "active_borrowings": 25,
  "overdue_borrowings": 3,
  "unpaid_fees": 150000,
  "recent_borrowings": [...],
  "top_books": [...]
}
```

---

## Error Responses

### Validation Error (422)

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["Email sudah terdaftar."],
        "password": ["Password minimal 6 karakter."]
    }
}
```

### Unauthorized (401)

```json
{
    "message": "Unauthenticated."
}
```

### Forbidden (403)

```json
{
    "message": "Akses ditolak."
}
```

### Not Found (404)

```json
{
    "message": "Data tidak ditemukan."
}
```

---

## Route Definitions

### routes/api.php

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BorrowingController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\DashboardController;

// Auth
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user', [AuthController::class, 'user']);

    // Books
    Route::get('/books', [BookController::class, 'index']);
    Route::get('/books/{book}', [BookController::class, 'show']);
    Route::post('/books/{book}/borrow', [BookController::class, 'borrow']);

    // Categories
    Route::get('/categories', [CategoryController::class, 'index']);

    // Borrowings
    Route::get('/borrowings', [BorrowingController::class, 'index']);
    Route::get('/borrowings/{borrowing}', [BorrowingController::class, 'show']);

    // Dashboard
    Route::get('/dashboard/user', [DashboardController::class, 'user']);

    // Admin Routes
    Route::middleware('admin')->group(function () {
        // Books CRUD
        Route::post('/books', [BookController::class, 'store']);
        Route::put('/books/{book}', [BookController::class, 'update']);
        Route::delete('/books/{book}', [BookController::class, 'destroy']);

        // Categories CRUD
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{category}', [CategoryController::class, 'update']);
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

        // Borrowings Actions
        Route::post('/borrowings/{borrowing}/return', [BorrowingController::class, 'return']);
        Route::patch('/borrowings/{borrowing}/paid', [BorrowingController::class, 'markPaid']);

        // Users
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::put('/users/{user}', [UserController::class, 'update']);

        // Settings
        Route::get('/settings', [SettingController::class, 'index']);
        Route::put('/settings/{key}', [SettingController::class, 'update']);

        // Dashboard
        Route::get('/dashboard/admin', [DashboardController::class, 'admin']);
    });
});
```
