import '../css/styles.css';
import { createApp } from 'vue';
import App from './components/App.vue'
//import ListaTareas from './components/ListaTareas.vue';
import router from './router';

createApp(App).use(router).mount('#app');

