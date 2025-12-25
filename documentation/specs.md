# Sukabaca - Technical Specifications (The Blueprint)

> Dokumen ini berisi SEMUA detail teknis dan fitur yang harus diimplementasikan. Referensi utama untuk "apa yang harus dibikin".

---

## 1. Project Overview

Sukabaca adalah **sistem manajemen perpustakaan sederhana** untuk bisnis rental buku skala kecil hingga menengah.

### MVP Features

| Module          | Kompleksitas | Deskripsi                                |
| --------------- | ------------ | ---------------------------------------- |
| Authentication  | Low          | Admin/User login, registration, session  |
| Book Management | Medium       | CRUD books, copy tracking, availability  |
| Catalog (User)  | Low          | Browse books, search, filter by category |
| Borrowing       | Medium       | Borrow/return flow, auto-assign copies   |
| Fees & Fines    | Low          | Dynamic rental fee, late fee             |
| Admin Dashboard | Low          | Statistics, recent borrowings, top books |
| User Dashboard  | Low          | Active borrowings, history, fees summary |

### Target Users

| User Type | Role                | Access                                        |
| --------- | ------------------- | --------------------------------------------- |
| **Admin** | Library staff/owner | Full system access, manage books & borrowings |
| **User**  | Library members     | Browse catalog, borrow books, view history    |

---

## 2. Pages & Routes

### User/Member Pages (Blade Templates)

| Page             | Route            | Description                                        |
| ---------------- | ---------------- | -------------------------------------------------- |
| Landing Page     | `/`              | Homepage with hero, featured books, call-to-action |
| Login            | `/login`         | User login form                                    |
| Register         | `/register`      | New member registration                            |
| Book Catalog     | `/books`         | Browse all books with search & category filter     |
| Book Detail      | `/books/{slug}`  | Book details + borrow button + availability        |
| User Dashboard   | `/dashboard`     | Overview: active borrowings, fees summary          |
| My Borrowings    | `/my-borrowings` | List of active & historical borrowings             |
| Profile Settings | `/profile`       | Edit profile info, change password                 |

### Admin Pages (Filament Panel)

| Page             | Route                | Description                       |
| ---------------- | -------------------- | --------------------------------- |
| Admin Dashboard  | `/admin`             | Stats cards, recent borrowings    |
| Books Management | `/admin/books`       | CRUD books with copy management   |
| Book Copies      | `/admin/book-copies` | Manage individual copies per book |
| Categories       | `/admin/categories`  | CRUD book categories              |
| Borrowings       | `/admin/borrowings`  | Process returns, mark payments    |
| Users            | `/admin/users`       | View & manage library members     |
| Settings         | `/admin/settings`    | Configure late fee rate           |

---

## 3. Authentication

### Overview

| Feature            | Status      |
| ------------------ | ----------- |
| User Registration  | ✅ Enabled  |
| Login/Logout       | ✅ Enabled  |
| Email Verification | ❌ Disabled |
| Password Reset     | ✅ Optional |
| Remember Me        | ✅ Enabled  |

### User Roles

| Role    | Access Level | Description                                |
| ------- | ------------ | ------------------------------------------ |
| `admin` | Full access  | Manage books, borrowings, users, settings  |
| `user`  | Limited      | Browse catalog, borrow books, view history |

### Registration Fields

| Field    | Type   | Validation       | Required |
| -------- | ------ | ---------------- | -------- |
| name     | string | max:255          | ✅       |
| email    | email  | unique:users     | ✅       |
| password | string | min:8, confirmed | ✅       |
| phone    | string | max:20           | ❌       |

### Middleware

```php
// Auth Middleware
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});

// Admin Middleware (Filament handles this automatically)
// Access via /admin/* routes
```

---

## 4. Blade Components

### Layout Structure

```
resources/views/
├── layouts/
│   └── app.blade.php           # Main layout
├── components/
│   ├── navbar.blade.php        # Top navigation
│   ├── footer.blade.php        # Footer
│   ├── book-card.blade.php     # Book card for catalog
│   ├── borrowing-card.blade.php
│   └── stats-card.blade.php
├── livewire/
│   ├── book-catalog.blade.php  # Catalog dengan search/filter
│   ├── book-detail.blade.php   # Book detail + borrow
│   └── user-borrowings.blade.php
└── pages/
    ├── home.blade.php
    ├── catalog.blade.php
    ├── book-detail.blade.php
    └── dashboard.blade.php
```

### Key Blade Components

| Component      | Props                         | Description           |
| -------------- | ----------------------------- | --------------------- |
| `x-book-card`  | :book                         | Book card for catalog |
| `x-stats-card` | title, value, icon, color     | Dashboard stat card   |
| `x-badge`      | type (success/warning/danger) | Status badge          |
| `x-modal`      | name, title                   | Modal dialog          |
| `x-alert`      | type, message                 | Notification alert    |

### Livewire Components

| Component        | Description                         |
| ---------------- | ----------------------------------- |
| `BookCatalog`    | Catalog dengan live search & filter |
| `BookDetail`     | Book detail + borrow action         |
| `UserBorrowings` | User borrowing list                 |
| `BorrowModal`    | Modal konfirmasi pinjam             |

---

## 5. User Flows

### Flow 1: User Borrows a Book

```
User Login → Browse Catalog → Click Book → Book Available?
    → Yes: Click Borrow → Select Duration → Confirm
    → System Assigns Copy → Show Borrowing Code + Due Date
    → User Pays Admin → Admin Marks Paid
```

### Flow 2: Admin Processes Return

