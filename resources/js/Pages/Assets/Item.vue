<script setup>
import { ref, computed, defineProps } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Notiflix from 'notiflix';
import formatDate from '@/functions/formatDate';
import { useForm } from '@inertiajs/vue3';

const form = useForm({});

const props = defineProps({
    items: Array,
    tipe: String
});

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

const searchQuery = ref('');

const latestAssets = computed(() => {
    return [...(props.items || [])]
        .filter(item =>
            (item.user?.name || '').toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            (item.serial_number || '').toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            (item.nama || '').toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            (item.seri || '').toLowerCase().includes(searchQuery.value.toLowerCase())
        )
        .sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
});


const deleteReport = (serial_number) => {
    Notiflix.Confirm.show(
        'Hapus Aset',
        'Apakah Anda yakin ingin menghapus item ini?',
        'Ya',
        'Tidak',
        () => {
            form.delete(`/item/${serial_number}`, {
                onSuccess: () => {
                    Notiflix.Notify.success('Aset berhasil dihapus', {
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
            Notiflix.Notify.warning('Aset tidak jadi dihapus', {
                position: 'center-top',
                distance: '70px',
            });
        }
    );
};
</script>

<template>

    <AuthenticatedLayout>

        <div class="container mx-auto m-10 bg-white shadow-lg border rounded-lg p-6 min-h-screen">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ tipe }} List</h1>
            <div class="mb-4 w-3/12 mx-auto">
                <input v-model="searchQuery" type="text" placeholder="Cari sesuatu..."
                    class="w-full px-4 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-300">
            </div>
            <div class="overflow-x-auto">
                <table v-if="items.length > 0" class="w-full border text-sm border-gray-200 rounded-lg shadow-md">
                    <thead>
                        <tr class="bg-gray-800 text-white">
                            <th class="px-4 py-2">No</th>
                            <th class="px-4 py-2">Serial Number</th>
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2">Seri</th>
                            <th class="px-4 py-2">Divisi</th>
                            <th class="px-4 py-2">Tanggal Beli</th>
                            <th class="px-4 py-2">Terakhir Servis</th>
                            <th class="px-4 py-2">Gambar</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in latestAssets" :key="items.id_asset"
                            class="border-b border-gray-200 hover:bg-gray-100 hover:cursor-pointer">
                            <td class="px-4 py-2 text-center align-middle">{{ index + 1 }}</td>
                            <td class="px-4 py-2 text-center align-middle">{{ item.serial_number }}</td>
                            <td class="px-4 py-2 text-center align-middle">{{ item.nama }}</td>
                            <td class="px-4 py-2 text-center align-middle">{{ item.seri }}</td>
                            <td class="px-4 py-2 text-center align-middle">{{ item.division.nama_divisi }}</td>
                            <td class="px-4 py-2 text-center align-middle">{{ formatDate(item.tanggal_beli) }}</td>
                            <td class="px-4 py-2 text-center align-middle">
                                {{ formatDate(item.terakhir_servis) || 'Belum Pernah Diservis' }}
                            </td>
                            <td class="px-4 py-2 text-center align-middle">
                                <img v-if="item.gambar" :src="`/storage/assets/${item.gambar}`" alt="Gambar Laporan"
                                    class="w-20 h-20 object-cover rounded-md mx-auto">
                                <span v-else class="text-gray-500">Tidak ada gambar</span>
                            </td>
                            <td class="py-3 px-4 flex justify-center my-5 gap-1">
                                <button @click="$inertia.get(`/item/${item.tipe.tipe}/${item.serial_number}`)"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md">
                                    <i class="fas fa-eye fa-sm"></i>
                                </button>
                                <template v-if="$page.props.auth.user.role === 'admin'">
                                    <button @click="$inertia.get(`/item/${item.tipe.tipe}/${item.serial_number}/edit`)"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md">
                                        <i class="fas fa-edit fa-sm"></i>
                                    </button>
                                    <button @click="deleteReport(item.serial_number)"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md">
                                        <i class="fas fa-trash fa-sm"></i>
                                    </button>
                                </template>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p v-else class="text-center text-gray-600 text-lg font-semibold py-6">Tidak ada item</p>
            </div>
        </div>

    </AuthenticatedLayout>

</template>
