<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Notiflix from 'notiflix';

const props = defineProps({
    operators: Array,
    assignment: Object
});

const form = useForm({
    petugas: '',
    tanggal_penugasan: '',
    lokasi: '',
});

function submit() {
    form.post(`/admin/penugasan/${props.assignment?.id}`, {
        onSuccess: () => {
            Notiflix.Notify.success('Penugasan berhasil disimpan!', {
                position: 'center-top',
                distance: '70px',
            }
            );
        },
        onError: () => {
            Notiflix.Notify.failure(`Penugasan gagal disimpan`, {
                position: 'center-top',
                distance: '70px',
            }
            );
        }
    });
}
</script>

<template>
    <AuthenticatedLayout>

        <div class="container mx-auto my-12 min-h-screen px-4">
            <div class="border border-gray-200 rounded-lg p-6 shadow-sm">
                <h1 class="text-3xl font-extrabold text-center text-gray-800 mb-8">Penugasan</h1>

                <form @submit.prevent="submit" class="space-y-4 max-w-lg mx-auto">
                    <div>
                        <label class="block text-gray-700">Nama Petugas</label>
                        <select v-model="form.petugas" class="w-full border border-gray-300 rounded p-2" required>
                            <option v-for="petugas in props.operators" :key="petugas.id" :value="petugas.id">
                                {{ petugas.name }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700">Tanggal Penugasan</label>
                        <input type="date" v-model="form.tanggal_penugasan"
                            class="w-full border border-gray-300 rounded p-2" required />
                    </div>

                    <div>
                        <label class="block text-gray-700">Lokasi</label>
                        <textarea v-model="form.lokasi" class="w-full border border-gray-300 rounded p-2"
                            required></textarea>
                    </div>

                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white px-4 py-2 rounded">
                        Simpan Penugasan
                    </button>
                </form>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
