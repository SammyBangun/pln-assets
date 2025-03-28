<script setup>
import { defineProps } from 'vue';
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';
import Notiflix from 'notiflix';
import { useForm } from '@inertiajs/vue3';

const form = useForm({});

const props = defineProps({
    items: Array,
    type: String
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
    <Navbar />
    <div class="container-fluid mx-3 m-10 bg-white shadow-lg border rounded-lg p-6 min-h-screen">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ type }} List</h1>
        <div class="overflow-x-auto">
            <table v-if="items.length > 0" class="w-full border border-gray-200 rounded-lg shadow-md">
                <thead>
                    <tr class="bg-gray-800 text-white">
                        <th class="px-4 py-2">No</th>
                        <th class="px-4 py-2">SN</th>
                        <th class="px-4 py-2">Nama</th>
                        <th class="px-4 py-2">Seri</th>
                        <th class="px-4 py-2">Gambar</th>
                        <th class="px-4 py-2">Tanggal Beli</th>
                        <th class="px-4 py-2">Terakhir Servis</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in items" :key="item.id_asset"
                        class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="px-4 py-2 text-center align-middle">{{ index + 1 }}</td>
                        <td class="px-4 py-2 text-center align-middle">{{ item.serial_number }}</td>
                        <td class="px-4 py-2 text-center align-middle">{{ item.name }}</td>
                        <td class="px-4 py-2 text-center align-middle">{{ item.series }}</td>
                        <td class="px-4 py-2 text-center align-middle">
                            <img v-if="item.gambar" :src="`/storage/assets/${item.gambar}`" alt="Gambar Laporan"
                                class="w-20 h-20 object-cover rounded-md mx-auto">
                            <span v-else class="text-gray-500">Tidak ada gambar</span>
                        </td>
                        <td class="px-4 py-2 text-center align-middle">{{ item.tgl_beli }}</td>
                        <td class="px-4 py-2 text-center align-middle">{{ item.last_service }}</td>
                        <td class="py-3 px-4 flex justify-center">
                            <button @click="$inertia.get(`/item/${item.type}/${item.serial_number}`)"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md mr-2">
                                Detail
                            </button>
                            <template v-if="$page.props.auth.user.role === 'admin'">
                                <button @click="$inertia.get(`/item/${item.type}/${item.serial_number}/edit`)"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md mr-2">
                                    Edit
                                </button>
                                <button @click="deleteReport(item.serial_number)"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md">
                                    Delete
                                </button>
                            </template>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p v-else class="text-center text-gray-600 text-lg font-semibold py-6">Tidak ada item</p>
        </div>
    </div>
    <Footer />
</template>
