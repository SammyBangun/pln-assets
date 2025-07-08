<script setup>
import Navbar from '@/components/Navbar.vue';
import Footer from '@/components/Footer.vue';
import SideNav from '@/components/SideNav.vue';
import { usePage } from '@inertiajs/vue3';

const userAdmin = usePage().props.auth.user;
</script>

<template>
    <Navbar class="no-print" />

    <div class="flex flex-col md:flex-row">
        <SideNav v-if="userAdmin?.role === 'admin'" class="no-print" />

        <main class="flex-1 p-4">
            <slot />
        </main>
    </div>

    <Footer />
</template>
