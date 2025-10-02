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
    users: Array,
});

const searchQuery = ref('');
const showFilter = ref(false);
const sortDirection = ref('desc');

const filterStatus = ref('');
const filterDateFrom = ref('');
const filterDateTo = ref('');
const filterAsset = ref('');
const filterUser = ref('');

const latestReports = computed(() => {
    return [...(props.reports || [])]
        .filter(report => {
            const matchesSearch =
                report.user?.name?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                report.deskripsi?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                report.assignment?.status?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                report.aset?.nama?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                formatDate(report.created_at).toLowerCase().includes(searchQuery.value.toLowerCase());

            const matchesStatus = filterStatus.value ? report.assignment?.status === filterStatus.value : true;

            const reportDate = new Date(report.created_at);

            const matchesDateFrom = filterDateFrom.value
                ? reportDate >= new Date(filterDateFrom.value)
                : true;

            const matchesDateTo = filterDateTo.value
                ? reportDate <= new Date(new Date(filterDateTo.value).setHours(23, 59, 59, 999))
                : true;

            const matchesAsset = filterAsset.value
                ? report.aset?.nama?.toLowerCase().includes(filterAsset.value.toLowerCase())
                : true;

            const matchesUser = filterUser.value
                ? report.user?.name?.toLowerCase().includes(filterUser.value.toLowerCase())
                : true;

            return matchesSearch && matchesStatus && matchesDateFrom && matchesDateTo && matchesAsset && matchesUser;
        })
        .sort((a, b) => {
            const dateA = new Date(a.created_at);
            const dateB = new Date(b.created_at);
            return sortDirection.value === 'asc'
                ? dateA - dateB
                : dateB - dateA;
        });
});

const resetFilter = () => {
    filterStatus.value = '';
    filterDateFrom.value = '';
    filterDateTo.value = '';
    filterAsset.value = '';
    filterUser.value = '';
}

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

const perPage = ref(10);
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

const toggleSort = () => {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
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
            <!-- Search + Filter -->
            <div class="flex w-full md:w-5/12 gap-2 no-print">
                <button @click="showFilter = !showFilter"
                    class="flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg shadow-md transition">
                    <i class="fas fa-filter"></i><span class="hidden sm:inline">Filter</span>
                </button>
                <input v-model="searchQuery" type="text" placeholder="🔍 Cari sesuatu..."
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
            </div>

            <!-- Action Buttons -->
            <template v-if="$page.props.auth.user.role === 'admin'">
                <div class="flex gap-3 no-print">
                    <button @click="$inertia.get('/admin/performance-monitoring')"
                        class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-md transition">
                        <i class="fa fa-chart-line"></i><span>Pemantauan Kinerja</span>
                    </button>
                    <button @click="printPage"
                        class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-md transition">
                        <i class="fa fa-print"></i><span>Print</span>
                    </button>
                </div>
            </template>
        </div>


        <!-- FILTER PANEL -->
        <Transition name="fade-slide">
            <div v-if="showFilter" class="bg-gray-100 p-4 rounded-lg mb-4 shadow-inner">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-1">Status</label>
                        <select v-model="filterStatus" class="w-full border rounded px-2 py-1">
                            <option value="">Semua</option>
                            <option>Menunggu Konfirmasi</option>
                            <option>Ditolak</option>
                            <option>Diterima</option>
                            <option>Ditugaskan</option>
                            <option>Finalisasi</option>
                            <option>Selesai</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-1">Tanggal Dari</label>
                        <input type="date" v-model="filterDateFrom" class="w-full border rounded px-2 py-1" />
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-1">Tanggal Sampai</label>
                        <input type="date" v-model="filterDateTo" class="w-full border rounded px-2 py-1" />
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-1">Nama Aset</label>
                        <input type="text" v-model="filterAsset" class="w-full border rounded px-2 py-1" />
                    </div>

                    <div v-if="$page.props.auth.user.role === 'admin'">
                        <label class="block text-gray-700 text-sm font-bold mb-1">Pelapor</label>
                        <select v-model="filterUser" class="w-full border rounded px-2 py-1">
                            <option value="">Semua Pelapor</option>
                            <option v-for="user in props.users" :key="user.id" :value="user.name">
                                {{ user.name }}
                            </option>
                        </select>
                    </div>
                    <div @click.stop="resetFilter" class="flex justify-end">
                        <button
                            class="border text-white bg-red-500 hover:bg-white hover:text-red-600 hover:border-red-600 w-200 p-3 rounded-lg shadow-md transition">Reset</button>
                    </div>
                </div>
            </div>

        </Transition>

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
                        <th class="py-3 px-4 text-left cursor-pointer" @click="toggleSort">
                            Tanggal
                            <span v-if="sortDirection === 'asc'"><i class="fa fa-arrow-up"></i></span>
                            <span v-else><i class="fa fa-arrow-down"></i></span>
                        </th>
                        <th class="py-3 px-4 text-left">Status</th>
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
                        <template v-if="$page.props.auth.user &&
                            ($page.props.auth.user.role === 'admin' ||
                                report.user_pelapor === $page.props.auth.user.id)">
                            <td class="py-3 px-4 text-center no-print">
                                <div class="flex justify-center gap-1">
                                    <button @click.stop="$inertia.get(`/riwayat/${report.id}`)"
                                        class="bg-blue-500 hover:bg-blue-600 text-white p-1 rounded-md text-sm">
                                        <i class="fas fa-eye fa-sm"></i>
                                    </button>
                                    <button @click.stop="$inertia.get(`/riwayat/${report.id}/edit`)"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white p-1 rounded-md text-sm">
                                        <i class="fas fa-edit fa-sm"></i>
                                    </button>
                                    <button @click.stop="deleteReport(report.id)"
                                        class="bg-red-500 hover:bg-red-600 text-white p-1 rounded-md text-sm">
                                        <i class="fas fa-trash fa-sm"></i>
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

.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: all 0.3s ease;
}

.fade-slide-enter-from {
    opacity: 0;
    transform: translateY(-10px);
}

.fade-slide-enter-to {
    opacity: 1;
    transform: translateY(0);
}

.fade-slide-leave-from {
    opacity: 1;
    transform: translateY(0);
}

.fade-slide-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>
