import { createRouter, createWebHistory } from 'vue-router'
import Orders from '../views/Orders/index.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'orders',
      component: Orders
    },
    {
      path: '/add',
      name: 'add',
      component: () => import('../views/Orders/action.vue')
    },
    {
      path: '/edit/:id',
      name: 'edit',
      component: () => import('../views/Orders/action.vue')
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue')
    }
  ]
})

export default router