```
Admin Login → Borrowings Page → Filter Active/Overdue
→ Find Borrowing → Click Return
→ Is Overdue? → Calculate Late Fee → Update Total Fee
→ Set Copy Available → Status = Returned
```

### Flow 3: User Registration

```
Visit Register → Fill Form → Submit
→ Validation OK? → Create Account → Auto Login → Dashboard
```

### Status Badges

| Status   | Color     | Description        |
| -------- | --------- | ------------------ |
| Active   | 🟢 Green  | Currently borrowed |
| Overdue  | 🔴 Red    | Past due date      |
| Returned | 🔵 Blue   | Already returned   |
| Unpaid   | 🟡 Yellow | Payment pending    |

### Business Rules

| Rule                | Description                       |
| ------------------- | --------------------------------- |
| One copy per borrow | User borrows a specific copy      |
| Auto-assign         | System picks first available copy |
| Duration options    | 7 days default                    |
| Late fee daily      | 10% × hari × harga sewa           |
| Manual payment      | Admin confirms payment receipt    |
| No reservations     | Cannot reserve out-of-stock books |
| Single category     | Each book has one category only   |

### Fee Calculations (dari Brief)

| Jenis Denda   | Perhitungan                       |
| ------------- | --------------------------------- |
| Keterlambatan | 10% × hari terlambat × harga sewa |
| Rusak         | 50% × harga buku                  |
| Hilang        | 75% × harga buku                  |

### Perpanjangan Rules

| Rule                  | Value                                 |
| --------------------- | ------------------------------------- |
| Maksimal perpanjangan | 1x per peminjaman                     |
| Biaya perpanjangan    | Sama dengan harga sewa awal           |
| Syarat perpanjangan   | Tidak terlambat, di hari H (deadline) |

### Borrowing Validation

| Validasi         | Kondisi Block              |
| ---------------- | -------------------------- |
| Limit buku aktif | Max 2 buku aktif per user  |
| Tunggakan        | Ada pembayaran belum lunas |
| Status user      | User suspended             |
| Ketersediaan     | Tidak ada copy available   |

### SEO & Performance

| Item               | Requirement                    |
| ------------------ | ------------------------------ |
| Meta tags          | Title, description per halaman |
| Sitemap            | Auto-generate sitemap.xml      |
| Responsive         | Mobile, tablet, desktop        |
| Loading states     | Skeleton/spinner saat loading  |
| Image optimization | Lazy loading, proper sizing    |

---

## 6. Admin Panel (Filament v3)

### Filament Resources

| Resource            | Model     | Features                              |
| ------------------- | --------- | ------------------------------------- |
| `BookResource`      | Book      | CRUD, image upload, copy management   |
| `CategoryResource`  | Category  | CRUD, book count badge                |
| `BorrowingResource` | Borrowing | List, return action, mark paid action |
| `UserResource`      | User      | List, view borrowings, role badge     |

### Admin Dashboard Widgets

| Widget             | Description            |
| ------------------ | ---------------------- |
| `StatsOverview`    | Total buku, copies     |
| `RecentBorrowings` | 10 peminjaman terakhir |
| `TopBooks`         | Buku paling populer    |
| `OverdueAlerts`    | Alert terlambat        |

### Admin Routes (Auto-generated by Filament)

| Route               | Description          |
| ------------------- | -------------------- |
| `/admin`            | Dashboard overview   |
| `/admin/books`      | Book management      |
| `/admin/categories` | Category management  |
| `/admin/borrowings` | Borrowing management |
| `/admin/users`      | User management      |

### Books Management Table

| Column         | Sortable | Searchable |
| -------------- | -------- | ---------- |
| Image          | ❌       | ❌         |
| Judul          | ✅       | ✅         |
| Penulis        | ✅       | ✅         |
| Kategori       | ✅       | ✅         |
| Tersedia/Total | ✅       | ❌         |
| Biaya Sewa     | ✅       | ❌         |

### Settings (Filament Settings Page)

| Key                | Label              | Type     |
| ------------------ | ------------------ | -------- |
| late_fee_per_day   | Denda per Hari     | Number   |
| max_borrow_days    | Maks Hari Pinjam   | Number   |
| max_books_per_user | Maks Buku per User | Number   |
| library_name       | Nama Perpustakaan  | Text     |
| library_address    | Alamat             | Textarea |

---

## 7. Client Requirements (BRIEF-CLIENT-FINAL)

> Referensi dari brief klien dengan estimasi waktu per fitur.

### Fitur Utama

| No  | Fitur                   | Estimasi |
| --- | ----------------------- | -------- |
| 1   | Sistem Autentikasi      | 16 jam   |
| 2   | User Management         | 32 jam   |
| 3   | Manajemen Kategori Buku | 32 jam   |
| 4   | Manajemen Buku          | 64 jam   |
| 5   | Wishlist                | 16 jam   |
| 6   | Sistem Sewa Buku        | 96 jam   |
| 7   | Sistem Pembayaran       | 40 jam   |
| 8   | Dashboard               | 48 jam   |
| 9   | Laporan & Analytics     | 64 jam   |
| 10  | Homepage & Landing      | 40 jam   |

### Out of Scope

-   Integrasi payment gateway (Midtrans/Xendit)
-   Mobile app native (Android/iOS)
-   Barcode/QR scanner
-   WhatsApp notification otomatis
-   API untuk sistem eksternal

### Teknologi

**Stack:** Laravel 11 + Blade + Alpine.js + Tailwind CSS + Livewire + Filament v3  
**Durasi:** 7 hari kerja
