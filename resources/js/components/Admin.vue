<template>
  <div class="admin-page">
    <div class="admin-header-bar">
      <div class="header-content">
        <h1>📊 Admin Dashboard</h1>
        <p>Manage your bike rental inventory</p>
      </div>
      <button @click="logout" class="logout-button">
        Logout
      </button>
    </div>

    <div class="admin-container">
      <!-- Inventory Management Section -->
      <section class="admin-section">
        <div class="section-header">
          <h2>🗂️ Inventory Management</h2>
          <span class="section-icon">⚙️</span>
        </div>
        
        <div class="section-content">
          <p class="section-description">Reset all bikes and accessories to their default quantities and availability.</p>
          <button @click="resetInventory" :disabled="loading" class="action-button reset-button">
            <span v-if="loading" class="spinner"></span>
            {{ loading ? 'Resetting...' : 'Reset All Inventory' }}
          </button>
          <div v-if="message" :class="['alert', messageType]">
            <span class="alert-icon">{{ messageType === 'success' ? '✅' : '⚠️' }}</span>
            <div class="alert-content">
              <p class="alert-title">{{ messageType === 'success' ? 'Success' : 'Error' }}</p>
              <p class="alert-message">{{ message }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- Stats Cards -->
      <section class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon">🚴</div>
          <div class="stat-content">
            <div class="stat-value">{{ totalBikes }}</div>
            <div class="stat-label">Total Bikes</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">✅</div>
          <div class="stat-content">
            <div class="stat-value">{{ availableBikes }}</div>
            <div class="stat-label">Available</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">🛠️</div>
          <div class="stat-content">
            <div class="stat-value">{{ totalAccessories }}</div>
            <div class="stat-label">Accessories</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">📝</div>
          <div class="stat-content">
            <div class="stat-value">{{ activity.length }}</div>
            <div class="stat-label">Activities</div>
          </div>
        </div>
      </section>

      <!-- Bike Returns Section -->
      <section class="admin-section">
        <div class="section-header">
          <h2>🚴 Bike Returns</h2>
          <span class="badge">{{ rentedBikes.length }}</span>
        </div>
        
        <div class="section-content">
          <div v-if="rentedBikes.length === 0" class="empty-state">
            <p class="empty-icon">😊</p>
            <p class="empty-text">No bikes currently rented out.</p>
          </div>
          <div v-else class="bike-list">
            <div v-for="bike in rentedBikes" :key="bike.id" class="bike-item">
              <div class="bike-item-header">
                <div class="bike-badge"></div>
                <div class="bike-info">
                  <p class="bike-name">{{ bike.name }}</p>
                  <p class="bike-type">{{ formatType(bike.type) }} Bike</p>
                </div>
              </div>
              <button @click="returnBike(bike)" :disabled="loading" class="action-button return-button">
                Mark Returned
              </button>
            </div>
          </div>
        </div>
      </section>

      <!-- Recent Activity Section -->
      <section class="admin-section">
        <div class="section-header">
          <h2>📊 Recent Activity</h2>
          <button @click="loadActivity" :disabled="loading" class="refresh-button" title="Refresh activity">
            🔄
          </button>
        </div>
        
        <div class="section-content">
          <div v-if="activity.length === 0" class="empty-state">
            <p class="empty-icon">📭</p>
            <p class="empty-text">No recent activity.</p>
          </div>
          <div v-else class="activity-list">
            <div v-for="item in activity.slice(0, 10)" :key="item.id" class="activity-item">
              <div class="activity-icon" :class="item.type">
                {{ getActivityIcon(item.type) }}
              </div>
              <div class="activity-details">
                <div class="activity-summary">
                  <strong>{{ getActivityAction(item.type) }}</strong>
                  <span class="activity-item-name">{{ item.item_name }}</span>
                </div>
                <div class="activity-meta">
                  {{ formatDate(item.timestamp) }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Admin',
  data() {
    return {
      loading: false,
      message: '',
      messageType: 'success',
      rentedBikes: [],
      activity: [],
      bikes: [],
      accessories: []
    };
  },
  computed: {
    totalBikes() {
      return this.bikes.length;
    },
    availableBikes() {
      return this.bikes.filter(b => b.availability > 0).length;
    },
    totalAccessories() {
      return this.accessories.length;
    }
  },
  async mounted() {
    await this.loadRentedBikes();
    await this.loadActivity();
    await this.loadBikes();
    await this.loadAccessories();
  },
  methods: {
    async resetInventory() {
      if (!confirm('Are you sure you want to reset all inventory? This will restore all bikes and accessories to default quantities. This action cannot be undone.')) {
        return;
      }

      this.loading = true;
      this.message = '';

      try {
        const response = await axios.post('/api/admin/reset');
        this.message = response.data.message || 'Inventory reset successfully';
        this.messageType = 'success';
        await this.loadRentedBikes();
        await this.loadBikes();
        await this.loadAccessories();
        await this.loadActivity();
      } catch (error) {
        this.message = error.response?.data?.message || 'Reset failed. Please try again.';
        this.messageType = 'error';
      } finally {
        this.loading = false;
      }
    },

    async loadBikes() {
      try {
        const response = await axios.get('/api/bikes');
        this.bikes = response.data.data || [];
      } catch (error) {
        console.error('Failed to load bikes:', error);
      }
    },

    async loadAccessories() {
      try {
        const response = await axios.get('/api/accessories');
        this.accessories = response.data.data || [];
      } catch (error) {
        console.error('Failed to load accessories:', error);
      }
    },

    async loadRentedBikes() {
      try {
        const response = await axios.get('/api/bikes');
        this.bikes = response.data.data || [];
        this.rentedBikes = this.bikes.filter(bike => bike.availability === 0);
      } catch (error) {
        console.error('Failed to load rented bikes:', error);
      }
    },

    async returnBike(bike) {
      this.loading = true;
      try {
        const response = await axios.post(`/api/admin/bikes/${bike.id}/return`);
        this.message = response.data.message || 'Bike returned successfully';
        this.messageType = 'success';
        await this.loadRentedBikes();
        await this.loadActivity();
      } catch (error) {
        this.message = error.response?.data?.message || 'Failed to return bike';
        this.messageType = 'error';
      } finally {
        this.loading = false;
      }
    },

    async loadActivity() {
      try {
        const response = await axios.get('/api/admin/activity');
        this.activity = response.data.data || [];
      } catch (error) {
        console.error('Failed to load activity:', error);
      }
    },

    getActivityIcon(type) {
      const icons = {
        rental: '🚴',
        return: '✅',
        order: '🛒',
        reset: '🔄'
      };
      return icons[type] || '📝';
    },

    getActivityAction(type) {
      const actions = {
        rental: 'Rented',
        return: 'Returned',
        order: 'Ordered',
        reset: 'Reset'
      };
      return actions[type] || 'Activity';
    },

    formatType(type) {
      if (type === 'beach-cruiser') return 'Beach Cruiser';
      if (type === 'mountain-bike') return 'Mountain Bike';
      return type;
    },

    formatDate(timestamp) {
      if (!timestamp) return 'Just now';
      const date = new Date(timestamp);
      const now = new Date();
      const diff = now - date;
      
      if (diff < 60000) return 'Just now';
      if (diff < 3600000) return Math.floor(diff / 60000) + 'm ago';
      if (diff < 86400000) return Math.floor(diff / 3600000) + 'h ago';
      
      return date.toLocaleDateString();
    },

    logout() {
      if (confirm('Are you sure you want to logout?')) {
        localStorage.removeItem('admin_authenticated');
        localStorage.removeItem('admin_user');
        this.$router.push('/admin/login');
      }
    }
  }
}
</script>

<style scoped>
.admin-page {
  flex: 1;
  background-color: #f9fafb;
}

.admin-header-bar {
  background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
  color: white;
  padding: 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 2rem;
}

.header-content h1 {
  margin: 0 0 0.25rem 0;
  font-size: 2rem;
  font-weight: 800;
  letter-spacing: -0.02em;
}

.header-content p {
  margin: 0;
  opacity: 0.9;
  font-size: 0.95rem;
}

.logout-button {
  padding: 0.75rem 1.5rem;
  background: rgba(255, 255, 255, 0.2);
  color: white;
  border: 2px solid rgba(255, 255, 255, 0.4);
  border-radius: var(--border-radius);
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  white-space: nowrap;
}

.logout-button:hover {
  background: rgba(255, 255, 255, 0.3);
  border-color: white;
  transform: translateY(-2px);
}

.admin-container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 2rem;
  display: grid;
  gap: 2rem;
}

