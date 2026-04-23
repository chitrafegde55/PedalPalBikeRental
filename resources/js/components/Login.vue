<template>
  <div class="login-page">
    <div class="login-container">
      <div class="login-box">
        <div class="login-header">
          <div class="login-logo">🚲</div>
          <h1>PedalPal Admin</h1>
          <p>Staff Portal</p>
        </div>

        <form @submit.prevent="login" class="login-form">
          <div class="form-group">
            <label for="username">Username</label>
            <input
              id="username"
              v-model="credentials.username"
              type="text"
              required
              placeholder="Enter your username"
              class="form-input"
            >
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <input
              id="password"
              v-model="credentials.password"
              type="password"
              required
              placeholder="Enter your password"
              class="form-input"
            >
          </div>

          <button type="submit" :disabled="loading" class="login-button">
            <span v-if="loading" class="spinner"></span>
            {{ loading ? 'Logging in...' : 'Sign In' }}
          </button>
        </form>

        <div v-if="error" class="error-alert">
          <span class="error-icon">⚠️</span>
          <div class="error-text">
            <p class="error-title">Login Failed</p>
            <p class="error-message">{{ error }}</p>
          </div>
        </div>

        <div class="login-footer">
          <p class="demo-credentials">
            <strong>Demo Credentials:</strong><br>
            Username: admin<br>
            Password: password
          </p>
        </div>
      </div>

      <div class="login-background">
        <div class="bg-gradient-1"></div>
        <div class="bg-gradient-2"></div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Login',
  data() {
    return {
      credentials: {
        username: '',
        password: ''
      },
      loading: false,
      error: null
    };
  },
  methods: {
    async login() {
      if (!this.credentials.username || !this.credentials.password) {
        this.error = 'Please enter both username and password';
        return;
      }

      this.loading = true;
      this.error = null;

      try {
        const response = await axios.post('/api/auth/login', this.credentials);
        if (response.data.success) {
          localStorage.setItem('admin_authenticated', 'true');
          localStorage.setItem('admin_user', JSON.stringify(response.data.data.user));
          this.$router.push('/admin');
        } else {
          this.error = response.data.message || 'Login failed';
        }
      } catch (error) {
        this.error = error.response?.data?.message || 'Invalid credentials or connection error';
      } finally {
        this.loading = false;
      }
    }
  }
}
</script>

<style scoped>
.login-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
  padding: 1rem;
}

.login-container {
  position: relative;
  width: 100%;
  max-width: 450px;
}

.login-background {
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  opacity: 0.1;
}

.bg-gradient-1, .bg-gradient-2 {
  position: absolute;
  border-radius: 50%;
  filter: blur(40px);
}

.bg-gradient-1 {
  width: 300px;
  height: 300px;
  background: white;
  top: -100px;
  right: -100px;
}

.bg-gradient-2 {
  width: 250px;
  height: 250px;
  background: white;
  bottom: -100px;
  left: -100px;
}

.login-box {
  background: white;
  border-radius: var(--border-radius);
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  padding: 3rem 2rem;
  position: relative;
  z-index: 1;
}

.login-header {
  text-align: center;
  margin-bottom: 2rem;
}

.login-logo {
  font-size: 3rem;
  margin-bottom: 1rem;
  display: inline-block;
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  padding: 0.8rem;
  border-radius: 50%;
  animation: bounce 2s infinite;
}

@keyframes bounce {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}

.login-header h1 {
  margin: 0 0 0.25rem 0;
  color: var(--text-dark);
  font-size: 2rem;
  font-weight: 800;
}

.login-header p {
  margin: 0;
  color: var(--text-light);
  font-size: 0.95rem;
}

.login-form {
  margin-bottom: 2rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  color: var(--text-dark);
  font-weight: 600;
  font-size: 0.95rem;
}

.form-input {
  width: 100%;
  padding: 0.875rem;
  border: 2px solid #e5e7eb;
  border-radius: 8px;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  font-family: inherit;
}

.form-input:focus {
  outline: none;
  border-color: var(--primary-color);
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.form-input::placeholder {
  color: var(--text-light);
}

.login-button {
  width: 100%;
  padding: 0.875rem;
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
}

.login-button:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
}

.login-button:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.spinner {
  display: inline-block;
  width: 16px;
  height: 16px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-top-color: white;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.error-alert {
  background: #fee2e2;
  border: 1px solid #fecaca;
  border-radius: 8px;
  padding: 1rem;
  display: flex;
  gap: 0.75rem;
  margin: 1rem 0;
}

.error-icon {
  font-size: 1.5rem;
  flex-shrink: 0;
}

.error-text {
  flex: 1;
}

.error-title {
  margin: 0 0 0.25rem 0;
  color: #991b1b;
  font-weight: 700;
  font-size: 0.95rem;
}

.error-message {
  margin: 0;
  color: #7c2d12;
  font-size: 0.9rem;
}

.login-footer {
  padding-top: 1.5rem;
  border-top: 1px solid #e5e7eb;
  text-align: center;
}

.demo-credentials {
  margin: 0;
  color: var(--text-light);
  font-size: 0.85rem;
  line-height: 1.6;
}

.demo-credentials strong {
  color: var(--text-dark);
  display: block;
  margin-bottom: 0.5rem;
}

@media (max-width: 480px) {
  .login-box {
    padding: 2rem 1.5rem;
  }

  .login-header h1 {
    font-size: 1.5rem;
  }

  .login-logo {
    font-size: 2.5rem;
  }
}
</style>