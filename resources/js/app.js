/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';
import App from './Vue/App.vue';
import router from './router';
import '../css/app.css';

createApp(App).use(router).mount('#app');

