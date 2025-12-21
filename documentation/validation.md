# Sukabaca - Validation Rules & Error Messages

## Form Validation Rules

All validation rules for the application, organized by form/request.

---

## User Authentication

### Registration

```php
[
    'name' => 'required|string|max:255',
    'email' => 'required|string|email|max:255|unique:users',
    'password' => 'required|string|min:8|confirmed',
    'phone' => 'nullable|string|max:20',
]
```

### Login

```php
[
    'email' => 'required|string|email',
    'password' => 'required|string',
]
```

### Update Profile

```php
[
    'name' => 'required|string|max:255',
    'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
    'phone' => 'nullable|string|max:20',
]
```

### Change Password

```php
[
    'current_password' => 'required|current_password',
    'password' => 'required|string|min:8|confirmed',
]
```

---

## Category Management

### Store Category

```php
[
    'name' => 'required|string|max:100|unique:categories,name',
    'description' => 'nullable|string|max:1000',
]
```

### Update Category

```php
[
    'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
    'description' => 'nullable|string|max:1000',
]
```

---

## Book Management

### Store Book

```php
[
    'title' => 'required|string|max:255',
    'author' => 'required|string|max:255',
    'category_id' => 'required|exists:categories,id',
    'isbn' => 'nullable|string|max:20|unique:books,isbn',
    'description' => 'nullable|string|max:5000',
    'copies' => 'required|integer|min:1|max:100',
]
```

### Update Book

```php
[
    'title' => 'required|string|max:255',
    'author' => 'required|string|max:255',
    'category_id' => 'required|exists:categories,id',
    'isbn' => 'nullable|string|max:20|unique:books,isbn,' . $book->id,
    'description' => 'nullable|string|max:5000',
]
```

### Add Copies

```php
[
    'count' => 'required|integer|min:1|max:50',
]
```

---

## Book Copy Management

### Update Copy Status

```php
[
    'status' => 'required|in:available,maintenance,lost',
    'notes' => 'nullable|string|max:1000',
]
```

---

## Borrowing

### Create Borrowing

```php
[
    'duration' => 'required|integer|in:7,14',
]
```

### Return Book (Admin)

```php
// No validation needed, just borrowing ID
```

### Mark as Paid

```php
// No validation needed, just borrowing ID
```

---

## Custom Validation Messages (Indonesian)

Create `resources/lang/id/validation.php`:

```php
<?php

return [
    'required' => ':attribute wajib diisi.',
    'string' => ':attribute harus berupa teks.',
    'email' => ':attribute harus berupa email yang valid.',
    'max' => [
        'string' => ':attribute maksimal :max karakter.',
    ],
    'min' => [
        'string' => ':attribute minimal :min karakter.',
    ],
    'unique' => ':attribute sudah digunakan.',
    'exists' => ':attribute tidak ditemukan.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'in' => ':attribute tidak valid.',
    'integer' => ':attribute harus berupa angka.',
    'between' => [
        'numeric' => ':attribute harus antara :min dan :max.',
    ],

    'attributes' => [
        'name' => 'Nama',
        'email' => 'Email',
        'password' => 'Password',
        'phone' => 'Nomor Telepon',
        'title' => 'Judul',
        'author' => 'Penulis',
        'category_id' => 'Kategori',
        'isbn' => 'ISBN',
        'description' => 'Deskripsi',
        'copies' => 'Jumlah Copy',
        'count' => 'Jumlah',
        'duration' => 'Durasi',
        'status' => 'Status',
        'notes' => 'Catatan',
    ],
];
```

---

## Form Request Example

### StoreBookRequest

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'isbn' => 'nullable|string|max:20|unique:books,isbn',
            'description' => 'nullable|string|max:5000',
            'copies' => 'required|integer|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul buku wajib diisi.',
            'author.required' => 'Nama penulis wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori tidak ditemukan.',
            'isbn.unique' => 'ISBN sudah terdaftar.',
            'copies.min' => 'Minimal 1 copy.',
            'copies.max' => 'Maksimal 100 copy.',
        ];
    }
}
```

---

## Business Rule Validations

These are validated in Service layer, not Form Requests:

### BorrowingService

```php
// User can borrow max 3 books at a time
if ($user->activeBorrowings()->count() >= 3) {
    throw new \Exception('Maksimal peminjaman 3 buku.');
}

// Book must have available copy
if (!$book->isAvailable()) {
    throw new \Exception('Tidak ada copy tersedia untuk buku ini.');
}

// Cannot return already returned book
if ($borrowing->status === 'returned') {
    throw new \Exception('Buku sudah dikembalikan.');
}
```

### BookService

```php
// Cannot change status of borrowed copy
if ($copy->isBorrowed() && $status !== 'borrowed') {
    throw new \Exception('Tidak bisa mengubah status copy yang sedang dipinjam.');
}
```

---

## Displaying Validation Errors in Blade

```blade
{{-- All errors --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Specific field error --}}
<input type="text" name="title" value="{{ old('title') }}"
       class="@error('title') is-invalid @enderror">
@error('title')
    <span class="text-danger">{{ $message }}</span>
@enderror
```
