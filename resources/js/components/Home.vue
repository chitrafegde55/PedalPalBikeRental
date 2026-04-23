<template>
  <div class="home">
    <section class="hero">
      <div class="hero-content">
        <p class="hero-subtitle">BIKE RENTALS • EST. 2008</p>
        <h1 class="hero-title">Choose your adventure</h1>
        <p class="hero-tagline">Pedals provided. Excuses are extra.</p>
        <div class="hero-actions">
          <router-link to="/bikes" class="btn btn-primary">Browse Bikes</router-link>
          <router-link to="/accessories" class="btn btn-secondary">Shop Accessories</router-link>
        </div>
      </div>
    </section>

    <section class="bike-categories">
      <div class="category-grid">
        <div class="category-card">
          <div class="category-icon">🏖️</div>
          <h3>Beach Cruisers</h3>
          <p>Comfortable, smooth rides perfect for casual cycling along the coast.</p>
          <router-link to="/bikes?type=beach" class="category-link">View Bikes →</router-link>
        </div>
        <div class="category-card">
          <div class="category-icon">⛰️</div>
          <h3>Mountain Bikes</h3>
          <p>Rugged and powerful bikes for off-road adventures and trails.</p>
          <router-link to="/bikes?type=mountain" class="category-link">View Bikes →</router-link>
        </div>
        <div class="category-card">
          <div class="category-icon">🛠️</div>
          <h3>Accessories</h3>
          <p>Essential gear to enhance your ride and stay safe on the road.</p>
          <router-link to="/accessories" class="category-link">Shop Now →</router-link>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  name: 'Home'
}
</script>

<style scoped>
:root {
  --primary-color: #6366f1;
  --secondary-color: #8b5cf6;
  --text-dark: #1f2937;
  --text-light: #6b7280;
  --bg-light: #f9fafb;
  --border-radius: 12px;
}

.home {
  width: 100%;
}

.hero {
  background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
  color: white;
  padding: 8rem 2rem;
  text-align: center;
  position: relative;
  overflow: hidden;
}

.hero::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="30" cy="20" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="15" r="1" fill="rgba(255,255,255,0.1)"/></svg>');
  opacity: 0.5;
}

.hero-content {
  position: relative;
  z-index: 1;
  max-width: 800px;
  margin: 0 auto;
}

.hero-subtitle {
  font-size: 0.875rem;
  letter-spacing: 0.15em;
  text-transform: uppercase;
  font-weight: 600;
  margin-bottom: 1rem;
  opacity: 0.9;
}

.hero-title {
  font-size: 3.5rem;
  font-weight: 800;
  line-height: 1.1;
  margin-bottom: 1rem;
  letter-spacing: -0.02em;
}

.hero-tagline {
  font-size: 1.25rem;
  margin-bottom: 2rem;
  opacity: 0.95;
  font-weight: 300;
}

.hero-actions {
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
}

.btn {
  display: inline-block;
  padding: 0.875rem 2rem;
  border-radius: var(--border-radius);
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s ease;
  font-size: 0.95rem;
  border: 2px solid transparent;
}

.btn-primary {
  background-color: white;
  color: var(--primary-color);
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.btn-secondary {
  background-color: transparent;
  color: white;
  border-color: white;
}

.btn-secondary:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

.bike-categories {
  padding: 4rem 2rem;
  background-color: var(--bg-light);
}

.category-grid {
  max-width: 1200px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 2rem;
}

.category-card {
  background-color: white;
  padding: 2rem;
  border-radius: var(--border-radius);
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
  text-align: center;
}

.category-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.category-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
}

.category-card h3 {
  font-size: 1.5rem;
  color: var(--text-dark);
  margin-bottom: 0.5rem;
}

.category-card p {
  color: var(--text-light);
  line-height: 1.6;
  margin-bottom: 1.5rem;
}

.category-link {
  color: var(--primary-color);
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s ease;
  display: inline-block;
}

.category-link:hover {
  gap: 0.5rem;
  transform: translateX(4px);
}

@media (max-width: 768px) {
  .hero {
    padding: 4rem 1.5rem;
  }

  .hero-title {
    font-size: 2rem;
  }

  .hero-tagline {
    font-size: 1rem;
  }

  .hero-actions {
    flex-direction: column;
  }

  .btn {
    width: 100%;
  }

  .category-grid {
    grid-template-columns: 1fr;
  }
}
</style>