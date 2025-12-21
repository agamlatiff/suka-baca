# Sukabaca - Project Overview

## What is Sukabaca?

Sukabaca is a **simplified library management system** designed for small to medium-sized book rental businesses. It provides essential features for managing books, tracking borrowings, and handling fees/fines.

## Project Scope (MVP Version)

This is a **Minimum Viable Product** focusing on core functionality:

### ✅ Included Features

| Module          | Complexity | Description                                              |
| --------------- | ---------- | -------------------------------------------------------- |
| Authentication  | Low        | Admin/User login, registration, session management       |
| Book Management | Medium     | CRUD books, copy tracking, availability status           |
| Catalog (User)  | Low        | Browse books, search, filter by category                 |
| Borrowing       | Medium     | Borrow/return flow, auto-assign copies                   |
| Fees & Fines    | Low        | Dynamic rental fee per book, admin-configurable late fee |
| Admin Dashboard | Low        | Basic statistics, recent borrowings, top books           |
| User Dashboard  | Low        | Active borrowings, history, fees summary                 |

### ❌ Excluded Features (Phase 2)

-   Book cover uploads
-   Email/WhatsApp notifications
-   Book reservations
-   Rating/reviews
-   Auto-renewal
-   Multiple categories per book
-   Report exports
-   Charts/graphs

## Target Users

| User Type | Role                | Access                                        |
| --------- | ------------------- | --------------------------------------------- |
| **Admin** | Library staff/owner | Full system access, manage books & borrowings |
| **User**  | Library members     | Browse catalog, borrow books, view history    |

## Business Model

-   **Rental Fee**: Dynamic per book (admin sets price for each book)
-   **Late Fee**: Admin-configurable penalty rate per day (system setting)
-   **Payment**: Manual (cash/bank transfer) - admin confirms payment

## Documents in This Series

| File                                                     | Description                |
| -------------------------------------------------------- | -------------------------- |
| [01-ARCHITECTURE.md](./01-ARCHITECTURE.md)               | Tech stack & system design |
| [02-DATABASE.md](./02-DATABASE.md)                       | Database schema & ERD      |
| [03-AUTHENTICATION.md](./03-AUTHENTICATION.md)           | Auth implementation        |
| [04-API-ENDPOINTS.md](./04-API-ENDPOINTS.md)             | Routes & pages             |
| [05-COMPONENTS.md](./05-COMPONENTS.md)                   | Feature modules            |
| [06-FEATURES.md](./06-FEATURES.md)                       | User flows                 |
| [07-DEPLOYMENT.md](./07-DEPLOYMENT.md)                   | Deployment guide           |
| [08-ADMIN-PANELS.md](./08-ADMIN-PANELS.md)               | Filament admin             |
| [09-DEVELOPMENT-ROADMAP.md](./09-DEVELOPMENT-ROADMAP.md) | Timeline                   |
| [10-PHASE-2.md](./10-PHASE-2.md)                         | Future features            |
