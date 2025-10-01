<script setup>
import { useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    disruptions: Array,
});

const form = useForm({
    nama_gangguan: '',
});

const submit = () => {
    form.post(route('admin.disruptions.store'), {
        onSuccess: () => form.reset(),
    });
};

const deleteDivision = (id) => {
    if (confirm('Yakin ingin menghapus gangguan ini?')) {
        router.delete(route('admin.disruptions.delete', id));
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="p-6 min-h-screen bg-gray-50">
            <div class="max-w-3xl mx-auto">
                <h1 class="text-3xl font-bold text-gray-800 text-center">Manajemen Gangguan</h1>
                <p class="text-gray-600 text-center mb-5 underline">Jenis gangguan yang bisa diselesaikan oleh admin dan
                    petugas</p>


                <!-- Form Tambah Gangguan -->
                <div class="bg-white border border-gray-200 rounded-xl shadow p-6 mb-8">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Tambah Nama Gangguan</h2>
                    <form @submit.prevent="submit" class="flex flex-col sm:flex-row items-center gap-4">
                        <input v-model="form.nama_gangguan" type="text" placeholder="Nama gangguan"
                            class="w-full sm:w-auto flex-1 border border-gray-300 p-3 rounded-lg shadow-sm focus:ring focus:ring-blue-200" />
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                            Tambah
                        </button>
                    </form>
                </div>

                <!-- Daftar Gangguan -->
                <div class="bg-white border border-gray-200 rounded-xl shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Daftar Gangguan</h2>
                    <ul class="divide-y divide-gray-100">
                        <li v-for="disruption in props.disruptions" :key="disruption.id"
                            class="flex justify-between items-center py-3">
                            <span class="text-gray-800 text-lg">{{ disruption.nama_gangguan }}</span>
                            <button @click="deleteDivision(disruption.id)"
                                class="text-red-600 border border-red-500 px-4 py-1 rounded hover:bg-red-500 hover:text-white transition">
                                Hapus
                            </button>
                        </li>
                        <li v-if="props.disruptions.length === 0" class="text-gray-500 text-center py-4">
                            Belum ada data gangguan.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
