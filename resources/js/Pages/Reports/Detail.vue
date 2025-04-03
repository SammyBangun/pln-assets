<script setup>
import { ref, defineProps } from 'vue';
import Navbar from '@/components/Navbar.vue';
import Footer from '@/components/Footer.vue';
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

    <div class="container mx-auto my-12 min-h-screen px-4">
        <h1 class="text-3xl font-extrabold text-center text-gray-800 mb-8">Detail Laporan</h1>
        <div class="my-6">
            <button @click="$inertia.get('/riwayat')"
                class="bg-yellow-500 hover:bg-yellow-700 text-white font-semibold px-6 py-3 rounded-lg shadow-md transition-all">
                Kembali ke Riwayat
            </button>
        </div>

        <div class="max-w-full mx-auto bg-white shadow-xl rounded-2xl p-8 border border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="border border-gray-300 p-4 rounded-md bg-gray-50">
                        <p class="text-lg text-gray-600"><strong>Tanggal:</strong> {{ formatDate(report.created_at) }}
                        </p>
                        <p class="text-lg font-semibold text-gray-700"><strong>Pelapor:</strong> {{ report.user?.name }}
                        </p>
                        <p class="text-lg text-gray-600"><strong>Serial Number:</strong> {{ report.aset }}</p>
                        <p class="text-lg text-gray-600"><strong>Aset:</strong> {{ assetName }}</p>
                        <p class="text-lg text-gray-600"><strong>Laporan Kerusakan:</strong> {{ report.laporan_kerusakan
                            }}</p>
                    </div>

                    <div class="border border-gray-300 p-4 rounded-md bg-gray-50">
                        <p class="text-lg text-gray-700"><strong>Deskripsi:</strong></p>
                        <p class="text-lg text-gray-600">{{ report.deskripsi }}</p>
                    </div>

                    <div>
                        <p class="text-lg font-semibold text-gray-700">Gambar:</p>
                        <div v-if="report.gambar" class="mt-3 flex justify-center">
                            <img :src="report.gambar" alt="Gambar Laporan" @click="openImage(report.gambar)"
                                class="cursor-pointer max-w-64 max-h-64 rounded-lg shadow-md">
                        </div>
                        <span v-else class="text-gray-500">Tidak ada gambar</span>
                    </div>
                </div>

                <div class="h-full border border-gray-300 p-6 rounded-lg bg-gray-50">
                    <div class="mb-5 flex justify-between items-center">
                        <p class="text-xl font-semibold text-gray-800">Konfirmasi Admin</p>
                        <p class="text-lg font-semibold text-gray-700 flex items-center">
                            <strong>Status:</strong>&nbsp;{{ report.status }}
                            <span v-if="report.status === 'Selesai'" class="ml-2 text-green-500">✅</span>
                            <span v-if="report.status === 'Diproses'" class="ml-2 text-yellow-500">⏳</span>
                            <span v-if="report.status === 'Diterima'" class="ml-2 text-blue-500">➡️</span>
                        </p>
                    </div>
                    <div>
                        <p class="text-lg text-gray-700"><strong>Tindak Lanjut:</strong> {{ report.tindak_lanjut ||
                            'Belum dikonfirmasi admin' }}</p>
                        <p class="text-lg text-gray-700"><strong>Deskripsi Tindak Lanjut:</strong> {{
                            report.deskripsi_lanjut || 'Belum dikonfirmasi admin' }}</p>
                        <p class="text-lg text-gray-700"><strong>Realisasi Hasil Pekerjaan:</strong> {{ report.realisasi
                            || 'Belum dikonfirmasi admin' }}</p>
                    </div>
                    <div class="mt-6">
                        <p class="text-lg font-semibold text-gray-700">Gambar Konfirmasi:</p>
                        <div v-if="report.gambar_konfirmasi" class="mt-3 flex justify-center">
                            <img :src="report.gambar_konfirmasi" alt="Gambar Konfirmasi"
                                @click="openImage(report.gambar_konfirmasi)"
                                class="cursor-pointer max-w-64 max-h-64 rounded-lg shadow-md">
                        </div>
                        <span v-else class="text-gray-500">Tidak ada gambar</span>
                    </div>
                    <div v-if="report.status === 'Selesai' && ($page.props.auth.user.role === 'admin' || $page.props.auth.user.id === report.user_pelapor)"
                        class="mt-12">
                        <button @click="printPdf(report.id)"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-lg transition-all">
                            Print PDF
                        </button>
                    </div>
                </div>
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
