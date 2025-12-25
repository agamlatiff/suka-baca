# Sukabaca - Development Progress (The Memory)

> Checklist progress development. Berdasarkan BRIEF-CLIENT-FINAL dan pages.md.

---

## Tech Stack

-   **Backend:** Laravel 11
-   **Frontend:** Blade + Livewire + Alpine.js + Tailwind CSS
-   **Admin:** Filament v3
-   **Auth:** Laravel Breeze
-   **Database:** MySQL

---

## Progress Summary

| Phase                           | Status      | Estimasi |
| ------------------------------- | ----------- | -------- |
| Phase 1: Setup & Foundation     | ✅ Complete | 2 hari   |
| Phase 2: Admin Panel (Filament) | ✅ Complete | 4 hari   |
| Phase 2.10: Service Repository  | ✅ Complete | 2 hari   |
| Phase 3: User Frontend          | 🔲 Pending  | 5 hari   |
| Phase 4: Payment & Approval     | 🔲 Pending  | 3 hari   |
| Phase 5: Reports & Analytics    | 🔲 Pending  | 2 hari   |
| Phase 6: Testing & Polish       | 🔲 Pending  | 2 hari   |

**Total Estimasi:** 18 hari kerja

---

## Phase 1: Setup & Foundation ✅

### 1.1 Project Setup

-   [x] Install Laravel 11
-   [x] Configure database (MySQL)
-   [x] Install Laravel Breeze
-   [x] Install Livewire
-   [x] Install Filament v3
-   [x] Setup Tailwind CSS
-   [x] Configure storage link untuk images

### 1.2 Database Migrations

-   [x] Create `users` migration (+ role, phone, status)
-   [x] Create `categories` migration
-   [x] Create `books` migration (+ image, rental_fee)
-   [x] Create `book_copies` migration
-   [x] Create `borrowings` migration
-   [x] Create `settings` migration
-   [x] Create `sessions` migration
-   [x] Create `payments` migration
-   [x] Create `wishlists` migration
-   [x] Update `categories` migration (+ icon)
-   [x] Update `users` migration (+ address, status)
-   [x] Update `books` migration (+ slug, publisher, year, isbn, book_price)
-   [x] Update `borrowings` migration (+ damage_fee, return_condition, is_extended)

### 1.3 Models & Relationships

-   [x] User model + relationships (+ wishlists, payments, status)
-   [x] Category model + relationships (+ icon)
-   [x] Book model + relationships (+ new fields, wishlists)
-   [x] BookCopy model + relationships
-   [x] Borrowing model + relationships (+ payments, extension)
-   [x] Setting model
-   [x] Payment model + relationships
-   [x] Wishlist model + relationships

### 1.4 Seeders

-   [x] AdminSeeder (default admin)
-   [x] CategorySeeder (sample categories)
-   [x] BookSeeder (sample books)
-   [x] SettingSeeder (default settings)
-   [x] UserSeeder (sample users)
-   [x] BorrowingSeeder (sample borrowings)

---

## Phase 2: Admin Panel (Filament) 🔄

### 2.1 Dashboard

-   [x] Stats widgets (buku, eksemplar, member)
-   [x] Recent borrowings table widget
-   [x] Top books chart widget
-   [x] Revenue widgets (hari ini, minggu, bulan)
-   [x] Pending verification widget
-   [x] Overdue alert widget

### 2.2 Categories Resource

-   [x] Table: icon, nama, deskripsi, jumlah buku
-   [x] Form: nama, deskripsi
-   [x] Delete protection (jika ada buku)
-   [x] Add icon picker field

### 2.3 Books Resource

-   [x] Table: cover, judul, penulis, kategori, stok, harga
-   [x] Form: cover upload, judul, penulis, kategori, sinopsis, harga, jumlah eksemplar
-   [x] Relation manager: Book Copies
-   [x] Auto-generate copy codes saat create
-   [x] Add penerbit, tahun, ISBN, book_price fields
-   [x] Add slug auto-generate from title
-   [x] Header action: Import Excel
-   [x] Header action: Export Excel

### 2.4 Book Copies Management

-   [x] Table: kode, status, notes
-   [x] Form: status, notes
-   [x] Bulk status update

### 2.5 Users Resource

-   [x] Table: nama, email, phone, status, pinjaman aktif
-   [x] Form: nama, phone, status toggle
-   [x] Action: Suspend user
-   [x] Action: Activate user
-   [x] Relation manager: User borrowings

