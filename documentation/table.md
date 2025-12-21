# Sukabaca - Database Schema Documentation

## 1. Tabel: `users`

Menyimpan data pengguna (admin dan user peminjam).

| Kolom | Tipe Data | Constraint | Default | Keterangan |
|-------|-----------|------------|---------|------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | - | ID unik user |
| `name` | VARCHAR(255) | NOT NULL | - | Nama lengkap user |
| `email` | VARCHAR(255) | NOT NULL, UNIQUE | - | Email untuk login |
| `password` | VARCHAR(255) | NOT NULL | - | Password (hashed) |
| `role` | ENUM('admin','user') | NOT NULL | 'user' | Role user |
| `phone` | VARCHAR(20) | NULLABLE | NULL | Nomor telepon |
| `email_verified_at` | TIMESTAMP | NULLABLE | NULL | Waktu verifikasi email |
| `remember_token` | VARCHAR(100) | NULLABLE | NULL | Token remember me |
| `created_at` | TIMESTAMP | NULLABLE | NULL | Waktu dibuat |
| `updated_at` | TIMESTAMP | NULLABLE | NULL | Waktu diupdate |

**Indexes:**
- PRIMARY KEY: `id`
- UNIQUE: `email`
- INDEX: `role` (untuk filter by role)

---

## 2. Tabel: `categories`

Menyimpan kategori/genre buku.

| Kolom | Tipe Data | Constraint | Default | Keterangan |
|-------|-----------|------------|---------|------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | - | ID unik kategori |
| `name` | VARCHAR(100) | NOT NULL, UNIQUE | - | Nama kategori |
| `description` | TEXT | NULLABLE | NULL | Deskripsi kategori |
| `created_at` | TIMESTAMP | NULLABLE | NULL | Waktu dibuat |
| `updated_at` | TIMESTAMP | NULLABLE | NULL | Waktu diupdate |

**Indexes:**
- PRIMARY KEY: `id`
- UNIQUE: `name`

---

## 3. Tabel: `books`

Menyimpan informasi buku (master data).

| Kolom | Tipe Data | Constraint | Default | Keterangan |
|-------|-----------|------------|---------|------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | - | ID unik buku |
| `title` | VARCHAR(255) | NOT NULL | - | Judul buku |
| `author` | VARCHAR(255) | NOT NULL | - | Nama penulis |
| `category_id` | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | - | ID kategori |
| `isbn` | VARCHAR(20) | NULLABLE, UNIQUE | NULL | ISBN buku |
| `description` | TEXT | NULLABLE | NULL | Sinopsis/deskripsi |
| `total_copies` | INT UNSIGNED | NOT NULL | 0 | Total jumlah copy |
| `available_copies` | INT UNSIGNED | NOT NULL | 0 | Jumlah copy tersedia |
| `times_borrowed` | INT UNSIGNED | NOT NULL | 0 | Counter berapa kali dipinjam |
| `created_at` | TIMESTAMP | NULLABLE | NULL | Waktu dibuat |
| `updated_at` | TIMESTAMP | NULLABLE | NULL | Waktu diupdate |

**Foreign Keys:**
- `category_id` REFERENCES `categories(id)` ON DELETE RESTRICT

**Indexes:**
- PRIMARY KEY: `id`
- UNIQUE: `isbn`
- INDEX: `category_id` (untuk join dengan categories)
- INDEX: `title` (untuk search)
- INDEX: `available_copies` (untuk filter buku tersedia)

---

## 4. Tabel: `book_copies`

Menyimpan data setiap copy/eksemplar buku fisik.

| Kolom | Tipe Data | Constraint | Default | Keterangan |
|-------|-----------|------------|---------|------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | - | ID unik copy |
| `book_id` | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | - | ID buku induk |
| `copy_code` | VARCHAR(50) | NOT NULL, UNIQUE | - | Kode unik copy (misal: BK001-C01) |
| `status` | ENUM('available','borrowed','maintenance','lost') | NOT NULL | 'available' | Status copy |
| `notes` | TEXT | NULLABLE | NULL | Catatan (kondisi, dll) |
| `created_at` | TIMESTAMP | NULLABLE | NULL | Waktu dibuat |
| `updated_at` | TIMESTAMP | NULLABLE | NULL | Waktu diupdate |

**Foreign Keys:**
- `book_id` REFERENCES `books(id)` ON DELETE CASCADE

