# Development Roadmap

## Project Timeline

**Phase 1 (Completed):** Laravel Backend + Filament - 13 working days  
**Phase 2 (New):** React.js Frontend + Service Repository Pattern - 10 working days

**Total Estimated Duration:** 23 working days (~4.5 weeks)

---

## Language Convention

| Area            | Language             | Example                                 |
| --------------- | -------------------- | --------------------------------------- |
| **Code**        | English              | `$book->title`, `function getLateFee()` |
| **Database**    | English              | `books`, `category_id`, `borrowed_at`   |
| **UI/Labels**   | **Bahasa Indonesia** | "Judul Buku", "Tanggal Pinjam", "Denda" |
| **Messages**    | **Bahasa Indonesia** | "Buku berhasil dipinjam!"               |
| **Admin Panel** | **Bahasa Indonesia** | Menu, form labels, notifications        |

> **Target Market:** Indonesia  
> **Semua teks yang tampil ke pengguna harus dalam Bahasa Indonesia.**

---

## Progress Overview

### Phase 1: Laravel Backend + Filament (Completed)

| Phase              | Status      | Days    |
| ------------------ | ----------- | ------- |
| Setup              | ✅ Complete | Day 1   |
| Books & Categories | ✅ Complete | Day 2-3 |
| Book Copies        | ✅ Complete | Day 4-5 |
| User Catalog       | ✅ Complete | Day 6   |
| Borrowing System   | ✅ Complete | Day 7-9 |
| Fees & Fines       | ✅ Complete | Day 10  |
| Admin Dashboard    | ✅ Complete | Day 11  |
| User Dashboard     | ✅ Complete | Day 12  |
| Testing & Polish   | ✅ Complete | Day 13  |

### Phase 2: React.js Frontend + Refactoring (New)

| Phase                       | Status     | Days      |
| --------------------------- | ---------- | --------- |
| Backend Refactoring         | 🔲 Pending | Day 14-15 |
| Frontend Setup              | 🔲 Pending | Day 16    |
| Auth & Layout               | 🔲 Pending | Day 17    |
| Catalog & Detail            | 🔲 Pending | Day 18    |
| User Borrowings & Dashboard | 🔲 Pending | Day 19    |
| Admin Dashboard             | 🔲 Pending | Day 20    |
| Admin Books & Categories    | 🔲 Pending | Day 21    |
| Admin Borrowings & Users    | 🔲 Pending | Day 22    |
| Testing & Polish            | 🔲 Pending | Day 23    |

---

## Phase 2 Detailed Roadmap

### Day 14-15: Backend Refactoring

#### Service Repository Pattern

-   [ ] Create `app/Repositories/Contracts/` interfaces
-   [ ] Create `app/Repositories/Eloquent/` implementations
-   [ ] Create `app/Services/` business logic layer
-   [ ] Create `RepositoryServiceProvider.php`
-   [ ] Refactor existing controllers to use services

#### Database Updates

-   [ ] Create migration: add `image` to books table
-   [ ] Update Book model with image attribute
-   [ ] Create BookImageSeeder with sample images

#### API Controllers

-   [ ] Create `app/Http/Controllers/Api/AuthController.php`
-   [ ] Create `app/Http/Controllers/Api/BookController.php`
-   [ ] Create `app/Http/Controllers/Api/CategoryController.php`
-   [ ] Create `app/Http/Controllers/Api/BorrowingController.php`
-   [ ] Create `app/Http/Controllers/Api/UserController.php`
-   [ ] Create `app/Http/Controllers/Api/SettingController.php`
-   [ ] Create `app/Http/Controllers/Api/DashboardController.php`

#### API Routes & Auth

-   [ ] Configure Laravel Sanctum for SPA
-   [ ] Create admin middleware
-   [ ] Define all API routes in `routes/api.php`

---

### Day 16: Frontend Setup

-   [ ] Create `frontend/` directory
-   [ ] Initialize Vite + React + TypeScript
-   [ ] Configure Tailwind CSS
-   [ ] Setup folder structure
-   [ ] Install dependencies:
    -   react-router-dom
    -   @tanstack/react-query
    -   axios
    -   zustand
    -   zod
-   [ ] Create Axios instance with interceptors
-   [ ] Setup TanStack Query provider
-   [ ] Create base TypeScript types

---

### Day 17: Auth & Layout

#### Authentication

-   [ ] Create `authStore.ts` (Zustand)
-   [ ] Create `authService.ts` (API)
-   [ ] Create `LoginPage.tsx`
-   [ ] Create `RegisterPage.tsx`
-   [ ] Create `AuthGuard.tsx` component
-   [ ] Create `AdminGuard.tsx` component

#### Layout Components

-   [ ] Create `Navbar.tsx` (responsive)
-   [ ] Create `Footer.tsx`
-   [ ] Create `UserLayout.tsx`
-   [ ] Create `AdminLayout.tsx` with sidebar
-   [ ] Create `Sidebar.tsx` (admin)

