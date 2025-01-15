<template>
    <Navbar />
    <div class="bg-cover bg-center min-h-screen flex flex-col items-center justify-center text-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-3/4 md:w-1/2">
            <h1 class="text-3xl font-bold mb-4">Form Laporan</h1>
            <p class="text-gray-600 mb-6">Tekan tombol di bawah untuk membuat laporan</p>
            <router-link to="/form-laporan"
                class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                +Buat Laporan
            </router-link>

        </div>
    </div>
    <Footer />
</template>
<script>
import Navbar from '../components/Navbar.vue';
import Footer from '../components/Footer.vue';

export default {
    name: 'Home',
    components: { Navbar, Footer },
    methods: {
        createReport() {
            alert('Membuat laporan baru!');
        },
    },
};
</script>