<script setup>
import { defineProps } from 'vue';
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';

const props = defineProps({
    report: Object
});
</script>

<template>
    <Navbar />
    <div class="container mx-auto my-8 min-h-screen">
        <h1 class="text-2xl font-bold text-center mb-6">Detail Laporan</h1>

        <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-6">
            <p class="text-lg"><strong>Pelapor:</strong> {{ report.user?.name }}</p>
            <p class="text-lg"><strong>Laporan Kerusakan:</strong> {{ report.laporan_kerusakan }}</p>
            <p class="text-lg"><strong>Deskripsi:</strong> {{ report.deskripsi }}</p>

            <div class="mt-4">
                <strong>Gambar:</strong>
                <div v-if="report.gambar" class="mt-2">
                    <img :src="report.gambar" alt="Gambar Laporan"
                        class="max-w-sm max-h-80 rounded-md shadow-md mx-auto">
                </div>
                <span v-else class="text-gray-500">Tidak ada gambar</span>
            </div>

            <div class="mt-6">
                <button @click="$inertia.get('/riwayat')"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                    Kembali ke Riwayat
                </button>
            </div>
        </div>
    </div>
    <Footer />
</template>