---

### Day 18: Catalog & Detail

-   [ ] Create UI components: `Button`, `Card`, `Input`, `Badge`
-   [ ] Create `BookCard.tsx`
-   [ ] Create `BookGrid.tsx`
-   [ ] Create `SearchFilter.tsx`
-   [ ] Create `CatalogPage.tsx`
-   [ ] Create `BookDetailPage.tsx`
-   [ ] Create `BorrowModal.tsx`
-   [ ] Implement `useBooks()` hook
-   [ ] Implement `useBook(id)` hook
-   [ ] Implement borrow mutation

---

### Day 19: User Borrowings & Dashboard

#### Borrowings Page

-   [ ] Create `BorrowingCard.tsx`
-   [ ] Create `BorrowingList.tsx`
-   [ ] Create `BorrowingsPage.tsx`
-   [ ] Implement `useBorrowings()` hook

#### User Dashboard

-   [ ] Create `StatsCard.tsx`
-   [ ] Create `DueAlert.tsx`
-   [ ] Create `DashboardPage.tsx`
-   [ ] Implement `useUserDashboard()` hook

---

### Day 20: Admin Dashboard

-   [ ] Create admin stats cards
-   [ ] Create `RecentBorrowingsTable.tsx`
-   [ ] Create `TopBooksChart.tsx` (using Recharts)
-   [ ] Create `AdminDashboard.tsx`
-   [ ] Implement `useAdminDashboard()` hook

---

### Day 21: Admin Books & Categories

#### Books Management

-   [ ] Create `Table` component (TanStack Table)
-   [ ] Create `BooksPage.tsx` with CRUD
-   [ ] Create `BookForm.tsx` with image upload
-   [ ] Create `BookImageUpload.tsx`
-   [ ] Implement book mutations (create, update, delete)

#### Categories Management

-   [ ] Create `CategoriesPage.tsx`
-   [ ] Create `CategoryForm.tsx`
-   [ ] Implement category mutations

---

### Day 22: Admin Borrowings & Users

#### Borrowings Management

-   [ ] Create `AdminBorrowingsPage.tsx`
-   [ ] Create `BorrowingActions.tsx` (return, mark paid)
-   [ ] Implement return and markPaid mutations

#### Users Management

-   [ ] Create `UsersPage.tsx`
-   [ ] Create `UserDetail.tsx`

#### Settings

-   [ ] Create `SettingsPage.tsx`
-   [ ] Implement settings update mutation

---

### Day 23: Testing & Polish

-   [ ] Test all user flows
-   [ ] Test all admin flows
-   [ ] Fix bugs and edge cases
-   [ ] Responsive testing
-   [ ] Performance optimization
-   [ ] Final code review

---

## Milestones

| Milestone | Target | Deliverable                             | Status |
| --------- | ------ | --------------------------------------- | ------ |
| M1        | Day 1  | Project setup complete                  | ✅     |
| M2        | Day 3  | Admin can manage books & categories     | ✅     |
| M3        | Day 6  | Users can browse catalog                | ✅     |
| M4        | Day 9  | Full borrowing flow working             | ✅     |
| M5        | Day 13 | Phase 1 MVP complete (Blade + Filament) | ✅     |
| M6        | Day 15 | Backend refactored with Service Repo    | 🔲     |
| M7        | Day 19 | User-facing React frontend complete     | 🔲     |
| M8        | Day 22 | Admin React panel complete              | 🔲     |
| M9        | Day 23 | Phase 2 complete, ready for deployment  | 🔲     |

---

## Tech Stack Summary

### Backend (Laravel 11)

```
app/
├── Http/Controllers/Api/      # API Controllers
├── Models/                    # Eloquent Models
├── Repositories/
│   ├── Contracts/             # Interfaces
│   └── Eloquent/              # Implementations
├── Services/                  # Business Logic
└── Providers/
    └── RepositoryServiceProvider.php
```

### Frontend (React.js)

```
frontend/
├── src/
│   ├── components/            # Reusable UI
│   ├── pages/                 # Page components
│   ├── hooks/                 # Custom hooks
│   ├── stores/                # Zustand stores
│   ├── services/              # API services
│   ├── types/                 # TypeScript types
│   └── schemas/               # Zod schemas
└── package.json
```

### Key Dependencies

| Package               | Purpose          |
| --------------------- | ---------------- |
| react                 | UI framework     |
| react-router-dom      | Client routing   |
| @tanstack/react-query | Server state     |
| axios                 | HTTP client      |
| zustand               | State management |
| zod                   | Validation       |
| tailwindcss           | Styling          |

---

## Notes

-   **Design Reference**: Will be provided by user (HTML templates)
-   **Image Storage**: Local storage (`storage/app/public/books/`)
-   **Authentication**: Laravel Sanctum (SPA mode)
-   **Admin Panel**: Custom React.js (replacing Filament)
