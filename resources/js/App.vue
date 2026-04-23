<template>
  <div id="app">
    <header class="navbar">
      <div class="navbar-container">
        <div class="navbar-logo">
          <span class="logo-icon">🚲</span>
          <span class="logo-text">PedalPal</span>
        </div>
        <nav class="navbar-nav">
          <router-link to="/bikes" class="nav-link">Beach Cruisers</router-link>
          <router-link to="/bikes" class="nav-link">Mountain Bikes</router-link>
          <router-link to="/accessories" class="nav-link">Accessories</router-link>
          <router-link to="/admin/login" class="nav-link staff-login">Staff login</router-link>
        </nav>
      </div>
    </header>
    <main>
      <router-view />
    </main>
  </div>
</template>

<script>
export default {
  name: 'App'
}
</script>

<style scoped>
:root {
  --primary-color: #6366f1;
  --text-dark: #1f2937;
  --text-light: #6b7280;
  --border-radius: 8px;
}

.navbar {
  background-color: white;
  border-bottom: 1px solid #e5e7eb;
  padding: 1rem 0;
  position: sticky;
  top: 0;
  z-index: 100;
}

.navbar-container {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.navbar-logo {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-weight: 700;
  font-size: 1.25rem;
  color: var(--text-dark);
  text-decoration: none;
  cursor: pointer;
}

.logo-icon {
  font-size: 1.5rem;
  background: linear-gradient(135deg, var(--primary-color), #8b5cf6);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.navbar-nav {
  display: flex;
  gap: 2rem;
  align-items: center;
}

.nav-link {
  text-decoration: none;
  color: var(--text-dark);
  font-size: 0.95rem;
  font-weight: 500;
  padding: 0.5rem 0;
  border-bottom: 2px solid transparent;
  transition: all 0.3s ease;
}

.nav-link:hover {
  color: var(--primary-color);
  border-bottom-color: var(--primary-color);
}

.nav-link.router-link-active {
  color: var(--primary-color);
  border-bottom-color: var(--primary-color);
}

.staff-login {
  background-color: var(--primary-color);
  color: white !important;
  padding: 0.5rem 1rem;
  border-radius: var(--border-radius);
  border-bottom: none;
}

.staff-login:hover {
  background-color: #4f46e5;
  border-bottom: none;
}

main {
  flex: 1;
  max-width: 1280px;
  margin: 0 auto;
  width: 100%;
  padding: 0;
}

@media (max-width: 768px) {
  .navbar-container {
    padding: 0 1rem;
  }

  .navbar-nav {
    gap: 1rem;
    font-size: 0.85rem;
  }

  .logo-text {
    display: none;
  }
}
</style>