### 2.6 Borrowings Resource

-   [x] Table: kode, member, buku, tanggal, jatuh tempo, status, biaya
-   [x] Action: Kembalikan
-   [x] Action: Tandai lunas
-   [x] Filters: status, pembayaran, tanggal
-   [x] Action: Approve (pending -> aktif)
-   [x] Action: Reject (dengan notes)
-   [x] Action: Approve perpanjangan
-   [x] View detail + bukti transfer
-   [x] Modal kondisi buku (rusak/hilang)

### 2.7 Settings Resource

-   [x] Table & Form settings
-   [x] Section: Pengaturan Peminjaman (denda, maks hari, maks buku)
-   [x] Section: Denda kondisi (rusak/hilang %)
-   [x] Section: Rekening Pembayaran (bank, no rek, QRIS)

### 2.8 Payments Resource

-   [x] Create PaymentResource
-   [x] Table: tanggal, member, kode pinjam, jenis, jumlah, bukti, status
-   [x] Filters: status, jenis
-   [x] Action: Verify payment
-   [x] Action: Reject payment (dengan notes)
-   [x] Modal view bukti transfer

### 2.9 Reports Page

-   [x] Tab: Buku Terpopuler (ranking + chart)
-   [x] Tab: Peminjam Teraktif (ranking)
-   [x] Tab: Revenue (line chart 12 bulan)
-   [x] Tab: Laporan Peminjaman (detail table)
-   [x] Filter periode untuk semua report
-   [x] Export Excel untuk semua report

### 2.10 Service Repository Pattern

-   [x] Create Contracts/Interfaces folder structure
    -   [x] `App\Contracts\Repositories\BookRepositoryInterface`
    -   [x] `App\Contracts\Repositories\CategoryRepositoryInterface`
    -   [x] `App\Contracts\Repositories\BorrowingRepositoryInterface`
    -   [x] `App\Contracts\Repositories\UserRepositoryInterface`
    -   [x] `App\Contracts\Repositories\PaymentRepositoryInterface`
-   [x] Create Repository Implementations
    -   [x] `App\Repositories\Eloquent\BookRepository`
    -   [x] `App\Repositories\Eloquent\CategoryRepository`
    -   [x] `App\Repositories\Eloquent\BorrowingRepository`
    -   [x] `App\Repositories\Eloquent\UserRepository`
    -   [x] `App\Repositories\Eloquent\PaymentRepository`
-   [x] Create Service Classes
    -   [x] `App\Services\BookService`
    -   [x] `App\Services\CategoryService`
    -   [x] `App\Services\BorrowingService`
    -   [x] `App\Services\UserService`
    -   [x] `App\Services\PaymentService`
    -   [x] `App\Services\DashboardService`
-   [x] Create Service Provider for binding
    -   [x] `App\Providers\RepositoryServiceProvider`
-   [x] Refactor existing code to use Services
    -   [x] Update Controllers to use Services
    -   [x] Update Livewire components to use Services (N/A - none exist yet)
    -   [x] Update Filament Resources to use Services (Reports page)

---

## Phase 3: User Frontend 🔄

### 3.1 Layout & Components

-   [x] app.blade.php layout
-   [x] guest.blade.php layout
-   [x] Navbar component
-   [x] Footer component (in welcome)
-   [ ] Book card component
-   [ ] Stats card component
-   [ ] Badge component
-   [ ] Alert component
-   [ ] Modal component

### 3.2 Landing Page

-   [x] Hero section
-   [x] Buku terbaru section
-   [x] Buku populer section
-   [x] Kategori populer section
-   [x] Cara sewa section
-   [x] Kenapa pilih kami section
-   [x] Contact section
-   [x] Footer
-   [ ] Add icon per kategori

### 3.3 Authentication

-   [x] Login page
-   [x] Register page
-   [x] Logout functionality
-   [ ] Forgot password page
-   [ ] Reset password page

### 3.4 Katalog & Detail Buku

-   [x] Katalog page (Livewire)
-   [x] Search functionality
-   [x] Filter kategori
-   [x] Pagination
-   [x] Book detail page
-   [ ] Filter ketersediaan
-   [ ] Sort (A-Z, terbaru, harga)
-   [ ] Related books section

### 3.5 Wishlist

