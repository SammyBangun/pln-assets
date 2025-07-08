<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { Notify } from 'notiflix';
import formatDate from '@/functions/formatDate';

const props = defineProps({
    report: Object,
    report_assign: Object
});

const form = useForm({
    status: '',
    keterangan_status: ''
});

function submit() {
    console.log(props.report.id);
    form.post(`/admin/konfirmasi/${props.report.id}`, {
        onSuccess: () => {
            Notify.success('Status berhasil diperbarui!',
                {
                    position: 'center-top',
                    distance: '70px',
                }
            );
        },
        onError: () => {
            Notify.failure('Terjadi kesalahan saat memperbarui status.',
                {
                    position: 'center-top',
                    distance: '70px',
                }
            );
        }
    });
}
</script>

<template>

    <AuthenticatedLayout>

        <div class="container mx-auto my-12 min-h-screen px-4">
            <div class="flex justify-between items-center my-6">
                <h1 class="text-3xl font-extrabold text-center text-gray-800 mb-8">Konfirmasi Admin</h1>
                <button @click="$inertia.get('/riwayat')"
                    class="bg-yellow-500 hover:bg-yellow-700 text-white font-semibold px-6 py-3 rounded-lg shadow-md transition-all">
                    Kembali ke Riwayat
                </button>
            </div>

            <div class="max-w-full mx-auto bg-white shadow-xl rounded-2xl p-8 border border-gray-200">
                <!-- Ubah ke grid dua kolom -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                    <!-- Kolom Kiri: Detail Laporan -->
                    <div class="space-y-4">
                        <div class="border border-gray-300 p-4 rounded-md bg-gray-50">
                            <p class="text-lg text-gray-600"><strong>Tanggal:</strong> {{ formatDate(report.created_at)
                                }}
                            </p>
                            <p class="text-lg font-semibold text-gray-700"><strong>Pelapor:</strong> {{
                                report.user?.name }}
                            </p>
                            <p class="text-lg text-gray-600"><strong>Serial Number:</strong> {{
                                report.aset?.serial_number
                                ?? 'Tidak ditemukan' }}</p>
                            <p class="text-lg text-gray-600"><strong>Nama:</strong> {{ report.aset?.nama }}</p>
                            <p class="text-lg text-gray-600"><strong>Tipe:</strong> {{ report.aset?.tipe }}</p>
                            <p class="text-lg text-gray-600"><strong>Lokasi:</strong> {{ report.aset?.lokasi }}</p>

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
                    </div>

                    <!-- Kolom Kanan: Gambar -->
                    <div>
                        <div class="flex flex-col items-center justify-start space-y-4">
                            <p class="text-lg font-semibold text-gray-700">Gambar:</p>
                            <div v-if="report.gambar" class="mt-3">
                                <img :src="report.gambar" alt="Gambar Laporan" @click="openImage(report.gambar)"
                                    class="cursor-pointer w-full max-w-xs rounded-lg shadow-md">
                            </div>
                            <span v-else class="text-gray-500">Tidak ada gambar</span>
                        </div>
                        <div class="flex flex-col items-center justify-start space-y-4 mt-8">
                            <h3 class="text-xl font-extrabold text-gray-800 mb-2">Pilih Status</h3>
                            <form @submit.prevent="submit">
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-semibold mb-2">Status:</label>
                                    <div class="flex flex-col space-y-2">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="status" value="Diterima" v-model="form.status"
                                                class="text-green-500 focus:ring-yellow-500">
                                            <span class="ml-2 text-gray-700">Terima</span>
                                        </label>

                                        <label class="inline-flex items-center">
                                            <input type="radio" name="status" value="Ditolak" v-model="form.status"
                                                class="text-red-500 focus:ring-yellow-500">
                                            <span class="ml-2 text-gray-700">Tolak</span>
                                        </label>
                                    </div>

                                    <div v-if="form.status === 'Ditolak'">
                                        <label class="block text-gray-700 font-semibold mt-4">Alasan Penolakan:</label>
                                        <textarea v-model="form.keterangan_status" rows="4"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-yellow-500 focus:border-yellow-500"
                                            required></textarea>
                                    </div>
                                </div>

                                <button type="submit"
                                    class="bg-yellow-500 hover:bg-yellow-700 text-white font-semibold px-6 py-3 rounded-lg shadow-md transition-all">
                                    Konfirmasi
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </AuthenticatedLayout>

</template>
