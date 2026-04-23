# PedalPal Bike Rentals
## Product Requirements Document (PRD)
**Legacy PHP 7 → Laravel 11 + Vue 3 Modernization**

| | |
|---|---|
| Version | 1.0 |
| Date | April 2026 |
| Stack | Laravel 11 + Vue 3 + REST API |
| Type | Modernization Assignment — Interview Deliverable |

---

## 1. Executive Summary

PedalPal Bike Rentals is an existing bike rental platform currently running on raw PHP 7 with no framework, jQuery 1.12.4 (EOL), file-based JSON/XML storage, and no authentication layer. The system serves two product lines — Beach Cruisers and Mountain Bikes — along with an accessories ordering module.

This document defines the requirements for modernizing PedalPal into a production-grade web application using **Laravel 11** (backend API) and **Vue 3** (frontend SPA), with a MySQL database, proper authentication, RESTful routing, and a polished responsive UI.

---

## 2. Background & Problem Statement

### 2.1 Current State Assessment

A full audit of the legacy codebase reveals the following critical issues:

| Category | Legacy Issue | Risk Level |
|---|---|---|
| Architecture | No framework, manual `require_once` everywhere, static service locator anti-pattern | HIGH |
| Data Storage | JSON/XML flat files with no transactions, race conditions on concurrent writes | HIGH |
| Security | No authentication, no CSRF protection, `@` error suppressor masking failures | **CRITICAL** |
| PHP Version | PHP 7 with deprecated `create_function()` and `FILTER_SANITIZE_STRING` | HIGH |
| Frontend | jQuery 1.12.4 (EOL since 2016), no component model, inline CSS/JS per page | MEDIUM |
| Data Consistency | Inconsistent key casing: `snake_case` for beach, `PascalCase` for mountain bikes | MEDIUM |
| Scalability | All services re-initialized on every request, no caching layer, no DB | HIGH |
| Code Reuse | Identical repository pattern copy-pasted 3 times with no base class | LOW |

### 2.2 Business Motivation

- Reduce operational risk from deprecated and unsupported dependencies
- Enable horizontal scaling as PedalPal grows its fleet
- Provide a secure foundation for future customer-facing features (bookings, payments)
- Improve developer velocity with a modern, testable codebase
- Deliver a premium UI/UX to compete with modern rental platforms

---

## 3. Goals & Non-Goals

### 3.1 Goals

- Migrate backend to Laravel 11 with proper MVC, service containers, and Eloquent ORM
- Replace flat file storage with MySQL relational database
- Build a Vue 3 SPA frontend with a component-based design system
- Implement secure authentication (Laravel Sanctum / session-based)
- Expose a RESTful API with consistent JSON responses and proper HTTP status codes
- Eliminate all deprecated PHP 7 patterns and upgrade to PHP 8.2+
- Resolve naming inconsistencies across data models
- Implement CSRF protection, input validation, and proper error handling

### 3.2 Non-Goals (Out of Scope)

- Payment gateway integration (deferred to Phase 2)
- Mobile native applications (iOS/Android)
- Real-time availability notifications (WebSockets)
- Multi-tenancy or multi-location support
- Customer account registration (authentication targets staff/admin only in Phase 1)

---

## 4. User Personas

| Persona | Role | Goals | Pain Points (Legacy) |
|---|---|---|---|
| Rental Customer | End user browsing bikes | Find and rent a bike quickly, add accessories | Confusing UI, no availability filter, no feedback |
| Shop Staff | PedalPal employee | Manage inventory, process returns, reset stock | No auth, Easter egg reset button, no audit trail |
| Store Manager | Business owner | View revenue, manage fleet, configure pricing | No admin panel, no reporting, no pricing control |
| Developer | Engineer maintaining the app | Extend features safely, write tests | No framework, no tests, deprecated APIs everywhere |

---

## 5. Functional Requirements

The functional requirements below are organized into feature groups and numbered for traceability.

### 5.1 Bike Catalog — Beach Cruisers

- FRS-01: Display all beach cruisers with model name, color, frame size, daily rate, and availability
- FRS-02: Filter beach cruisers by availability (Available / All)
- FRS-03: Display bike details in a dedicated view or modal with full specifications
- FRS-04: Rent a beach cruiser and update availability in the database with success feedback
- FRS-05: Prevent renting an unavailable bike and show a disabled state on the action button

### 5.2 Bike Catalog — Mountain Bikes

