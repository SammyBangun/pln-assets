<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Notiflix from 'notiflix';
import SignaturePad from '@/components/SignaturePad.vue';

Notiflix.Confirm.init({
    width: "400px",
    borderRadius: "10px",
    titleColor: "#D32F2F",
    okButtonBackground: "#D32F2F",
    okButtonColor: "#FFF",
    cancelButtonBackground: "#757575",
    cancelButtonColor: "#FFF",
    backgroundColor: "#FFF",
    titleFontSize: "18px",
    messageFontSize: "16px",
    cssAnimationStyle: "zoom",
});

const props = defineProps({
    assignment: Object,
    deliverables: Array
});

const signatureData = ref(null);
const gambarPreview = ref(null);

const form = useForm({
    realisasi_hasil: props.assignment.realisasi || null,
    gambar_tindak_lanjut: null,
    catatan: props.assignment.catatan || '',
    ttd_user_it: props.assignment.ttd_user_it || null,
});

onMounted(() => {
    if (props.assignment.gambar_tindak_lanjut) {
        gambarPreview.value = `/storage/${props.assignment.gambar_tindak_lanjut}`;
    }
    if (props.assignment.ttd_user_it) {
        signatureData.value = props.assignment.ttd_user_it;
    }
});

const selectedDeliverableText = computed(() => {
    const selected = props.deliverables.find(d => d.id === form.realisasi_hasil);
    return selected ? selected.realisasi_hasil : '';
});

const showCatatanField = computed(() => {
    return (
        selectedDeliverableText.value.includes('Catatan') ||
        selectedDeliverableText.value.includes('Tidak Dapat Dilaksanakan')
    );
});

const handleSignatureSaved = (data) => {
    signatureData.value = data;
    form.ttd_user_it = data;
};

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    form.gambar_tindak_lanjut = file;

    if (file) {
        gambarPreview.value = URL.createObjectURL(file);
    }
};

const canSubmit = computed(() => {
    if (props.assignment.status === 'Finalisasi') {
        return true;
    }

    const selected = props.deliverables.find(d => d.id === form.realisasi_hasil);
    if (!selected) return false;
    const text = selected.realisasi_hasil.toLowerCase();
    return text.includes('selesai');
});

