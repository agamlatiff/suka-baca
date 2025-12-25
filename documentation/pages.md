# Sukabaca - UI/UX Design Specification

> Dokumen ini berisi detail halaman dan section yang dibutuhkan untuk design. Referensi untuk membuat mockup/wireframe.
> **Berdasarkan:** BRIEF-CLIENT-FINAL

---

## 🌐 USER PAGES (Blade + Livewire)

---

### 1. Landing Page `/`

**Purpose:** Homepage untuk menarik pengunjung dan member baru.

#### Sections:

| Section               | Deskripsi         | Komponen                                                                            |
| --------------------- | ----------------- | ----------------------------------------------------------------------------------- |
| **Navbar**            | Navigation bar    | Logo, Menu (Beranda, Katalog, Wishlist, Login/Register), User dropdown jika login   |
| **Hero Section**      | Banner utama      | Heading besar, Subheading, CTA button "Jelajahi Katalog", Background image/gradient |
| **Buku Terbaru**      | 8 buku terbaru    | Grid card buku, Judul, Penulis, Cover, Badge "Baru", Link "Lihat Semua"             |
| **Buku Populer**      | 8 buku terpopuler | Grid card buku, Badge jumlah dipinjam, Cover, Link "Lihat Semua"                    |
| **Kategori Populer**  | 4-6 kategori      | Icon kategori + Nama, Jumlah buku, Clickable ke filter katalog                      |
| **Cara Sewa Buku**    | Langkah-langkah   | 4 step: Daftar → Pilih Buku → Bayar → Ambil Buku                                    |
| **Kenapa Pilih Kami** | Benefits          | 3-4 poin: Koleksi Lengkap, Harga Terjangkau, Proses Mudah, Perpanjangan Mudah       |
| **Contact Section**   | Info kontak       | Alamat, Email, No HP, Jam Operasional, Map (opsional)                               |
| **Footer**            | Informasi         | Logo, Alamat, Kontak, Link penting, Social media, Copyright                         |

---

### 2. Katalog Buku `/books`

**Purpose:** Halaman untuk browse dan cari buku.

#### Sections:

| Section                 | Deskripsi            | Komponen                                                                                                           |
| ----------------------- | -------------------- | ------------------------------------------------------------------------------------------------------------------ |
| **Page Header**         | Judul halaman        | Title "Katalog Buku", Breadcrumb                                                                                   |
| **Search & Filter Bar** | Filter buku          | Search input (judul/penulis/ISBN), Dropdown kategori, Toggle "Hanya Tersedia", Sort dropdown (A-Z, Terbaru, Harga) |
| **Book Grid**           | Daftar buku          | Responsive grid 3-4 kolom, Book cards                                                                              |
| **Book Card**           | Per buku             | Cover image, Judul, Penulis, Kategori badge, Harga sewa, Status, Tombol "Detail", Tombol ❤️ Wishlist               |
| **Pagination**          | Navigasi halaman     | Previous, Page numbers, Next, Info "Menampilkan 1-12 dari 100"                                                     |
| **Empty State**         | Jika tidak ada hasil | Ilustrasi, Pesan "Tidak ada buku ditemukan", Tombol reset filter                                                   |

---

### 3. Detail Buku `/books/{slug}`

**Purpose:** Halaman detail lengkap buku + aksi pinjam.

#### Sections:

| Section             | Deskripsi           | Komponen                                                                                    |
| ------------------- | ------------------- | ------------------------------------------------------------------------------------------- |
| **Breadcrumb**      | Navigasi            | Beranda > Katalog > [Nama Buku]                                                             |
| **Book Info**       | Info utama          | Cover besar (kiri), Info (kanan): Judul, Penulis, Penerbit, Tahun, Kategori, ISBN, Sinopsis |
| **Availability**    | Status ketersediaan | Badge status (Tersedia/Habis), Jumlah tersedia "3 dari 5 eksemplar tersedia"                |
| **Pricing**         | Harga sewa          | Harga sewa per 7 hari                                                                       |
| **Borrow Action**   | Aksi pinjam         | Tombol "Pinjam Buku" → Modal konfirmasi                                                     |
| **Wishlist Button** | Simpan wishlist     | Tombol ❤️ "Tambah ke Wishlist"                                                              |
| **Stats**           | Statistik           | Jumlah kali dipinjam                                                                        |
| **Related Books**   | Buku terkait        | 4 buku dari kategori yang sama                                                              |

