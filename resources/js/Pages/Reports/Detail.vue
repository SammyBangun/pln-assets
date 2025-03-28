<script setup>
import { ref, defineProps, onMounted } from 'vue';
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';
import formatDate from '@/functions/formatDate';

const pdfUrl = ref(null);

const props = defineProps({
    report: Object
});

const showModal = ref(false);
const selectedImage = ref('');
const assetName = ref(props.report.asset ? props.report.asset.name : 'Tidak ditemukan');

const openImage = (image) => {
    selectedImage.value = image;
    showModal.value = true;
}

const closeModal = () => {
    showModal.value = false;
}

const printPdf = (id) => {
    window.open(`/laporan/${id}/export`, '_blank');
};
</script>

<template>
    <Navbar />
    <div class="container mx-auto my-8 min-h-screen">
        <h1 class="text-2xl font-bold text-center mb-6">Detail Laporan</h1>

        <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-lg"><strong>Pelapor:</strong> {{ report.user?.name }}</p>
                    <p class="text-lg"><strong>Serial Number:</strong> {{ report.aset }}</p>
                    <p class="text-lg"><strong>Aset:</strong> {{ assetName }}</p>
                    <p class="text-lg"><strong>Laporan Kerusakan:</strong> {{ report.laporan_kerusakan }}</p>
                    <div class="my-3 border border-gray-500 p-4 rounded-md">
                        <p class="text-lg"><strong>Deskripsi:</strong> <br>{{ report.deskripsi }}</p>
                    </div>
                    <p class="text-lg"><strong>Tanggal:</strong> {{ formatDate(report.created_at) }}</p>
                </div>

                <template
                    v-if="report.status === 'Selesai' && ($page.props.auth.user.role === 'admin' || $page.props.auth.user.id === report.user_pelapor)">
                    <div class="text-right">
                        <button @click="printPdf(report.id)"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                            Print PDF
                        </button>
                    </div>
                </template>
            </div>

            <div class="mt-2">
                <p class="text-lg"><strong>Gambar:</strong></p>
                <div v-if="report.gambar" class="mt-2">
                    <img :src="report.gambar" alt="Gambar Laporan" @click="openImage(report.gambar)"
                        class="cursor-pointer max-w-sm max-h-80 rounded-md shadow-md mx-auto">
                </div>
                <span v-else class="text-gray-500">Tidak ada gambar</span>
            </div>

            <div class="border border-gray-500 p-6 my-8 rounded-lg">
                <div class="mb-4 flex justify-between">
                    <p class="text-xl"><strong>Konfirmasi Admin</strong></p>
                    <p class="text-lg"><strong>Status :</strong> {{ report.status }}</p>
                </div>
                <div>
                    <p class="text-lg ">
                        <strong>Tindak Lanjut : </strong>
                        {{ report.tindak_lanjut || `Belum dikonfirmasi
                        admin` }}
                    </p>
                    <p class="text-lg ">
                        <strong>Deskrpsi Tindak Lanjut : </strong>
                        {{ report.deskripsi_lanjut || `Belum dikonfirmasi
                        admin` }}
                    </p>
                    <p class="text-lg ">
                        <strong>Realisasi Hasil Pekerjaan : </strong>
                        {{ report.realisasi || `Belum dikonfirmasi
                        admin` }}
                    </p>
                </div>

                <div class="mt-2">
                    <p class="text-lg"><strong>Gambar Konfirmasi :</strong></p>
                    <div v-if="report.gambar_konfirmasi" class="mt-2">
                        <img :src="report.gambar_konfirmasi" alt="Gambar Laporan"
                            @click="openImage(report.gambar_konfirmasi)"
                            class="cursor-pointer max-w-sm max-h-80 rounded-md shadow-md mx-auto">
                    </div>
                    <span v-else class="text-gray-500">Tidak ada gambar</span>
                </div>

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


    <div v-if="pdfUrl" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-white p-4 rounded-lg shadow-lg w-4/5 h-4/5 relative">
            <button @click="pdfUrl = null"
                class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white p-2 rounded-full">
                &times;
            </button>
            <iframe :src="pdfUrl" class="w-full h-full"></iframe>
        </div>
    </div>

    <Footer />
</template>
