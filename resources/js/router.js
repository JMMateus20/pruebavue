// resources/js/router.js
import { createRouter, createWebHistory } from 'vue-router';
import ListaTareas from './components/ListaTareas.vue';
import Login from './components/LoginPage.vue';

const routes = [
  { path: '/', component: ListaTareas },
  { path: '/login', component: Login },
];

export default createRouter({
  history: createWebHistory(),
  routes,
});
