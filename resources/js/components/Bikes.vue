<template>
  <div class="bikes-page">
    <div class="page-header">
      <div class="header-content">
        <h1>🚴‍♂️ Bike Rentals</h1>
        <p>Choose from our premium collection of bicycles</p>
      </div>
    </div>

    <div class="bikes-container">
      <div class="filters-sidebar">
        <h3>Filter by Type</h3>
        <div class="filter-buttons">
          <button 
            @click="filterType = null" 
            :class="{ active: filterType === null }"
            class="filter-btn"
          >
            All Bikes
          </button>
          <button 
            @click="filterType = 'beach-cruiser'" 
            :class="{ active: filterType === 'beach-cruiser' }"
            class="filter-btn"
          >
            🏖️ Beach Cruisers
          </button>
          <button 
            @click="filterType = 'mountain-bike'" 
            :class="{ active: filterType === 'mountain-bike' }"
            class="filter-btn"
          >
            ⛰️ Mountain Bikes
          </button>
        </div>
      </div>

      <div class="bikes-main">
        <div v-if="loading" class="loading">
          <p>Loading bikes...</p>
        </div>
        <div v-else-if="filteredBikes.length === 0" class="no-bikes">
          <p>No bikes available</p>
        </div>
        <div v-else class="bike-grid">
          <div v-for="bike in filteredBikes" :key="bike.id" class="bike-card">
            <div class="bike-card-header">
              <h3>{{ bike.name }}</h3>
              <span class="availability-badge" :class="{ available: bike.availability > 0, unavailable: bike.availability === 0 }">
                {{ bike.availability > 0 ? `${bike.availability} available` : 'Out of stock' }}
              </span>
            </div>
            <p class="bike-description">{{ bike.description }}</p>
            <div class="bike-details">
              <div class="detail-item">
                <span class="label">Price:</span>
                <span class="value">${{ bike.price_per_hour }}/hour</span>
              </div>
              <div class="detail-item">
                <span class="label">Type:</span>
                <span class="value">{{ formatType(bike.type) }}</span>
              </div>
            </div>
            <button 
              @click="rentBike(bike)" 
              :disabled="bike.availability === 0 || loading"
              class="rent-button"
            >
              {{ loading ? 'Processing...' : 'Rent Now' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Bikes',
  data() {
    return {
      bikes: [],
      filterType: null,
      loading: true,
      error: null
    };
  },
  computed: {
    filteredBikes() {
      if (!this.filterType) return this.bikes;
      return this.bikes.filter(bike => bike.type === this.filterType);
    }
  },
  async mounted() {
    await this.fetchBikes();
  },
  methods: {
    async fetchBikes() {
      this.loading = true;
      try {
        const response = await axios.get('/api/bikes');
        this.bikes = response.data.data || [];
      } catch (error) {
        console.error('Error fetching bikes:', error);
        this.error = 'Failed to load bikes';
      } finally {
        this.loading = false;
      }
    },
    async rentBike(bike) {
      this.loading = true;
      try {
        const response = await axios.post(`/api/bikes/${bike.id}/rent`);
        alert(response.data.message || 'Bike rented successfully!');
        await this.fetchBikes();
      } catch (error) {
        alert('Error: ' + (error.response?.data?.message || 'Failed to rent bike'));
      } finally {
        this.loading = false;
      }
    },
    formatType(type) {
      if (type === 'beach-cruiser') return 'Beach Cruiser';
      if (type === 'mountain-bike') return 'Mountain Bike';
      return type;
    }
  }
}
</script>

<style scoped>
.bikes-page {
  flex: 1;
  background-color: #f9fafb;
}

.page-header {
  background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
  color: white;
  padding: 3rem 2rem;
  text-align: center;
}

.header-content h1 {
  font-size: 2.5rem;
  font-weight: 800;
  margin-bottom: 0.5rem;
  letter-spacing: -0.02em;
}

.header-content p {
  font-size: 1.1rem;
  opacity: 0.95;
}

.bikes-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
  display: grid;
  grid-template-columns: 250px 1fr;
  gap: 2rem;
}

.filters-sidebar {
  background: white;
  padding: 1.5rem;
  border-radius: var(--border-radius);
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  height: fit-content;
}

.filters-sidebar h3 {
  margin-bottom: 1rem;
  color: var(--text-dark);
}

.filter-buttons {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.filter-btn {
  padding: 0.75rem 1rem;
  border: 2px solid #e5e7eb;
  background: white;
  color: var(--text-dark);
  border-radius: var(--border-radius);
  cursor: pointer;
  font-weight: 500;
  transition: all 0.3s ease;
  text-align: left;
}

.filter-btn:hover {
  border-color: var(--primary-color);
  color: var(--primary-color);
}

.filter-btn.active {
  background: var(--primary-color);
  color: white;
  border-color: var(--primary-color);
}

.bikes-main {
  background: white;
  border-radius: var(--border-radius);
  padding: 2rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.loading, .no-bikes {
  text-align: center;
  padding: 3rem;
  color: var(--text-light);
}

.bike-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 1.5rem;
}

.bike-card {
  background: white;
  border: 1px solid #e5e7eb;
  border-radius: var(--border-radius);
  overflow: hidden;
  transition: all 0.3s ease;
}

.bike-card:hover {
  border-color: var(--primary-color);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  transform: translateY(-2px);
}

.bike-card-header {
  padding: 1.5rem;
  border-bottom: 1px solid #e5e7eb;
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 1rem;
}

.bike-card-header h3 {
  margin: 0;
  color: var(--text-dark);
  font-size: 1.25rem;
}

.availability-badge {
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
  white-space: nowrap;
}

.availability-badge.available {
  background: #d1fae5;
  color: #065f46;
}

.availability-badge.unavailable {
  background: #fee2e2;
  color: #991b1b;
}

.bike-description {
  padding: 1rem 1.5rem;
  color: var(--text-light);
  margin: 0;
  font-size: 0.95rem;
}

.bike-details {
  padding: 0 1.5rem;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.detail-item {
  display: flex;
  justify-content: space-between;
  padding: 0.5rem 0;
  border-bottom: 1px solid #f3f4f6;
}

.detail-item .label {
  color: var(--text-light);
  font-size: 0.9rem;
}

.detail-item .value {
  color: var(--text-dark);
  font-weight: 600;
}

.rent-button {
  width: 100%;
  padding: 1rem;
  margin: 1.5rem 0 0 0;
  background: var(--primary-color);
  color: white;
  border: none;
  border-radius: 0 0 var(--border-radius) var(--border-radius);
  font-weight: 600;
  font-size: 0.95rem;
  cursor: pointer;
  transition: all 0.3s ease;
}

.rent-button:hover:not(:disabled) {
  background: #4f46e5;
  transform: translateY(-1px);
}

.rent-button:disabled {
  background: #d1d5db;
  cursor: not-allowed;
}

@media (max-width: 768px) {
  .bikes-container {
    grid-template-columns: 1fr;
  }

  .filters-sidebar {
    order: 2;
  }

  .bikes-main {
    order: 1;
  }

  .bike-grid {
    grid-template-columns: 1fr;
  }

  .page-header h1 {
    font-size: 1.75rem;
  }
}
</style>