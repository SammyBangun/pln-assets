<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

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
    <AdminLayout>
        <div class="p-4">
            <h1 class="text-2xl font-bold mb-4">Divisi</h1>

            <form @submit.prevent="submit" class="mb-6">
                <input v-model="form.nama_divisi" type="text" placeholder="Nama divisi"
                    class="border p-2 rounded mr-2" />
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                    Tambah
                </button>
            </form>

            <ul>
                <li v-for="division in props.divisions" :key="division.id" class="flex justify-between mb-2">
                    <span>{{ division.nama_divisi }}</span>
                    <button @click="deleteDivision(division.id)"
                        class="text-red-500 border border-red-500 p-2 hover:bg-red-500 hover:text-white">Hapus</button>
                </li>
            </ul>
        </div>
    </AdminLayout>
</template>
