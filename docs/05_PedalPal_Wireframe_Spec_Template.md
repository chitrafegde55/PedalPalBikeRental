# Wireframe Specification Template

## 1. Overview
- Project: PedalPal Bike Rental modernization
- Purpose: Capture wireframe pages, user interactions, and responsive behavior.

## 2. Design principles
- Clear navigation and page hierarchy.
- Responsive layout for desktop and mobile.
- Accessible controls and consistent UI patterns.

## 3. Wireframe pages

### 3.1 Homepage
- Navigation: logo, bike categories, accessories, admin login.
- Hero: value proposition, primary action buttons, category cards.
- Key sections: featured categories, how it works, quick links.
- Annotations: CTA placement, hierarchy, user journey.

### 3.2 Bike catalog
- Filter bar: availability, type, price, terrain.
- Product grid: cards with name, image, specs, rent action.
- Status indicator: available vs rented.
- Annotations: card layout, responsive wrap, disabled state.

### 3.3 Accessory shop
- Product list: image, description, price, add-to-cart controls.
- Cart sidebar: live summary, totals, checkout button.
- Annotations: quantity controls, bundle suggestions, stock status.

### 3.4 Rental flow
- Rent modal: selected bike summary, rental period, confirm action.
- Feedback: success/error toast notifications.
- Annotations: confirmation clarity, error handling, CTA text.

### 3.5 Admin dashboard
- Login page: simple auth form.
- Dashboard: stat cards, recent rentals, management actions.
- Annotations: secure staff-only flows, reset/danger actions.

## 4. Responsive behavior
- Breakpoints: mobile (≤640px), tablet (641–1024px), desktop (≥1025px).
- Mobile layout: stacked content, simplified navigation, collapsible filters.
- Desktop layout: grids, sidebars, expanded controls.

## 5. Component inventory
- `AppHeader`
- `CategoryCard`
- `BikeCard`
- `AccessoryCard`
- `RentModal`
- `CartSidebar`
- `ToastNotification`
- `AdminDashboard`

## 6. Notes for implementation
- Use Vue 3 composition API and component-driven markup.
- Keep styling utility-friendly for future Tailwind or CSS-in-JS.
- Annotate any custom animations or state transitions.
