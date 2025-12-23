# Admin Panel (React.js)

## Overview

The admin panel is built using **React.js + TypeScript**, replacing Laravel Filament with a custom SPA admin interface.

### Why React.js Admin?

| Benefit          | Description                           |
| ---------------- | ------------------------------------- |
| Consistent stack | Same tech as user-facing frontend     |
| Full control     | Custom UI/UX based on provided design |
| Better UX        | SPA experience, faster navigation     |
| Type safety      | TypeScript end-to-end                 |
| State management | Zustand for complex admin workflows   |

---

## Tech Stack

| Technology      | Purpose                  |
| --------------- | ------------------------ |
| React.js 18     | UI framework             |
| TypeScript      | Type safety              |
| React Router    | Client-side routing      |
| TanStack Query  | Server state management  |
| TanStack Table  | Data tables with sorting |
| React Hook Form | Form handling            |
| Zod             | Form validation          |
| Zustand         | Global state             |
| Tailwind CSS    | Styling                  |
| Axios           | API calls                |

---

## Admin Panel Structure

```
frontend/src/pages/admin/
├── AdminLayout.tsx           # Admin layout wrapper
├── AdminDashboard.tsx        # Dashboard with stats
├── books/
│   ├── BooksPage.tsx         # Books list + CRUD
│   ├── BookForm.tsx          # Create/Edit form
│   └── BookImageUpload.tsx   # Image upload component
├── categories/
│   ├── CategoriesPage.tsx    # Categories list
│   └── CategoryForm.tsx      # Create/Edit form
├── borrowings/
│   ├── BorrowingsPage.tsx    # All borrowings
│   └── BorrowingActions.tsx  # Return, Mark Paid
├── users/
│   ├── UsersPage.tsx         # Users list
│   └── UserDetail.tsx        # User detail + borrowings
└── settings/
    └── SettingsPage.tsx      # System settings
```

---

## Pages & Features

### 1. Admin Dashboard

**Widgets/Stats:**
| Widget | Description |
|--------|-------------|
| Total Buku | Number of unique titles |
| Total Eksemplar | All physical copies |
| Tersedia | Copies available |
| Sedang Dipinjam | Active borrowings |
| Total Anggota | Registered members |
| Belum Dibayar | Unpaid fees count |

**Components:**

-   `StatsCards` - Grid of stat cards
-   `RecentBorrowings` - Last 10 borrowings table
-   `TopBooks` - Top 5 most borrowed (chart)

---

### 2. Books Management

**Features:**
| Feature | Description |
|---------|-------------|
| List | Paginated table with search & filter |
| Create | Form with image upload |
| Edit | Update book + replace image |
| Delete | Soft confirmation modal |
| Copies | Manage book copies inline |

**Table Columns:**
| Column | Sortable | Searchable |
|--------|----------|------------|
| Image | ❌ | ❌ |
| Judul | ✅ | ✅ |
| Penulis | ✅ | ✅ |
| Kategori | ✅ | ✅ |
| Tersedia/Total | ✅ | ❌ |
| Biaya Sewa | ✅ | ❌ |
| Actions | ❌ | ❌ |

**Form Fields:**
| Field | Type | Required |
|-------|------|----------|
| Gambar Cover | FileUpload | ❌ |
| Judul | TextInput | ✅ |
| Penulis | TextInput | ✅ |
| Kategori | Select | ✅ |
| Deskripsi | Textarea | ❌ |
| Biaya Sewa | NumberInput | ✅ |
| Jumlah Eksemplar | NumberInput | ✅ (create only) |

---

### 3. Categories Management

**Table Columns:**
| Column | Description |
|--------|-------------|
| Nama | Category name |
| Deskripsi | Description |
| Jumlah Buku | Books count badge |
| Actions | Edit, Delete |

---

### 4. Borrowings Management

**Table Columns:**
| Column | Description |
|--------|-------------|
| Kode | Borrowing code |
| Anggota | User name |
| Buku | Book title |
| Kode Eksemplar | Copy code |
| Tgl Pinjam | Borrowed date |
| Jatuh Tempo | Due date |
| Status | Badge (Aktif/Dikembalikan/Terlambat) |
| Lunas | Icon (✅/❌) |
| Actions | Return, Mark Paid |

**Filters:**
| Filter | Type |
|--------|------|
| Status | Select (Semua/Aktif/Dikembalikan/Terlambat) |
| Pembayaran | Select (Semua/Lunas/Belum Lunas) |
| Tanggal | DateRange picker |

**Actions:**
| Action | Visible When | Description |
|--------|--------------|-------------|
| Kembalikan | status = active | Process return, calculate late fee |
| Tandai Lunas | is_paid = false | Mark as paid |

---

### 5. Users Management

**Table Columns:**
| Column | Description |
|--------|-------------|
| Nama | User name |
| Email | Email address |
| Telepon | Phone number |
| Role | Badge (Admin/User) |
| Peminjaman | Active borrowings count |
| Actions | View Detail |

---

### 6. Settings Page

**Editable Settings:**
| Key | Label | Type |
|-----|-------|------|
| late_fee_per_day | Denda per Hari | Number (Rp) |
| max_borrow_days | Maks Hari Pinjam | Number |
| max_books_per_user | Maks Buku per User | Number |
| library_name | Nama Perpustakaan | Text |
| library_address | Alamat | Textarea |

---

## Admin Routes

| Route                   | Component      | Description          |
| ----------------------- | -------------- | -------------------- |
| `/admin`                | AdminDashboard | Dashboard overview   |
| `/admin/books`          | BooksPage      | Book management      |
| `/admin/books/create`   | BookForm       | Create book          |
| `/admin/books/:id/edit` | BookForm       | Edit book            |
| `/admin/categories`     | CategoriesPage | Category management  |
| `/admin/borrowings`     | BorrowingsPage | Borrowing management |
| `/admin/users`          | UsersPage      | User management      |
| `/admin/users/:id`      | UserDetail     | User detail          |
| `/admin/settings`       | SettingsPage   | System settings      |

---

## Access Control

Admin routes are protected by:

1. **Laravel Sanctum** - API authentication
2. **Role Middleware** - Check `user.role === 'admin'`
3. **React Router Guard** - Redirect if not admin

```typescript
// frontend/src/guards/AdminGuard.tsx
export function AdminGuard({ children }: { children: React.ReactNode }) {
    const { user, isLoading } = useAuth();

    if (isLoading) return <LoadingSpinner />;
    if (!user || user.role !== "admin") {
        return <Navigate to="/login" replace />;
    }

    return <>{children}</>;
}
```

---

## API Endpoints for Admin

```
# Books (Admin)
GET    /api/books              # List with pagination
POST   /api/books              # Create with image upload
PUT    /api/books/{id}         # Update
DELETE /api/books/{id}         # Delete
POST   /api/books/{id}/image   # Upload/replace image

# Book Copies
GET    /api/books/{id}/copies
POST   /api/books/{id}/copies
PUT    /api/copies/{id}
DELETE /api/copies/{id}

# Categories
POST   /api/categories
PUT    /api/categories/{id}
DELETE /api/categories/{id}

# Borrowings
POST   /api/borrowings/{id}/return    # Process return
PATCH  /api/borrowings/{id}/paid      # Mark as paid

# Users
GET    /api/users
GET    /api/users/{id}

# Settings
GET    /api/settings
PUT    /api/settings/{key}

# Dashboard
GET    /api/admin/dashboard    # Admin stats
```
