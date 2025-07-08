<script setup>
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Notiflix from 'notiflix';

const props = defineProps({
    disruption: Array,
    detail_disruption: Array,
    assignment: Object
});

const selectedDisruption = props.disruption[0];

const getDetailById = (id) => filteredDetails.value.find(d => d.id === id);

const filteredDetails = computed(() =>
    props.detail_disruption.filter(d => d.jenis_gangguan === 1)
);

const form = useForm({
    jenis_gangguan: 1,
    detail: [],
    hal_lain: {},
    hardware_replacements: [
        {
            nama_komponen: '',
            detail_merek_hardware: '',
            jumlah: 1
        }
    ]
});

function addHardwareReplacement() {
    if (form.detail.length === 0) return;

    form.hardware_replacements.push({
        nama_komponen: '',
        detail_merek_hardware: '',
        jumlah: 1
    });
}

function removeHardwareReplacement(id) {
    form.hardware_replacements = form.hardware_replacements.filter(item => item.id !== id);
}

function submit() {
    form.post(`/admin/tindak-lanjut/hardware/${props.assignment}`, {
        onSuccess: () => {
            Notiflix.Notify.success('Tindak lanjut berhasil disimpan!', {
                position: 'center-top',
                distance: '70px',
            });
        },
        onError: (errors) => {
            const allErrors = Object.values(errors).join('\n');
            console.log(allErrors);
            Notiflix.Notify.failure(`Gagal menyimpan:\n${allErrors}`, {
                position: 'center-top',
                distance: '70px',
            });
        }

    });
}
</script>


<template>
    <AuthenticatedLayout>

        <div class="mx-auto my-12 min-h-screen px-4">
            <div class="min-w-full border border-gray-200 rounded-lg p-6 shadow-sm">
                <h1 class="text-3xl font-extrabold text-center text-gray-800">Tindak Lanjut Pekerjaan</h1>
                <h3 class="text-lg font-semibold text-center text-gray-700 mb-8">(Penugasan yang dikerjakan)</h3>

                <form @submit.prevent="submit" class="space-y-4 w-full mx-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Kolom Gangguan Karena Hardware -->
                        <div class="border border-gray-300 p-4 rounded-md bg-gray-50">
                            <h1 v-if="selectedDisruption" class="mb-4 text-center">{{ selectedDisruption.nama_gangguan
                            }}
                            </h1>
                            <div v-if="filteredDetails.length > 0" class="grid sm:grid-cols-1 md:grid-cols-3 gap-2">
                                <label v-for="detail in filteredDetails" :key="detail.id"
                                    class="flex items-center space-x-2">
                                    <input type="checkbox" :value="detail.id" v-model="form.detail"
                                        class="rounded border-gray-300 text-[#98c01d] shadow-sm focus:ring-[#98c01d]" />
                                    <span class="text-gray-700">{{ detail.detail }}</span>
                                </label>
                            </div>
                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <div v-for="id in form.detail" :key="id" class="border p-2 rounded bg-white">
                                    <div class="font-semibold text-gray-800">{{ getDetailById(id)?.detail }}</div>
                                    <label class="block text-sm text-gray-700 mt-1">Keterangan:</label>
                                    <input type="text" v-model="form.hal_lain[id]"
                                        class="w-full rounded border-gray-300 shadow-sm focus:ring-[#98c01d] focus:border-[#98c01d]">
                                </div>
                            </div>

                        </div>

                        <!-- Kolom Hardware yang diganti baru -->
                        <div class="border border-gray-300 p-4 rounded-md bg-gray-50">
                            <h1 class="mb-4 text-center">Hardware yang diganti baru</h1>

                            <!-- Tombol Tambah -->
                            <div class="flex justify-end mb-2">
                                <button type="button" :disabled="form.detail.length === 0"
                                    @click="addHardwareReplacement"
                                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded disabled:opacity-50 disabled:cursor-not-allowed">
                                    + Tambah
                                </button>

                            </div>

                            <!-- Daftar Form Penggantian -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div v-for="(replacement, index) in form.hardware_replacements" :key="replacement.id"
                                    class="border rounded-md p-3 bg-white space-y-2 relative">

                                    <!-- Tombol Hapus -->
                                    <button type="button" @click="removeHardwareReplacement(replacement.id)"
                                        class="absolute top-2 right-2 text-red-500 hover:text-red-700 text-sm">
                                        ✕
                                    </button>

                                    <!-- Dropdown Nama Komponen -->
                                    <label class="block text-sm font-medium text-gray-700">Nama Komponen</label>
                                    <select v-model="replacement.nama_komponen"
                                        class="w-full rounded border-gray-300 shadow-sm focus:ring-[#98c01d] focus:border-[#98c01d]">
                                        <option disabled value="">Pilih Komponen</option>
                                        <option v-for="id in form.detail" :key="id" :value="id">
                                            {{ getDetailById(id)?.detail }}
                                        </option>
                                    </select>

                                    <!-- Detail Merek -->
                                    <label class="block text-sm font-medium text-gray-700">Detail Merek</label>
                                    <input type="text" v-model="replacement.detail_merek_hardware"
                                        class="w-full rounded border-gray-300 shadow-sm focus:ring-[#98c01d] focus:border-[#98c01d]" />

                                    <!-- Jumlah -->
                                    <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                                    <input type="number" min="1" v-model="replacement.jumlah"
                                        class="w-full rounded border-gray-300 shadow-sm focus:ring-[#98c01d] focus:border-[#98c01d]" />
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="flex justify-center space-x-4">
                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white px-4 py-2 rounded">
                            Simpan
                        </button>
                    </div>
                </form>
                <button @click="$inertia.get(route('admin.tindak_lanjut.indexSoftware', props.assignment.id))"
                    type="submit" class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">
                    Lanjutkan
                </button>

            </div>
        </div>

    </AuthenticatedLayout>
</template>
