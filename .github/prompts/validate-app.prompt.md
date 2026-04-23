---
name: validate-app
description: Use this prompt to comprehensively validate that PedalPal is functioning correctly across all components, UI, and backend systems
---

# PedalPal Application Validation Guide

## Pre-Validation Checklist

- [ ] Application running on `http://localhost:8080`
- [ ] No errors in browser console (F12 → Console)
- [ ] PHP server running: `php -S localhost:8080 -t public`
- [ ] All dependencies installed: `npm install` ✓
- [ ] Latest build compiled: `npm run build` ✓

## Component Rendering Validation

### 1. Home Page (Landing/Hero Section)
**URL**: `http://localhost:8080`

**Visual Checks**:
- [ ] Navbar displays with logo 🚲 on left
- [ ] Navigation links visible: Beach Cruisers, Mountain Bikes, Accessories, Staff login
- [ ] Hero section renders with **purple gradient background** (linear-gradient 135deg #6366f1 to #8b5cf6)
- [ ] Hero title: "Choose your adventure"
- [ ] Hero tagline: "Pedals provided. Excuses are extra."
- [ ] Two CTA buttons visible: "Browse Bikes", "Shop Accessories"
- [ ] Buttons are white with proper contrast against gradient
- [ ] Category cards below hero section:
  - [ ] Beach Cruisers 🏖️ (description visible)
  - [ ] Mountain Bikes ⛰️ (description visible)
  - [ ] Accessories 🛠️ (description visible)
- [ ] Cards have hover effects (subtle shadow change)

**Technical Checks**:
```bash
# Open DevTools Console (F12)
# Should see NO errors
# Check Network tab for CSS/JS files - all should be status 200
```

### 2. Bikes Catalog Page
**URL**: `http://localhost:8080#/bikes` OR click "Beach Cruisers" from home

**Visual Checks**:
- [ ] Page title: "Our Bikes Collection"
- [ ] Filter buttons visible on left sidebar:
  - [ ] "All" button
  - [ ] "Beach Cruisers 🏖️" with count
  - [ ] "Mountain Bikes ⛰️" with count
- [ ] Filter functionality works (click each, page updates)
- [ ] Bike cards display in grid:
  - [ ] Bike name
  - [ ] Bike image/icon (or placeholder)
  - [ ] Availability badge (green "In Stock" or red "Out of Stock")
  - [ ] Price per day
  - [ ] "Rent" button (enabled if available, disabled if not)
- [ ] Grid is responsive (3 columns on desktop, 1-2 on mobile)
- [ ] Hover effects on cards
- [ ] Loading state displays while fetching (brief spinner)

**Data Validation**:
- [ ] Beach Cruisers count: 6 (from seed data)
- [ ] Mountain Bikes count: 6 (from seed data)
- [ ] All bikes have prices displayed
- [ ] Availability status matches database

### 3. Accessories Shop Page
**URL**: `http://localhost:8080#/accessories` OR click "Shop Accessories" from home

**Visual Checks**:
- [ ] Page title: "Bike Accessories"
- [ ] Accessories displayed in grid:
  - [ ] Accessory name
  - [ ] Price
  - [ ] Quantity selector (+/- buttons)
  - [ ] "Add to Cart" button
- [ ] Shopping cart sidebar visible on right (sticky position)
- [ ] Cart shows:
  - [ ] "Shopping Cart" header
  - [ ] Items count
  - [ ] Total price
  - [ ] List of selected items with quantities
  - [ ] "Checkout" button
- [ ] Quantity controls work:
  - [ ] Click + increases quantity
  - [ ] Click - decreases quantity (min 1)
  - [ ] Total updates in real-time
- [ ] Responsive design (cart moves below grid on mobile)

**Data Validation**:
- [ ] Accessories count: 4 items (helmets, lights, locks, water bottles from seed data)
- [ ] All prices display correctly
- [ ] Stock levels accurate from database

### 4. Admin Dashboard Page
**URL**: `http://localhost:8080#/admin` OR click "Staff login" → use `admin`/`admin`

**Visual Checks**:
- [ ] Admin dashboard displays (may require login)
- [ ] Statistics cards visible:
  - [ ] Total Bikes
  - [ ] Available Bikes
  - [ ] Accessories Inventory
  - [ ] Recent Activities
- [ ] Inventory management section:
  - [ ] "Reset Inventory" button with confirmation
  - [ ] Button functionality tested
- [ ] Bike returns list (recent rentals):
  - [ ] Date/time of rental
  - [ ] Bike name and type
  - [ ] Return status
  - [ ] "Mark as Returned" button
- [ ] Activity log:
  - [ ] Timeline of rental activities
  - [ ] Timestamps displayed
  - [ ] Events clearly described
- [ ] Logout button functional

**Functional Checks**:
- [ ] Click "Mark as Returned" - status updates
- [ ] Click "Reset Inventory" - confirmation appears, inventory resets on confirm
- [ ] Statistics reflect actual data

### 5. Login Page
**URL**: `http://localhost:8080#/login`

**Visual Checks**:
- [ ] Login form centered and professional
- [ ] Gradient background behind form
- [ ] Logo displayed (🚲)
- [ ] Form fields:
  - [ ] Username input labeled
  - [ ] Password input (masked)
  - [ ] "Sign In" button
- [ ] Demo credentials displayed: `admin` / `admin`
- [ ] Error message area (test with wrong credentials)
- [ ] Login button shows spinner during submission

**Functional Checks**:
- [ ] Enter wrong credentials → error message appears
- [ ] Enter correct credentials (`admin`/`admin`) → redirected to admin dashboard
- [ ] Session persists on page refresh

## Responsive Design Validation

### Desktop (1200px+)
- [ ] All components display with full width
- [ ] Navbar horizontal layout
- [ ] Multi-column grids (3+ columns)
- [ ] Cart sidebar appears on right
- [ ] Typography easily readable

### Tablet (768px - 1199px)
- [ ] Navbar remains responsive
- [ ] Grids reduce to 2 columns
- [ ] Touch targets appropriately sized (44px+)
- [ ] No horizontal scrolling
- [ ] Cart moves appropriately

### Mobile (< 768px)
- [ ] Navbar collapses or streamlines
- [ ] Grids stack to 1 column
- [ ] Cart appears below content
- [ ] All buttons touch-friendly (44px+ height)
- [ ] No horizontal overflow
- [ ] Text readable without zooming

**Test Steps**:
```bash
# Open DevTools (F12)
# Click device toolbar icon (toggle device emulation)
# Test at: iPhone 12 (390px), iPad (768px), Desktop (1200px)
# Verify no layout breaks, no overflow
```

## API Validation

### Backend API Endpoints

**Test Bikes API:**
```
http://localhost:8080/handlers/bike-handler.php?action=list
```
Expected response: JSON array with beach cruisers and mountain bikes
```json
[
  {"id": 1, "name": "Beach Cruiser", "available": true, "price": 25},
  ...
]
```
- [ ] Returns 200 status
- [ ] Data is valid JSON
- [ ] Contains both Beach Cruisers (6) and Mountain Bikes (6)
- [ ] All fields present: id, name, type, available, price

**Test Accessories API:**
```
http://localhost:8080/handlers/accessory-handler.php?action=list
```
Expected response: JSON array with 4 accessories
- [ ] Returns 200 status
- [ ] 4 items returned (helmet, lights, lock, water bottle)
- [ ] All fields present: id, name, price, stock

### DevTools Network Validation
**Steps**:
1. Open DevTools (F12 → Network tab)
2. Reload page
3. Click through all pages (home, bikes, accessories, admin, login)
4. Verify all API calls:
   - [ ] All requests complete successfully (200 status)
   - [ ] No 404 errors
   - [ ] No 500 errors
   - [ ] Response times < 500ms
   - [ ] Asset sizes within budget (CSS < 20kB gzip, JS < 200kB gzip)

## Testing Validation

### Run PHPUnit Tests
```bash
vendor\bin\phpunit
```

**Expected Output**:
- [ ] 9 tests total
- [ ] All tests PASS ✓
- [ ] BeachCruiserServiceTest: 3 tests pass
- [ ] MountainBikeServiceTest: 3 tests pass
- [ ] AccessoryServiceTest: 3 tests pass
- [ ] Execution time < 100ms
- [ ] No errors or warnings

### View Coverage Report (Optional)
```bash
vendor\bin\phpunit --coverage-text
```
- [ ] Coverage > 80% target
- [ ] All service methods covered
- [ ] Critical paths tested

## CSS & Styling Validation

### Global Design System
- [ ] CSS variables used consistently:
  - [ ] `--primary-color`: #6366f1 (indigo)
  - [ ] `--secondary-color`: #8b5cf6 (purple)
  - [ ] `--success-color`: #10b981 (green for available)
  - [ ] `--danger-color`: #ef4444 (red for unavailable)
- [ ] Gradients render correctly:
  - [ ] Hero section: 135deg indigo to purple
  - [ ] Smooth color transitions
  - [ ] No visual glitches or banding

### Component Styling
- [ ] Buttons:
  - [ ] Hover states visible
  - [ ] Active states clear
  - [ ] Disabled states gray out
  - [ ] Consistent padding and sizing
- [ ] Cards:
  - [ ] Shadow on hover
  - [ ] Rounded corners
  - [ ] Consistent spacing
- [ ] Forms:
  - [ ] Input focus states visible
  - [ ] Error states red with icon
  - [ ] Placeholder text visible
- [ ] Badges:
  - [ ] In Stock: green background
  - [ ] Out of Stock: red background
  - [ ] Clear, readable text

## Accessibility Validation

**Keyboard Navigation:**
1. [ ] Tab through all interactive elements
2. [ ] Focus states clearly visible
3. [ ] Enter key activates buttons
4. [ ] Escape closes modals
5. [ ] Skip links present (optional)

**Screen Reader (with accessibility tools):**
- [ ] Navigation labels descriptive
- [ ] Button text clear (not just "Click here")
- [ ] Images/icons have alt text or aria-label
- [ ] Form labels associated with inputs
- [ ] Landmarks used (nav, main, footer)

**Color Contrast:**
- [ ] All text has sufficient contrast ratio (4.5:1 for normal, 3:1 for large)
- [ ] Use DevTools: Inspect element → Accessibility panel

## Performance Validation

### Load Time
- [ ] First load: < 3 seconds
- [ ] Subsequent loads: < 500ms
- [ ] API responses: < 200ms

### Browser DevTools Lighthouse
```
1. Open DevTools (F12 → Lighthouse tab)
2. Run audit for Desktop and Mobile
3. Target scores:
   - [ ] Performance > 90
   - [ ] Accessibility > 90
   - [ ] Best Practices > 90
   - [ ] SEO > 90
```

### Resource Sizes
```
DevTools → Network tab → right-click columns → Size
```
- [ ] CSS (app-*.css): ~14-15 kB
- [ ] JS (app-*.js): ~135-140 kB
- [ ] HTML: < 5 kB
- [ ] Total: < 160 kB (reasonable for Vue 3 SPA)

## Error Handling Validation

### Test Error Scenarios

**Database Connection Error:**
- [ ] If database unavailable, graceful error message (no ugly PHP errors)
- [ ] Error message is user-friendly
- [ ] Page doesn't crash

**Invalid API Response:**
- [ ] Missing data handled gracefully
- [ ] Type mismatches don't break UI
- [ ] Empty states display appropriately

**Form Validation:**
- [ ] Submit empty form → validation errors
- [ ] Submit with invalid email → error
- [ ] Submit with missing fields → error
- [ ] Error messages are clear

## Final Sign-Off Checklist

✅ **All core functionality works:**
- [ ] Home page renders with hero gradient
- [ ] Bikes page filters and displays all bikes
- [ ] Accessories page adds/removes items from cart
- [ ] Admin page manages inventory
- [ ] Login page authenticates users

✅ **All technical requirements met:**
- [ ] Tests pass: 9/9
- [ ] API endpoints respond (200 status)
- [ ] Responsive design works (desktop/tablet/mobile)
- [ ] CSS gradients render correctly
- [ ] No console errors
- [ ] Performance acceptable (< 3s load)
- [ ] Accessibility basics covered

✅ **Code quality verified:**
- [ ] No deprecated PHP functions
- [ ] Vue 3 patterns followed
- [ ] Service layer properly used
- [ ] TypeScript types present
- [ ] Proper error handling

✅ **Documentation complete:**
- [ ] README.md up to date
- [ ] API endpoints documented
- [ ] Component structure clear
- [ ] Setup instructions work

## Sign-Off

Once all checks pass, PedalPal is ready for:
- [ ] Deployment to staging
- [ ] User acceptance testing (UAT)
- [ ] Production release

**Date Validated**: [Today's date]
**Validated By**: [Your name]
**Notes**: [Any additional observations]