**Indexes:**
- PRIMARY KEY: `id`
- UNIQUE: `copy_code`
- INDEX: `book_id` (untuk join dengan books)
- INDEX: `status` (untuk filter by status)
- COMPOSITE INDEX: `(book_id, status)` (untuk query "berapa copy tersedia per buku")

---

## 5. Tabel: `borrowings`

Menyimpan data transaksi peminjaman buku.

| Kolom | Tipe Data | Constraint | Default | Keterangan |
|-------|-----------|------------|---------|------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | - | ID unik peminjaman |
| `borrowing_code` | VARCHAR(50) | NOT NULL, UNIQUE | - | Kode peminjaman (misal: BRW-20241221-001) |
| `user_id` | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | - | ID user peminjam |
| `book_copy_id` | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | - | ID copy yang dipinjam |
| `borrowed_at` | DATE | NOT NULL | - | Tanggal pinjam |
| `due_date` | DATE | NOT NULL | - | Tanggal jatuh tempo |
| `returned_at` | DATE | NULLABLE | NULL | Tanggal pengembalian (NULL jika belum dikembalikan) |
| `rental_fee` | DECIMAL(10,2) | NOT NULL | 0.00 | Biaya sewa |
| `late_fee` | DECIMAL(10,2) | NOT NULL | 0.00 | Biaya denda keterlambatan |
| `total_fee` | DECIMAL(10,2) | NOT NULL | 0.00 | Total biaya (rental + late fee) |
| `is_paid` | BOOLEAN | NOT NULL | FALSE | Status pembayaran |
| `status` | ENUM('active','returned','overdue') | NOT NULL | 'active' | Status peminjaman |
| `days_late` | INT | NOT NULL | 0 | Jumlah hari terlambat |
| `created_at` | TIMESTAMP | NULLABLE | NULL | Waktu dibuat |
| `updated_at` | TIMESTAMP | NULLABLE | NULL | Waktu diupdate |

**Foreign Keys:**
- `user_id` REFERENCES `users(id)` ON DELETE RESTRICT
- `book_copy_id` REFERENCES `book_copies(id)` ON DELETE RESTRICT

**Indexes:**
- PRIMARY KEY: `id`
- UNIQUE: `borrowing_code`
- INDEX: `user_id` (untuk query "peminjaman by user")
- INDEX: `book_copy_id` (untuk query "siapa yang pinjam copy ini")
- INDEX: `status` (untuk filter by status)
- INDEX: `due_date` (untuk query "peminjaman yang akan jatuh tempo")
- INDEX: `is_paid` (untuk query pembayaran)
- COMPOSITE INDEX: `(user_id, status)` (untuk query "peminjaman aktif user")
- COMPOSITE INDEX: `(status, due_date)` (untuk query "overdue borrowings")

---

## Penjelasan Relasi Antar Tabel

### 1. `users` → `borrowings` (One-to-Many)

**Relasi:** Satu user bisa melakukan banyak peminjaman.

**Foreign Key:** `borrowings.user_id` → `users.id`

**ON DELETE:** RESTRICT (user tidak bisa dihapus jika masih punya riwayat peminjaman)

**Use Case:**
- Lihat semua peminjaman user tertentu
- Lihat siapa yang meminjam buku tertentu

**Query Example:**
```sql
-- Lihat peminjaman user ID 5
SELECT * FROM borrowings WHERE user_id = 5;

-- Lihat user yang meminjam copy tertentu
SELECT u.* FROM users u
JOIN borrowings b ON b.user_id = u.id
WHERE b.book_copy_id = 10 AND b.status = 'active';
```

---

### 2. `categories` → `books` (One-to-Many)

**Relasi:** Satu kategori bisa punya banyak buku.

**Foreign Key:** `books.category_id` → `categories.id`

**ON DELETE:** RESTRICT (kategori tidak bisa dihapus jika masih ada buku yang menggunakannya)

**Use Case:**
- Filter buku by kategori
- Lihat semua buku dalam kategori tertentu

**Query Example:**
```sql
-- Lihat semua buku dalam kategori "Novel"
SELECT b.* FROM books b
JOIN categories c ON b.category_id = c.id
WHERE c.name = 'Novel';

-- Hitung jumlah buku per kategori
SELECT c.name, COUNT(b.id) as total_books
FROM categories c
LEFT JOIN books b ON b.category_id = c.id
GROUP BY c.id, c.name;
```

