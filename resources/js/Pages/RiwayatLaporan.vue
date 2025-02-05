<template>
    <Navbar />
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold text-center mb-6">Riwayat Laporan Kerusakan</h1>
        <div class="overflow-x-auto shadow-md rounded-lg">
            <table class="min-w-full divide-y divide-gray-300 bg-white text-sm text-gray-800">
                <thead>
                    <tr class="bg-blue-600 text-white text-left">
                        <th class="py-4 px-6 font-semibold">#</th>
                        <th class="py-4 px-6 font-semibold">Laporan Kerusakan</th>
                        <th class="py-4 px-6 font-semibold">Deskripsi</th>
                        <th class="py-4 px-6 font-semibold">Pelapor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="(report, index) in reports" :key="report.id" class="hover:bg-blue-50 transition-colors">
                        <td class="py-4 px-6">{{ index + 1 }}</td>
                        <td class="py-4 px-6 font-semibold">{{ report.laporan_kerusakan }}</td>
                        <td class="py-4 px-6">{{ report.deskripsi }}</td>
                        <td class="py-4 px-6"><i>{{ report.user?.name || 'Tidak diketahui' }}</i></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <Footer />
</template>

<script>
import axios from "axios";
import Navbar from "../Components/Navbar.vue";
import Footer from "../Components/Footer.vue";

export default {
    name: "RiwayatLaporan",
    components: { Navbar, Footer },
    data() {
        return {
            reports: []
        };
    },
    mounted() {
        this.fetchReports();
    },
    methods: {
        async fetchReports() {
            try {
                const response = await axios.get("Reports/Index.vue"); // Sesuaikan endpoint API Laravel
                this.reports = response.data;
            } catch (error) {
                console.error("Gagal mengambil data laporan", error);
            }
        }
    }
};
</script>