.admin-section, .stats-grid {
  background: white;
  border-radius: var(--border-radius);
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem;
  border-bottom: 1px solid #e5e7eb;
  background: #f9fafb;
}

.section-header h2 {
  margin: 0;
  color: var(--text-dark);
  font-size: 1.5rem;
  flex: 1;
}

.section-icon, .refresh-button {
  font-size: 1.5rem;
}

.refresh-button {
  background: none;
  border: none;
  cursor: pointer;
  font-size: 1.25rem;
  transition: transform 0.3s ease;
  padding: 0.5rem;
}

.refresh-button:hover:not(:disabled) {
  transform: rotate(180deg);
}

.refresh-button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.badge {
  display: inline-block;
  background: var(--primary-color);
  color: white;
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 600;
  margin-left: 0.75rem;
}

.section-content {
  padding: 1.5rem;
}

.section-description {
  margin: 0 0 1.5rem 0;
  color: var(--text-light);
  line-height: 1.6;
}

.action-button {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 0.95rem;
}

.reset-button {
  background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
  color: white;
}

.reset-button:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 10px 20px rgba(251, 191, 36, 0.3);
}

.return-button {
  background: var(--primary-color);
  color: white;
  padding: 0.5rem 1rem;
  font-size: 0.9rem;
}

.return-button:hover:not(:disabled) {
  background: #4f46e5;
  transform: translateY(-1px);
}