**States:**

-   **Guest:** Tampil tombol "Login untuk Pinjam"
-   **User + tersedia:** Tampil form pinjam
-   **User + habis:** Tampil "Stok Habis" + Wishlist button
-   **User punya tunggakan:** Tampil alert "Lunasi dulu tagihan Anda"
-   **User di-suspend:** Tampil alert "Akun Anda di-suspend"
-   **User sudah pinjam 2 buku:** Tampil alert "Batas maksimal peminjaman"

---

### 4. Login Page `/login`

**Purpose:** Halaman login member.

#### Sections:

  | Section            | Deskripsi    | Komponen                                                          |
  | ------------------ | ------------ | ----------------------------------------------------------------- |
  | **Page Layout**    | Split layout | Ilustrasi/branding (kiri), Form (kanan)                           |
  | **Login Form**     | Form login   | Email input, Password input, Remember me checkbox, Tombol "Masuk" |
  | **Links**          | Navigasi     | Link "Lupa Password?", Link "Belum punya akun? Daftar"            |
  | **Error Messages** | Validasi     | Alert untuk email/password salah                                  |

---

### 5. Register Page `/register`

**Purpose:** Halaman registrasi member baru.

#### Sections:

| Section           | Deskripsi    | Komponen                                                                                       |
| ----------------- | ------------ | ---------------------------------------------------------------------------------------------- |
| **Page Layout**   | Split layout | Ilustrasi/branding (kiri), Form (kanan)                                                        |
| **Register Form** | Form daftar  | Nama lengkap, Email, No. HP, Alamat (opsional), Password, Konfirmasi password, Tombol "Daftar" |
| **Links**         | Navigasi     | Link "Sudah punya akun? Masuk"                                                                 |
| **Validation**    | Validasi     | Error messages per field                                                                       |

---

### 6. Forgot Password `/forgot-password`

**Purpose:** Request reset password.

#### Sections:

| Section       | Deskripsi   | Komponen                                       |
| ------------- | ----------- | ---------------------------------------------- |
| **Form**      | Input email | Email input, Tombol "Kirim Link Reset"         |
| **Success**   | Konfirmasi  | Pesan "Link reset telah dikirim ke email Anda" |
| **Back Link** | Navigasi    | Link "Kembali ke Login"                        |

---

### 7. Reset Password `/reset-password/{token}`

**Purpose:** Halaman ganti password dari link email.

#### Sections:

| Section     | Deskripsi      | Komponen                                                         |
| ----------- | -------------- | ---------------------------------------------------------------- |
| **Form**    | Input password | Password baru, Konfirmasi password baru, Tombol "Reset Password" |
| **Success** | Konfirmasi     | Redirect ke login dengan pesan sukses                            |

---

### 8. User Dashboard `/dashboard`

**Purpose:** Overview aktivitas peminjaman user.

#### Sections:

| Section                 | Deskripsi          | Komponen                                                                                       |
| ----------------------- | ------------------ | ---------------------------------------------------------------------------------------------- |
| **Welcome Header**      | Greeting           | "Selamat datang, [Nama]", Tanggal hari ini                                                     |
| **Alert (jika ada)**    | Notifikasi penting | Alert merah: buku terlambat, Alert kuning: tagihan belum lunas, Alert info: H-2 jatuh tempo    |
| **Stats Cards**         | Statistik cepat    | 4 card: Sedang dipinjam, Total dipinjam, Wishlist, Outstanding tagihan                         |
| **Pinjaman Aktif**      | Buku dipinjam      | List: Cover, Judul, Jatuh tempo, Countdown, Status, Biaya, Tombol "Perpanjang" (jika eligible) |
| **Request Pending**     | Menunggu approval  | List request yang masih pending verifikasi pembayaran                                          |
| **Outstanding Payment** | Tagihan            | Alert + total tagihan + link ke detail payment                                                 |
| **Quick Actions**       | Aksi cepat         | Tombol ke Katalog, Tombol ke Wishlist                                                          |

---

### 9. My Borrowings `/my-borrowings`

**Purpose:** Histori lengkap semua peminjaman.

#### Sections:

