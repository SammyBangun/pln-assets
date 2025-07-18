<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
const props = defineProps({
    petugas: Array,
    pelapor: Array
});

const printPage = () => {
    window.print();
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="min-h-screen p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-bold mb-1">Pemantauan Kinerja</h1>
                <p class="text-gray-600 mb-2">Kegiatan Pelaporan Gangguan</p>
                <button @click="printPage"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md shadow-md no-print">
                    🖨️ Print
                </button>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <!-- Petugas -->
                <div>
                    <h2 class="text-lg font-semibold mb-2">📋 Daftar Petugas Teraktif</h2>
                    <table class="min-w-full text-sm bg-white border rounded shadow">
                        <thead class="bg-gray-100 text-left">
                            <tr>
                                <th class="px-4 py-2">Nama</th>
                                <th class="px-4 py-2">Jumlah Penanganan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(p, index) in petugas" :key="index" class="border-t hover:bg-gray-50">
                                <td class="px-4 py-2">{{ p.name }}</td>
                                <td class="px-4 py-2">{{ p.assignments_count }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pelapor -->
                <div>
                    <h2 class="text-lg font-semibold mb-2">🗣️ Pelapor Teraktif</h2>
                    <table class="min-w-full text-sm bg-white border rounded shadow">
                        <thead class="bg-gray-100 text-left">
                            <tr>
                                <th class="px-4 py-2">Nama</th>
                                <th class="px-4 py-2">Divisi</th>
                                <th class="px-4 py-2">Jumlah Laporan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(p, index) in pelapor" :key="index" class="border-t hover:bg-gray-50">
                                <td class="px-4 py-2">{{ p.name }}</td>
                                <td class="px-4 py-2">{{ p.division.nama_divisi }}</td>
                                <td class="px-4 py-2">{{ p.reports_count }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
