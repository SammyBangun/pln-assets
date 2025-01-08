<template>
    <div class="bg-cover bg-center min-h-screen flex flex-col items-center justify-center text-center"
        style="background-image: url('https://example.com/pln-background.jpg')">
        <div class="bg-white p-8 rounded-lg shadow-lg w-3/4 md:w-1/2">
            <h1 class="text-3xl font-bold mb-4">Form Laporan</h1>
            <p class="text-gray-600 mb-6">Tekan tombol di bawah untuk membuat laporan</p>
            <button @click="createReport" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + Buat Laporan
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Home',
    methods: {
        createReport() {
            alert('Membuat laporan baru!');
        },
    },
};
</script>