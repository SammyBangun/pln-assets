<script setup>
import { useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    operators: Array,
});

const form = useForm({
    nama_petugas: '',
    no_hp: '',
});

const submit = () => {
    form.post(route('admin.operators.store'), {
        onSuccess: () => form.reset(),
    });
};

const deleteOperator = (id) => {
    if (confirm('Yakin ingin menghapus petugas ini?')) {
        router.delete(route('admin.operators.delete', id));
    }
};
</script>

<template>
    <AdminLayout>
        <div class="p-4">
            <h1 class="text-2xl font-bold mb-4">Daftar Petugas</h1>

            <form @submit.prevent="submit" class="mb-6">
                <input v-model="form.nama_petugas" type="text" placeholder="Nama Petugas"
                    class="border p-2 rounded mr-2" required />
                <input v-model="form.no_hp" type="text" placeholder="No HP" class="border p-2 rounded mr-2" />
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                    Tambah
                </button>
            </form>

            <ul>
                <li v-for="operator in props.operators" :key="operator.id" class="flex justify-between mb-2">
                    <div>
                        <p class="font-semibold">{{ operator.nama_petugas }}</p>
                        <p class="text-sm text-gray-600">No HP: {{ operator.no_hp || '-' }}</p>
                    </div>
                    <button @click="deleteOperator(operator.id)"
                        class="text-red-500 border border-red-500 p-2 hover:bg-red-500 hover:text-white">Hapus</button>
                </li>
            </ul>
        </div>
    </AdminLayout>
</template>
