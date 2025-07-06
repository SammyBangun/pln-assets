<script setup>
import Navbar from '@/components/Navbar.vue';
import Footer from '@/components/Footer.vue';
import SideNav from '@/components/SideNav.vue';
</script>

<template>
    <Navbar />

    <div class="flex flex-col md:flex-row">
        <SideNav />

        <main class="flex-1 p-4">
            <slot />
        </main>
    </div>

    <Footer />
</template>