| Section            | Deskripsi         | Komponen                                                                                                                                  |
| ------------------ | ----------------- | ----------------------------------------------------------------------------------------------------------------------------------------- |
| **Page Header**    | Judul             | Title "Riwayat Peminjaman", Filter tabs                                                                                                   |
| **Filter Tabs**    | Filter status     | Semua, Pending, Aktif, Dikembalikan, Terlambat                                                                                            |
| **Borrowing List** | Daftar peminjaman | Card: Kode, Cover, Judul, Tanggal pinjam, Jatuh tempo, Status badge, Biaya, Status bayar, Tombol "Perpanjang" jika aktif dan belum pernah |
| **Pagination**     | Navigasi          | Pagination standard                                                                                                                       |
| **Empty State**    | Jika kosong       | "Belum ada riwayat peminjaman", CTA ke katalog                                                                                            |

**Borrowing Card dengan Perpanjangan:**

```
┌─────────────────────────────────────────────────────────────┐
│ BRW-20241225-001                                   🟢 Aktif │
│ ┌─────┐                                                     │
│ │Cover│ Laskar Pelangi - Andrea Hirata                      │
│ └─────┘                                                     │
│ Dipinjam: 25 Des 2024                                       │
│ Jatuh Tempo: 1 Jan 2025 (7 hari lagi)                       │
│ Biaya Sewa: Rp 5.000 ✅ Lunas                               │
│                                                             │
│ [🔄 Perpanjang 7 Hari] (jika eligible)                      │
└─────────────────────────────────────────────────────────────┘
```

---

### 10. Wishlist `/wishlist`

**Purpose:** Daftar buku yang disimpan user.

#### Sections:

| Section           | Deskripsi   | Komponen                                                                 |
| ----------------- | ----------- | ------------------------------------------------------------------------ |
| **Page Header**   | Judul       | Title "Wishlist Saya", Badge jumlah item                                 |
| **Wishlist Grid** | Daftar buku | Grid card buku dengan tombol "Pinjam" (jika tersedia) dan "Hapus"        |
| **Book Card**     | Per buku    | Cover, Judul, Penulis, Status tersedia/habis, Tombol "Pinjam", Tombol ❌ |
| **Empty State**   | Jika kosong | "Wishlist kosong", CTA ke katalog                                        |

---

### 11. Request Pinjam Flow (Modal/Page)

**Purpose:** Flow untuk request pinjam buku dengan upload bukti bayar.

#### Steps:

| Step                     | Deskripsi               | Komponen                                                              |
| ------------------------ | ----------------------- | --------------------------------------------------------------------- |
| **Step 1: Konfirmasi**   | Konfirmasi buku & harga | Info buku, Harga sewa 7 hari, Total yang harus dibayar                |
| **Step 2: Metode Bayar** | Pilih metode            | Pilihan: Cash / Transfer QRIS                                         |
| **Step 3: Upload Bukti** | Upload bukti transfer   | FileUpload untuk bukti transfer, Info rekening tujuan (jika transfer) |
| **Step 4: Submit**       | Kirim request           | Tombol "Kirim Request Peminjaman"                                     |
| **Step 5: Pending**      | Konfirmasi              | Pesan "Request dikirim, menunggu verifikasi admin", Kode request      |

---

### 12. Perpanjangan Flow (Modal)

**Purpose:** Perpanjang peminjaman 7 hari lagi.

#### Sections:

| Section             | Deskripsi      | Komponen                                               |
| ------------------- | -------------- | ------------------------------------------------------ |
| **Info Peminjaman** | Detail current | Buku, Jatuh tempo sekarang, Jatuh tempo baru (+7 hari) |
| **Biaya**           | Harga          | Biaya perpanjangan = sama dengan harga sewa awal       |
| **Upload Bukti**    | Bukti bayar    | FileUpload untuk bukti transfer                        |
| **Submit**          | Tombol         | "Kirim Request Perpanjangan"                           |

**Rules:**

-   Maksimal 1x perpanjangan per peminjaman
-   Hanya bisa di hari H (deadline)
-   Tidak bisa jika sudah terlambat

---

### 13. Payment History `/my-payments`

**Purpose:** Histori pembayaran user.

#### Sections:

