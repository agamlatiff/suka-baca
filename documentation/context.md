# Sukabaca - Simplified MVP Version

## 1. Core Features (Simple & Essential)

### A. Modul Autentikasi
**Complexity: Low**

**Fitur:**
- Login sebagai Admin atau User
- Logout
- Session management

**Sub-fitur:**
- Register user baru (nama, email, password)
- Basic profile (nama, email, no telpon)

---

### B. Modul Buku (Admin Side)
**Complexity: Medium**

**Fitur Utama:**
- CRUD buku
- Tracking copy buku
- Status ketersediaan

**Sub-fitur:**
- Form tambah buku:
  - Judul buku
  - Penulis
  - Kategori (simple dropdown)
  - Jumlah copy (misal: 10 copy)
- Setiap buku auto-generate ID unik per copy (Buku A-001, Buku A-002, dst)
- Lihat status per copy:
  - ✅ Tersedia
  - 📖 Dipinjam (oleh siapa, sejak kapan)
- Edit/Delete buku
- Search buku (by judul atau penulis)

---

### C. Modul Katalog (User Side)
**Complexity: Low**

**Fitur Utama:**
- Browse buku yang tersedia
- Info detail buku

**Sub-fitur:**
- List semua buku dengan info:
  - Judul
  - Penulis
  - Kategori
  - Jumlah tersedia / total copy
  - Status: "Tersedia" atau "Habis"
- Filter by kategori
- Search by judul/penulis
- Detail buku:
  - Info lengkap
  - Berapa kali dipinjam (counter)
  - Button "Pinjam" (jika tersedia)

---

### D. Modul Peminjaman
**Complexity: Medium**

**Fitur Utama:**
- User pinjam buku
- Admin lihat siapa yang pinjam
- Proses pengembalian

**Sub-fitur:**
- **User melakukan peminjaman:**
  - Klik "Pinjam" pada buku
  - Pilih durasi (misal: 7 atau 14 hari)
  - Sistem otomatis assign copy yang tersedia
  - Tampil kode peminjaman & tanggal kembali
  
- **User lihat peminjaman aktif:**
  - Buku apa yang sedang dipinjam
  - Tanggal pinjam & jatuh tempo
  - Status: "Aktif" atau "Terlambat"
  
- **Admin lihat semua peminjaman:**
  - Tabel: Siapa | Buku apa | Copy mana | Tgl pinjam | Tgl kembali | Status
  - Filter: Aktif / Sudah kembali / Terlambat
  - Button "Kembalikan" untuk proses return
  
- **Pengembalian:**
  - Admin klik "Kembalikan"
  - Copy otomatis jadi tersedia lagi
  - Counter "dibaca berapa kali" bertambah

---

### E. Modul Biaya & Denda (Simple)
**Complexity: Low**

**Fitur Utama:**
- Biaya sewa flat per buku
- Denda otomatis kalau terlambat

**Sub-fitur:**
- Setting admin:
  - Biaya sewa per buku (misal: Rp 5.000/minggu)
  - Denda per hari (misal: Rp 2.000/hari)
  
- Kalkulasi otomatis:
  - Total biaya = biaya sewa + denda (jika ada)
  - Tampil di halaman peminjaman user
  
- Pembayaran manual (tidak perlu payment gateway):
  - User bayar cash/transfer
  - Admin centang "Sudah dibayar"

---

### F. Dashboard Admin
**Complexity: Low**

**Fitur:**
- Statistik sederhana:
  - Total buku (judul)
  - Total copy
  - Jumlah tersedia
  - Jumlah sedang dipinjam
  - Total user terdaftar
  
- Tabel peminjaman terbaru (5-10 terakhir)
- List buku paling sering dipinjam (top 5)

---

### G. Dashboard User
**Complexity: Low**

**Fitur:**
- Peminjaman aktif saya:
  - Buku apa
  - Kapan jatuh tempo
  - Sisa hari
  - Total biaya + denda
  
- History peminjaman (buku apa aja yang pernah dipinjam)

---

## 2. Database Schema (Simplified)

### Tabel: users
- id, name, email, password, role (admin/user), phone

### Tabel: categories
- id, name

### Tabel: books
- id, title, author, category_id, total_copies, times_borrowed (counter)

### Tabel: book_copies
- id, book_id, copy_code (misal: "BK001-C01"), status (available/borrowed)

