<template>
  <div class="accessories-page">
    <div class="page-header">
      <div class="header-content">
        <h1>🛠️ Accessories Shop</h1>
        <p>Everything you need to upgrade your ride</p>
      </div>
    </div>

    <div class="accessories-container">
      <div class="cart-sidebar">
        <div class="cart-summary">
          <h3>Shopping Cart</h3>
          <div class="cart-items">
            <div v-if="cartItems.length === 0" class="empty-cart">
              No items in cart
            </div>
            <div v-else>
              <div v-for="item in cartItems" :key="item.id" class="cart-item">
                <div class="cart-item-info">
                  <p class="cart-item-name">{{ item.name }}</p>
                  <p class="cart-item-qty">Qty: {{ item.quantity }}</p>
                </div>
                <p class="cart-item-price">${{ (item.price * item.quantity).toFixed(2) }}</p>
              </div>
            </div>
          </div>
          <div v-if="cartItems.length > 0" class="cart-footer">
            <div class="cart-total">
              <span>Total:</span>
              <span class="total-price">${{ cartTotal.toFixed(2) }}</span>
            </div>
            <button @click="orderAccessories" :disabled="loading" class="checkout-btn">
              {{ loading ? 'Processing...' : 'Checkout' }}
            </button>
            <button @click="clearCart" class="clear-cart-btn">Clear Cart</button>
          </div>
        </div>
      </div>

      <div class="accessories-main">
        <div v-if="loading" class="loading">
          <p>Loading accessories...</p>
        </div>
        <div v-else-if="accessories.length === 0" class="no-accessories">
          <p>No accessories available</p>
        </div>
        <div v-else class="accessory-grid">
          <div v-for="accessory in accessories" :key="accessory.id" class="accessory-card">
            <div class="accessory-header">
              <h3>{{ accessory.name }}</h3>
              <span class="stock-badge" :class="{ 'in-stock': accessory.stock > 0, 'out-of-stock': accessory.stock === 0 }">
                {{ accessory.stock > 0 ? `${accessory.stock} in stock` : 'Out of stock' }}
              </span>
            </div>
            <p class="accessory-description">{{ accessory.description }}</p>
            <div class="accessory-price">${{ accessory.price }}</div>
            <div class="quantity-selector">
              <label for="qty-item-{{ accessory.id }}">Quantity:</label>
              <div class="quantity-input-group">
                <button @click="decreaseQuantity(accessory.id)" class="qty-btn">−</button>
                <input 
                  :id="`qty-item-${accessory.id}`"
                  v-model.number="cart[accessory.id]" 
                  type="number" 
                  min="0" 
                  :max="accessory.stock"
                  class="qty-input"
                  @change="updateCart(accessory)"
                >
                <button @click="increaseQuantity(accessory.id, accessory.stock)" class="qty-btn">+</button>
              </div>
            </div>
            <button 
              @click="addToCart(accessory)" 
              :disabled="accessory.stock === 0 || cart[accessory.id] === 0"
              class="add-to-cart-btn"
            >
              Add to Cart
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
  name: 'Accessories',
  data() {
    return {
      accessories: [],
      cart: {},
      loading: true,
      error: null
    };
  },
  computed: {
    cartItems() {
      return Object.entries(this.cart)
        .filter(([id, qty]) => qty > 0)
        .map(([id, qty]) => {
          const accessory = this.accessories.find(a => a.id === parseInt(id));
          return { ...accessory, quantity: qty };
        });
    },
    cartTotal() {
      return this.cartItems.reduce((total, item) => total + (item.price * item.quantity), 0);
    }
  },
  async mounted() {
    await this.fetchAccessories();
  },
  methods: {
    async fetchAccessories() {
      this.loading = true;
      try {
        const response = await axios.get('/api/accessories');
        this.accessories = response.data.data || [];
        this.accessories.forEach(acc => {
          if (!this.cart[acc.id]) {
            this.$set(this.cart, acc.id, 0);
          }
        });
      } catch (error) {
        console.error('Error fetching accessories:', error);
        this.error = 'Failed to load accessories';
      } finally {
        this.loading = false;
      }
    },
    increaseQuantity(id, maxStock) {
      if ((this.cart[id] || 0) < maxStock) {
        this.cart[id] = (this.cart[id] || 0) + 1;
      }
    },
    decreaseQuantity(id) {
      if ((this.cart[id] || 0) > 0) {
        this.cart[id] = (this.cart[id] || 0) - 1;
      }
    },
    updateCart(accessory) {
      const qty = this.cart[accessory.id] || 0;
      if (qty > accessory.stock) {
        this.cart[accessory.id] = accessory.stock;
      } else if (qty < 0) {
        this.cart[accessory.id] = 0;
      }
    },
    addToCart(accessory) {
      const qty = this.cart[accessory.id] || 0;
      if (qty > 0) {
        // Item already added, cart will show it
      }
    },
    async orderAccessories() {
      if (this.cartItems.length === 0) {
        alert('Please select at least one accessory');
        return;
      }

      this.loading = true;
      const order = this.cartItems.map(item => ({ id: item.id, quantity: item.quantity }));

      try {
        const response = await axios.post('/api/accessories/order', { items: order });
        alert(`Order placed! Total: $${response.data.data.total.toFixed(2)}`);
        this.clearCart();
        await this.fetchAccessories();
      } catch (error) {
        alert('Error: ' + (error.response?.data?.message || 'Failed to place order'));
      } finally {
        this.loading = false;
      }
    },
    clearCart() {
      this.accessories.forEach(acc => {
        this.cart[acc.id] = 0;
      });
    }
  }
}
</script>

