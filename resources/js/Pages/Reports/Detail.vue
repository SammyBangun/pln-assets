<script setup>
import { ref, defineProps } from 'vue';
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';
import formatDate from '@/functions/formatDate';

const props = defineProps({
    report: Object
});

const showModal = ref(false);
const selectedImage = ref('');

const openImage = (image) => {
    selectedImage.value = image;
    showModal.value = true;
}

const closeModal = () => {
    showModal.value = false;
}
</script>

<template>
    <Navbar />
    <div class="container mx-auto my-8 min-h-screen">
        <h1 class="text-2xl font-bold text-center mb-6">Detail Laporan</h1>

        <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-6">
            <p class="text-lg"><strong>Pelapor:</strong> {{ report.user?.name }}</p>
            <p class="text-lg"><strong>Laporan Kerusakan:</strong> {{ report.laporan_kerusakan }}</p>
            <p class="text-lg"><strong>Deskripsi:</strong> {{ report.deskripsi }}</p>
            <p class="text-lg"><strong>Tanggal:</strong> {{ formatDate(report.created_at) }}</p>

            <div class="mt-4">
                <strong>Gambar:</strong>
                <div v-if="report.gambar" class="mt-2">
                    <img :src="report.gambar" alt="Gambar Laporan" @click="openImage(report.gambar)"
                        class="cursor-pointer max-w-sm max-h-80 rounded-md shadow-md mx-auto hover:opacity-80 transition">
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

    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50"
        @click.self="closeModal">
        <div class="relative">
            <img :src="selectedImage" alt="Gambar Diperbesar" class="max-w-full max-h-screen rounded-lg shadow-lg" />
            <button @click="closeModal"
                class="absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white rounded-full p-2">
                &times;
            </button>
        </div>
    </div>

    <Footer />
</template>
