import { createRouter, createWebHistory } from "vue-router";
import Home from "../router.getMatchedComponents()omponents/Home.vue";
// import Riwayat from '../Components/Riwayat.vue';
import NotFound from "../components/NotFound.vue"; // Tambahkan file NotFound.vue

const routes = [
    { path: "/", name: "Home", component: Home },
    // { path: '/riwayat', name: 'Riwayat', component: Riwayat },
    {
        path: "/register",
        name: "Register",
        component: () => import("../components/Register.vue"),
    },
    // Rute untuk halaman tidak ditemukan
    { path: "/:pathMatch(.*)*", name: "NotFound", component: NotFound },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