<style scoped>
.accessories-page {
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

.accessories-container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 2rem;
  display: grid;
  grid-template-columns: 300px 1fr;
  gap: 2rem;
}

.cart-sidebar {
  position: sticky;
  top: 100px;
  height: fit-content;
}

.cart-summary {
  background: white;
  border-radius: var(--border-radius);
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.cart-summary h3 {
  padding: 1.5rem;
  margin: 0;
  border-bottom: 1px solid #e5e7eb;
  color: var(--text-dark);
}

.cart-items {
  max-height: 400px;
  overflow-y: auto;
}

.empty-cart {
  padding: 2rem 1.5rem;
  text-align: center;
  color: var(--text-light);
}

.cart-item {
  padding: 1rem 1.5rem;
  border-bottom: 1px solid #e5e7eb;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.cart-item-info {
  flex: 1;
}

.cart-item-name {
  margin: 0;
  color: var(--text-dark);
  font-weight: 600;
  font-size: 0.9rem;
}

.cart-item-qty {
  margin: 0.25rem 0 0 0;
  color: var(--text-light);
  font-size: 0.85rem;
}

.cart-item-price {
  margin: 0;
  color: var(--primary-color);
  font-weight: 700;
  margin-left: 1rem;
}

.cart-footer {
  padding: 1.5rem;
  border-top: 2px solid #e5e7eb;
}

.cart-total {
  display: flex;
  justify-content: space-between;
  margin-bottom: 1rem;
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--text-dark);
}

.total-price {
  color: var(--primary-color);
}

.checkout-btn {
  width: 100%;
  padding: 0.875rem;
  background: var(--primary-color);
  color: white;
  border: none;
  border-radius: var(--border-radius);
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  margin-bottom: 0.5rem;
}

.checkout-btn:hover:not(:disabled) {
  background: #4f46e5;
  transform: translateY(-2px);
}

.checkout-btn:disabled {
  background: #d1d5db;
  cursor: not-allowed;
}

.clear-cart-btn {
  width: 100%;
  padding: 0.5rem;
  background: white;
  color: var(--text-light);
  border: 1px solid #e5e7eb;
  border-radius: var(--border-radius);
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
}

.clear-cart-btn:hover {
  border-color: var(--primary-color);
  color: var(--primary-color);
}

.accessories-main {
  background: white;
  border-radius: var(--border-radius);
  padding: 2rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.loading, .no-accessories {
  text-align: center;
  padding: 3rem;
  color: var(--text-light);
}

.accessory-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1.5rem;
}

.accessory-card {
  background: white;
  border: 1px solid #e5e7eb;
  border-radius: var(--border-radius);
  overflow: hidden;
  transition: all 0.3s ease;
}

.accessory-card:hover {
  border-color: var(--primary-color);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  transform: translateY(-2px);
}

.accessory-header {
  padding: 1.5rem;
  border-bottom: 1px solid #e5e7eb;
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 1rem;
}

.accessory-header h3 {
  margin: 0;
  color: var(--text-dark);
  font-size: 1.1rem;
}

.stock-badge {
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
  white-space: nowrap;
}

.stock-badge.in-stock {
  background: #d1fae5;
  color: #065f46;
}

.stock-badge.out-of-stock {
  background: #fee2e2;
  color: #991b1b;
}

.accessory-description {
  padding: 1rem 1.5rem;
  margin: 0;
  color: var(--text-light);
  font-size: 0.9rem;
  border-bottom: 1px solid #f3f4f6;
}

.accessory-price {
  padding: 1rem 1.5rem;
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--primary-color);
}

.quantity-selector {
  padding: 0 1.5rem;
  margin-bottom: 1rem;
}

.quantity-selector label {
  display: block;
  margin-bottom: 0.5rem;
  color: var(--text-dark);
  font-weight: 500;
  font-size: 0.9rem;
}

.quantity-input-group {
  display: flex;
  gap: 0.5rem;
  align-items: center;
}

.qty-btn {
  width: 36px;
  height: 36px;
  border: 1px solid #e5e7eb;
  background: white;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s ease;
}

.qty-btn:hover {
  background: var(--primary-color);
  color: white;
  border-color: var(--primary-color);
}

.qty-input {
  flex: 1;
  padding: 0.5rem;
  border: 1px solid #e5e7eb;
  border-radius: 6px;
  text-align: center;
  font-weight: 600;
}

.add-to-cart-btn {
  width: 100%;
  padding: 0.875rem;
  background: var(--secondary-color);
  color: white;
  border: none;
  border-radius: 0 0 var(--border-radius) var(--border-radius);
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.add-to-cart-btn:hover:not(:disabled) {
  background: #7c3aed;
}

.add-to-cart-btn:disabled {
  background: #d1d5db;
  cursor: not-allowed;
}

@media (max-width: 1024px) {
  .accessories-container {
    grid-template-columns: 1fr;
  }

  .cart-sidebar {
    position: static;
  }
}

@media (max-width: 768px) {
  .accessory-grid {
    grid-template-columns: 1fr;
  }

  .page-header h1 {
    font-size: 1.75rem;
  }
}
</style>