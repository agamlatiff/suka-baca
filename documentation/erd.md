erDiagram
    users ||--o{ borrowings : "melakukan"
    categories ||--o{ books : "memiliki"
    books ||--o{ book_copies : "memiliki"
    book_copies ||--o{ borrowings : "dipinjam"

    users {
        bigint id PK
        string name
        string email UK
        string password
        enum role
        string phone
        timestamp email_verified_at
        timestamp created_at
        timestamp updated_at
    }

    categories {
        bigint id PK
        string name UK
        string description
        timestamp created_at
        timestamp updated_at
    }

    books {
        bigint id PK
        string title
        string author
        bigint category_id FK
        string isbn
        text description
        int total_copies
        int available_copies
        int times_borrowed
        timestamp created_at
        timestamp updated_at
    }

    book_copies {
        bigint id PK
        bigint book_id FK
        string copy_code UK
        enum status
        text notes
        timestamp created_at
        timestamp updated_at
    }

    borrowings {
        bigint id PK
        string borrowing_code UK
        bigint user_id FK
        bigint book_copy_id FK
        date borrowed_at
        date due_date
        date returned_at
        decimal rental_fee
        decimal late_fee
        decimal total_fee
        boolean is_paid
        enum status
        int days_late
        timestamp created_at
        timestamp updated_at
    }
    
    