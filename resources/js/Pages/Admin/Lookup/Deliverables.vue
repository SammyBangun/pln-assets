<script setup>
import { useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    deliverables: Array,
});

const form = useForm({
    realisasi_hasil: '',
});

const submit = () => {
    form.post(route('admin.deliverables.store'), {
        onSuccess: () => form.reset(),
    });
};

const deleteDivision = (id) => {
    if (confirm('Yakin ingin menghapus realisasi ini?')) {
        router.delete(route('admin.deliverables.delete', id));
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="p-6 min-h-screen bg-gray-50">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-3xl font-bold text-gray-800 text-center">Realisasi Hasil Pekerjaan</h1>
                <p class="text-gray-600 text-center mb-5 underline">Jenis realisasi yang sudah dilakukan</p>

                <!-- Form Tambah -->
                <div class="bg-white border border-gray-200 rounded-xl shadow p-6 mb-8">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Tambah Realisasi</h2>
                    <form @submit.prevent="submit" class="flex flex-col sm:flex-row gap-4 items-center">
                        <input v-model="form.realisasi_hasil" type="text" placeholder="Realisasi hasil pekerjaan"
                            class="w-full sm:flex-1 border border-gray-300 p-3 rounded-lg shadow-sm focus:ring focus:ring-blue-200" />
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                            Tambah
                        </button>
                    </form>
                </div>

                <!-- Daftar Realisasi -->
                <div class="bg-white border border-gray-200 rounded-xl shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Daftar Realisasi</h2>
                    <div v-if="props.deliverables.length > 0">
                        <table class="w-full table-auto border-collapse">
                            <thead>
                                <tr class="bg-gray-100 text-left">
                                    <th class="p-3 font-semibold text-gray-700 border-b">Realisasi Hasil</th>
                                    <th class="p-3 font-semibold text-gray-700 border-b text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="deliverable in props.deliverables" :key="deliverable.id"
                                    class="hover:bg-gray-50">
                                    <td class="p-3 border-b">{{ deliverable.realisasi_hasil }}</td>
                                    <td class="p-3 border-b text-center">
                                        <button @click="deleteDivision(deliverable.id)"
                                            class="text-red-600 border border-red-500 px-4 py-1 rounded hover:bg-red-500 hover:text-white transition">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else class="text-gray-500 text-center py-4">
                        Belum ada data realisasi hasil pekerjaan.
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
