<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Notiflix from 'notiflix';

const props = defineProps({
    disruption: Array,
    detail_disruption: Array,
    assignment: Object
});

const getDetailById = (id) => filteredDetails.value.find(d => d.id === id);

const selectedDisruption = props.disruption[1];

const filteredDetails = computed(() =>
    props.detail_disruption.filter(d => d.jenis_gangguan === 2)
);

const isOtherSelected = computed(() =>
    form.detail.some(id => getDetailById(id)?.detail.toLowerCase().includes('lainnya'))
);

const form = useForm({
    detail: [],
    hal_lain: {}
});

function submit() {
    form.post(`/admin/tindak-lanjut/software/${props.assignment.id}`, {
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
                    <div class="border border-gray-300 p-4 rounded-md bg-gray-50">

                        <div v-if="filteredDetails.length > 0" class="p-2">
                            <label v-for="detail in filteredDetails" :key="detail.id"
                                class="flex items-center space-x-2">
                                <input type="checkbox" :value="detail.id" v-model="form.detail"
                                    class="rounded border-gray-300 text-[#98c01d] shadow-sm focus:ring-[#98c01d]" />
                                <span class="text-gray-700 p-1">{{ detail.detail }}</span>
                            </label>
                        </div>

                        <!-- Tambahan keterangan jika "Lainnya" dipilih -->
                        <div v-if="isOtherSelected">
                            <label class="block text-sm text-gray-700 mt-1">Isi detail lain:</label>
                            <input type="text" v-model="form.hal_lain['lainnya']"
                                class="w-full rounded border-gray-300 shadow-sm focus:ring-[#98c01d] focus:border-[#98c01d]" />
                        </div>
                    </div>

                    <div class="flex justify-center space-x-4">
                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white px-4 py-2 rounded">
                            Simpan
                        </button>
                    </div>
                </form>
                <button @click="$inertia.get(route('admin.tindak_lanjut.indexNetwork', props.assignment.id))"
                    type="submit" class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">
                    Lanjutkan
                </button>

            </div>
        </div>

    </AuthenticatedLayout>

</template>
