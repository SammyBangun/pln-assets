<script setup>
import { useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    detailDisruptions: Array,
    disruptions: Array
});

const form = useForm({
    tipe: '',
});

console.log(props.detailDisruptions);

const submit = () => {
    form.post(route('admin.detail-disruptions.store'), {
        onSuccess: () => form.reset(),
    });
};

const deleteDivision = (id) => {
    if (confirm('Yakin ingin menghapus detail gangguan ini?')) {
        router.delete(route('admin.detail-disruptions.delete', id));
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="p-6 min-h-screen bg-gray-50">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Detail Gangguan</h1>

                <!-- Form Tambah Detail -->
                <div class="bg-white border border-gray-200 rounded-xl shadow p-6 mb-8">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Tambah Detail Gangguan</h2>
                    <form @submit.prevent="submit" class="flex flex-col sm:flex-row items-center gap-4">
                        <select name="tipe" id=""
                            class="w-full sm:w-auto flex-1 border border-gray-300 p-3 rounded-lg shadow-sm focus:ring focus:ring-blue-200">
                            <option v-for="disruption in props.disruptions" :value="disruption.id">
                                {{ disruption.nama_gangguan }}
                            </option>
                        </select>
                        <input v-model="form.tipe" type="text" placeholder="Detail gangguan"
                            class="w-full sm:w-auto flex-1 border border-gray-300 p-3 rounded-lg shadow-sm focus:ring focus:ring-blue-200" />
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                            Tambah
                        </button>
                    </form>
                </div>

                <!-- Daftar Detail Gangguan -->
                <div class="bg-white border border-gray-200 rounded-xl shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Daftar Detail Gangguan</h2>
                    <div v-if="props.detailDisruptions.length > 0">
                        <table class="w-full table-auto border-collapse">
                            <thead>
                                <tr class="bg-gray-100 text-left">
                                    <th class="p-3 font-semibold text-gray-700 border-b">Nama Gangguan</th>
                                    <th class="p-3 font-semibold text-gray-700 border-b">Detail</th>
                                    <th class="p-3 font-semibold text-gray-700 border-b">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="detail in props.detailDisruptions" :key="detail.id" class="hover:bg-gray-50">
                                    <td class="p-3 border-b">{{ detail.disruption?.nama_gangguan || '-' }}</td>
                                    <td class="p-3 border-b">{{ detail.detail }}</td>
                                    <td class="p-3 border-b">
                                        <button @click="deleteDivision(detail.id)"
                                            class="text-red-600 border border-red-500 px-4 py-1 rounded hover:bg-red-500 hover:text-white transition">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else class="text-gray-500 text-center py-4">
                        Belum ada data detail gangguan.
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
