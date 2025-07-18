<script setup>
import { ref, defineProps } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import formatDate from '@/functions/formatDate';

const pdfUrl = ref(null);

const props = defineProps({
    report: Object,
    tipe: Object,
    assignment: Object,
    followUp: Array
});

console.log(props.assignment);

const showModal = ref(false);
const selectedImage = ref('');


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
    <AuthenticatedLayout>

        <div class="container mx-auto my-12 min-h-screen px-4">
            <div class="flex justify-between items-center my-6">
                <h1 class="text-3xl font-extrabold text-center text-gray-800 mb-8">Detail</h1>
                <button @click="$inertia.get('/riwayat')"
                    class="bg-yellow-500 hover:bg-yellow-700 text-white font-semibold px-6 py-3 rounded-lg shadow-md transition-all">
                    Kembali ke Riwayat
                </button>
            </div>

            <div class="max-w-full mx-auto bg-white shadow-xl rounded-2xl p-8 border border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="border border-gray-300 p-4 rounded-md bg-gray-50">
                            <p class="text-lg text-gray-600">
                                <strong>Tanggal:</strong>
                                {{ report.created_at ? formatDate(report.created_at) : 'Tidak diketahui' }}
                            </p>
                            <p class="text-lg font-semibold text-gray-700"><strong>Pelapor:</strong> {{
                                report.user?.name }}
                            </p>
                            <p class="text-lg text-gray-600"><strong>Lokasi:</strong> {{ report.user?.lokasi }}</p>
                            <p class="text-lg text-gray-600"><strong>Serial Number:</strong> {{
                                report.aset?.serial_number
                                ?? 'Tidak ditemukan' }}</p>
                            <p class="text-lg text-gray-600"><strong>Nama:</strong> {{ report.aset?.nama }}</p>
                            <p class="text-lg text-gray-600"><strong>Tipe:</strong> {{ tipe.tipe }}</p>

                            <!-- <p class="text-lg text-gray-600"><strong>Aset:</strong> {{ assetName }}</p> -->
                            <p class="text-lg text-gray-600"><strong>Identifikasi Masalah:</strong></p>
                            <ul class="ml-4 list-disc text-gray-600">
                                <li v-for="item in report.report_identifications" :key="item.id">
                                    {{ item.identification?.identifikasi_masalah ?? 'Tidak ditemukan' }}
                                </li>
                            </ul>
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
                        <div class="mt-6">
                            <p class="text-lg font-semibold text-gray-700">Gambar Tindak Lanjut:</p>
                            <div v-if="report.assignment?.gambar_tindak_lanjut" class="mt-3 flex justify-center">
                                <img :src="`/storage/${report.assignment?.gambar_tindak_lanjut}`"
                                    alt="Gambar Konfirmasi" @click="openImage(report.assignment?.gambar_tindak_lanjut)"
                                    class="cursor-pointer max-w-64 max-h-64 rounded-lg shadow-md">
                            </div>
                            <span v-else class="text-gray-500">Tidak ada gambar</span>
                        </div>
                    </div>

                    <div class="h-full border border-gray-300 p-6 rounded-lg bg-gray-50">
                        <div class="mb-5">
                            <p class="text-xl font-semibold text-gray-800 mb-6">Konfirmasi Admin</p>
                            <div class="text-lg font-semibold text-gray-700 flex items-center">
                                <strong>Status:</strong>&nbsp;{{ report.assignment?.status }}
                                <span v-if="report.assignment?.status === 'Selesai'"
                                    class="ml-2 text-green-500">✅</span>
                                <span v-if="report.assignment?.status === 'Menunggu Konfirmasi'"
                                    class="ml-2 text-green-500">⌛</span>
                                <span v-if="report.assignment?.status === 'Ditugaskan'"
                                    class="ml-2 text-yellow-500">♻️</span>
                                <span v-if="report.assignment?.status === 'Diterima'"
                                    class="ml-2 text-blue-500">➡️</span>
                                <span v-if="report.assignment?.status === 'Ditolak'" class="ml-2 text-red-500">❌</span>
                            </div>
                            <!-- Tanggal di baris baru -->
                            <div v-if="report.assignment?.status === 'Selesai'" class=" mt-1 text-sm text-gray-600">
                                Pada Tanggal {{ formatDate(report.assignment?.tanggal_selesai) }}
                            </div>
                        </div>
                        <div v-if="report.assignment?.status === 'Ditolak'"
                            class="border border-gray-300 p-4 rounded-md bg-gray-50 mb-3">
                            <p class="text-lg text-gray-700"><strong>Alasan Penolakan:</strong> {{
                                report.assignment?.keterangan_status
                                || 'Belum dikonfirmasi admin' }}</p>
                        </div>
                        <div class="border border-gray-300 p-4 rounded-md bg-gray-50">
                            <h2 class="text-xl font-semibold text-gray-800">Petugas</h2>
                            <p class="text-lg text-gray-700"><strong>Nama Petugas:</strong> {{
                                assignment?.petugas.name || 'Belum dikonfirmasi admin' }}</p>
                            <p class="text-lg text-gray-700"><strong>Tanggal Penugasan:</strong> {{
                                formatDate(assignment?.tanggal_penugasan) || 'Belum dikonfirmasi admin' }}</p>
                            <!-- <p class="text-lg text-gray-700"><strong>Lokasi Penugasan:</strong> {{ assignment?.lokasi
                                || 'Belum dikonfirmasi admin' }}</p> -->
                        </div>
                        <div class="border border-gray-300 p-4 rounded-md bg-gray-50 mt-2">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Tindak Lanjut</h2>
                            <div v-if="followUp.length > 0">
                                <div v-for="(item, index) in followUp" :key="item.id" class="mb-4 border-b pb-2">
                                    <p class="text-lg text-gray-700">
                                        <strong>Jenis Gangguan :</strong>
                                        {{ item.disruption.nama_gangguan }}
                                    </p>
                                    <p class="text-lg text-gray-700">
                                        <strong>Tindak Lanjut:</strong>
                                        {{ item.detail_disruption.detail }}
                                    </p>
                                    <p class="text-lg text-gray-700">
                                        <strong>Keterangan:</strong>
                                        {{ item.hal_lain || '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="border border-gray-300 p-4 rounded-md bg-gray-50 mt-2">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Realisasi Hasil Pekerjaan</h2>
                            <div>
                                <p class="text-lg text-gray-600"><strong>Realisasi: </strong>{{
                                    assignment.realisasi.realisasi_hasil }}</p>
                            </div>
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
                <img :src="selectedImage" alt="Gambar Diperbesar"
                    class="max-w-full max-h-screen rounded-lg shadow-lg" />
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

    </AuthenticatedLayout>
</template>
