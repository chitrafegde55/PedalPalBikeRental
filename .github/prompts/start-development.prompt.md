---
name: start-development
description: Use this prompt to help developers set up PedalPal development environment and understand the modernized codebase
---

# PedalPal Development Setup Guide

## Project Overview
PedalPal is a modernized bike rental management system rebuilt with:
- **Frontend**: Vue 3 SPA with Composition API and Vue Router
- **Backend**: PHP 8.2+ with service-oriented architecture
- **Database**: SQLite with seed data
- **Build Tool**: Vite 5.4.21
- **Testing**: PHPUnit 11.5.55

## Quick Start

### Prerequisites
- PHP 8.2.12+
- Node.js 18+
- npm 10+

### Setup Steps
1. **Install dependencies**: `npm install`
2. **Run Vite dev server**: `npm run dev` (optional; for live reload)
3. **Start PHP server**: `php -S localhost:8080 -t public` or `php artisan serve`
4. **Initialize database**: Database auto-seeds on first server request
5. **Access app**: Open `http://localhost:8080` in browser

## Development Patterns

### Vue 3 Components (Composition API)
All components use `<script setup lang="ts">` with TypeScript support. Key components:
- **Home.vue**: Hero section with gradient background and category cards
- **Bikes.vue**: Catalog with filtering, availability badges, responsive grid
- **Accessories.vue**: Shopping interface with cart management and quantity controls
- **Admin.vue**: Dashboard with inventory management and activity log
- **Login.vue**: Authentication portal with modern form design

See `resources/js/components/` for all components.

### Service Layer (PHP)
All business logic in service classes using dependency injection:
- **BeachCruiserService.php**: Manage beach cruiser rentals
- **MountainBikeService.php**: Manage mountain bike rentals
- **AccessoryService.php**: Handle accessories, bundles, and inventory
- **ApplicationServices.php**: Core application logic

Example pattern:
```php
class BeachCruiserService {
    public function rentBike($id) { /* modern PHP 8.2 syntax */ }
    public function getAll() { /* uses repositories */ }
}
```

### Database & Repositories
Repository pattern for data access:
- **BeachCruiserRepository.php**: Beach cruiser data operations
- **MountainBikeRepository.php**: Mountain bike data operations
- **AccessoryRepository.php**: Accessory data operations
- Uses SQLite with auto-seeding on initialization

### CSS & Styling
Global design system in `resources/css/app.css`:
- CSS variables for theming (`--primary-color`, `--secondary-color`)
- Gradient definitions for hero sections
- Responsive breakpoints (768px tablet breakpoint)
- Utility classes for buttons, cards, badges

## Testing
Run `vendor/bin/phpunit` to execute all 9 tests:
- 3 tests per service class (BeachCruiser, MountainBike, Accessory)
- Each tests: getAll(), rentBike success, rentBike unavailable scenarios
- Run `vendor/bin/phpunit --coverage-text` for coverage report

## Build & Deploy
- **Development**: `npm run dev` with hot reload + `php -S localhost:8080 -t public`
- **Production Build**: `npm run build` generates optimized assets in `public/build/assets/`
- **Update asset references**: After build, ensure `public/index.html` has latest hash references

## Directory Structure
```
resources/
  js/
    app.js           # Entry point with CSS import
    App.vue          # Root component with navbar and routing
    components/      # Vue 3 components
services/
  *Service.php       # Business logic services
data/
  *Repository.php    # Data access repositories
handlers/
  *-handler.php      # API request handlers
public/
  index.html         # Entry HTML (asset refs auto-updated)
  index.php          # PHP routing entry point
docs/
  01_PedalPal_PRD.md                  # Product requirements
  02_PedalPal_Architecture.md         # Architecture details
  wireframe-design-specs/             # UI wireframes
```

## Common Development Tasks

### Adding a New Component
1. Create `.vue` file in `resources/js/components/`
2. Use `<script setup lang="ts">` with Composition API
3. Import API service and handle loading/error states
4. Add route in Vue Router configuration
5. Run `npm run build` to compile

### Adding a New Service
1. Create `*Service.php` in `services/` folder
2. Follow pattern: dependency injection, repository usage, error handling
3. Use modern PHP 8.2+ syntax (no deprecated functions)
4. Add unit tests in `tests/Unit/`
5. Run `vendor/bin/phpunit` to verify

### Modifying Styles
1. Update `resources/css/app.css` for global changes
2. Use CSS variables for consistency
3. Run `npm run build` to compile
4. CSS automatically scoped with Vue `data-v-*` attributes

## Important Notes
- Always run tests before committing: `vendor/bin/phpunit`
- Keep components focused and extract logic to composables
- Use service layer for all business logic (no logic in components)
- Update README.md when adding features or changing setup
- PHPUnit downgraded from v13 to v11.5.55 for PHP 8.2 compatibility