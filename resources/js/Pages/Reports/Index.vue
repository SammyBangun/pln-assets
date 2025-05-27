<script setup>
import { ref, computed } from 'vue';
import Navbar from '@/components/Navbar.vue';
import Footer from '@/components/Footer.vue';
import Notiflix from "notiflix";
import formatDate from '@/functions/formatDate';
import { useForm } from '@inertiajs/vue3';

Notiflix.Confirm.init({
    width: "400px",
    borderRadius: "10px",
    titleColor: "#D32F2F",
    okButtonBackground: "#D32F2F",
    okButtonColor: "#FFF",
    cancelButtonBackground: "#757575",
    cancelButtonColor: "#FFF",
    backgroundColor: "#FFF",
    titleFontSize: "18px",
    messageFontSize: "16px",
    cssAnimationStyle: "zoom",
});

const props = defineProps({
    reports: Array
});

const searchQuery = ref('');

const latestReports = computed(() => {
    return [...(props.reports || [])]
        .filter(report =>
            report.user?.name?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            report.laporan_kerusakan?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            report.deskripsi?.toLowerCase().includes(searchQuery.value.toLowerCase())
        )
        .sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
});

const form = useForm({});
const deleteReport = (id) => {
    Notiflix.Confirm.show(
        'Hapus Laporan',
        'Apakah Anda yakin ingin menghapus laporan ini?',
        'Ya',
        'Tidak',
        () => {
            form.delete(`/riwayat/${id}`, {
                onSuccess: () => {
                    Notiflix.Notify.success('Laporan berhasil dihapus', {
                        position: 'center-top',
                        distance: '70px',
                    });
                },
                onError: () => {
                    Notiflix.Notify.failure('Terjadi kesalahan saat menghapus laporan', {
                        position: 'center-top',
                        distance: '70px',
                    });
                }
            });
        },
        () => {
            Notiflix.Notify.warning('Laporan tidak jadi dihapus', {
                position: 'center-top',
                distance: '70px',
            });
        }
    );
};

</script>

<template>
    <Navbar />
    <div class="container-fluid mx-3 my-8 min-h-screen">
        <h1 class="text-2xl font-bold text-center mb-6">Riwayat</h1>

        <div class="mb-4 w-3/12 mx-auto">
            <input v-model="searchQuery" type="text" placeholder="Cari laporan..."
                class="w-full px-4 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-300">
        </div>

        <div class="overflow-x-auto mb-32 rounded-lg">
            <table class="min-w-full bg-white border border-gray-200 shadow-lg rounded-lg">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Pelapor</th>
                        <th class="py-3 px-4 text-left">Serial Number</th>
                        <th class="py-3 px-4 text-left">Laporan Kerusakan</th>
                        <th class="py-3 px-4 text-left">Deskripsi</th>
                        <th class="py-3 px-4 text-left">Tanggal</th>
                        <th class="py-3 px-4 text-left">Status</th>
                        <th class="py-3 px-4 text-left">Gambar</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                        <template v-if="$page.props.auth.user && $page.props.auth.user.role === 'admin'">
                            <th class="py-3 px-4 text-center">Aksi Admin</th>
                        </template>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(report, index) in latestReports" :key="report.id" class="border-b hover:bg-gray-100">
                        <td class="py-3 px-4">{{ index + 1 }}</td>
                        <td class="py-3 px-4">{{ report.user?.name }}</td>
                        <td class="py-3 px-4">{{ report.aset }}</td>
                        <td class="py-3 px-4">{{ report.laporan_kerusakan }}</td>
                        <td class="py-3 px-4">{{ report.deskripsi?.slice(0, 60) }}{{ report.deskripsi?.length > 60 ?
                            '...' : '' }}</td>
                        <td class="py-3 px-4">{{ formatDate(report.created_at) }}</td>
                        <td class="py-3 px-4">
                            <span v-if="report.status === 'Diproses'"
                                class="text-yellow-500 font-semibold">Diproses</span>
                            <span v-else-if="report.status === 'Selesai'"
                                class="text-green-500 font-semibold">Selesai</span>
                            <span v-else-if="report.status === 'Diterima'"
                                class="text-blue-500 font-semibold">Diterima</span>
                        </td>
                        <td class="py-3 px-4">
                            <img v-if="report.gambar" :src="report.gambar" alt="Gambar Laporan"
                                class="w-20 h-20 object-cover rounded-md">
                            <span v-else class="text-gray-500">Tidak ada gambar</span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button @click="$inertia.get(`/riwayat/${report.id}`)"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md">
                                    🔎
                                </button>
                                <template v-if="$page.props.auth.user &&
                                    ($page.props.auth.user.role === 'admin' ||
                                        report.user_pelapor === $page.props.auth.user.id)">
                                    <button @click="$inertia.get(`/riwayat/${report.id}/edit`)"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md">
                                        ✏️
                                    </button>
                                    <button @click="deleteReport(report.id)"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md">
                                        🗑️
                                    </button>
                                </template>
                            </div>
                        </td>
                        <template v-if="$page.props.auth.user && $page.props.auth.user.role === 'admin'">
                            <td class="py-3 px-4">
                                <button @click="$inertia.get(`/admin/konfirmasi/${report.id}`)"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md mr-2">
                                    Konfirmasi Admin
                                </button>
                            </td>
                        </template>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <Footer />
</template>
