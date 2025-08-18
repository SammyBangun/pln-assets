<script setup>
import { ref, computed } from 'vue';
import Notiflix from "notiflix";
import formatDate from '@/functions/formatDate';
import { useForm } from '@inertiajs/vue3';

const form = useForm();

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
    reports: Array,
});

const searchQuery = ref('');

const latestReports = computed(() => {
    return [...(props.reports || [])]
        .filter(report =>
            report.user?.name?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            report.identifikasi_masalah?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            report.deskripsi?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            report.assignment?.status?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            report.aset?.nama?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            formatDate(report.created_at).toLowerCase().includes(searchQuery.value.toLowerCase())
        )
        .sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
});

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

const printPage = () => {
    window.print();
};

const perPage = ref(20);
const currentPage = ref(1);

const paginatedReports = computed(() => {
    const start = (currentPage.value - 1) * perPage.value;
    const end = start + perPage.value;
    return latestReports.value.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(latestReports.value.length / perPage.value);
});

const changePage = (page) => {
    currentPage.value = page;
};

</script>

<template>
    <div class="container-fluid mx-3 my-8 min-h-screen border border-gray-200 p-3 rounded-lg shadow-md">
        <div class="my-6 print-header text-center">
            <h3 class="text-2xl font-extrabold text-gray-800">Riwayat Laporan Gangguan</h3>
            <p class="text-gray-500 text-lg mt-1">
                {{ new Date().toLocaleDateString('id-ID', {
                    day: '2-digit', month: 'long', year: 'numeric'
                }).replace(/\s/g, '/') }}
            </p>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
            <!-- Search Box -->
            <div class="w-full md:w-4/12">
                <input v-model="searchQuery" type="text" placeholder="🔍 Cari sesuatu..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
            </div>

            <!-- Action Buttons -->
            <template v-if="$page.props.auth.user.role === 'admin'">
                <div class="flex gap-3">
                    <button @click="$inertia.get('/admin/performance-monitoring')"
                        class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-md transition">
                        📊 <span>Pemantauan Kinerja</span>
                    </button>
                    <button @click="printPage"
                        class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-md transition">
                        🖨️ <span>Print</span>
                    </button>
                </div>
            </template>
        </div>

        <div class="overflow-x-auto mb-32 rounded-lg">
            <table class="min-w-full text-sm bg-white border border-gray-200 shadow-lg rounded-lg">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left">No</th>
                        <template v-if="$page.props.auth.user && $page.props.auth.user.role === 'admin'">
                            <th class="py-3 px-4 text-left">Pelapor</th>
                        </template>
                        <th class="py-3 px-4 text-left">Serial Number</th>
                        <th class="py-3 px-4 text-left">Nama</th>
                        <th class="py-3 px-4 text-left">Identifikasi Masalah</th>
                        <!-- <th class="py-3 px-4 text-left">Deskripsi</th> -->
                        <th class="py-3 px-4 text-left">Tanggal</th>
                        <th class="py-3 px-4 text-left">Status</th>
                        <!-- <th class="py-3 px-4 text-left">Gambar</th> -->
                        <template
                            v-if="$page.props.auth.user && $page.props.auth.user.role === 'admin' || $page.props.auth.user.role === 'user'">
                            <th class="py-3 px-4 text-center no-print">Aksi</th>
                        </template>
                        <template
                            v-if="$page.props.auth.user && $page.props.auth.user.role === 'admin' || $page.props.auth.user.role === 'petugas'">
                            <th class="py-3 px-4 text-center no-print">Penanganan</th>
                        </template>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(report, index) in paginatedReports" :key="report.id"
                        @click="$inertia.get(`/riwayat/${report.id}`)"
                        class="border-b hover:bg-gray-100 cursor-pointer">
                        <td class="py-3 px-4">{{ index + 1 }}</td>
                        <template v-if="$page.props.auth.user && $page.props.auth.user.role === 'admin'">
                            <td class="py-3 px-4">{{ report.user?.name }}</td>
                        </template>
                        <td class="py-3 px-4">{{ report.aset?.serial_number }}</td>
                        <td class="py-3 px-4">{{ report.aset?.nama }}</td>
                        <td class="py-3 px-4">
                            <ul>
                                <li v-for="(item, index) in report.report_identifications.slice(0, 1)" :key="item.id">
                                    {{ item.identification?.identifikasi_masalah ?? 'Tidak ditemukan' }}
                                    <span v-if="report.report_identifications.length > 1"> ...</span>
                                </li>
                            </ul>
                        </td>
                        <!-- <td class="py-3 px-4">{{ report.deskripsi?.slice(0, 60) }}{{ report.deskripsi?.length > 60 ?
                            '...' : '' }}</td> -->
                        <td class="py-3 px-4">{{ formatDate(report.created_at) }}</td>
                        <td class="py-3 px-4">
                            <span v-if="report.assignment?.status === 'Ditugaskan'"
                                class="text-yellow-500 font-semibold">Ditugaskan</span>
                            <span v-else-if="report.assignment?.status === 'Selesai'"
                                class="text-green-500 font-semibold">Selesai,
                                <span v-if="report.assignment?.status === 'Selesai'">pada tanggal:
                                    {{ formatDate(report.assignment?.tanggal_selesai) }}</span>
                            </span>
                            <span v-else-if="report.assignment?.status === 'Diterima'"
                                class="text-blue-500 font-semibold">Diterima</span>
                            <span v-else-if="report.assignment?.status === 'Ditolak'"
                                class="text-red-500 font-semibold">Ditolak</span>
                            <span v-else-if="report.assignment?.status === 'Menunggu Konfirmasi'"
                                class="text-gray-500 font-semibold">Menunggu Konfirmasi</span>
                            <span v-else-if="report.assignment?.status === 'Finalisasi'"
                                class="text-gray-500 font-semibold">Finalisasi</span>
                            <span v-else class="text-gray-400 italic">Belum ada status</span>
                        </td>
                        <!-- <td class="py-3 px-4">
                            <img v-if="report.gambar" :src="report.gambar" alt="Gambar Laporan"
                                class="w-20 h-20 object-cover rounded-md">
                            <span v-else class="text-gray-500">Tidak ada gambar</span>
                        </td> -->
                        <template v-if="$page.props.auth.user &&
                            ($page.props.auth.user.role === 'admin' ||
                                report.user_pelapor === $page.props.auth.user.id)">
                            <td class="py-3 px-4 text-center no-print">
                                <div class="flex justify-center gap-2">
                                    <button @click.stop="$inertia.get(`/riwayat/${report.id}/edit`)"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button @click.stop="deleteReport(report.id)"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </template>
                        <template
                            v-if="$page.props.auth.user && $page.props.auth.user.role === 'admin' || $page.props.auth.user.role === 'petugas'">
                            <td class="py-3 px-4 no-print text-center">
                                <div class="flex justify-center">
                                    <button @click.stop="$inertia.get(`/admin/konfirmasi/${report.id}`)"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md mr-2 disabled:bg-gray-500 disabled:cursor-not-allowed disabled:opacity-50"
                                        :disabled="report.assignment?.status === 'Selesai' || report.assignment?.status === 'Ditolak'">
                                        <span
                                            v-if="report.assignment?.status === 'Menunggu Konfirmasi'">Konfirmasi</span>
                                        <span v-if="report.assignment?.status === 'Ditolak'">Ditolak</span>
                                        <span v-if="report.assignment?.status === 'Diterima'">Penugasan</span>
                                        <template
                                            v-if="$page.props.auth.user.role === 'petugas' || $page.props.auth.user.role === 'admin'">
                                            <span v-if="report.assignment?.status === 'Ditugaskan'">Tindak Lanjut</span>
                                            <span v-if="report.assignment?.status === 'Finalisasi'">Finalisasi</span>
                                        </template>
                                        <span v-if="report.assignment?.status === 'Selesai'">Selesai</span>
                                    </button>
                                </div>
                            </td>
                        </template>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="mb-4 flex justify-between items-center px-4 no-print">
        <div>
            <label class="mr-2 font-semibold">Tampilkan:</label>
            <select v-model="perPage" class="border rounded px-2 py-1 w-16">
                <option :value="10">10</option>
                <option :value="20">20</option>
                <option :value="50">50</option>
                <option :value="100">100</option>
            </select>
            <span class="ml-2">data per halaman</span>
        </div>
    </div>
    <div class="flex justify-center mt-4 space-x-2 no-print">
        <button @click="changePage(page)" v-for="page in totalPages" :key="page" :class="[
            'px-3 py-1 border rounded',
            page === currentPage ? 'bg-blue-500 text-white' : 'bg-white text-gray-800'
        ]">
            {{ page }}
        </button>
    </div>
</template>

<style>
@media print {

    body {
        background: white;
        color: black;
        font-size: 12px;
        margin: 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 6px;
        text-align: left;
    }

    img {
        max-width: 100px;
        height: auto;
    }

    .container-fluid {
        margin: 0;
        padding: 0;
    }
}
</style>
