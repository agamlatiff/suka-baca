# React Components & Pages

## Frontend Structure

```
frontend/src/
├── components/         # Reusable components
├── pages/              # Page components
├── hooks/              # Custom hooks
├── stores/             # Zustand stores
├── services/           # API services
├── types/              # TypeScript types
├── schemas/            # Zod schemas
└── lib/                # Utilities
```

---

## Module Overview

| Module          | Complexity | Admin | User | Key Functionality           |
| --------------- | ---------- | ----- | ---- | --------------------------- |
| Authentication  | Low        | ✅    | ✅   | Login, Register, Session    |
| Book Management | Medium     | ✅    | ❌   | CRUD books, Image upload    |
| Catalog         | Low        | ❌    | ✅   | Browse, Search, Filter      |
| Borrowing       | Medium     | ✅    | ✅   | Borrow, Return, Tracking    |
| Fees & Fines    | Low        | ✅    | ✅   | Rental fees, Late penalties |
| Dashboard       | Low        | ✅    | ✅   | Statistics, Overview        |

---

## Shared Components (ui/)

| Component    | Props                            | Description           |
| ------------ | -------------------------------- | --------------------- |
| `Button`     | variant, size, loading, disabled | Primary action button |
| `Card`       | title, children, footer          | Content container     |
| `Input`      | label, error, type               | Form input field      |
| `Select`     | options, value, onChange         | Dropdown select       |
| `Modal`      | isOpen, onClose, title           | Dialog modal          |
| `Table`      | columns, data, pagination        | Data table            |
| `Badge`      | variant, children                | Status badge          |
| `Spinner`    | size                             | Loading indicator     |
| `Alert`      | type, message                    | Notification alert    |
| `Pagination` | page, totalPages, onChange       | Page navigation       |

---

## Layout Components

### Navbar.tsx

-   Logo + app name
-   Navigation links (Katalog, Peminjaman Saya)
-   User menu (Profile, Logout)
-   Responsive mobile menu

### Sidebar.tsx (Admin)

-   Admin navigation menu
-   Collapsible sections
-   Active state highlight

### Footer.tsx

-   Copyright text
-   Library info from settings

---

## Catalog Components

### BookCard.tsx

```typescript
interface BookCardProps {
    book: Book;
    onBorrow?: () => void;
}
```

-   Book cover image
-   Title, author, category
-   Availability badge
-   Rental fee
-   "Pinjam" button

### BookGrid.tsx

-   Responsive grid layout
-   Pagination
-   Loading skeleton

### SearchFilter.tsx

-   Search input (title/author)
-   Category dropdown
-   "Show all" toggle

---

## Borrowing Components

### BorrowingCard.tsx

-   Book info
-   Due date with status
-   Days remaining/overdue
-   Fee info

### BorrowingList.tsx

-   Active borrowings section
-   History section
-   Empty state

### BorrowModal.tsx

-   Duration selector (7/14 days)
-   Fee preview
-   Confirm button

---

## Dashboard Components

### StatsCard.tsx

```typescript
interface StatsCardProps {
    title: string;
    value: number | string;
    icon: ReactNode;
    variant?: "default" | "warning" | "danger";
}
```

### RecentBorrowings.tsx

-   Table of recent transactions
-   Status badges
-   Quick actions

### DueAlert.tsx

-   Overdue alert (red)
-   Due soon alert (yellow)

---

## Pages

### Auth Pages

| Page         | Route       | Features                     |
| ------------ | ----------- | ---------------------------- |
| LoginPage    | `/login`    | Email, password, remember me |
| RegisterPage | `/register` | Name, email, password, phone |

### User Pages

| Page           | Route          | Features                    |
| -------------- | -------------- | --------------------------- |
| CatalogPage    | `/catalog`     | Book grid, search, filter   |
| BookDetailPage | `/catalog/:id` | Book info, borrow action    |
| DashboardPage  | `/dashboard`   | Stats, alerts, active loans |
| BorrowingsPage | `/borrowings`  | Active + history list       |

