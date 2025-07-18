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
    if (confirm('Yakin ingin menghapus divisi ini?')) {
        router.delete(route('admin.disruptions.delete', id));
    }
};
</script>

<template>

    <AuthenticatedLayout>

        <div class="p-4 min-h-screen">
            <h1 class="text-2xl font-bold mb-4">Nama Gangguan</h1>

            <form @submit.prevent="submit" class="mb-6">
                <input v-model="form.nama_gangguan" type="text" placeholder="Nama gangguan"
                    class="border p-2 rounded mr-2" />
                <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded">
                    Tambah
                </button>
            </form>

            <ul>
                <li v-for="disruption in props.disruptions" :key="disruption.id" class="flex justify-between mb-1">
                    <span>{{ disruption.nama_gangguan }}</span>
                    <button @click="deleteDivision(disruption.id)"
                        class="text-red-500 border border-red-500 p-2 hover:bg-red-500 hover:text-white">Hapus</button>
                </li>
            </ul>
        </div>

    </AuthenticatedLayout>

</template>
