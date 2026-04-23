# PedalPal Bike Rentals
## Architecture & Technical Design Document
**System Design for PedalPal Modernization**

| | |
|---|---|
| Version | 1.0 |
| Date | April 2026 |
| Stack | Laravel 11 + Vue 3 + REST API |
| Type | Modernization Assignment — Interview Deliverable |

---

## 1. System Overview

The modernized PedalPal system follows a clean separation between backend API (Laravel 11) and frontend SPA (Vue 3). The backend exposes a RESTful JSON API consumed by the Vue frontend. Authentication is handled via Laravel Sanctum (cookie-based SPA auth). Data is persisted in a relational database (MySQL in production, SQLite for local dev).

### 1.1 Architecture Diagram (Conceptual)

```
┌─────────────────────────────────────────────────┐
│                    BROWSER                       │
│         Vue 3 SPA (Vite + Vue Router             │
│              + Pinia + Axios)                    │
└───────────────────┬─────────────────────────────┘
                    │  HTTPS / REST API
┌───────────────────▼─────────────────────────────┐
│              LARAVEL 11 BACKEND                  │
│  Routes => Controllers => Services =>            │
│  Repositories => Eloquent Models                 │
└───────────────────┬─────────────────────────────┘
                    │  Eloquent ORM
┌───────────────────▼─────────────────────────────┐
│       MySQL (prod) / SQLite (local)              │
└─────────────────────────────────────────────────┘
```

---

## 2. Technology Stack

| Layer | Technology | Rationale |
|---|---|---|
| Backend Framework | Laravel 11 | Industry-standard PHP framework; DI container, Eloquent ORM, built-in auth, routing, middleware, artisan CLI |
| PHP Version | PHP 8.2+ | Eliminates all deprecated constructs from legacy; named args, fibers, readonly props, union types |
| Database | MySQL 8 (SQLite local) | Replaces flat files; ACID transactions prevent stock inconsistencies on concurrent orders |
| Authentication | Laravel Sanctum | Cookie-based SPA auth; CSRF token handling built-in; simpler than Passport for this use case |
| Frontend | Vue 3 (Composition API) | Reactive, component-based; `script setup` syntax; excellent TypeScript support |
| Build Tool | Vite 5 | Lightning fast HMR; native ESM; first-class Vue plugin; replaces Webpack |
| State Management | Pinia | Official Vue 3 state manager; simpler than Vuex; DevTools integration |
| Routing (SPA) | Vue Router 4 | Declarative routing, navigation guards for auth, lazy-loaded route chunks |
| HTTP Client | Axios | Promise-based; interceptors for token/error handling; consistent API with the team |
| UI Framework | Tailwind CSS 3 | Utility-first; no CSS bloat; consistent design tokens; dark mode ready |
| Testing (PHP) | PHPUnit + Pest | Pest for readable feature tests; PHPUnit for unit; artisan test runner |
| Testing (JS) | Vitest + Vue Testing Library | Co-located with Vite; fast; component testing without a browser |
| Code Quality | Laravel Pint + ESLint | Pint enforces PSR-12; ESLint + Vue plugin for consistent JS/Vue style |

---

## 3. Directory Structure

### 3.1 Laravel Backend

```
app/
├── Http/
│   ├── Controllers/Api/
│   │   ├── BikeController.php
│   │   ├── AccessoryController.php
│   │   └── AdminController.php
│   ├── Requests/
│   │   ├── RentBikeRequest.php
│   │   └── OrderAccessoriesRequest.php
│   └── Resources/
│       ├── BikeResource.php
│       └── AccessoryResource.php
├── Models/
│   ├── Bike.php
│   ├── Accessory.php
│   └── RentalLog.php
├── Services/
│   ├── BikeService.php
│   ├── AccessoryService.php
│   └── BundleDiscountService.php
└── Repositories/
    ├── Contracts/
    │   ├── BikeRepositoryInterface.php
    │   └── AccessoryRepositoryInterface.php
    └── Eloquent/
        ├── EloquentBikeRepository.php
        └── EloquentAccessoryRepository.php

database/
├── migrations/
│   ├── create_bikes_table.php
│   ├── create_accessories_table.php
│   └── create_rental_logs_table.php
└── seeders/
    ├── BikeSeeder.php        ← migrates legacy JSON/XML data
    └── AccessorySeeder.php

routes/
└── api.php                   ← all /api/* routes

config/
└── pedalpal.php              ← bundle IDs, discount rate, default stock

tests/Feature/
├── BikeRentalTest.php
├── AccessoryOrderTest.php
└── AdminResetTest.php
```

