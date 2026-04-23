# PedalPal — Quick Reference: Key Legacy Bugs → Modern Fixes

> Grounded in the actual `BikeRentalWeb_php7` codebase audit.
> Every row references a real file and a real fix in the modernized Laravel 11 + Vue 3 stack.

---

## 🔴 Critical — Security

| # | Legacy Bug | File(s) | Risk | Modern Fix |
|---|---|---|---|---|
| 1 | **No authentication anywhere** — any user can trigger destructive actions | Entire app | Anyone can reset all inventory | Laravel Sanctum cookie-based auth; `auth:sanctum` middleware on all `/admin/*` routes |
| 2 | **Easter egg 🚲 reset** — clicking a hidden bike emoji on the homepage fires an unauthenticated POST that wipes all data | `index.html` | Silent data destruction by any visitor | Removed entirely; reset moved to authenticated `/api/admin/reset` endpoint behind confirmation modal |
| 3 | **No CSRF protection** on any state-changing handler | `handlers/bike-handler.php`, `handlers/accessory-handler.php` | Cross-site request forgery on rent/order/reset | Laravel `VerifyCsrfToken` middleware; Sanctum XSRF-TOKEN cookie on SPA requests |
| 4 | **`@ error suppressor`** on `json_decode()` — failures swallowed silently, app continues with `null` data | `data/BikeRepository.php`, `data/BeachCruiserRepository.php` | Corrupt/missing data served to users with no log trail | `try/catch` + `json_last_error_msg()` + Laravel `Log::error()` |

---

## 🔴 Critical — Data Integrity

| # | Legacy Bug | File(s) | Risk | Modern Fix |
|---|---|---|---|---|
| 5 | **No DB transaction on accessory orders** — stock deducted first, then order saved separately; a crash between the two leaves phantom stock loss | `services/AccessoryService.php` | Inventory count decremented but no order recorded | `DB::transaction()` wrapping both deduction and order insert; full rollback on failure |
| 6 | **Race condition on concurrent writes** — multiple users can rent the same bike simultaneously via flat file reads | `data/BikeRepository.php` | Same bike rented twice | Eloquent pessimistic locking (`lockForUpdate()`) inside a transaction; single source of truth in MySQL |

---

## 🟠 High — Deprecated / Broken on PHP 8

| # | Legacy Bug | File(s) | Risk | Modern Fix |
|---|---|---|---|---|
| 7 | **`create_function()`** — deprecated PHP 7.2, removed PHP 8.0; used to build sort callbacks | `data/BeachCruiserRepository.php` | Fatal error on PHP 8 — app won't boot | Native arrow function: `fn($a, $b) => $a['daily_rate'] <=> $b['daily_rate']` |
| 8 | **`FILTER_SANITIZE_STRING`** — removed in PHP 8.1 | `handlers/accessory-handler.php` | Fatal error on PHP 8.1+ | Laravel Form Request with `Rules\SafeString` or explicit `strip_tags()` + `trim()` |
| 9 | **`intval()` / `floatval()` as sole validation** — no type checking, no range enforcement, no existence check | `handlers/accessory-handler.php`, `handlers/bike-handler.php` | Negative quantities, non-existent IDs accepted silently | `OrderAccessoriesRequest` with `items.*.id` → `exists:accessories,id`, `items.*.quantity` → `integer\|min:1` |

---

## 🟠 High — Architecture

| # | Legacy Bug | File(s) | Risk | Modern Fix |
|---|---|---|---|---|
| 10 | **Static service locator anti-pattern** — `ApplicationServices::getBikeService()` creates hidden global state | `services/ApplicationServices.php` | Untestable; services can't be mocked or swapped | Laravel Service Container with constructor injection; interfaces bound in `AppServiceProvider` |
| 11 | **Three copy-paste repositories** — `BikeRepository`, `BeachCruiserRepository`, `AccessoryRepository` share identical logic with no base class or interface | `data/` folder | Bug fixed in one repo silently missed in the other two | `BikeRepositoryInterface` + `AccessoryRepositoryInterface`; single `EloquentBikeRepository` implementation; `BaseRepository` abstract class |
| 12 | **Business logic in handler files** — rent validation, discount calculation, stock check all live in HTTP handler | `handlers/bike-handler.php`, `handlers/accessory-handler.php` | Can't unit test logic without HTTP context | `BikeService`, `AccessoryService`, `BundleDiscountService` — controllers are thin, logic is injectable and testable |
| 13 | **Hardcoded bundle accessory IDs** — `BUNDLE_ACCESSORY_A = 1`, `BUNDLE_ACCESSORY_B = 3` as class constants | `services/AccessoryService.php` | Changing the bundle deal requires a code deploy | `config/pedalpal.php` → `bundle_accessory_a`, `bundle_accessory_b`, `bundle_discount_rate`; changeable via `.env` |

---

## 🟠 High — Data Consistency

