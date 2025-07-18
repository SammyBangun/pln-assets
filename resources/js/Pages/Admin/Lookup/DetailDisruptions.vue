<script setup>
import { useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    detailDisruptions: Array,
});

console.log(props.detailDisruptions);

const form = useForm({
    tipe: '',
});

const submit = () => {
    form.post(route('admin.detail-disruptions.store'), {
        onSuccess: () => form.reset(),
    });
};

const deleteDivision = (id) => {
    if (confirm('Yakin ingin menghapus divisi ini?')) {
        router.delete(route('admin.detail-disruptions.delete', id));
    }
};
</script>

<template>

    <AuthenticatedLayout>

        <div class="p-4 min-h-screen">
            <h1 class="text-2xl font-bold mb-4">Detail Gangguan</h1>

            <form @submit.prevent="submit" class="mb-6">
                <input v-model="form.tipe" type="text" placeholder="Detail Gangguan" class="border p-2 rounded mr-2" />
                <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded">
                    Tambah
                </button>
            </form>

            <ul>
                <li v-for="detail in props.detailDisruptions" :key="detail.id" class="flex justify-between mb-1">
                    <span>{{ detail.disruption.nama_gangguan }}</span>
                    <span>{{ detail.detail }}</span>
                    <button @click="deleteDivision(detail.id)"
                        class="text-red-500 border border-red-500 p-2 hover:bg-red-500 hover:text-white">Hapus</button>
                </li>
            </ul>
        </div>

    </AuthenticatedLayout>

</template>
