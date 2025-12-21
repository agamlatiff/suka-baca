# Admin Panel (Laravel Filament)

## Overview

The admin panel is built using **Laravel Filament v3**, providing a modern, feature-rich CMS out of the box.

### Why Filament?

| Benefit           | Description                           |
| ----------------- | ------------------------------------- |
| Rapid development | Auto-generates CRUD from models       |
| Modern UI         | Beautiful, responsive design          |
| Built-in features | Search, filters, bulk actions, export |
| Customizable      | Easy to extend and customize          |

---

## Installation

```bash
# Install Filament
composer require filament/filament:"^3.2"

# Install panel
php artisan filament:install --panels

# Create admin user
php artisan make:filament-user
```

---

## Admin Panel Structure

```
app/Filament/
├── Resources/
│   ├── BookResource.php
│   ├── BookCopyResource.php
│   ├── CategoryResource.php
│   ├── BorrowingResource.php
│   ├── UserResource.php
│   └── SettingResource.php
├── Widgets/
│   ├── StatsOverview.php
│   ├── RecentBorrowings.php
│   └── TopBooks.php
└── Pages/
    └── Dashboard.php
```

---

## Resources

### 1. BookResource

Manages book master data.

**List Columns:**
| Column | Type | Searchable | Sortable |
|--------|------|------------|----------|
| Title | Text | ✅ | ✅ |
| Author | Text | ✅ | ✅ |
| Category | Relationship | ✅ | ❌ |
| Available/Total | Badge | ❌ | ✅ |
| Times Borrowed | Number | ❌ | ✅ |

**Form Fields:**
| Field | Type | Required |
|-------|------|----------|
| Title | TextInput | ✅ |
| Author | TextInput | ✅ |
| Category | Select | ✅ |
| ISBN | TextInput | ❌ |
| Description | Textarea | ❌ |
| Rental Fee | MoneyInput | ✅ |
| Copies Count | NumberInput | ✅ |

---

### 2. CategoryResource

Manages book categories.

**List Columns:**
| Column | Type |
|--------|------|
| Name | Text |
| Description | Text |
| Books Count | Badge |

**Form Fields:**
| Field | Type | Required |
|-------|------|----------|
| Name | TextInput | ✅ |
| Description | Textarea | ❌ |

---

### 3. BorrowingResource

Manages all borrowing transactions.

**List Columns:**
| Column | Type |
|--------|------|
| Borrowing Code | Text |
| User | Relationship |
| Book Title | Relationship |
| Copy Code | Relationship |
| Borrowed At | Date |
| Due Date | Date |
| Status | Badge |
| Is Paid | Icon |

**Filters:**
| Filter | Type |
|--------|------|
| Status | Select (Active/Returned/Overdue) |
| Payment | Boolean (Paid/Unpaid) |
| Date Range | DatePicker |

**Actions:**
| Action | Description |
|--------|-------------|
| Return | Mark as returned, free up copy |
| Mark Paid | Update payment status |

---

### 4. UserResource

Manages user accounts.

**List Columns:**
| Column | Type |
|--------|------|
| Name | Text |
| Email | Text |
| Role | Badge |
| Phone | Text |
| Borrowings Count | Badge |

---

### 5. SettingResource

Manages system-wide settings like late fee.

**List Columns:**
| Column | Type |
|--------|------|
| Key | Text |
| Value | Text |
| Description | Text |

**Form Fields:**
| Field | Type | Required |
|-------|------|----------|
| Key | TextInput (readonly) | ✅ |
| Value | TextInput | ✅ |
| Description | TextInput | ❌ |

**Default Settings:**
| Key | Description |
|-----|-------------|
| late_fee_per_day | Late penalty per day (Rp) |
| max_borrow_days | Maximum borrowing duration |
| max_books_per_user | Max books per user at once |

## Dashboard Widgets

### StatsOverview Widget

```php
class StatsOverview extends Widget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Books', Book::count()),
            Stat::make('Total Copies', BookCopy::count()),
            Stat::make('Available', BookCopy::available()->count()),
            Stat::make('Borrowed', BookCopy::borrowed()->count()),
            Stat::make('Total Users', User::where('role', 'user')->count()),
            Stat::make('Active Loans', Borrowing::active()->count()),
        ];
    }
}
```

### RecentBorrowings Widget

Displays a table of the 10 most recent borrowing transactions.

### TopBooks Widget

Shows top 5 most borrowed books as a list or chart.

---

## Custom Actions

### Return Book Action

```php
Tables\Actions\Action::make('return')
    ->label('Return')
    ->icon('heroicon-o-arrow-uturn-left')
    ->requiresConfirmation()
    ->visible(fn (Borrowing $record) => $record->status === 'active')
    ->action(function (Borrowing $record) {
        // Calculate late fee if overdue
        $record->processReturn();
    });
```

### Mark as Paid Action

```php
Tables\Actions\Action::make('markPaid')
    ->label('Mark Paid')
    ->icon('heroicon-o-check-circle')
    ->visible(fn (Borrowing $record) => !$record->is_paid)
    ->action(fn (Borrowing $record) => $record->update(['is_paid' => true]));
```

---

## Filament Configuration

### Access Control

Only allow admin users to access the panel:

```php
// app/Providers/Filament/AdminPanelProvider.php
public function panel(Panel $panel): Panel
{
    return $panel
        ->authMiddleware([
            Authenticate::class,
        ])
        ->authGuard('web');
}
```

### Model Policy (Optional)

```php
// app/Policies/BookPolicy.php
public function viewAny(User $user): bool
{
    return $user->role === 'admin';
}
```

---

## Admin Routes

| URI                 | Description          |
| ------------------- | -------------------- |
| `/admin`            | Dashboard            |
| `/admin/books`      | Book management      |
| `/admin/categories` | Category management  |
| `/admin/borrowings` | Borrowing management |
| `/admin/users`      | User management      |

---

## Customization Tips

### Adding Book Copies Inline

Use `RelationManager` to manage copies within the Book resource:

```php
class CopiesRelationManager extends RelationManager
{
    protected static string $relationship = 'copies';

    // Table and form configuration
}
```

### Custom Dashboard

Create a custom dashboard page with specific widgets:

```php
class Dashboard extends BaseDashboard
{
    protected function getWidgets(): array
    {
        return [
            StatsOverview::class,
            RecentBorrowings::class,
            TopBooks::class,
        ];
    }
}
```
