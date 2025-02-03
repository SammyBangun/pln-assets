<script setup>
import Navbar from '@/Components/Navbar.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>

    <Head title="Admin Dashboard" />

    <Navbar />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    Selamat datang di Admin Dashboard!
                </div>
            </div>
        </div>
    </div>

</template>