| Section           | Deskripsi        | Komponen                                                              |
| ----------------- | ---------------- | --------------------------------------------------------------------- |
| **Page Header**   | Judul            | Title "Riwayat Pembayaran"                                            |
| **Filter**        | Filter status    | Semua, Pending, Verified, Rejected                                    |
| **Payment List**  | Daftar transaksi | Card: Tanggal, Jenis (sewa/perpanjangan/denda), Jumlah, Bukti, Status |
| **Total Tagihan** | Outstanding      | Card merah jika ada tagihan belum lunas                               |

---

### 14. Profile Settings `/profile`

**Purpose:** Edit profil dan password.

#### Sections:

| Section             | Deskripsi      | Komponen                                                                              |
| ------------------- | -------------- | ------------------------------------------------------------------------------------- |
| **Profile Info**    | Data profil    | Form: Nama, Email (read-only), No. HP, Alamat, Tombol "Simpan Perubahan"              |
| **Change Password** | Ganti password | Form: Password lama, Password baru, Konfirmasi password baru, Tombol "Ganti Password" |

---

## 🔒 ADMIN PAGES (Filament v3)

---

### 1. Admin Dashboard `/admin`

**Purpose:** Overview statistik dan quick actions.

#### Widgets:

| Widget                   | Type         | Deskripsi                                                   |
| ------------------------ | ------------ | ----------------------------------------------------------- |
| **Stats Overview**       | Stat Cards   | Total Buku, Total Eksemplar, Tersedia, Dipinjam             |
| **Member Stats**         | Stat Cards   | Total Member, Member Aktif                                  |
| **Pending Verification** | Stat Card    | Badge jumlah request pending verifikasi (clickable)         |
| **Overdue Alert**        | Alert Widget | Alert merah jika ada peminjaman terlambat                   |
| **Revenue Summary**      | Stat Cards   | Revenue hari ini, Minggu ini, Bulan ini                     |
| **Recent Borrowings**    | Table Widget | 10 peminjaman terakhir: Kode, Member, Buku, Tanggal, Status |
| **Top Books**            | Chart/List   | 5 buku paling sering dipinjam                               |
| **Quick Actions**        | Buttons      | Tombol ke Pending Requests, Tombol ke Overdue               |

---

### 2. Books Resource `/admin/books`

**Purpose:** CRUD buku.

#### Table Columns:

| Column            | Searchable | Sortable |
| ----------------- | ---------- | -------- |
| Cover (thumbnail) | ❌         | ❌       |
| Judul             | ✅         | ✅       |
| Penulis           | ✅         | ✅       |
| Penerbit          | ✅         | ❌       |
| Kategori          | ✅         | ✅       |
| Tersedia/Total    | ❌         | ✅       |
| Biaya Sewa        | ❌         | ✅       |
| Kali Dipinjam     | ❌         | ✅       |

#### Form Fields (Create/Edit):

| Field            | Type                           | Required         |
| ---------------- | ------------------------------ | ---------------- |
| Gambar Cover     | FileUpload (image)             | ❌               |
| Judul            | TextInput                      | ✅               |
| Penulis          | TextInput                      | ✅               |
| Penerbit         | TextInput                      | ❌               |
| Tahun Terbit     | TextInput (year)               | ❌               |
| Kategori         | Select (relationship)          | ✅               |
| ISBN             | TextInput                      | ❌               |
| Sinopsis         | RichEditor / Textarea          | ❌               |
| Biaya Sewa       | TextInput (numeric, prefix Rp) | ✅               |
| Jumlah Eksemplar | TextInput (numeric)            | ✅ (create only) |

#### Header Actions:

| Action           | Deskripsi                        |
| ---------------- | -------------------------------- |
| **Import Excel** | Bulk import buku dari file Excel |
| **Export Excel** | Export daftar buku ke Excel      |

#### Relation Manager:

| Tab           | Deskripsi                                                     |
| ------------- | ------------------------------------------------------------- |
| **Eksemplar** | Table copies: Kode copy, Status, Notes, Actions (edit status) |

---

### 3. Categories Resource `/admin/categories`

**Purpose:** CRUD kategori buku dengan icon.

#### Table Columns:

| Column      | Searchable | Sortable |
| ----------- | ---------- | -------- |
| Icon        | ❌         | ❌       |
| Nama        | ✅         | ✅       |
| Deskripsi   | ❌         | ❌       |
| Jumlah Buku | ❌         | ✅       |

#### Form Fields:

| Field     | Type                 | Required |
| --------- | -------------------- | -------- |
| Icon      | Select (icon picker) | ❌       |
| Nama      | TextInput            | ✅       |
| Deskripsi | Textarea             | ❌       |