- FRS-06: Display all mountain bikes with model, brand, gear count, suspension, frame material, terrain, weight, and daily rate
- FRS-07: Filter mountain bikes by terrain type and suspension type
- FRS-08: Display mountain bike details in a dedicated view or modal
- FRS-09: Rent a mountain bike with the same flow and validation as beach cruisers
- FRS-10: Show a distinct availability badge for mountain bikes (green/red)

### 5.3 Accessories Shop

- FRS-11: List all accessories with name, category, description, unit price, and stock count
- FRS-12: Filter accessories by bike type compatibility (Beach / Mountain / All)
- FRS-13: Add accessories to cart with quantity controls that respect stock limits
- FRS-14: Display a live order summary with subtotal, discount, and final total
- FRS-15: Apply a configurable bundle discount when compatible accessories are selected together
- FRS-16: Deduct accessory stock on successful order and prevent out-of-stock purchases

### 5.4 Admin / Staff Panel

- FRS-17: Authenticate staff users before allowing access to admin routes
- FRS-18: Reset inventory to default demo state from the admin dashboard
- FRS-19: View current stock levels for accessories and bike availability in admin UI
- FRS-20: Mark a rented bike as returned and restore its availability
- FRS-21: Record basic rental activity for audit and staff review

### 5.5 API Layer

- FRS-22: `GET /api/bikes?type=beach` — list beach cruisers
- FRS-23: `GET /api/bikes?type=mountain` — list mountain bikes
- FRS-24: `POST /api/bikes/{id}/rent` — rent a specific bike
- FRS-25: `GET /api/accessories?bikeType={type}` — list accessories with optional filter
- FRS-26: `POST /api/accessories/order` — process accessory order
- FRS-27: `POST /api/admin/reset` — reset all data (authenticated)
- FRS-28: All API responses must use a consistent JSON envelope `{ success, data, message }`

---

## 6. Non-Functional Requirements

| ID | Requirement | Specification | Priority |
|---|---|---|---|
| NFR-01 | Performance | Page load < 2s on 3G; API response < 300ms for typical queries | HIGH |
| NFR-02 | Security | CSRF protection, XSS-safe output, SQL injection prevention via Eloquent ORM, secure session handling | CRITICAL |
| NFR-03 | Scalability | Stateless API design; support database scaling and separated backend/front-end deployment | MEDIUM |
| NFR-04 | Maintainability | PSR-12 style, PHPDoc, consistent naming, and feature tests for critical flows | HIGH |
| NFR-05 | Accessibility | WCAG 2.1 AA compliance, keyboard navigation, ARIA labels, color contrast | MEDIUM |
| NFR-06 | Browser Support | Modern browsers: Chrome, Firefox, Safari, Edge (last 2 versions) | HIGH |
| NFR-07 | Mobile | Fully responsive design from 375px mobile to 1440px desktop | HIGH |
| NFR-08 | Reliability | Safe fallback messaging for API errors, no silent failure states, predictable UX | HIGH |

---

## 7. Features and User Stories

The stories are grouped into feature areas and then broken into smaller slices for implementation.

### Feature 1: Bike Catalog

**US-01:** As a customer, I want to browse available bikes so I can choose one to rent.
- Given I visit `/bikes`, I see all bikes grouped by type
- Each bike card shows availability, daily rate, and core specs
- Available bikes display a green "Available" badge; rented bikes display a red "Rented" badge

**US-02:** As a customer, I want to filter bikes so I can narrow results by availability and type.
- Given I use filters, the bike list updates immediately without page refresh
- I can filter beach cruisers by availability and mountain bikes by terrain/suspension

**US-03:** As a customer, I want to view bike details so I can understand the full specifications.
- Given I click a bike card, I see a detail modal or page with all available specs
- The detail view highlights daily rate, availability, and rent CTA

**US-04:** As a customer, I want to rent a bike so I can reserve it for the day.
- Given I click "Rent Now" on an available bike, a confirmation dialog appears
- On confirmation, the bike is marked unavailable in the database and the card updates
- A toast success message appears and the bike can no longer be rented until returned
- If the bike is already rented, the rent action is disabled with a clear label

### Feature 2: Accessories

**US-05:** As a customer, I want to browse accessories so I can add items to my rental.
- Given I visit `/accessories`, accessories display with name, price, stock, and compatibility
- I can filter accessories by bike type compatibility

