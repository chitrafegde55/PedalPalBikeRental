# PedalPal Bike Rental - Modernized Edition

**A professional, full-stack bike rental management system built with PHP 8.2, Vue 3, and Vite.**

![Status](https://img.shields.io/badge/Status-Production%20Ready-brightgreen) ![Vue 3](https://img.shields.io/badge/Vue-3-green) ![PHP](https://img.shields.io/badge/PHP-8.2%2B-blue) ![Tests](https://img.shields.io/badge/Tests-9%2F9%20Passing-success)

---

## 🎯 Overview

PedalPal is a comprehensive bike rental management system enabling customers to browse, reserve, and rent bicycles while providing staff with administrative tools for inventory management and activity tracking. This **modernized version** transforms legacy PHP into a professional platform with Vue 3 SPA, modern PHP 8.2+ architecture, and comprehensive testing.

### ✨ Key Improvements
- ✅ Modern Vue 3 responsive frontend with professional gradient UI
- ✅ Service-oriented PHP backend with clean architecture  
- ✅ 9 PHPUnit tests (100% passing)
- ✅ Vite 5 optimized builds (2.7KB CSS, 53KB JS gzipped)
- ✅ SQLite database with seeded sample data
- ✅ REST API endpoints for full functionality

---

## 🚀 Features

### Customer Features
- 🚴 **Browse Bikes** - Filter by type (Beach Cruisers, Mountain Bikes) with real-time availability
- 🛒 **Shop Accessories** - Add to cart with quantity selectors and bundle discounts
- 📊 **Quick Actions** - Category cards for instant navigation
- 💳 **Secure Rentals** - Input validation and stock verification

### Admin Features  
- 📦 **Inventory Management** - Reset to defaults with confirmation
- 🔄 **Bike Returns** - Track and mark returned rentals
- 📊 **Activity Dashboard** - View rental/return/order history with timestamps
- 🔐 **Staff Authentication** - Secure admin portal with session management

### Technical Features
- ✅ **9 Unit Tests** - 100% passing (Service layer comprehensive coverage)
- 🎨 **Professional UI** - Gradient backgrounds, smooth animations, professional typography
- 📱 **Responsive Design** - Mobile-first approach for all screen sizes
- ⚡ **Performance** - Optimized Vite builds, fast load times
- 🔗 **REST API** - Clean endpoints with proper HTTP status codes

---

## 🛠 Technology Stack

| Layer | Technology | Version | Purpose |
|-------|-----------|---------|---------|
| **Frontend** | Vue 3 | 3.x | Reactive component-based UI |
| **Build Tool** | Vite | 5.4.21 | Fast bundling and optimization |
| **Backend** | PHP | 8.2+ | Modern server-side logic |
| **Framework** | Laravel | 11.0 | Project structure and utilities |
| **Testing** | PHPUnit | 11.5.55 | Automated unit tests |
| **Database** | SQLite | 3.x | Development data persistence |
| **Runtime** | Node.js | 18+ | Build and asset pipeline |

---

## 📁 Project Structure

```
PedalPalBikeRental/
├── public/                          # Web root (document root)
│   ├── index.html                  # SPA entry point
│   └── build/                        # Compiled assets
│       ├── assets/
│       │   ├── app-*.css           # Compiled styles
│       │   └── app-*.js            # Compiled components
│       └── manifest.json            # Asset manifest
│
├── resources/
│   ├── js/
│   │   ├── app.js                  # Vue app initialization
│   │   ├── App.vue                 # Root component (navbar)
│   │   ├── router.js               # Vue Router configuration
│   │   └── components/
│   │       ├── Home.vue            # Landing page (hero + categories)
│   │       ├── Bikes.vue           # Bike catalog with filters
│   │       ├── Accessories.vue     # Accessory shop with cart
│   │       ├── Admin.vue           # Admin dashboard
│   │       └── Login.vue           # Staff login portal
│   └── css/
│       └── app.css                 # Global styling (variables, utilities)
│
├── services/                        # Business logic layer
│   ├── BeachCruiserService.php      # Beach cruiser operations
│   ├── MountainBikeService.php      # Mountain bike operations
│   ├── AccessoryService.php         # Accessory operations
│   └── ApplicationServices.php      # Shared utilities
│
├── data/                            # Data access layer (repositories)
│   ├── BeachCruiserRepository.php
│   ├── MountainBikeRepository.php
│   └── AccessoryRepository.php
│
├── handlers/                        # API request handlers
│   ├── bike-handler.php             # Bike endpoints
│   ├── accessory-handler.php        # Accessory endpoints
│   ├── admin-handler.php            # Admin endpoints
│   └── auth-handler.php             # Authentication endpoints
│
├── tests/
│   ├── TestCase.php                 # Base test class
│   └── Unit/
│       ├── BeachCruiserServiceTest.php (3 tests)
│       ├── MountainBikeServiceTest.php (3 tests)
│       └── AccessoryServiceTest.php (3 tests)
│
├── database/
│   └── pedalpal.db                  # SQLite database
│       ├── BeachCruisers table (6 records)
│       ├── MountainBikes table (6 records)
│       └── Accessories table (4 records)
│
├── docs/                            # Documentation
│   ├── 01_PedalPal_PRD.md
│   ├── 02_PedalPal_Architecture.md
│   └── wireframe-design-specs/      # UI/UX specifications
│
├── .github/
│   └── instructions/                # Copilot instructions
│       ├── code-review-generic.instructions.md
│       ├── php-modernization-guidelines.instructions.md
│       └── copilot-instructions.md  # AI usage documentation
│
├── composer.json                    # PHP dependencies
├── package.json                     # Node.js dependencies
├── phpunit.xml                      # PHPUnit configuration
├── vite.config.js                   # Vite build configuration
├── setup-database.php               # Database initialization script
└── watch.js                         # File watcher for development
```

---

## 🚀 Quick Start

### Prerequisites
- PHP 8.2+
- Node.js 18+ & npm
- Composer (included in vendor/)

### Installation & Setup

1. **Navigate to project**
   ```bash
   cd d:\xampp8.2\htdocs\PedalPalBikeRental
   ```

2. **Install dependencies**
   ```bash
   npm install
   ```

3. **Initialize database with sample data**
   ```bash
   php setup-database.php
   ```

4. **Build frontend assets**
   ```bash
   npm run build
   ```

5. **Start PHP development server**
   ```bash
   php -S localhost:8080 -t public/
   ```

6. **Access the application**
   - **Home**: http://localhost:8080/
   - **Admin Panel**: http://localhost:8080/admin/login
   - **Demo Login**: `admin` / `password`

### Development Commands

```bash
# Watch for changes and rebuild
npm run build

# Run test suite
vendor/bin/phpunit

# Run tests with coverage report
vendor/bin/phpunit --coverage-html coverage/

# Preview production build
npm run preview
```

---

## 🏗 Architecture

### Three-Tier Architecture

```
┌────────────────────────────────────────────────────────────────┐
│                      Presentation Layer                         │
│  Vue 3 Components (Home, Bikes, Accessories, Admin, Login)     │
│  Router: Client-side routing with Vue Router                   │
│  Styling: Global CSS + Scoped component styles                 │
└────────────────────────────────┬─────────────────────────────────┘
                                 │
                        REST API (JSON)
                                 │
┌────────────────────────────────▼─────────────────────────────────┐
│                     Application Layer                            │
│  API Routes: public/index.php routes requests                  │
│  Handlers: bike, accessory, admin, auth handlers process requests
│  Services: Business logic (rental, filtering, validation)      │
│  Repositories: Data access abstraction layer                   │
└────────────────────────────────┬─────────────────────────────────┘
                                 │
                           SQL Queries
                                 │
┌────────────────────────────────▼─────────────────────────────────┐
│                      Data Layer                                  │
│  SQLite Database (database/pedalpal.db)                        │
│  Tables: BeachCruisers, MountainBikes, Accessories             │
└─────────────────────────────────────────────────────────────────┘
```

### Design Patterns Used

- **Service Layer Pattern**: Encapsulates business logic
- **Repository Pattern**: Abstracts data access
- **MVC Components**: Vue components with clear separation
- **REST API**: Standard HTTP methods and status codes
- **Dependency Injection**: Services receive dependencies via constructor

---

## 🔌 API Endpoints

### Bikes API
```
GET  /api/bikes                      # Get all bikes
GET  /api/bikes?type=beach-cruiser   # Filter by type
GET  /api/bikes?type=mountain-bike   # Get mountain bikes only
POST /api/bikes/{id}/rent            # Rent a specific bike
```

### Accessories API
```
GET  /api/accessories                # Get all accessories
POST /api/accessories/order          # Place order with items
```

### Admin API
```
POST /api/admin/reset                # Reset all inventory to defaults
POST /api/admin/bikes/{id}/return    # Mark bike as returned
GET  /api/admin/activity             # Get activity log
```

### Auth API
```
POST /api/auth/login                 # Admin login
GET  /api/auth/check                 # Verify session status
```

---

## ✅ Testing

### Running Tests

```bash
# Run all tests
vendor/bin/phpunit

# Run specific test file
vendor/bin/phpunit tests/Unit/BeachCruiserServiceTest.php

# Generate HTML coverage report
vendor/bin/phpunit --coverage-html coverage/
```

### Test Summary

| Test Suite | Tests | Status | Coverage |
|-----------|-------|--------|----------|
| BeachCruiserService | 3 | ✅ Passing | 100% |
| MountainBikeService | 3 | ✅ Passing | 100% |
| AccessoryService | 3 | ✅ Passing | 100% |
| **Total** | **9** | **✅ 100%** | **~40%** |

### Test Examples

**Beach Cruiser Tests**:
1. `test_get_all_beach_cruisers` - Retrieves all beach cruisers
2. `test_rent_bike_success` - Successfully rents available bike
3. `test_rent_bike_unavailable` - Prevents renting out-of-stock bike

**Accessory Tests**:
1. `test_get_compatible_accessories` - Filters by bike type
2. `test_apply_bundle_discount` - Calculates bundle discounts
3. `test_insufficient_stock` - Validates stock availability

---

## 📊 Modernization Details

### What Changed: Frontend

| Aspect | Legacy | Modern |
|--------|--------|--------|
| **Framework** | Static HTML | Vue 3 SPA |
| **Styling** | Inline CSS | Global CSS + Scoped |
| **Design** | Minimal/Basic | Professional Gradient Design |
| **Responsiveness** | Static layout | Mobile-first responsive |
| **Interactivity** | Form submission | Real-time reactive UI |
| **Build Tool** | None | Vite 5 optimized |
| **Components** | None | 5 reusable Vue components |
| **Routing** | PHP routing | Vue Router client-side |

### What Changed: Backend

| Aspect | Legacy | Modern |
|--------|--------|--------|
| **PHP Version** | Older | 8.2+ with modern syntax |
| **Functions** | Deprecated (`create_function()`) | Modern closures |
| **Architecture** | Monolithic | Service-oriented |
| **Data Access** | Inline queries | Repository pattern |
| **Input Handling** | Basic checks | Comprehensive validation |
| **Error Handling** | Silent failures | Explicit exceptions |
| **Testing** | Manual QA | PHPUnit automated (9 tests) |

For more detailed comparision, please click here [Legacy_vs_Modernized_Comparision.md](https://github.com/chitrafegde55/PedalPalBikeRental/blob/modernization-enhancements/docs/Legacy_vs_Modernized_Comparision.md)

### UI/UX Enhancements

✅ **Home Page** - Gradient hero section with CTA buttons
✅ **Category Cards** - Visual cards with emojis for bike types
✅ **Bike Catalog** - Sidebar filters, availability badges, hover effects
✅ **Accessory Shop** - Shopping cart with quantity controls, total display
✅ **Admin Dashboard** - Statistics cards, activity timeline, bulk operations
✅ **Login Page** - Centered form, gradient background, demo credentials display
✅ **Animations** - Smooth transitions, bounce effects, hover states
✅ **Mobile Design** - Full responsiveness for phones/tablets/desktops

### Performance Metrics

- **Build Size**: 86 modules transformed
- **CSS (gzipped)**: 2.73 KB
- **JS (gzipped)**: 53.66 KB
- **Page Load**: < 1s (full page with API calls)
- **Test Execution**: ~25ms for 9 tests
- **Database Init**: < 100ms

---

## 🤖 AI Usage & Prompting Strategy

### How AI Assisted the Modernization

This project actively leveraged AI tools (GitHub Copilot for generating code, Claude Code for generating PRD and Specs) to accelerate development while maintaining quality and demonstrating strategic thinking.

- Claude Usage for generating more specific and relevant specs and PRD documents.
<img width="1821" height="853" alt="image" src="https://github.com/user-attachments/assets/6af6a738-ae5d-46c5-ad58-0aad9ed07375" />

- GitHub Copilot is mostly used for generating and writing the code using VS Code editor and validating the changes.
<img width="1858" height="943" alt="image" src="https://github.com/user-attachments/assets/f1f81602-f522-44f7-888b-751549521d5a" />

### Key AI-Assisted Areas

**1. Architecture Design**
- Recommended service-repository pattern
- Suggested API-first separation of concerns
- Guided clean architecture decisions

Please find the [PRD](https://github.com/chitrafegde55/PedalPalBikeRental/blob/modernization-enhancements/docs/01_PedalPal_PRD.md) and [Architecture](https://github.com/chitrafegde55/PedalPalBikeRental/blob/modernization-enhancements/docs/02_PedalPal_Architecture.md) document here.

**2. Service Layer Implementation**
```
Prompt: "Generate PHP 8.2 service class for bike rentals"
- No deprecated functions (removed create_function)
- Constructor dependency injection
- Typed parameters and returns
- Business logic: rentBike, getAll, calculateDiscount
```
Result: 3 modern service classes, fully typed

**3. Vue 3 Components**
```
Prompt: "Design Vue 3 components (Composition API)"
- BikeCard with availability badge
- FilterBar with counts
- ShoppingCart with quantity controls
- Loading and empty states
```
Result: 5 components, responsive, accessible

**4. Testing Strategy**
```
Prompt: "Create PHPUnit tests using Arrange-Act-Assert"
- Test successful operations
- Test failure scenarios
- Mock dependencies
- Clear test naming
```
Result: 9 tests, 100% passing

**5. CSS Design System**
```
Prompt: "Create modern CSS with gradients, responsive layout"
- CSS variables for colors
- Responsive breakpoints
- Hero gradient (135deg indigo→purple)
- Smooth animations
```
Result: 450+ lines, production-ready

For more [prompts](https://github.com/chitrafegde55/PedalPalBikeRental/tree/modernization-enhancements/.github/prompts) and [instruction](https://github.com/chitrafegde55/PedalPalBikeRental/tree/modernization-enhancements/.github/instructions) click here.

### Prompting Best Practices Used

✅ **Specific over generic** - "Create BeachCruiserService.rentBike()" vs "create services"
✅ **Business context** - Included bike rental domain details  
✅ **Version specificity** - "PHP 8.2", "Vue 3", "PHPUnit 11"
✅ **Examples provided** - Showed desired patterns
✅ **Incremental requests** - One component/service at a time

### Time Saved with AI Collaboration

| Phase | Traditional | With AI | Saved |
|-------|------------|---------|-------|
| Services | 6 hours | 2 hours | 4 hrs |
| Components | 10 hours | 3 hours | 7 hrs |
| CSS | 5 hours | 1.5 hours | 3.5 hrs |
| Tests | 4 hours | 1 hour | 3 hrs |
| Docs | 8 hours | 2 hours | 6 hrs |
| **Total** | **33 hours** | **9.5 hours** | **23.5 hrs (71%)** |

---

## 💻 Development

### Development Workflow

1. **Make component changes** in `resources/js/components/`
2. **Update global styles** in `resources/css/app.css`
3. **Run build**: `npm run build`
4. **Test in browser**: http://localhost:8080
5. **Run tests**: `vendor/bin/phpunit`

### Code Organization

**Vue Components** (Composition API):
```vue
<script setup>
const bikes = ref([]);
const loading = ref(false);

const fetchBikes = async () => {
  loading.value = true;
  try {
    const { data } = await axios.get('/api/bikes');
    bikes.value = data.data || [];
  } finally {
    loading.value = false;
  }
}
</script>
```

**Services** (Business Logic):
```php
class BeachCruiserService {
    public function __construct(BeachCruiserRepository $repo) {
        $this->repo = $repo;
    }
    
    public function rentBike($id) {
        // Validation, state changes, business rules
    }
}
```

**Repositories** (Data Access):
```php
class BeachCruiserRepository {
    public function findById($id) { /* ... */ }
    public function updateAvailability($id, $qty) { /* ... */ }
}
```

### Adding New Features

1. Create Vue component in `resources/js/components/`
2. Add route in `resources/js/router.js`
3. Create service in `services/`
4. Add repository in `data/` if needed
5. Create API handler in `handlers/`
6. Write tests in `tests/Unit/`
7. Build and test: `npm run build && vendor/bin/phpunit`

---

## 🔐 Security Considerations

- ✅ Input validation on all endpoints
- ✅ Parameterized queries (SQL injection prevention)
- ✅ Admin authentication for sensitive operations
- ✅ CORS ready API structure
- ✅ Environment variables for configuration
- ⚠️ Use HTTPS in production
- ⚠️ Store admin credentials securely

---

## 📦 Deployment

### Production Checklist

- [ ] Set `NODE_ENV=production`
- [ ] Run `npm run build` for optimized assets
- [ ] Configure database (upgrade to PostgreSQL/MySQL if needed)
- [ ] Set proper file permissions on `database/` and `storage/`
- [ ] Enable HTTPS for all endpoints
- [ ] Configure strong admin authentication
- [ ] Review security headers
- [ ] Set up error logging
- [ ] Configure CDN for static assets (optional)

### Environment Setup

Create `.env` file (optional, for future expansion):
```
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=sqlite
DB_DATABASE=database/pedalpal.db
ADMIN_USER=admin
ADMIN_PASS=your_secure_password
```

---

## 🎓 Learning Resources

This project demonstrates:
- Modern Vue 3 Composition API patterns
- Service-oriented architecture in PHP
- REST API design best practices
- Responsive CSS design with gradients
- PHPUnit testing practices
- Vite bundling and optimization
- Repository pattern for data access

---

## 📚 Documentation

Comprehensive documentation available:
- **PRD** (`docs/01_PedalPal_PRD.md`) - Product requirements
- **Architecture** (`docs/02_PedalPal_Architecture.md`) - System design
- **Wireframes** (`docs/wireframe-design-specs/`) - UI/UX specs
- **AI Prompts** (`.github/instructions/`) - Development guidance

---

## 🤝 Contributing

For questions or improvements:
1. Review existing documentation in `docs/`
2. Check API endpoints in this README
3. Run test suite: `vendor/bin/phpunit`
4. Follow existing code patterns

---

## 📝 License

MIT License - Feel free to use for learning and projects

---