-   [ ] Add to wishlist (dari katalog & detail)
-   [ ] Wishlist page
-   [ ] Remove from wishlist
-   [ ] Quick borrow dari wishlist

### 3.6 User Dashboard

-   [x] Welcome header
-   [x] Stats cards
-   [x] Pinjaman aktif list
-   [ ] Alert notifikasi (terlambat, jatuh tempo, tagihan)
-   [ ] Countdown hari tersisa
-   [ ] Request pending list
-   [ ] Outstanding payment alert
-   [ ] Quick actions

### 3.7 My Borrowings

-   [x] Borrowing list
-   [ ] Filter tabs (semua, pending, aktif, dikembalikan, terlambat)
-   [ ] Pagination
-   [ ] Empty state

### 3.8 Profile Settings

-   [x] Edit profile form
-   [x] Change password form
-   [x] Delete account

---

## Phase 4: Payment & Approval Flow 🔲

### 4.1 Request Pinjam Flow

-   [ ] Modal konfirmasi buku & harga
-   [ ] Pilih metode bayar (Cash/Transfer)
-   [ ] Upload bukti transfer
-   [ ] Submit request
-   [ ] Status pending notification

### 4.2 Validasi Peminjaman

-   [ ] Cek limit 2 buku aktif
-   [ ] Cek tunggakan belum bayar
-   [ ] Cek status user (suspended?)
-   [ ] Cek ketersediaan buku

### 4.3 Perpanjangan Flow

-   [ ] Tombol perpanjang (jika eligible)
-   [ ] Modal perpanjangan
-   [ ] Upload bukti bayar perpanjangan
-   [ ] Submit request perpanjangan
-   [ ] Validasi: maks 1x, di hari H, tidak terlambat

### 4.4 Payment History

-   [ ] Payment history page
-   [ ] Filter status
-   [ ] Outstanding payment summary

### 4.5 Denda Calculation

-   [ ] Denda keterlambatan (per hari)
-   [ ] Denda rusak (50%)
-   [ ] Denda hilang (75%)
-   [ ] Auto-calculate di admin return

---

## Phase 5: Reports & Analytics 🔲

### 5.1 Data Aggregation

-   [ ] Service: Buku terpopuler query
-   [ ] Service: Peminjam teraktif query
-   [ ] Service: Revenue calculation
-   [ ] Service: Borrowing summary

### 5.2 Charts

-   [ ] Revenue line chart
-   [ ] Top books bar chart
-   [ ] Category distribution pie chart (opsional)

### 5.3 Export

-   [ ] Export buku terpopuler ke Excel
-   [ ] Export peminjam teraktif ke Excel
-   [ ] Export revenue ke Excel
-   [ ] Export laporan peminjaman ke Excel

---

## Phase 6: Testing & Polish 🔲

### 6.1 Functional Testing

-   [ ] Test registrasi & login
-   [ ] Test browse katalog
-   [ ] Test wishlist
-   [ ] Test request pinjam flow
-   [ ] Test approval flow
-   [ ] Test return flow dengan denda
-   [ ] Test perpanjangan flow
-   [ ] Test payment verification

### 6.2 UI/UX Polish

-   [ ] Responsive testing (mobile, tablet, desktop)
-   [ ] Loading states
-   [ ] Empty states
-   [ ] Error states
-   [ ] Toast notifications
-   [ ] Image fallbacks

### 6.3 Performance

-   [ ] Eager loading optimization
-   [ ] Query optimization
-   [ ] Image optimization
-   [ ] Caching untuk stats

### 6.4 Pre-Deployment

-   [ ] Environment config production
-   [ ] Storage link
-   [ ] Database seeding production
-   [ ] Final code review

---

## Milestones

| Milestone | Deliverable               | Status |
| --------- | ------------------------- | ------ |
| M1        | Project setup complete    | ✅     |
| M2        | Database & models ready   | ✅     |
| M3        | Admin CRUD complete       | �      |
| M4        | Admin dashboard & reports | 🔲     |
| M5        | User frontend complete    | �      |
| M6        | Payment & approval flow   | 🔲     |
| M7        | Testing complete          | 🔲     |
| M8        | Ready for deployment      | 🔲     |

---

## Notes

-   **Detail halaman:** Lihat `pages.md`
-   **Database schema:** Lihat `architecture.md` atau `erd.dbml`
-   **Fitur teknis:** Lihat `specs.md`
-   **Requirement klien:** Lihat `BRIEF-CLIENT-FINAL`
