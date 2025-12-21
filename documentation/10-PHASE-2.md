# Phase 2: Future Features

> These features are **not included** in the MVP but can be added after successful deployment and client satisfaction.

---

## Feature Priorities

| Priority  | Feature             | Effort   | Value                    |
| --------- | ------------------- | -------- | ------------------------ |
| 🔴 High   | Email notifications | 2-3 days | Reduces manual follow-up |
| 🔴 High   | Book cover uploads  | 1-2 days | Better visual catalog    |
| 🟡 Medium | Data export (Excel) | 1-2 days | Reporting capability     |
| 🟡 Medium | Advanced filters    | 1 day    | Better user experience   |
| 🟡 Medium | Loan extension      | 2 days   | User convenience         |
| 🟢 Low    | Statistics charts   | 1-2 days | Visual dashboards        |
| 🟢 Low    | Book reservations   | 3-4 days | Complex feature          |
| 🟢 Low    | Ratings & reviews   | 2-3 days | Community features       |

---

## Feature Details

### 1. Email Notifications

**Description:** Automatic email reminders for borrowers.

**Trigger Events:**
| Event | Email Content |
|-------|---------------|
| Book borrowed | Confirmation with details |
| 3 days before due | Reminder to return |
| Overdue | Late notice with fee |
| Book returned | Return confirmation |

**Implementation:**

-   Use Laravel Mail
-   Queue emails for performance
-   HTML email templates
-   Configurable on/off per user

**Estimated Effort:** 2-3 days

---

### 2. Book Cover Uploads

**Description:** Allow admin to upload book cover images.

**Features:**

-   Upload cover image in book form
-   Automatic image resizing
-   Display in catalog grid
-   Fallback placeholder image

**Implementation:**

-   Laravel file storage
-   Image intervention package
-   Thumbnail generation

**Estimated Effort:** 1-2 days

---

### 3. Data Export (Excel)

**Description:** Export reports to Excel for offline analysis.

**Reports:**
| Report | Data |
|--------|------|
| Books inventory | All books with copy counts |
| Borrowings | All transactions with dates |
| Overdue report | Current overdue loans |
| User activity | Borrowings per user |
| Financial | Fees collected summary |

**Implementation:**

-   Laravel Excel package
-   Export actions in Filament
-   Date range filtering

**Estimated Effort:** 1-2 days

---

### 4. Advanced Filters

**Description:** More filtering options for catalog and admin.

**Catalog Filters:**

-   By availability status
-   By author
-   By publication year
-   By borrow count (popular books)

**Admin Filters:**

-   By date range
-   By payment status
-   By overdue status
-   By user

**Estimated Effort:** 1 day

---

### 5. Loan Extension

**Description:** Allow users to extend their borrowing period.

**Rules:**

-   Can extend once per borrowing
-   Extension: +7 days
-   Only if not overdue
-   Only if no one is waiting

**User Flow:**

1. User views active borrowing
2. Clicks "Extend" button
3. System validates rules
4. New due date calculated
5. Additional rental fee applied

**Estimated Effort:** 2 days

---

### 6. Statistics Charts

**Description:** Visual charts for admin dashboard.

**Charts:**
| Chart | Type |
|-------|------|
| Monthly borrowings | Line chart |
| Revenue per month | Bar chart |
| Category distribution | Pie chart |
| Daily active users | Line chart |

**Implementation:**

-   Chart.js or ApexCharts
-   Filament chart widgets

**Estimated Effort:** 1-2 days

---

### 7. Book Reservations

**Description:** Allow users to reserve out-of-stock books.

**Flow:**

1. User sees "Out of Stock" book
2. User clicks "Reserve"
3. Added to reservation queue
4. When copy returns, first in queue notified
5. User has 24 hours to borrow
6. If not claimed, next in queue notified

**Complexity:** High (queue management, notifications)

**Estimated Effort:** 3-4 days

---

### 8. Ratings & Reviews

**Description:** Users can rate and review books after returning.

**Features:**

-   1-5 star rating
-   Text review (optional)
-   Average rating display
-   Review moderation (admin)

**Estimated Effort:** 2-3 days

---

## Phase 2 Pricing Estimate

| Feature Bundle | Features                     | Days  | Cost Range     |
| -------------- | ---------------------------- | ----- | -------------- |
| Essential      | Notifications + Covers       | 4-5   | Rp 1.2M - 2.5M |
| Standard       | Essential + Export + Filters | 6-8   | Rp 2M - 4M     |
| Premium        | All features                 | 15-18 | Rp 4.5M - 9M   |

---

## Recommended Phase 2 Timeline

**Start After:** 1-2 months of MVP usage

**Why Wait:**

-   Gather real user feedback
-   Identify actual pain points
-   Ensure MVP is stable
-   Budget for additional development

---

## How to Request Phase 2

1. Review this list with stakeholders
2. Prioritize features by business need
3. Contact developer with selected features
4. Get detailed quote and timeline
5. Plan development sprint
