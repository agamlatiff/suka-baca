# Architecture & Tech Stack

## Technology Stack

| Layer             | Technology           | Purpose                    |
| ----------------- | -------------------- | -------------------------- |
| **Backend**       | Laravel 10           | PHP framework, API & logic |
| **Database**      | MySQL                | Data persistence           |
| **Auth**          | Laravel Breeze       | Authentication scaffolding |
| **Admin Panel**   | Laravel Filament     | CMS & admin dashboard      |
| **Frontend**      | Blade + Tailwind CSS | Server-side rendering      |
| **Interactivity** | Alpine.js            | Lightweight JS (optional)  |

## System Architecture

```
┌─────────────────────────────────────────────────────────┐
│                      PRESENTATION                        │
├─────────────────────────────────────────────────────────┤
│  User Interface          │  Admin Panel                 │
│  (Blade + Tailwind)      │  (Laravel Filament)          │
├─────────────────────────────────────────────────────────┤
│                      APPLICATION                         │
├─────────────────────────────────────────────────────────┤
│  Controllers  │  Services  │  Middleware  │  Requests   │
├─────────────────────────────────────────────────────────┤
│                      DOMAIN                              │
├─────────────────────────────────────────────────────────┤
│  Models  │  Repositories  │  Events  │  Observers       │
├─────────────────────────────────────────────────────────┤
│                      INFRASTRUCTURE                      │
├─────────────────────────────────────────────────────────┤
│  MySQL Database  │  File Storage  │  Session/Cache      │
└─────────────────────────────────────────────────────────┘
```

## Directory Structure

```
app/
├── Http/
│   ├── Controllers/        # Request handlers
│   ├── Middleware/         # Auth, CORS, etc.
│   └── Requests/           # Form validation
├── Models/                 # Eloquent models
├── Services/               # Business logic
└── Filament/               # Admin panel resources

resources/
├── views/                  # Blade templates
│   ├── layouts/
│   ├── components/
│   ├── catalog/
│   └── user/
└── css/                    # Tailwind styles

database/
├── migrations/             # Schema definitions
├── seeders/                # Test data
└── factories/              # Model factories
```

## Key Dependencies

```json
{
    "laravel/framework": "^10.0",
    "laravel/breeze": "^1.0",
    "filament/filament": "^3.0"
}
```

## Development Tools

| Tool     | Purpose                    |
| -------- | -------------------------- |
| Composer | PHP dependency management  |
| NPM      | Frontend asset compilation |
| Artisan  | Laravel CLI commands       |
| Vite     | Asset bundling             |

## Environment Configuration

Key `.env` variables:

```env
APP_NAME=Sukabaca
APP_ENV=local
APP_DEBUG=true
DB_CONNECTION=mysql
DB_DATABASE=sukabaca
```

See [07-DEPLOYMENT.md](./07-DEPLOYMENT.md) for production configuration.
