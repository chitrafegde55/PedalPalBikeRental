---
name: run-application
description: Use this prompt to help developers start the PedalPal application and verify it's running correctly
---

# Running PedalPal Application

## Prerequisites
- PHP 8.2.12+ installed and accessible
- Node.js 18+ and npm 10+
- Port 8080 available (customizable)

## Quick Start (5 minutes)

### Option 1: Direct PHP Server (Simplest)
```bash
# From project root: d:\xampp8.2\htdocs\PedalPalBikeRental
php -S localhost:8080 -t public
```
Then open `http://localhost:8080` in browser.

### Option 2: With Vite Dev Server (with hot reload)
```bash
# Terminal 1: Start Vite dev server
npm run dev

# Terminal 2: Start PHP server
php -S localhost:8080 -t public
```

## Startup Sequence

### Step 1: Navigate to Project
```bash
cd d:\xampp8.2\htdocs\PedalPalBikeRental
```

### Step 2: Install Dependencies (first time only)
```bash
npm install
```

### Step 3: Build Vue Components
```bash
npm run build
```
Expected output:
```
✓ 8 modules transformed
public/build/assets/app-XXXXX.css (14.53 kB)
public/build/assets/app-XXXXX.js (139.90 kB)
Built in 828ms
```

### Step 4: Start PHP Development Server
```bash
php -S localhost:8080 -t public
```
Expected output:
```
[Mon Jan 15 10:30:45 2024] Local: http://127.0.0.1:8080
[Mon Jan 15 10:30:45 2024] Press Ctrl+C to quit
```

### Step 5: Access Application
Open browser to: `http://localhost:8080`

## Verify Application is Running

### Visual Checklist
- [ ] **Page loads without errors** (no JS console errors)
- [ ] **Navbar visible** with logo 🚲 and navigation links
- [ ] **Hero section renders** with purple gradient background
- [ ] **Category cards** visible (Beach Cruisers 🏖️, Mountain Bikes ⛰️, Accessories 🛠️)
- [ ] **Navigation links functional** (click Beach Cruisers, Mountain Bikes, Accessories, Staff login)
- [ ] **Responsive design** works (resize browser to mobile size)

### URL Endpoints to Test

**Frontend Routes:**
- `http://localhost:8080` → Home page with hero section
- `http://localhost:8080#/bikes` → Bike catalog
- `http://localhost:8080#/accessories` → Accessories shop
- `http://localhost:8080#/admin` → Admin dashboard (try demo credentials: admin/admin)
- `http://localhost:8080#/login` → Staff login page

**API Endpoints (for debugging):**
- `http://localhost:8080/handlers/bike-handler.php?action=list` → Get all bikes (JSON)
- `http://localhost:8080/handlers/accessory-handler.php?action=list` → Get all accessories (JSON)
- Check developer tools (F12 → Network tab) to verify API responses

## Database Auto-Initialization

Database seeds automatically on first request:
- **Beach Cruisers**: 6 models with rental prices
- **Mountain Bikes**: 6 models with rental prices  
- **Accessories**: 4 items (helmets, lights, locks, water bottles)

Data stored in SQLite (auto-created on first run).

## Development Features

### Hot Reload (Optional)
If using `npm run dev`:
- Changes to `.vue` files auto-compile
- Changes to `app.css` auto-reload in browser
- Changes to PHP files require manual page reload

### Debug Console
Open browser DevTools (F12):
- **Console tab**: Check for JavaScript errors
- **Network tab**: Monitor API calls to `bike-handler.php` and `accessory-handler.php`
- **Application tab**: Inspect localStorage for cart data

## Common Issues & Fixes

### Issue: Port 8080 already in use
**Fix**: Use different port:
```bash
php -S localhost:8081 -t public
```

### Issue: Assets not loading (404 errors)
**Fix**: Run build again:
```bash
npm run build
```
Then check that `public/index.html` has correct hash references (e.g., `app-D6CZ3Kt9.css`, `app-CtBHwqA0.js`).

### Issue: Database errors or seed data missing
**Fix**: Database auto-creates on first request. If issues persist:
1. Delete any existing database file
2. Restart PHP server
3. Reload page in browser

### Issue: CSS gradients not showing
**Fix**: Ensure `resources/css/app.css` is imported in `resources/js/app.js` and run `npm run build`.

### Issue: Components not rendering
**Fix**: Check browser console for Vue errors. Verify Vue Router is loaded:
```bash
npm run build  # Recompile
```

## Performance Tips

- First load: ~2-3 seconds (database seed + Vue compilation)
- Subsequent loads: <500ms (cached)
- CSS file size: ~14.53 kB gzip optimized
- JS bundle: ~139.90 kB (includes Vue 3, Vue Router, axios)

## Stopping the Server

Press `Ctrl+C` in the terminal running `php -S localhost:8080 -t public`

## Next Steps After Running

1. **Run tests**: `vendor/bin/phpunit` (verify 9 tests pass)
2. **Browse app**: Click through all pages and test functionality
3. **Check console**: Look for any JavaScript warnings
4. **Review API calls**: Open DevTools Network tab to see API performance
5. **Check responsive**: Resize browser and test mobile view

## Architecture Notes

- **Entry Point**: `public/index.html` loads Vue app from compiled assets
- **PHP Routing**: `public/index.php` routes requests to handlers
- **Handlers**: `handlers/*-handler.php` process API requests and return JSON
- **Services**: Business logic in `services/` layer
- **Repositories**: Data access in `data/` repositories
- **Vue Components**: Frontend in `resources/js/components/`

## Troubleshooting Commands

```bash
# Check PHP version
php --version

# Check if port 8080 is free
netstat -ano | findstr :8080

# Clear browser cache
# Press Ctrl+F5 in browser

# Rebuild everything fresh
npm run build

# Run tests to verify backend
vendor\bin\phpunit
```