### 3.2 Vue 3 Frontend

```
src/
├── views/
│   ├── HomeView.vue
│   ├── BeachCruisersView.vue
│   ├── MountainBikesView.vue
│   ├── AccessoriesView.vue
│   └── AdminView.vue
├── components/
│   ├── bikes/
│   │   ├── BikeCard.vue
│   │   ├── BikeGrid.vue
│   │   ├── BikeFilterBar.vue
│   │   └── RentModal.vue
│   ├── accessories/
│   │   ├── AccessoryCard.vue
│   │   ├── AccessoryFilter.vue
│   │   ├── CartSidebar.vue
│   │   └── OrderSummary.vue
│   └── ui/
│       ├── AppButton.vue
│       ├── AppBadge.vue
│       ├── AppToast.vue
│       ├── AppModal.vue
│       ├── AppSpinner.vue
│       └── AppEmptyState.vue
├── stores/
│   ├── useBikeStore.js
│   ├── useAccessoryStore.js
│   ├── useCartStore.js
│   └── useAuthStore.js
├── composables/
│   ├── useApi.js
│   ├── useToast.js
│   ├── useBundleDiscount.js
│   └── useFilters.js
├── api/
│   ├── bikeApi.js
│   ├── accessoryApi.js
│   └── adminApi.js
└── router/
    └── index.js              ← auth guard on /admin/*
```

---

## 4. Database Schema

### 4.1 `bikes`

| Column | Type | Nullable | Notes |
|---|---|---|---|
| `id` | BIGINT PK | NO | Auto-increment |
| `type` | ENUM('beach','mountain') | NO | Replaces two separate data sources |
| `model_name` | VARCHAR(100) | NO | Unified column name (was `ModelName` / `model_name`) |
| `brand` | VARCHAR(100) | YES | Mountain bikes only |
| `color` | VARCHAR(50) | YES | Beach cruisers only |
| `frame_size` | VARCHAR(20) | YES | Beach: Small / Medium / Large |
| `gear_count` | TINYINT | YES | Mountain bikes only |
| `suspension_type` | VARCHAR(30) | YES | Full / Hardtail |
| `frame_material` | VARCHAR(30) | YES | Aluminum / Carbon / Steel |
| `terrain` | VARCHAR(40) | YES | All-Mountain / Enduro / Trail etc. |
| `weight_kg` | DECIMAL(5,2) | YES | Mountain bikes only |
| `daily_rate` | DECIMAL(8,2) | NO | Rental price per day |
| `is_available` | BOOLEAN | NO | Default: `true` |
| `created_at` / `updated_at` | TIMESTAMP | YES | Laravel timestamps |

### 4.2 `accessories`

| Column | Type | Nullable | Notes |
|---|---|---|---|
| `id` | BIGINT PK | NO | Auto-increment |
| `name` | VARCHAR(100) | NO | |
| `category` | VARCHAR(50) | NO | Hydration / Safety / Storage |
| `description` | TEXT | YES | |
| `unit_price` | DECIMAL(8,2) | NO | |
| `stock_count` | INT | NO | Default 0 |
| `compatible_with` | JSON | NO | `["beach","mountain","all"]` stored as JSON array |
| `created_at` / `updated_at` | TIMESTAMP | YES | Laravel timestamps |

### 4.3 `rental_logs`

| Column | Type | Notes |
|---|---|---|
| `id` | BIGINT PK | Auto-increment |
| `bike_id` | BIGINT FK | References `bikes.id` |
| `action` | ENUM('rented','returned','reset') | Audit trail action |
| `performed_at` | TIMESTAMP | When the action occurred |

---

## 5. REST API Design

