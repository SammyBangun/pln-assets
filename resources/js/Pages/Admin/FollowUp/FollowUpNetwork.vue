<script setup>
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Notiflix from 'notiflix';

const props = defineProps({
    disruption: Array,
    detail_disruption: Array,
    assignment: Object
});

const selectedDisruption = props.disruption[2];

const filteredDetails = computed(() =>
    props.detail_disruption.filter(d => d.jenis_gangguan === 3)
);

const markForm = useForm({})

const form = useForm({
    detail: [],
    hal_lain: {}
});

function markAsDone() {
    markForm.post(route('admin.tindak_lanjut.markAsDone', props.assignment.id), {
        preserveScroll: true,
        onSuccess: () => {
            Notiflix.Notify.success('Berhasil menyelesaikan tindak lanjut!', {
                position: 'center-top',
                distance: '70px',
            });
        },
        onError: (errors) => {
            const allErrors = Object.values(errors).join('\n');
            Notiflix.Notify.failure(`Gagal menyimpan:\n${allErrors}`, {
                position: 'center-top',
                distance: '70px',
            });
        }
    });
}

function submit() {
    form.post(`/admin/tindak-lanjut/network/${props.assignment.id}`, {
        onSuccess: () => {
            Notiflix.Notify.success('Tindak lanjut berhasil disimpan!', {
                position: 'center-top',
                distance: '70px',
            });
        },
        onError: (errors) => {
            const allErrors = Object.values(errors).join('\n');
            Notiflix.Notify.failure(`Gagal menyimpan:\n${allErrors}`, {
                position: 'center-top',
                distance: '70px',
            });
        }
    });
}
</script>

<template>

    <AuthenticatedLayout>

        <div class="mx-auto my-12 min-h-screen px-4">
            <div class="min-w-full border border-gray-200 rounded-lg p-6 shadow-sm">
                <h1 class="text-3xl font-extrabold text-center text-gray-800">Tindak Lanjut Pekerjaan</h1>
                <h3 class="text-lg font-semibold text-center text-gray-700 mb-8">(Penugasan yang dikerjakan)</h3>

                <form @submit.prevent="submit" class="space-y-4 w-fit mx-auto">

                    <h1 v-if="selectedDisruption" class="mb-4 text-center">{{ selectedDisruption.nama_gangguan }}</h1>
                    <div class="border border-gray-300 p-4 rounded-md bg-gray-50 ">

                        <div v-if="filteredDetails.length > 0" class="grid sm:grid-cols-1 md:grid-cols-2 gap-4">
                            <label v-for="detail in filteredDetails" :key="detail.id"
                                class="flex items-center space-x-2">
                                <input type="checkbox" :value="detail.id" v-model="form.detail"
                                    class="rounded border-gray-300 text-[#98c01d] shadow-sm focus:ring-[#98c01d]" />
                                <span class="text-gray-700">{{ detail.detail }}</span>
                            </label>
                        </div>

                    </div>

                    <div class="flex justify-center space-x-4">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            Simpan
                        </button>
                    </div>
                </form>
                <div class="flex justify-between space-x-4">
                    <div>
                        <button @click="$inertia.get(route('admin.tindak_lanjut.indexSoftware', props.assignment.id))"
                            type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white px-4 py-2 rounded">
                            Kembali
                        </button>
                    </div>
                    <div>
                        <form @submit.prevent="markAsDone">
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">
                                Selesai
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>

</template>
