import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/Home.vue';
import FormLaporan from '../components/FormLaporan.vue';
import Riwayat from '../components/Riwayat.vue';
import NotFound from '../components/NotFound.vue'; // Tambahkan file NotFound.vue

const routes = [
  { path: '/', name: 'Home', component: Home },
  { path: '/form-laporan', name: 'FormLaporan', component: FormLaporan },
  { path: '/riwayat', name: 'Riwayat', component: Riwayat },
  // Rute untuk halaman tidak ditemukan
  { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