---

### 3. `books` → `book_copies` (One-to-Many)

**Relasi:** Satu buku bisa punya banyak copy/eksemplar fisik.

**Foreign Key:** `book_copies.book_id` → `books.id`

**ON DELETE:** CASCADE (jika buku dihapus, semua copy-nya juga terhapus)

**Use Case:**
- Lihat semua copy dari buku tertentu
- Cek ketersediaan copy per buku

**Query Example:**
```sql
-- Lihat semua copy buku "Harry Potter"
SELECT bc.* FROM book_copies bc
JOIN books b ON bc.book_id = b.id
WHERE b.title LIKE '%Harry Potter%';

-- Cek copy mana saja yang tersedia untuk buku ID 3
SELECT * FROM book_copies
WHERE book_id = 3 AND status = 'available';

-- Hitung jumlah copy tersedia per buku
SELECT b.title, COUNT(bc.id) as available
FROM books b
LEFT JOIN book_copies bc ON bc.book_id = b.id AND bc.status = 'available'
GROUP BY b.id, b.title;
```

---

### 4. `book_copies` → `borrowings` (One-to-Many)

**Relasi:** Satu copy bisa dipinjam berkali-kali (tapi tidak bersamaan).

**Foreign Key:** `borrowings.book_copy_id` → `book_copies.id`

**ON DELETE:** RESTRICT (copy tidak bisa dihapus jika masih ada riwayat peminjaman)

**Use Case:**
- Lihat history peminjaman suatu copy
- Cek apakah copy sedang dipinjam

**Query Example:**
```sql
-- Lihat history peminjaman copy BK001-C01
SELECT br.* FROM borrowings br
JOIN book_copies bc ON br.book_copy_id = bc.id
WHERE bc.copy_code = 'BK001-C01'
ORDER BY br.borrowed_at DESC;

-- Cek apakah copy sedang dipinjam
SELECT * FROM borrowings
WHERE book_copy_id = 15 AND status = 'active';
```

---

## Indexes yang Direkomendasikan

### 1. **Primary Keys** (otomatis terindex)
Semua kolom `id` sudah auto-index sebagai PRIMARY KEY.

### 2. **Unique Indexes**
```sql
-- users table
ALTER TABLE users ADD UNIQUE INDEX idx_email (email);

-- categories table
ALTER TABLE categories ADD UNIQUE INDEX idx_name (name);

-- books table
ALTER TABLE books ADD UNIQUE INDEX idx_isbn (isbn);

-- book_copies table
ALTER TABLE book_copies ADD UNIQUE INDEX idx_copy_code (copy_code);

-- borrowings table
ALTER TABLE borrowings ADD UNIQUE INDEX idx_borrowing_code (borrowing_code);
```

### 3. **Foreign Key Indexes**
```sql
-- books table
ALTER TABLE books ADD INDEX idx_category_id (category_id);

-- book_copies table
ALTER TABLE book_copies ADD INDEX idx_book_id (book_id);

-- borrowings table
ALTER TABLE borrowings ADD INDEX idx_user_id (user_id);
ALTER TABLE borrowings ADD INDEX idx_book_copy_id (book_copy_id);
```

### 4. **Search & Filter Indexes**
```sql
-- users table
ALTER TABLE users ADD INDEX idx_role (role);

-- books table
ALTER TABLE books ADD INDEX idx_title (title);
ALTER TABLE books ADD INDEX idx_available_copies (available_copies);

-- book_copies table
ALTER TABLE book_copies ADD INDEX idx_status (status);

-- borrowings table
ALTER TABLE borrowings ADD INDEX idx_status (status);
ALTER TABLE borrowings ADD INDEX idx_due_date (due_date);
ALTER TABLE borrowings ADD INDEX idx_is_paid (is_paid);
```

### 5. **Composite Indexes** (untuk query kompleks)
```sql
-- book_copies: cari copy tersedia per buku
ALTER TABLE book_copies 
ADD INDEX idx_book_status (book_id, status);

-- borrowings: peminjaman aktif per user
ALTER TABLE borrowings 
ADD INDEX idx_user_status (user_id, status);

-- borrowings: peminjaman overdue
ALTER TABLE borrowings 
ADD INDEX idx_status_duedate (status, due_date);
```

---

## Query Umum & Indexes yang Digunakan