---

### 4. Borrowings Resource `/admin/borrowings`

**Purpose:** Manage peminjaman, approval, return.

#### Table Columns:

| Column          | Searchable | Sortable |
| --------------- | ---------- | -------- |
| Kode Pinjam     | ✅         | ✅       |
| Member          | ✅         | ✅       |
| Buku            | ✅         | ✅       |
| Kode Eksemplar  | ✅         | ❌       |
| Tanggal Pinjam  | ❌         | ✅       |
| Jatuh Tempo     | ❌         | ✅       |
| Tanggal Kembali | ❌         | ✅       |
| Status          | ❌         | ✅       |
| Biaya Sewa      | ❌         | ✅       |
| Denda           | ❌         | ✅       |
| Total           | ❌         | ✅       |
| Lunas           | ❌         | ✅       |

#### Filters:

| Filter         | Type                                          |
| -------------- | --------------------------------------------- |
| Status         | Select (Pending/Aktif/Dikembalikan/Terlambat) |
| Pembayaran     | Select (Lunas/Belum)                          |
| Tanggal Pinjam | DateRange                                     |

#### Actions:

| Action                 | Kondisi           | Deskripsi                                              |
| ---------------------- | ----------------- | ------------------------------------------------------ |
| **Approve**            | status = pending  | Approve request, set active, kurangi stok              |
| **Reject**             | status = pending  | Reject dengan notes alasan                             |
| **Kembalikan**         | status = active   | Modal: pilih kondisi (Baik/Rusak/Hilang), hitung denda |
| **Tandai Lunas**       | is_paid = false   | Set is_paid = true                                     |
| **Approve Perpanjang** | has extension req | Approve perpanjangan, update due_date +7 hari          |
| **Lihat Detail**       | Always            | Modal detail lengkap + bukti transfer                  |

#### Return Modal (Kondisi Buku):

| Kondisi    | Denda               |
| ---------- | ------------------- |
| **Baik**   | Tidak ada denda     |
| **Rusak**  | 50% dari harga buku |
| **Hilang** | 75% dari harga buku |

---

### 5. Users Resource `/admin/users`

**Purpose:** Lihat dan manage member.

#### Table Columns:

| Column         | Searchable | Sortable |
| -------------- | ---------- | -------- |
| Nama           | ✅         | ✅       |
| Email          | ✅         | ✅       |
| No. HP         | ✅         | ❌       |
| Status         | ❌         | ✅       |
| Pinjaman Aktif | ❌         | ✅       |
| Tanggal Daftar | ❌         | ✅       |

#### Form Fields (Edit):

| Field  | Type                    | Deskripsi    |
| ------ | ----------------------- | ------------ |
| Nama   | TextInput               | Nama lengkap |
| Email  | TextInput (disabled)    | Email (read) |
| No. HP | TextInput               | Telepon      |
| Status | Toggle (Aktif/Nonaktif) | Status akun  |

#### Actions:

| Action             | Deskripsi                               |
| ------------------ | --------------------------------------- |
| **Lihat Pinjaman** | Relation manager: semua borrowings user |
| **Suspend**        | Nonaktifkan user                        |
| **Activate**       | Aktifkan kembali user                   |

---

### 6. Payments Resource `/admin/payments`

**Purpose:** Verifikasi pembayaran.

#### Table Columns:

| Column         | Searchable | Sortable |
| -------------- | ---------- | -------- |
| Tanggal        | ❌         | ✅       |
| Member         | ✅         | ✅       |
| Kode Pinjam    | ✅         | ❌       |
| Jenis          | ❌         | ✅       |
| Jumlah         | ❌         | ✅       |
| Bukti Transfer | ❌         | ❌       |
| Status         | ❌         | ✅       |

#### Filters:

| Filter | Type                               |
| ------ | ---------------------------------- |
| Status | Select (Pending/Verified/Rejected) |
| Jenis  | Select (Sewa/Perpanjangan/Denda)   |

#### Actions:

| Action         | Deskripsi                        |
| -------------- | -------------------------------- |
| **Verify**     | Approve pembayaran               |
| **Reject**     | Reject dengan notes              |
| **View Proof** | Modal untuk lihat bukti transfer |

---

### 7. Reports Page `/admin/reports`