function submit() {
    const url = `/admin/tindak-lanjut/finalization/${props.assignment.id}`;
    const isPending = props.assignment.status === 'Pending';
    const method = isPending ? form.put : form.post;

    if (isPending) {
        Notiflix.Confirm.show(
            'Konfirmasi Pembaruan',
            'Finalisasi ini masih berstatus Pending. Apakah Anda yakin ingin memperbaruinya?',
            'Ya, Perbarui',
            'Batal',
            () => {
                method.call(form, url, {
                    onSuccess: () => {
                        Notiflix.Notify.success('Finalisasi berhasil diperbarui!', {
                            position: 'center-top',
                            distance: '70px',
                        });
                    },
                    onError: (errors) => {
                        const allErrors = Object.values(errors).join('\n');
                        Notiflix.Notify.failure(`Gagal memperbarui:\n${allErrors}`, {
                            position: 'center-top',
                            distance: '70px',
                        });
                    }
                });
            },
            () => {
                Notiflix.Notify.info('Pembaruan dibatalkan.', {
                    position: 'center-top',
                    distance: '70px',
                });
            },
        );
    } else {
        method.call(form, url, {
            onSuccess: () => {
                Notiflix.Notify.success('Data finalisasi berhasil disimpan!', {
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
}

</script>


<template>

    <AuthenticatedLayout>

        <div class="mx-auto my-8 min-h-screen px-4">
            <div class="min-w-full border border-gray-200 rounded-lg p-6 shadow-sm">
                <h1 class="text-3xl font-extrabold text-center text-gray-800">
                    {{ props.assignment.status === 'Pending' ? 'Edit Finalisasi Pending' : 'Finalisasi Pekerjaan' }}
                </h1>

                <h3 class="text-lg font-semibold text-center text-gray-700 mb-8">
                    ({{ props.assignment.status === 'Pending' ? 'Perbarui data finalisasi' : 'Isi data finalisasi baru'
                    }})
                </h3>

                <form @submit.prevent="submit" class="space-y-4 w-10/12 h-full mx-auto">

                    <div class="w-full mx-auto border border-gray-300 p-4 rounded-md bg-gray-50 ">

                        <div>
                            <h3 class="text-lg font-semibold text-center text-gray-700 mb-4">Gambar Tindak
                                Lanjut/Perangkat
                                Yang
                                Diganti</h3>
                            <div class="w-full px-3 mb-8">
                                <label
                                    class="mx-auto cursor-pointer flex w-full max-w-lg flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-400 bg-white p-6 text-center"
                                    for="dropzone-file">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-800" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2">
                                        <path strokeLinecap="round" strokeLinejoin="round"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>

                                    <h2 class="mt-4 text-xl font-medium text-gray-700 tracking-wide">Upload Gambar</h2>
                                    <p class="mt-2 text-gray-500 tracking-wide">Unggah atau seret & letakkan file Anda
                                        (SVG,
                                        PNG, JPG, GIF).</p>

                                    <input id="dropzone-file" type="file" class="hidden" name="gambar_tindak_lanjut"
                                        accept="image/png, image/jpeg, image/webp" @change="handleFileUpload" />
                                </label>

                                <!-- Tampilkan preview gambar -->
                                <div v-if="gambarPreview" class="mt-4">
                                    <p class="text-gray-700 text-center">Preview Gambar:</p>
                                    <img :src="gambarPreview" alt="Preview Gambar"
                                        class="max-w-full h-80 rounded-lg shadow-md mx-auto">
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col items-center mb-8">
                            <h3 class="text-lg font-semibold text-center text-gray-700 mb-4">
                                Realisasi Hasil Pekerjaan
                            </h3>

                            <div v-if="deliverables.length > 0" class="space-y-3 flex flex-col items-center">
                                <label v-for="deliverable in deliverables" :key="deliverable.id"
                                    class="flex items-center space-x-2">
                                    <input type="radio" :value="deliverable.id" v-model="form.realisasi_hasil"
                                        class="mt-0.5 rounded border-gray-300 text-[#98c01d] shadow-sm focus:ring-[#98c01d]"
                                        required />
                                    <span class="text-gray-700 leading-snug text-center">
                                        {{ deliverable.realisasi_hasil }}
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div v-if="showCatatanField"
                            class="flex flex-col items-center mb-8 transition-all duration-300">
                            <h3 class="text-lg font-semibold text-center text-gray-700 mb-4">Catatan</h3>
                            <textarea v-model="form.catatan" class="w-3/6 px-3 py-2 border border-gray-300 rounded-md"
                                placeholder="Masukkan catatan">
                            </textarea>
                        </div>

                        <!-- <div class="w-full text-center mt-8 px-3 mb-6">
                            <label class="block uppercase tracking-wide text-gray-700 text-sm font-bold mb-2">
                                Tanda Tangan
                            </label>

                            <div class="mx-auto max-w-sm">
                                <SignaturePad @signatureSaved="handleSignatureSaved" />
                            </div>

                            <div v-if="signatureData" class="mt-4">
                                <p class="text-gray-700">Preview Tanda Tangan:</p>
                                <div class="flex justify-center">
                                    <img :src="signatureData" alt="Preview Signature"
                                        class="max-w-full h-auto rounded-lg shadow-md">
                                </div>
                            </div>
                        </div> -->
                    </div>

                    <div class="flex justify-center space-x-4">
                        <button type="submit" :disabled="!canSubmit" class="px-4 py-2 rounded text-white transition bg-blue-500 hover:bg-blue-700
                            disabled:bg-gray-500 disabled:cursor-not-allowed disabled:opacity-50">
                            {{ props.assignment.status === 'Pending' ? 'Selesai' : 'Perbarui Finalisasi' }}
                        </button>
                    </div>

                </form>
                <button @click="$inertia.get(route('admin.dashboard'))" type="submit"
                    class="bg-yellow-500 hover:bg-yellow-700 text-white px-4 py-2 rounded">
                    Kembali
                </button>

            </div>
        </div>

    </AuthenticatedLayout>

</template>