### Admin Pages

| Page           | Route               | Features                |
| -------------- | ------------------- | ----------------------- |
| AdminDashboard | `/admin`            | Stats, charts, recent   |
| BooksPage      | `/admin/books`      | CRUD table              |
| CategoriesPage | `/admin/categories` | CRUD table              |
| BorrowingsPage | `/admin/borrowings` | All borrowings, actions |
| UsersPage      | `/admin/users`      | User list               |
| SettingsPage   | `/admin/settings`   | System settings form    |

---

## Zustand Stores

### authStore.ts

```typescript
interface AuthState {
    user: User | null;
    token: string | null;
    isLoading: boolean;
    login: (email: string, password: string) => Promise<void>;
    logout: () => Promise<void>;
    checkAuth: () => Promise<void>;
}
```

### uiStore.ts

```typescript
interface UIState {
    sidebarOpen: boolean;
    borrowModalOpen: boolean;
    selectedBook: Book | null;
    toggleSidebar: () => void;
    openBorrowModal: (book: Book) => void;
    closeBorrowModal: () => void;
}
```

---

## TanStack Query Hooks

### Books

```typescript
// hooks/useBooks.ts
export function useBooks(params?: BookParams) {
    return useQuery({
        queryKey: ["books", params],
        queryFn: () => bookService.getBooks(params),
    });
}

export function useBook(id: number) {
    return useQuery({
        queryKey: ["books", id],
        queryFn: () => bookService.getBook(id),
    });
}
```

### Borrowings

```typescript
// hooks/useBorrowings.ts
export function useBorrowings() {
    return useQuery({
        queryKey: ["borrowings"],
        queryFn: () => borrowingService.getBorrowings(),
    });
}

export function useBorrowBook() {
    return useMutation({
        mutationFn: borrowingService.borrowBook,
        onSuccess: () => queryClient.invalidateQueries(["borrowings"]),
    });
}
```

---

## Zod Schemas

### authSchema.ts

```typescript
export const loginSchema = z.object({
    email: z.string().email("Email tidak valid"),
    password: z.string().min(6, "Password minimal 6 karakter"),
});

export const registerSchema = z
    .object({
        name: z.string().min(2, "Nama minimal 2 karakter"),
        email: z.string().email("Email tidak valid"),
        password: z.string().min(6, "Password minimal 6 karakter"),
        password_confirmation: z.string(),
        phone: z.string().optional(),
    })
    .refine((data) => data.password === data.password_confirmation, {
        message: "Password tidak cocok",
        path: ["password_confirmation"],
    });
```

### bookSchema.ts

```typescript
export const bookSchema = z.object({
    title: z.string().min(1, "Judul wajib diisi"),
    author: z.string().min(1, "Penulis wajib diisi"),
    category_id: z.number().min(1, "Kategori wajib dipilih"),
    description: z.string().optional(),
    rental_fee: z.number().min(0, "Biaya sewa tidak boleh negatif"),
    copies_count: z.number().min(1, "Minimal 1 eksemplar"),
});
```

---

## API Services

### api.ts

```typescript
import axios from "axios";

export const api = axios.create({
    baseURL: import.meta.env.VITE_API_URL,
    withCredentials: true,
});

api.interceptors.request.use((config) => {
    const token = localStorage.getItem("token");
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});
```

### bookService.ts

```typescript
export const bookService = {
    getBooks: (params?: BookParams) =>
        api.get<PaginatedResponse<Book>>("/books", { params }),

    getBook: (id: number) => api.get<Book>(`/books/${id}`),

    createBook: (data: FormData) => api.post<Book>("/books", data),

    updateBook: (id: number, data: FormData) =>
        api.put<Book>(`/books/${id}`, data),

    deleteBook: (id: number) => api.delete(`/books/${id}`),
};
```