### Query 1: Katalog buku dengan info ketersediaan
```sql
SELECT 
    b.id,
    b.title,
    b.author,
    c.name as category,
    b.available_copies,
    b.total_copies,
    b.times_borrowed
FROM books b
JOIN categories c ON b.category_id = c.id
WHERE b.available_copies > 0
ORDER BY b.title;
```
**Indexes digunakan:** `idx_category_id`, `idx_available_copies`, `idx_title`

---

### Query 2: Peminjaman aktif user
```sql
SELECT 
    br.borrowing_code,
    b.title,
    bc.copy_code,
    br.borrowed_at,
    br.due_date,
    br.total_fee,
    br.is_paid,
    CASE 
        WHEN br.due_date < CURDATE() THEN 'overdue'
        ELSE 'active'
    END as status
FROM borrowings br
JOIN book_copies bc ON br.book_copy_id = bc.id
JOIN books b ON bc.book_id = b.id
WHERE br.user_id = ? AND br.status IN ('active', 'overdue')
ORDER BY br.due_date;
```
**Indexes digunakan:** `idx_user_status`, `idx_book_copy_id`, `idx_book_id`

---

### Query 3: Semua peminjaman (Admin view)
```sql
SELECT 
    br.borrowing_code,
    u.name as borrower,
    b.title as book_title,
    bc.copy_code,
    br.borrowed_at,
    br.due_date,
    br.status,
    br.is_paid
FROM borrowings br
JOIN users u ON br.user_id = u.id
JOIN book_copies bc ON br.book_copy_id = bc.id
JOIN books b ON bc.book_id = b.id
WHERE br.status = 'active'
ORDER BY br.due_date;
```
**Indexes digunakan:** `idx_status`, `idx_user_id`, `idx_book_copy_id`, `idx_book_id`

---

### Query 4: Buku paling sering dipinjam (Top 5)
```sql
SELECT 
    b.title,
    b.author,
    b.times_borrowed,
    c.name as category
FROM books b
JOIN categories c ON b.category_id = c.id
ORDER BY b.times_borrowed DESC
LIMIT 5;
```
**Indexes digunakan:** `idx_category_id`, sort by `times_borrowed` (no index needed karena LIMIT kecil)

---

### Query 5: Cek peminjaman overdue
```sql
SELECT 
    br.borrowing_code,
    u.name as borrower,
    u.phone,
    b.title,
    br.borrowed_at,
    br.due_date,
    DATEDIFF(CURDATE(), br.due_date) as days_overdue
FROM borrowings br
JOIN users u ON br.user_id = u.id
JOIN book_copies bc ON br.book_copy_id = bc.id
JOIN books b ON bc.book_id = b.id
WHERE br.status = 'active' AND br.due_date < CURDATE()
ORDER BY br.due_date;
```
**Indexes digunakan:** `idx_status_duedate` (composite index sangat efektif di sini)

---

## Tips Optimasi Query

1. **Gunakan EXPLAIN** untuk analisis query:
```sql
EXPLAIN SELECT * FROM borrowings WHERE user_id = 5 AND status = 'active';
```

2. **Hindari SELECT *** pada production, pilih kolom yang dibutuhkan saja.

3. **Gunakan pagination** untuk list yang panjang:
```sql
SELECT * FROM books LIMIT 20 OFFSET 0;
```

4. **Cache query yang sering digunakan** (misal: statistik dashboard).

5. **Regularly analyze & optimize tables:**
```sql
ANALYZE TABLE borrowings;
OPTIMIZE TABLE borrowings;
```

---

## Estimasi Storage

Asumsi: 1000 buku, 5000 copies, 100 users, 10.000 borrowings

| Tabel | Rows | Avg Row Size | Total Size |
|-------|------|--------------|------------|
| users | 100 | ~500 bytes | 50 KB |
| categories | 20 | ~200 bytes | 4 KB |
| books | 1000 | ~800 bytes | 800 KB |
| book_copies | 5000 | ~300 bytes | 1.5 MB |
| borrowings | 10,000 | ~400 bytes | 4 MB |
| **Total** | - | - | **~6.5 MB** |
| **+ Indexes** | - | - | **~3 MB** |
| **Grand Total** | - | - | **~10 MB** |

Database size sangat kecil, tidak akan ada masalah performa untuk aplikasi skala kecil-menengah.

---

**Database design ini sudah optimal untuk use case Sukabaca!** 🚀