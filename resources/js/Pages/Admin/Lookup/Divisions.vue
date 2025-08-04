<script setup>
import { useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    divisions: Array,
});

const form = useForm({
    nama_divisi: '',
});

const submit = () => {
    form.post(route('admin.divisions.store'), {
        onSuccess: () => form.reset(),
    });
};

const deleteDivision = (id) => {
    if (confirm('Yakin ingin menghapus divisi ini?')) {
        router.delete(route('admin.divisions.delete', id));
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="p-6 min-h-screen bg-gray-50">
            <div class="max-w-3xl mx-auto">
                <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Manajemen Divisi</h1>

                <!-- Form Tambah Divisi -->
                <div class="bg-white shadow-md rounded-xl p-6 mb-8 border border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Tambah Divisi</h2>
                    <form @submit.prevent="submit" class="flex flex-col sm:flex-row items-center gap-4">
                        <input v-model="form.nama_divisi" type="text" placeholder="Nama divisi"
                            class="w-full sm:w-auto flex-1 border border-gray-300 p-3 rounded-lg shadow-sm focus:ring focus:ring-blue-200" />
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                            Tambah
                        </button>
                    </form>
                </div>

                <!-- Daftar Divisi -->
                <div class="bg-white shadow-md rounded-xl p-6 border border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Daftar Divisi</h2>
                    <ul class="divide-y divide-gray-100">
                        <li v-for="division in props.divisions" :key="division.id"
                            class="flex justify-between items-center py-3">
                            <span class="text-gray-800 text-lg">{{ division.nama_divisi }}</span>
                            <button @click="deleteDivision(division.id)"
                                class="text-red-600 border border-red-500 px-4 py-1 rounded hover:bg-red-500 hover:text-white transition">
                                Hapus
                            </button>
                        </li>
                        <li v-if="props.divisions.length === 0" class="text-gray-500 text-center py-4">
                            Belum ada divisi yang ditambahkan.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