### Tabel: borrowings
- id, user_id, book_copy_id, borrowed_at, due_date, returned_at, rental_fee, late_fee, paid (boolean), status (active/returned/overdue)

---

## 3. Removed Features (Untuk Simplifikasi)

❌ Upload cover buku → nanti aja
❌ Notifikasi email/WA → manual dulu
❌ Reservasi buku → gak perlu dulu
❌ Rating/review → skip
❌ Perpanjangan otomatis → manual via admin
❌ Multiple kategori per buku → 1 buku = 1 kategori
❌ Export laporan → gak perlu dulu
❌ Grafik/chart → statistik angka aja cukup
❌ Upload bukti bayar → admin centang manual

---

## 4. User Flow Sederhana

### Flow User Pinjam Buku:
1. User login
2. Browse katalog buku
3. Klik "Pinjam" pada buku yang tersedia
4. Pilih durasi (7 atau 14 hari)
5. Konfirmasi → sistem assign copy otomatis
6. User dapat kode peminjaman + tanggal kembali
7. User bayar (cash/transfer ke admin)
8. Admin centang "Sudah dibayar"

### Flow Admin Kembalikan Buku:
1. Admin masuk halaman "Peminjaman"
2. Cari peminjaman yang mau dikembalikan
3. Klik "Kembalikan"
4. Copy otomatis jadi available
5. Counter "times_borrowed" +1
6. Status peminjaman → "Returned"

---

## 5. Estimasi Waktu (Simplified)

| Modul | Estimasi | Reasoning |
|-------|----------|-----------|
| Setup Laravel + Auth | 1 hari | Breeze/Jetstream |
| Master Buku + Kategori | 2 hari | CRUD sederhana |
| Book Copies Management | 1.5 hari | Generate copy ID |
| Katalog User | 1 hari | List + filter + search |
| Peminjaman Flow | 3 hari | Core logic |
| Biaya & Denda | 1 hari | Simple calculation |
| Dashboard Admin | 1 hari | Stats sederhana |
| Dashboard User | 1 hari | History |
| Testing | 1.5 hari | Manual testing |
| **TOTAL** | **13 hari** | **~2.5 minggu** |

---

## 6. Tech Stack (Simple)

- **Laravel 10** + **MySQL**
- **Laravel Breeze** (auth scaffolding)
- **Blade + Tailwind CSS** (no JS framework)
- **Alpine.js** (untuk interaktivitas kecil, optional)

---

## 7. Halaman yang Dibutuhkan

### Admin:
1. `/admin/dashboard` - Statistik sederhana
2. `/admin/books` - CRUD buku + lihat copy
3. `/admin/categories` - CRUD kategori
4. `/admin/borrowings` - List semua peminjaman
5. `/admin/users` - List user (optional)

### User:
1. `/dashboard` - Peminjaman aktif + history
2. `/catalog` - Browse buku
3. `/catalog/{id}` - Detail buku
4. `/my-borrowings` - Peminjaman saya

---

## 8. Fitur "Nice to Have" Nanti (Phase 2)

Kalau client puas dengan MVP dan mau tambah fitur:
- Notifikasi email reminder
- Upload cover buku
- Export data ke Excel
- Grafik statistik
- Filter lanjutan (by tanggal, status, dll)
- Perpanjangan masa pinjam

---

## 9. Pertanyaan Konfirmasi ke Client

1. **Biaya sewa per buku sama semua atau beda-beda?**
2. **Durasi peminjaman: fixed (misal: 7 hari) atau user bisa pilih?**
3. **Denda keterlambatan: berapa per hari?**
4. **Apakah perlu approval admin atau langsung bisa pinjam?**
5. **User bisa pinjam berapa buku sekaligus?**
6. **Pembayaran: cash/transfer manual, atau perlu online payment?**

---

## 10. Pricing Recommendation

Untuk MVP sederhana seperti ini:
- **Development**: 13 hari kerja
- **Rate freelancer Laravel**: Rp 300.000 - 600.000/hari (tergantung experience)
- **Estimasi total**: Rp 3.900.000 - 7.800.000

**Termasuk:**
- Source code
- Database setup
- Basic deployment guide
- 2 minggu bug fixing setelah delivery

**Tidak termasuk:**
- Hosting/server (client sediakan)
- Domain
- SSL certificate
- Maintenance bulanan

---

**Versi ini jauh lebih realistis dan achievable dalam waktu singkat!** 🚀

Mau saya buatkan juga database migration atau ERD diagram-nya?