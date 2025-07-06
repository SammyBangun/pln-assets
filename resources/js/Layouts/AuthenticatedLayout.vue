<script setup>
import Navbar from '@/components/Navbar.vue';
import Footer from '@/components/Footer.vue';
</script>

<template>
    <Navbar />

    <main class="p-4">
        <slot />
    </main>

    <Footer />
</template>
