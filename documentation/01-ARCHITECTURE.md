# Architecture & Tech Stack

## Technology Stack

| Layer             | Technology               | Purpose                      |
| ----------------- | ------------------------ | ---------------------------- |
| **Backend**       | Laravel 11               | PHP framework, API & logic   |
| **Pattern**       | MVC + Service Repository | Scalable & maintainable code |
| **Database**      | MySQL                    | Data persistence             |
| **Auth**          | Laravel Sanctum          | API token authentication     |
| **Frontend**      | React.js + TypeScript    | Single Page Application      |
| **Styling**       | Tailwind CSS             | Utility-first CSS            |
| **State**         | Zustand                  | Global state management      |
| **Data Fetching** | Axios + TanStack Query   | API calls & caching          |
| **Validation**    | Zod                      | Frontend schema validation   |
| **Routing**       | React Router             | Client-side routing          |
| **Image Storage** | Local Storage            | `storage/app/public/books/`  |

---

## System Architecture

```
┌──────────────────────────────────────────────────────────────────┐
│                        PRESENTATION LAYER                         │
├──────────────────────────────────────────────────────────────────┤
│  React.js SPA                                                     │
│  ├── User Interface (Catalog, Dashboard, Borrowings)             │
│  └── Admin Panel (Books, Users, Borrowings, Settings)            │
│                                                                   │
│  Tech: TypeScript, Tailwind CSS, Zustand, TanStack Query, Zod    │
├──────────────────────────────────────────────────────────────────┤
│                           API LAYER                               │
├──────────────────────────────────────────────────────────────────┤
│  Laravel API (RESTful JSON)                                       │
│  ├── Controllers    → Handle HTTP requests                       │
│  ├── Middleware     → Auth, CORS, Rate Limiting                  │
│  └── Requests       → Form validation                            │
├──────────────────────────────────────────────────────────────────┤
│                         SERVICE LAYER                             │
├──────────────────────────────────────────────────────────────────┤
│  Services           → Business logic                              │
│  ├── BookService                                                  │
│  ├── BorrowingService                                             │
│  ├── UserService                                                  │
│  └── DashboardService                                             │
├──────────────────────────────────────────────────────────────────┤
│                       REPOSITORY LAYER                            │
├──────────────────────────────────────────────────────────────────┤
│  Repositories       → Data access abstraction                     │
│  ├── Contracts/     → Interfaces                                  │
│  └── Eloquent/      → Implementations                             │
├──────────────────────────────────────────────────────────────────┤
│                      INFRASTRUCTURE LAYER                         │
├──────────────────────────────────────────────────────────────────┤
│  MySQL Database  │  Local File Storage  │  Cache (Redis/File)    │
└──────────────────────────────────────────────────────────────────┘
```

---

## Directory Structure

### Backend (Laravel)

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/                # API Controllers
│   │       ├── AuthController.php
│   │       ├── BookController.php
│   │       ├── CategoryController.php
│   │       ├── BorrowingController.php
│   │       ├── UserController.php
│   │       ├── SettingController.php
│   │       └── DashboardController.php
│   ├── Middleware/             # Auth, CORS, etc.
│   └── Requests/               # Form validation
├── Models/                     # Eloquent models
├── Repositories/
│   ├── Contracts/              # Repository interfaces
│   │   ├── BookRepositoryInterface.php
│   │   ├── CategoryRepositoryInterface.php
│   │   ├── BorrowingRepositoryInterface.php
│   │   └── UserRepositoryInterface.php
│   └── Eloquent/               # Eloquent implementations
│       ├── BookRepository.php
│       ├── CategoryRepository.php
│       ├── BorrowingRepository.php
│       └── UserRepository.php
├── Services/                   # Business logic
│   ├── BookService.php
│   ├── BorrowingService.php
│   ├── UserService.php
│   └── DashboardService.php
└── Providers/
    └── RepositoryServiceProvider.php

database/
├── migrations/                 # Schema definitions
├── seeders/                    # Test data
└── factories/                  # Model factories

routes/
└── api.php                     # All API routes

storage/
└── app/public/
    └── books/                  # Book cover images
```

### Frontend (React.js)

```
frontend/
├── public/
│   └── index.html
├── src/
│   ├── components/
│   │   ├── ui/                 # Reusable UI (Button, Card, Input, Modal, Table)
│   │   ├── layout/             # Navbar, Sidebar, Footer
│   │   ├── catalog/            # BookCard, BookGrid, SearchFilter
│   │   ├── borrowing/          # BorrowingList, BorrowingCard, BorrowModal
│   │   └── dashboard/          # StatsCard, RecentBorrowings, DueAlert
│   ├── pages/
│   │   ├── auth/               # LoginPage, RegisterPage
│   │   ├── catalog/            # CatalogPage, BookDetailPage
│   │   ├── user/               # DashboardPage, BorrowingsPage
│   │   └── admin/              # AdminDashboard, BooksManagement, etc.
│   ├── hooks/                  # Custom React hooks
│   ├── stores/                 # Zustand stores
│   │   ├── authStore.ts
│   │   └── uiStore.ts
│   ├── services/               # API service layer (Axios)
│   │   ├── api.ts              # Axios instance
│   │   ├── authService.ts
│   │   ├── bookService.ts
│   │   ├── borrowingService.ts
│   │   └── categoryService.ts
│   ├── types/                  # TypeScript types
│   │   ├── book.ts
│   │   ├── borrowing.ts
│   │   ├── user.ts
│   │   └── api.ts
│   ├── schemas/                # Zod validation schemas
│   │   ├── authSchema.ts
│   │   ├── bookSchema.ts
│   │   └── borrowingSchema.ts
│   ├── lib/                    # Utilities
│   ├── App.tsx
│   ├── main.tsx
│   └── index.css               # Tailwind entry
├── tailwind.config.js
├── tsconfig.json
├── vite.config.ts
└── package.json
```

---

## Key Dependencies

### Backend (composer.json)

```json
{
    "laravel/framework": "^11.0",
    "laravel/sanctum": "^4.0"
}
```

### Frontend (package.json)

```json
{
    "react": "^18.0",
    "react-dom": "^18.0",
    "react-router-dom": "^6.0",
    "typescript": "^5.0",
    "@tanstack/react-query": "^5.0",
    "axios": "^1.6",
    "zustand": "^4.0",
    "zod": "^3.0",
    "tailwindcss": "^3.0"
}
```

---

## Development Tools

| Tool     | Purpose                       |
| -------- | ----------------------------- |
| Composer | PHP dependency management     |
| NPM      | Frontend dependencies         |
| Artisan  | Laravel CLI commands          |
| Vite     | Frontend bundler & dev server |

---

## API Communication Flow

```
React Component
      │
      ▼
TanStack Query (useQuery/useMutation)
      │
      ▼
Axios Service (services/bookService.ts)
      │
      ▼
Laravel API Controller
      │
      ▼
Service Layer (BookService)
      │
      ▼
Repository Layer (BookRepository)
      │
      ▼
Eloquent Model → MySQL Database
```

---

## Environment Configuration

### Backend (.env)

```env
APP_NAME=Sukabaca
APP_ENV=local
APP_DEBUG=true
DB_CONNECTION=mysql
DB_DATABASE=sukabaca

# Sanctum
SANCTUM_STATEFUL_DOMAINS=localhost:5173
SESSION_DOMAIN=localhost
```

### Frontend (.env)

```env
VITE_API_URL=http://localhost:8000/api
VITE_APP_NAME=Sukabaca
```

See [07-DEPLOYMENT.md](./07-DEPLOYMENT.md) for production configuration.