| # | Legacy Bug | File(s) | Risk | Modern Fix |
|---|---|---|---|---|
| 14 | **`PascalCase` keys in mountain bike JSON** (`ModelName`, `DailyRate`, `GearCount`) vs **`snake_case` in beach cruiser XML** (`model_name`, `daily_rate`) — same app, two formats | `SampleData/mountain_bikes.json`, `SampleData/beach_cruisers.xml` | Frontend must handle two different key formats; bugs when mapping data | Single unified `bikes` table with `snake_case` columns; `BikeSeeder` normalises all legacy keys on import |
| 15 | **Two separate data sources for the same entity** — beach cruisers in XML, mountain bikes in JSON | `SampleData/` | Duplicate repository logic, no cross-type queries, no unified availability count | Single `bikes` table with `type ENUM('beach','mountain')`; one `BikeRepository` handles both |
| 16 | **No audit trail** — no record of who rented what, when, or when inventory was reset | Entire app | No accountability, no debugging history | `rental_logs` table: `bike_id`, `action ENUM('rented','returned','reset')`, `performed_at`; written inside every service action |

---

## 🟡 Medium — Frontend

| # | Legacy Bug | File(s) | Risk | Modern Fix |
|---|---|---|---|---|
| 17 | **jQuery 1.12.4** — end-of-life since 2016, no security patches, no component model | `index.html`, `beach-cruisers.html`, `mountain-bikes.html` | Security vulnerabilities; no reactivity; global DOM manipulation | Vue 3 Composition API (`<script setup>`); reactive state via Pinia; no jQuery dependency |
| 18 | **`alert()` for all user feedback** — rent confirmation, order success, errors all use browser `alert()` | All HTML pages | Blocks UI thread; ugly; not styleable; kills UX | Global `AppToast.vue` component; success / error / info variants; auto-dismiss after 4s |
| 19 | **No loading states** — data either appears or it doesn't; no indication of in-flight requests | All HTML pages | Users click buttons multiple times; double-submit bugs | Pinia `loading` flag per store; skeleton card components during fetch; spinner on action buttons |
| 20 | **No empty states** — if all bikes are rented or filter matches nothing, page is just blank | All listing pages | Confusing dead-end UX | `AppEmptyState.vue` with contextual message and CTA (e.g., "All beach cruisers are rented — check back soon") |
| 21 | **No filters on any listing page** | `beach-cruisers.html`, `mountain-bikes.html` | Users must scroll entire list to find available bikes | `BikeFilterBar.vue` — availability toggle for all bikes; terrain + suspension dropdowns for mountain bikes; reactive Pinia getters |
| 22 | **Hardcoded purple gradient** `#667eea → #764ba2` as inline `<style>` in every HTML file | All HTML pages | Brand change requires editing 3 files; no design system | Tailwind CSS 3 design token system; `--color-primary` CSS custom property; single source of truth |
| 23 | **No responsive design** — `flex-wrap` only; no mobile-optimised touch targets or layout shifts | All HTML pages | Poor experience on mobile devices | Tailwind responsive grid (`grid-cols-1 sm:grid-cols-2 lg:grid-cols-3`); bottom cart drawer on mobile; 44px min touch targets |
| 24 | **3 separate HTML files with duplicated nav/footer markup** | `index.html`, `beach-cruisers.html`, `mountain-bikes.html` | Nav change requires editing 3 files | Single `AppLayout.vue` with `<RouterView>`; one nav bar, one footer, shared across all pages |

---

## 🟡 Medium — Code Quality

| # | Legacy Bug | File(s) | Risk | Modern Fix |
|---|---|---|---|---|
| 25 | **No tests** — zero unit or integration tests across the entire codebase | Entire app | Regressions go undetected; refactoring is dangerous | Pest feature tests for bike rental, accessory order, and admin reset flows; `RefreshDatabase` + factories |
| 26 | **`require_once` chains** — manual file inclusion with fragile relative paths | All PHP files | Include order bugs; path errors break silently | Composer PSR-4 autoloading; zero manual `require_once` in modernized code |
| 27 | **Mixed concerns in every file** — HTML rendering, SQL-equivalent logic, and HTTP handling in the same file | `handlers/` | Impossible to test or reuse in isolation | Controllers (HTTP), Services (logic), Repositories (data), Resources (output) — strict layer separation |
| 28 | **No standardised API response format** — raw `echo json_encode()` with inconsistent key names | `handlers/bike-handler.php`, `handlers/accessory-handler.php` | Frontend must defensively handle different shapes | Laravel API Resources + standard envelope: `{ "success": bool, "data": mixed, "message": string }` |

---

## Summary Counts

| Severity | Count |
|---|---|
| 🔴 Critical (Security) | 4 |
| 🔴 Critical (Data Integrity) | 2 |
| 🟠 High (PHP 8 Breaking) | 3 |
| 🟠 High (Architecture) | 4 |
| 🟠 High (Data Consistency) | 3 |
| 🟡 Medium (Frontend) | 8 |
| 🟡 Medium (Code Quality) | 4 |
| **Total** | **28** |