**US-06:** As a customer, I want to add accessories to my cart with quantity controls.
- Given I adjust quantity, the cart updates with line totals and respects stock limits
- The UI prevents adding more than available stock

**US-07:** As a customer, I want a bundle discount to apply automatically.
- Given I add both Water Bottle and Bike Light to the cart, a 10% discount applies
- The order summary shows the discount amount and final total clearly

**US-08:** As a customer, I want to submit my accessory order and receive confirmation.
- Given I place the order, stock counts decrease and I receive a confirmation message
- If stock is insufficient, the order is rejected with an error message

### Feature 3: Admin

**US-09:** As a staff member, I want to log in securely so I can manage inventory.
- Given I go to `/admin/login`, I see a secure login form
- On successful authentication, I am redirected to `/admin/dashboard`
- Unauthorized users cannot access admin routes

**US-10:** As a staff member, I want to reset inventory so I can restore the app to demo defaults.
- Given I click the reset button, a confirmation modal appears
- On confirmation, all bike availability and accessory stock reset to default values

**US-11:** As a staff member, I want to mark returned bikes so availability is restored.
- Given I mark a rented bike returned, its status becomes available in the catalog
- The admin dashboard reflects the updated bike status immediately

**US-12:** As a staff member, I want to view recent rental activity so I can understand current usage.
- Given I open the dashboard, I see recent rentals and returns with timestamps

### Feature 4: System & API

**US-13:** As a developer, I want consistent API responses so client integration is predictable.
- Given I call any API endpoint, I receive `{ success, data, message }`
- Errors are returned with appropriate HTTP status codes and descriptive messages

**US-14:** As a developer, I want protected admin APIs so sensitive actions require authentication.
- Given I call `/api/admin/reset` without auth, I receive HTTP 401 Unauthorized
- Authenticated staff can perform approved admin actions

### 7.1 Feature & Story Sharding

This PRD uses the following story partitioning approach:
- Feature-level grouping for major functionality domains: bikes, accessories, admin, system
- Incremental stories for browsing, filtering, details, actions, and confirmation flows
- Separate stories for UI behavior, API contract, and admin security to enable parallel work
- Smaller slices such as `US-02` and `US-06` allow frontend and backend to be implemented independently

---

## 8. Release Milestones

| Phase | Target | Deliverables | Status |
|---|---|---|---|
| Phase 0 | Week 1 | Project setup: Laravel 11 scaffold, DB migrations, Eloquent models, seeders | Planned |
| Phase 1 | Week 2 | REST API: bike & accessory endpoints, bundle logic, admin reset (authenticated) | Planned |
| Phase 2 | Week 3 | Vue 3 SPA: component library, bike catalog, accessories shop, cart | Planned |
| Phase 3 | Week 4 | Admin panel, auth, polish, tests, documentation, final zip | Planned |

---

## 9. Assumptions & Constraints

- The assignment is treated as a single-developer project; CI/CD pipelines are out of scope
- SQLite can be used locally; MySQL in production is the target
- The bundle discount IDs (1 & 3) are configurable via `.env` or config file, not hardcoded
- Authentication is session-based for the admin panel (not customer-facing auth)
- Beach Cruiser data format (XML) will be migrated to the database; no XML in production
- All API responses follow the envelope: `{ success: bool, data: mixed, message: string }`

---

## 8. Release Milestones

| Phase | Target | Deliverables | Status |
|---|---|---|---|
| Phase 0 | Week 1 | Project setup: Laravel 11 scaffold, DB migrations, Eloquent models, seeders | Planned |
| Phase 1 | Week 2 | REST API: bike & accessory endpoints, bundle logic, admin reset (authenticated) | Planned |
| Phase 2 | Week 3 | Vue 3 SPA: component library, bike catalog, accessories shop, cart | Planned |
| Phase 3 | Week 4 | Admin panel, auth, polish, tests, documentation, final zip | Planned |

---

## 9. Assumptions & Constraints

- The assignment is treated as a single-developer project; CI/CD pipelines are out of scope
- SQLite can be used locally; MySQL in production is the target
- The bundle discount IDs (1 & 3) are configurable via `.env` or config file, not hardcoded
- Authentication is session-based for the admin panel (not customer-facing auth)
- Beach Cruiser data format (XML) will be migrated to the database; no XML in production
- All API responses follow the envelope: `{ success: bool, data: mixed, message: string }`
