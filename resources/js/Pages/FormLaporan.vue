<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { fetchAssets, assets } from '@/functions/fetchAssets';
import { useForm } from '@inertiajs/vue3';
import { Notify } from 'notiflix';

onMounted(fetchAssets);

defineProps({
    identifications: Array,
});

const gambarPreview = ref(null);

const form = useForm({
    aset: '',
    identifikasi_masalah: [],
    deskripsi: '',
    gambar: null
});

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    form.gambar = file;

    if (file) {
        gambarPreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    const formData = new FormData();

    console.log('formData aset:', form.aset);
    console.log('formData identifikasi:', form.identifikasi_masalah);

    formData.append('aset', form.aset);
    form.identifikasi_masalah.forEach((id, index) => {
        formData.append(`identifikasi_masalah[${index}]`, id);
    });
    formData.append('deskripsi', form.deskripsi);
    if (form.gambar) {
        formData.append('gambar', form.gambar);
    }

    form.post(route("riwayat.store"), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            Notify.success('Pelaporan gangguan berhasil tersimpan', {
                position: 'center-top',
                distance: '70px',
            });
            form.reset();
            gambarPreview.value = null;
        },
        onError: (errors) => {
            let errorMessage = errors[Object.keys(errors)[0]] ?? 'Terjadi kesalahan saat menyimpan data.';
            Notify.failure(errorMessage, {
                position: 'center-top',
                distance: '70px',
            });
        },
        onFail: (error) => {
            Notify.error('Gagal menyimpan data. Silakan coba lagi nanti.', {
                position: 'center-top',
                distance: '70px',
            });
            console.error("Error:", error);
        }
    });

};
</script>

<template>

    <AuthenticatedLayout>

        <div class="min-h-screen">
            <div class="flex flex-col mx-3 mt-6 lg:flex-row">
                <div class="w-full lg:w-1/3 mx-auto my-8">
                    <form class="w-full bg-white shadow-md p-6" @submit.prevent="submit">
                        <div class="flex flex-wrap -mx-3 mb-6">

                            <div class="w-full md:w-full px-3 mb-6">
                                <label class="block uppercase tracking-wide text-gray-700 text-sm font-bold mb-2"
                                    for="aset">
                                    Pilih Aset
                                </label>

                                <!-- Jika data tersedia, tampilkan select -->
                                <select v-if="assets.length > 0" v-model="form.aset" name="aset"
                                    class="appearance-none block w-full bg-white text-gray-900 font-medium border border-gray-400 rounded-lg py-3 px-3 leading-tight focus:outline-none focus:border-[#98c01d]">
                                    <option v-for="asset in assets" :key="asset.serial_number"
                                        :value="asset.serial_number">
                                        {{ asset.serial_number }} - {{ asset.nama }}
                                    </option>
                                </select>

                                <!-- Jika data kosong, tampilkan pesan -->
                                <p v-else class="text-gray-500 text-sm italic">Aset tidak tersedia</p>
                            </div>

                            <div class="w-full md:w-full px-3 mb-6">
                                <label class="block uppercase tracking-wide text-gray-700 text-sm font-bold mb-2">
                                    Identifikasi Masalah
                                </label>

                                <div v-if="identifications.length > 0" class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <label v-for="identification in identifications" :key="identification.id"
                                        class="flex items-center space-x-2">
                                        <input type="checkbox" :value="identification.id"
                                            v-model="form.identifikasi_masalah"
                                            class="rounded border-gray-300 text-[#98c01d] shadow-sm focus:ring-[#98c01d]" />
                                        <span class="text-gray-700">{{ identification.identifikasi_masalah }}</span>
                                    </label>
                                </div>
                                <p v-else class="text-gray-500 text-sm italic">Data identifikasi belum tersedia</p>
                            </div>

                            <div class="w-full px-3 mb-6">
                                <label class="block uppercase tracking-wide text-gray-700 text-sm font-bold mb-2"
                                    for="deskripsi">Deskripsi Masalah</label>
                                <textarea rows="4"
                                    class="appearance-none block w-full bg-white text-gray-900 font-medium border border-gray-400 rounded-lg py-3 px-3 leading-tight focus:outline-none focus:border-[#98c01d]"
                                    name="deskripsi" v-model="form.deskripsi" required></textarea>
                            </div>

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

                                    <input id="dropzone-file" type="file" class="hidden" name="gambar"
                                        accept="image/png, image/jpeg, image/webp" @change="handleFileUpload" />
                                </label>

                                <!-- Tampilkan preview gambar -->
                                <div v-if="gambarPreview" class="mt-4">
                                    <p class="text-gray-700">Preview Gambar:</p>
                                    <img :src="gambarPreview" alt="Preview Gambar"
                                        class="max-w-full h-auto rounded-lg shadow-md">
                                </div>
                            </div>


                            <div class="w-full md:w-full px-3 mb-6">
                                <button
                                    class="appearance-none block w-full bg-blue-700 text-gray-100 font-bold border border-gray-200 rounded-lg py-3 px-3 leading-tight hover:bg-blue-600 focus:outline-none focus:bg-white focus:border-gray-500">
                                    Kirim
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>

</template>
