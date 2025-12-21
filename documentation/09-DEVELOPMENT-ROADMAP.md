# Development Roadmap

## Project Timeline

**Total Estimated Duration:** 13 working days (~2.5 weeks)

---

## Phase 1: MVP Development

| Day   | Module             | Tasks                                               | Estimated Time |
| ----- | ------------------ | --------------------------------------------------- | -------------- |
| 1     | Setup              | Laravel install, Breeze, Filament, Database config  | 1 day          |
| 2-3   | Books & Categories | CRUD books, categories, Filament resources          | 2 days         |
| 4-5   | Book Copies        | Copy management, auto-generate IDs, status tracking | 1.5 days       |
| 6     | User Catalog       | Book list, search, filter, detail page              | 1 day          |
| 7-9   | Borrowing          | Borrow flow, return process, status management      | 3 days         |
| 10    | Fees & Fines       | Rental fee, late fee calculation, payment marking   | 1 day          |
| 11    | Admin Dashboard    | Statistics widgets, recent borrowings, top books    | 1 day          |
| 12    | User Dashboard     | Active borrowings, history, fees display            | 1 day          |
| 13-14 | Testing            | Manual testing, bug fixes, polish                   | 1.5 days       |

---

## Detailed Task Breakdown

### Day 1: Project Setup

-   [ ] Install Laravel 10
-   [ ] Configure MySQL database
-   [ ] Install Laravel Breeze
-   [ ] Install Laravel Filament
-   [ ] Set up Tailwind CSS
-   [ ] Create initial migrations
-   [ ] Create admin seeder

### Day 2-3: Books & Categories

-   [ ] Create Category model, migration, seeder
-   [ ] Create Book model, migration
-   [ ] Create CategoryResource (Filament)
-   [ ] Create BookResource (Filament)
-   [ ] Implement book form with category dropdown
-   [ ] Add search and filter to book list

### Day 4-5: Book Copies

-   [ ] Create BookCopy model, migration
-   [ ] Implement copy auto-generation
-   [ ] Add copy code format (BK001-C01)
-   [ ] Create CopiesRelationManager
-   [ ] Track copy status (available/borrowed)
-   [ ] Update available_copies on book

### Day 6: User Catalog

-   [ ] Create CatalogController
-   [ ] Create catalog index view
-   [ ] Implement search by title/author
-   [ ] Add category filter
-   [ ] Show availability status
-   [ ] Create book detail view

### Day 7-9: Borrowing System

-   [ ] Create Borrowing model, migration
-   [ ] Implement borrow action on catalog
-   [ ] Auto-assign available copy
-   [ ] Generate borrowing code
-   [ ] Calculate due date (7/14 days)
-   [ ] Create BorrowingResource (Filament)
-   [ ] Implement return action
-   [ ] Update copy status on return
-   [ ] Increment times_borrowed counter
-   [ ] Handle overdue detection

### Day 10: Fees & Fines

-   [ ] Add fee columns to borrowings
-   [ ] Implement rental fee setting
-   [ ] Calculate late fee automatically
-   [ ] Display fees on user dashboard
-   [ ] Add "Mark as Paid" action
-   [ ] Show unpaid borrowings

### Day 11: Admin Dashboard

-   [ ] Create StatsOverview widget
-   [ ] Show total books, copies, available
-   [ ] Show active borrowings count
-   [ ] Create RecentBorrowings widget
-   [ ] Create TopBooks widget
-   [ ] Configure dashboard layout

### Day 12: User Dashboard

-   [ ] Create user dashboard view
-   [ ] Display active borrowings
-   [ ] Show due dates and status
-   [ ] Calculate days remaining/overdue
-   [ ] Show total fees owed
-   [ ] Add borrowing history section

### Day 13-14: Testing & Polish

-   [ ] Test registration flow
-   [ ] Test login/logout
-   [ ] Test catalog browsing
-   [ ] Test borrowing flow
-   [ ] Test return process
-   [ ] Test fee calculations
-   [ ] Fix any bugs found
-   [ ] Polish UI/UX
-   [ ] Mobile responsive check

---

## Milestones

| Milestone | Target | Deliverable                         |
| --------- | ------ | ----------------------------------- |
| M1        | Day 3  | Admin can manage books & categories |
| M2        | Day 6  | Users can browse catalog            |
| M3        | Day 9  | Full borrowing flow working         |
| M4        | Day 12 | All dashboards complete             |
| M5        | Day 14 | MVP ready for deployment            |

---

## Sprint Structure (Suggested)

### Sprint 1 (Day 1-5): Foundation

-   Setup & configuration
-   Core data models
-   Book management

### Sprint 2 (Day 6-10): Core Features

-   User catalog
-   Borrowing system
-   Fee management

### Sprint 3 (Day 11-14): Polish

-   Dashboards
-   Testing
-   Bug fixes

---

## Risk Mitigation

| Risk                    | Mitigation                       |
| ----------------------- | -------------------------------- |
| Scope creep             | Stick to MVP features only       |
| Complex borrowing logic | Break into small steps           |
| Filament learning curve | Use official docs, copy examples |
| Integration issues      | Test each module independently   |

---

## Post-MVP Support

**Included (2 weeks after delivery):**

-   Bug fixes for issues found
-   Minor adjustments
-   Deployment support

**Not Included:**

-   New feature development
-   Major UI changes
-   Server/hosting management
