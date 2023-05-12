import './assets/main.css'
//theme
import "primevue/resources/themes/lara-light-indigo/theme.css";     
    
//core
import "primevue/resources/primevue.min.css";
import 'primeicons/primeicons.css';
import 'primeflex/primeflex.css';


import { createApp } from 'vue'
import { createPinia } from 'pinia'

import PrimeVue from 'primevue/config';

import App from './App.vue'
import router from './router'

import Button from "primevue/button";

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.use(PrimeVue)

app.component("Button", Button);

app.mount('#app')
