<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Navbar from '@/components/Navbar.vue';
import Footer from '@/components/Footer.vue';
import Notiflix from 'notiflix';

const props = defineProps({
    assignment: Object,
    deliverables: Array
});

const gambarPreview = ref(null);

const form = useForm({
    realisasi_hasil: [],
    gambar_tindak_lanjut: null,
    catatan: ''
});

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    form.gambar_tindak_lanjut = file;

    if (file) {
        gambarPreview.value = URL.createObjectURL(file);
    }
};

function submit() {
    form.post(`/admin/tindak-lanjut/finalization/${props.assignment.id}`, {
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
    <Navbar />

    <div class="mx-auto my-12 min-h-screen px-4">
        <div class="min-w-full border border-gray-200 rounded-lg p-6 shadow-sm">
            <h1 class="text-3xl font-extrabold text-center text-gray-800">Tindak Lanjut Pekerjaan</h1>
            <h3 class="text-lg font-semibold text-center text-gray-700 mb-8">(Penugasan yang dikerjakan)</h3>

            <form @submit.prevent="submit" class="space-y-4 w-8/12 mx-auto">

                <h1 class="mb-4 text-center">Finalisasi</h1>
                <div class="border border-gray-300 p-4 rounded-md bg-gray-50 ">

                    <div>
                        <h3 class="text-lg font-semibold text-center text-gray-700 mb-4">Gambar Tindak Lanjut/Perangkat
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
                                <p class="mt-2 text-gray-500 tracking-wide">Unggah atau seret & letakkan file Anda (SVG,
                                    PNG, JPG, GIF).</p>

                                <input id="dropzone-file" type="file" class="hidden" name="gambar_tindak_lanjut"
                                    accept="image/png, image/jpeg, image/webp" @change="handleFileUpload" />
                            </label>

                            <!-- Tampilkan preview gambar -->
                            <div v-if="gambarPreview" class="mt-4">
                                <p class="text-gray-700 text-center">Preview Gambar:</p>
                                <img :src="gambarPreview" alt="Preview Gambar"
                                    class="max-w-full h-auto rounded-lg shadow-md mx-auto">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-center text-gray-700 mb-4">Realisasi Hasil Pekerjaan
                            </h3>
                            <div v-if="deliverables.length > 0" class="">
                                <label v-for="deliverable in deliverables" :key="deliverable.id"
                                    class="flex items-center space-x-2">
                                    <input type="radio" :value="deliverable.id" v-model="form.realisasi_hasil"
                                        class="rounded border-gray-300 text-[#98c01d] shadow-sm focus:ring-[#98c01d]" />
                                    <span class="text-gray-700 text-center">{{ deliverable.realisasi_hasil }}</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-center text-gray-700 mb-4">Catatan</h3>
                            <textarea v-model="form.catatan" class="w-full px-3 py-2 border border-gray-300 rounded-md"
                                placeholder="Masukkan catatan"></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center space-x-4">
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white px-4 py-2 rounded">
                        Simpan
                    </button>
                </div>
            </form>
            <button @click="$inertia.get(route('admin.tindak_lanjut.indexNetwork', props.assignment.id))" type="submit"
                class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">
                Lanjutkan
            </button>

        </div>
    </div>

    <Footer />
</template>
