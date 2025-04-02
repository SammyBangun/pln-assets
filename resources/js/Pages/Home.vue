<script setup>
import Navbar from '../Components/Navbar.vue';
import Footer from '../Components/Footer.vue';
import { Link } from '@inertiajs/vue3';

</script>

<template>
    <Navbar />
    <div class="bg-cover bg-center min-h-screen flex flex-col items-center justify-center text-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-3/4 md:w-1/2">
            <h1 class="text-3xl font-bold mb-4">Form Laporan</h1>
            <p class="text-gray-600 mb-6">Tekan tombol di bawah untuk membuat laporan</p>
            <!-- Menggunakan Inertia Link untuk navigasi -->
            <Link href="/form" class="bg-yellow-400 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
            + Buat Laporan
            </Link>
        </div>
    </div>
    <Footer />
</template>
