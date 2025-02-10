<script setup>
import { ref, computed } from 'vue';
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    reports: Array
});

// State untuk pencarian
const searchQuery = ref('');

// Sorting terbaru (menggunakan created_at)
const latestReports = computed(() => {
    return [...(props.reports || [])]
        .filter(report =>
            report.user?.name?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            report.laporan_kerusakan?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            report.deskripsi?.toLowerCase().includes(searchQuery.value.toLowerCase())
        )
        .sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
});

// Function untuk menghapus laporan
const form = useForm({});
const deleteReport = (id) => {
    if (confirm("Apakah Anda yakin ingin menghapus laporan ini?")) {
        form.delete(`/riwayat/${id}`);
    }
};
</script>

<template>
    <Navbar />
    <div class="container mx-auto my-8 min-h-screen">
        <h1 class="text-2xl font-bold text-center mb-6">Riwayat Laporan</h1>

        <div class="mb-4 w-3/12 mx-auto">
            <input v-model="searchQuery" type="text" placeholder="Cari laporan..."
                class="w-full px-4 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-300">
        </div>

        <div class="overflow-x-auto mb-32">
            <table class="min-w-full bg-white border border-gray-200 shadow-lg rounded-lg">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Pelapor</th>
                        <th class="py-3 px-4 text-left">Laporan Kerusakan</th>
                        <th class="py-3 px-4 text-left">Deskripsi</th>
                        <th class="py-3 px-4 text-left">Gambar</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(report, index) in latestReports" :key="report.id" class="border-b hover:bg-gray-100">
                        <td class="py-3 px-4">{{ index + 1 }}</td>
                        <td class="py-3 px-4">{{ report.user?.name }}</td>
                        <td class="py-3 px-4">{{ report.laporan_kerusakan }}</td>
                        <td class="py-3 px-4">{{ report.deskripsi }}</td>
                        <td class="py-3 px-4">
                            <img v-if="report.gambar" :src="report.gambar" alt="Gambar Laporan"
                                class="w-20 h-20 object-cover rounded-md">
                            <span v-else class="text-gray-500">Tidak ada gambar</span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <button @click="$inertia.get(`/riwayat/${report.id}`)"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md mr-2">
                                Detail
                            </button>
                        </td>

                        <!-- <td class="py-3 px-4 text-center">
                            <button @click="$inertia.get(`/riwayat/${report.id}`)"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md mr-2">
                                Show
                            </button>
                            <button @click="$inertia.get(`/riwayat/${report.id}/edit`)"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md mr-2">
                                Edit
                            </button>
                            <button @click="deleteReport(report.id)"
                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md">
                                Delete
                            </button>
                        </td> -->
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <Footer />
</template>
