import './bootstrap';

import '../sass/app.scss'
import 'bootstrap-icons/font/bootstrap-icons.css'

import { createApp } from "vue";

import App from "./App.vue";

import router from "./router/index.js";

const app = createApp(App);

app.use(router).mount('#app');