.action-button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.spinner {
  display: inline-block;
  width: 14px;
  height: 14px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-top-color: white;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.alert {
  display: flex;
  gap: 1rem;
  padding: 1rem;
  border-radius: 8px;
  margin-top: 1rem;
  align-items: flex-start;
}

.alert-icon {
  font-size: 1.25rem;
  flex-shrink: 0;
}

.alert-content {
  flex: 1;
}

.alert-title {
  margin: 0 0 0.25rem 0;
  font-weight: 700;
  font-size: 0.95rem;
}

.alert-message {
  margin: 0;
  font-size: 0.9rem;
  opacity: 0.9;
}

.alert.success {
  background: #d1fae5;
  color: #065f46;
}

.alert.error {
  background: #fee2e2;
  color: #991b1b;
}

.empty-state {
  text-align: center;
  padding: 2rem;
  color: var(--text-light);
}

.empty-icon {
  font-size: 3rem;
  margin: 0 0 1rem 0;
}

.empty-text {
  margin: 0;
  font-size: 0.95rem;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1.5rem;
  padding: 1.5rem;
}

.stat-card {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1.5rem;
  background: linear-gradient(135deg, #f0f4ff 0%, #f5f0ff 100%);
  border-radius: 12px;
  border: 1px solid #e5e7eb;
}

.stat-icon {
  font-size: 2rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.stat-value {
  font-size: 2rem;
  font-weight: 800;
  color: var(--primary-color);
}

.stat-label {
  color: var(--text-light);
  font-size: 0.9rem;
  margin-top: 0.25rem;
}

.bike-list {
  display: grid;
  gap: 1rem;
}

.bike-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.25rem;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  background: #f9fafb;
  transition: all 0.3s ease;
}

.bike-item:hover {
  border-color: var(--primary-color);
  box-shadow: 0 4px 12px rgba(99, 102, 241, 0.1);
}

.bike-item-header {
  display: flex;
  align-items: center;
  gap: 1rem;
  flex: 1;
}

.bike-badge {
  width: 8px;
  height: 40px;
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  border-radius: 4px;
}

.bike-info {
  flex: 1;
}

.bike-name {
  margin: 0;
  color: var(--text-dark);
  font-weight: 600;
  font-size: 0.95rem;
}

.bike-type {
  margin: 0.25rem 0 0 0;
  color: var(--text-light);
  font-size: 0.85rem;
}

.activity-list {
  display: grid;
  gap: 1rem;
}

.activity-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  border-left: 4px solid #e5e7eb;
  background: #f9fafb;
  border-radius: 4px;
  transition: all 0.3s ease;
}

.activity-item:hover {
  border-left-color: var(--primary-color);
}

.activity-icon {
  font-size: 1.5rem;
  width: 2rem;
  text-align: center;
}

.activity-details {
  flex: 1;
}

.activity-summary {
  margin: 0;
  color: var(--text-dark);
  font-size: 0.95rem;
}

.activity-item-name {
  color: var(--primary-color);
  font-weight: 600;
}

.activity-meta {
  margin: 0.25rem 0 0 0;
  color: var(--text-light);
  font-size: 0.85rem;
}

@media (max-width: 768px) {
  .admin-header-bar {
    flex-direction: column;
    text-align: center;
  }

  .header-content h1 {
    font-size: 1.5rem;
  }

  .logout-button {
    width: 100%;
  }

  .section-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 1rem;
  }

  .bike-item {
    flex-direction: column;
    align-items: flex-start;
  }

  .return-button {
    width: 100%;
  }

  .stats-grid {
    grid-template-columns: 1fr;
  }
}
</style>
