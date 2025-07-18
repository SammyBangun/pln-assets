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
    if (confirm('Yakin ingin menghapus divisi ini?')) {
        router.delete(route('admin.deliverables.delete', id));
    }
};
</script>

<template>

    <AuthenticatedLayout>

        <div class="p-4 min-h-screen">
            <h1 class="text-2xl font-bold mb-4">Realisasi Hasil Pekerjaan</h1>

            <form @submit.prevent="submit" class="mb-6">
                <input v-model="form.realisasi_hasil" type="text" placeholder="Realisasi hasil pekerjaan"
                    class="border p-2 rounded mr-2" />
                <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded">
                    Tambah
                </button>
            </form>

            <ul>
                <li v-for="deliverable in props.deliverables" :key="deliverable.id" class="flex justify-between mb-1">
                    <span>{{ deliverable.realisasi_hasil }}</span>
                    <button @click="deleteDivision(deliverable.id)"
                        class="text-red-500 border border-red-500 p-2 hover:bg-red-500 hover:text-white">Hapus</button>
                </li>
            </ul>
        </div>

    </AuthenticatedLayout>

</template>
