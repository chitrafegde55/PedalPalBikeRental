import { createRouter, createWebHistory } from 'vue-router';
import Home from './components/Home.vue';
import Bikes from './components/Bikes.vue';
import Accessories from './components/Accessories.vue';
import Admin from './components/Admin.vue';
import Login from './components/Login.vue';

const routes = [
  { path: '/', component: Home },
  { path: '/bikes', component: Bikes },
  { path: '/accessories', component: Accessories },
  { path: '/admin/login', component: Login },
  {
    path: '/admin',
    component: Admin,
    beforeEnter: (to, from, next) => {
      // Check if admin is authenticated
      const isAuthenticated = localStorage.getItem('admin_authenticated') === 'true';
      if (isAuthenticated) {
        next();
      } else {
        next('/admin/login');
      }
    }
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;