**Purpose:** Laporan & Analytics.

#### Tabs/Sections:

| Report                 | Deskripsi                                 | Komponen                                         |
| ---------------------- | ----------------------------------------- | ------------------------------------------------ |
| **Buku Terpopuler**    | Ranking buku berdasarkan total peminjaman | Filter periode, Table ranking, Bar chart, Export |
| **Peminjam Teraktif**  | Ranking member berdasarkan frekuensi      | Filter periode, Table: Nama, Total, Status       |
| **Revenue**            | Grafik pendapatan                         | Line chart 12 bulan, Breakdown by type, Export   |
| **Laporan Peminjaman** | Detail semua transaksi                    | Filter (status, tanggal, member, buku), Export   |

#### Filter Periode (semua report):

| Option           | Nilai         |
| ---------------- | ------------- |
| Bulan ini        | Current month |
| 3 bulan terakhir | Last 3 months |
| 6 bulan terakhir | Last 6 months |
| Tahun ini        | Current year  |
| Custom           | Date range    |

#### Export Options:

-   Excel (.xlsx)
-   PDF (untuk Revenue report)

---

### 8. Settings Page `/admin/settings`

**Purpose:** Konfigurasi sistem.

#### Form Sections:

**Pengaturan Peminjaman:**

| Setting            | Type        | Deskripsi                               |
| ------------------ | ----------- | --------------------------------------- |
| Denda per Hari     | Number (Rp) | Denda keterlambatan per hari            |
| Maks Hari Pinjam   | Number      | Durasi maksimal peminjaman              |
| Maks Buku per User | Number      | Batas buku yang bisa dipinjam bersamaan |
| Denda Buku Rusak   | Number (%)  | Persentase harga buku (default 50%)     |
| Denda Buku Hilang  | Number (%)  | Persentase harga buku (default 75%)     |

**Informasi Perpustakaan:**

| Setting           | Type     | Deskripsi           |
| ----------------- | -------- | ------------------- |
| Nama Perpustakaan | Text     | Nama untuk branding |
| Alamat            | Textarea | Alamat lengkap      |
| No. Telepon       | Text     | Kontak telepon      |
| Email             | Text     | Email perpustakaan  |
| Jam Operasional   | Text     | Jam buka-tutup      |

**Rekening Pembayaran:**

| Setting      | Type       | Deskripsi                |
| ------------ | ---------- | ------------------------ |
| Nama Bank    | Text       | Nama bank untuk transfer |
| No. Rekening | Text       | Nomor rekening           |
| Atas Nama    | Text       | Nama pemilik rekening    |
| QRIS Image   | FileUpload | Gambar QRIS              |

---

## 📱 Responsive Breakpoints

| Device  | Width      | Grid Columns |
| ------- | ---------- | ------------ |
| Mobile  | < 640px    | 1 column     |
| Tablet  | 640-1024px | 2 columns    |
| Desktop | > 1024px   | 3-4 columns  |

---

## 🎨 Design System

### Colors

| Name    | Usage             | Tailwind Class |
| ------- | ----------------- | -------------- |
| Primary | CTA, links        | `blue-600`     |
| Success | Available, paid   | `green-600`    |
| Warning | Due soon, pending | `yellow-600`   |
| Danger  | Overdue, unpaid   | `red-600`      |
| Info    | Notification      | `blue-500`     |
| Neutral | Text, borders     | `gray-*`       |

### Status Badges

| Status       | Background      | Text              |
| ------------ | --------------- | ----------------- |
| Tersedia     | `bg-green-100`  | `text-green-800`  |
| Dipinjam     | `bg-yellow-100` | `text-yellow-800` |
| Habis        | `bg-red-100`    | `text-red-800`    |
| Pending      | `bg-yellow-100` | `text-yellow-800` |
| Aktif        | `bg-green-100`  | `text-green-800`  |
| Dikembalikan | `bg-blue-100`   | `text-blue-800`   |
| Terlambat    | `bg-red-100`    | `text-red-800`    |
| Lunas        | `bg-green-100`  | `text-green-800`  |
| Belum Lunas  | `bg-red-100`    | `text-red-800`    |
| Verified     | `bg-green-100`  | `text-green-800`  |
| Rejected     | `bg-red-100`    | `text-red-800`    |
| Suspended    | `bg-gray-100`   | `text-gray-800`   |