| Method | Endpoint | Auth Required | Description |
|---|---|---|---|
| GET | `/api/bikes` | No | List all bikes (optional `?type=beach\|mountain`) |
| GET | `/api/bikes/{id}` | No | Get single bike details |
| POST | `/api/bikes/{id}/rent` | No | Rent a bike (sets `is_available=false`) |
| GET | `/api/accessories` | No | List accessories (optional `?bikeType=`) |
| POST | `/api/accessories/order` | No | Place accessory order, apply bundle discount |
| POST | `/api/auth/login` | No | Admin login (returns session cookie) |
| POST | `/api/auth/logout` | Yes | Admin logout |
| POST | `/api/admin/reset` | Yes | Reset all inventory to defaults |
| GET | `/api/admin/logs` | Yes | View recent rental activity log |
| PATCH | `/api/admin/bikes/{id}/return` | Yes | Mark bike as returned (`is_available=true`) |

### 5.1 Standard Response Envelope

```json
// Success
{
  "success": true,
  "data": { "...": "..." },
  "message": "Bike rented successfully."
}

// Error
{
  "success": false,
  "data": null,
  "message": "Bike is not available."
}
```

---

## 6. Architectural Decision Records (ADRs)

### ADR-01: Repository Pattern with Interface Binding

**Decision:** Define `BikeRepositoryInterface` and bind `EloquentBikeRepository` in `AppServiceProvider`.

- **Rationale:** Allows swapping implementations (e.g., in-memory repo for tests)
- **Legacy issue:** Copy-paste repositories with no abstraction or interface

### ADR-02: Service Layer for Business Logic

**Decision:** `BikeService` and `AccessoryService` handle all business logic; controllers are thin.

- **Rationale:** Testable in isolation without HTTP context; mirrors DDD patterns
- **Legacy issue:** Business logic mixed directly into handler files

### ADR-03: Configurable Bundle Discount

**Decision:** Bundle discount accessory IDs and rate stored in `config/pedalpal.php`.

- **Rationale:** Marketing can change the bundle without a code deployment
- **Legacy issue:** `BUNDLE_ID_A = 1`, `BUNDLE_ID_B = 3` hardcoded as class constants

### ADR-04: Unified Bike Table

**Decision:** Single `bikes` table with a `type` column instead of two separate tables/files.

- **Rationale:** Simplifies queries, ensures consistent schema, eliminates snake/PascalCase split
- **Legacy issue:** Beach cruisers in XML (`snake_case`), mountain bikes in JSON (`PascalCase`)

### ADR-05: Database Transactions for Orders

**Decision:** Wrap accessory order stock deduction in a `DB::transaction()` block.

- **Rationale:** Eliminates the "point of no return" issue in the legacy `processOrder` method
- **Legacy issue:** No rollback on failed `save()` after stock deduction

---

## 7. Security Improvements

| Area | Legacy Problem | Modern Fix |
|---|---|---|
| CSRF Protection | None | `VerifyCsrfToken` middleware on all POST/PATCH/DELETE routes |
| SQL Injection | Raw string queries via file I/O | Eloquent ORM with parameterized queries |
| XSS | Manual `htmlspecialchars()` scattered throughout | Vue 3 escapes template interpolation by default |
| Authentication | No auth — anyone can reset data | `auth:sanctum` middleware on all `/admin/*` routes |
| Input Validation | `intval()` / `floatval()` casts only | Form Request classes validate all input before service layer |
| Error Suppression | `@json_decode()` masks failures silently | `try/catch` with `json_last_error()` + proper logging |
| Admin Reset | Easter egg bike emoji on homepage | Requires authenticated session; confirmation modal |

---

## 8. Integration into a Larger System

PedalPal is designed as a standalone bounded context that can be embedded into a larger platform:

- **API Gateway:** All `/api/*` routes sit behind a gateway (Kong, AWS API GW) for rate limiting and routing
- **Central Auth:** Replace Sanctum with OAuth2 tokens from a central identity provider (Auth0, Keycloak)
- **Event Bus:** Dispatch `BikeRented`, `AccessoryOrdered` events to a message queue (SQS, RabbitMQ) for billing/notifications
- **Service Registry:** Register the Laravel service in Consul / Kubernetes service discovery
- **Shared DB Schema:** Tables prefixed (`pedalpal_bikes`) for multi-module DB sharing
- **Containerization:** Stateless design allows horizontal scaling with Docker / Kubernetes
