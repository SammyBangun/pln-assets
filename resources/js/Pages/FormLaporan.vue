<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SignaturePad from '@/components/SignaturePad.vue'
import { fetchAssets, assets } from '@/functions/fetchAssets';
import { useForm } from '@inertiajs/vue3';
import { Notify } from 'notiflix';

onMounted(fetchAssets);

const props = defineProps({
    identifications: Array,
    assets: Array,
    assetTypes: Array
});

const gambarPreview = ref(null);
const signatureData = ref(null);

const showDropdown = ref(false);
const selectedType = ref(null);
const selectedSeri = ref(null);
const selectedAsset = ref(null);

const selectAsset = (asset) => {
    selectedAsset.value = asset;
    form.aset = asset.serial_number;
    showDropdown.value = false;
};

const form = useForm({
    aset: '',
    identifikasi_masalah: [],
    deskripsi: '',
    gambar: null,
    ttd_user: null,
});

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    form.gambar = file;

    if (file) {
        gambarPreview.value = URL.createObjectURL(file);
    }
};

const handleSignatureSaved = (data) => {
    signatureData.value = data;
    form.ttd_user = data;
    if (data !== null) {
        console.log(data);
    }
};

const submit = () => {
    const formData = new FormData();

    formData.append('aset', form.aset);
    form.identifikasi_masalah.forEach((id, index) => {
        formData.append(`identifikasi_masalah[${index}]`, id);
    });
    formData.append('deskripsi', form.deskripsi);
    if (form.gambar) {
        formData.append('gambar', form.gambar);
    }
    if (form.ttd_user) {
        formData.append('ttd_user', form.ttd_user);
    }

    form.post(route("riwayat.store"), {
        data: formData,
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            Notify.success('Pelaporan gangguan berhasil tersimpan', {
                position: 'center-top',
                distance: '70px',
            });
            form.reset();
            gambarPreview.value = null;
            signatureData.value = null;
        },
        onError: (errors) => {
            let errorMessage = errors[Object.keys(errors)[0]] ?? 'Terjadi kesalahan saat menyimpan data.';
            console.log(errorMessage);
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

                            <div class="relative w-full sm:w-auto max-w-lg mx-auto mb-6">
                                <!-- Tombol trigger -->
                                <button @click.prevent="showDropdown = !showDropdown"
                                    class="w-full bg-white border border-gray-400 rounded-lg px-4 py-2 flex justify-between items-center shadow-sm relative z-20">
                                    <span class="mx-auto">{{ selectedAsset?.nama || "Pilih Aset IT" }}</span>
                                    <i :class="showDropdown ? 'fa fa-caret-up ml-2' : 'fa fa-caret-down ml-2'"></i>
                                </button>

                                <!-- Dropdown -->
                                <div v-if="showDropdown"
                                    class="absolute top-full left-1/2 -translate-x-1/2 mt-2 w-[600px] max-w-[95vw] bg-white border border-gray-300 rounded-xl shadow-lg flex flex-col sm:flex-row overflow-hidden z-10">

                                    <!-- Kolom 1: Tipe Aset -->
                                    <div
                                        class="w-full sm:w-1/4 border-b sm:border-b-0 sm:border-r border-gray-200 max-h-[250px] overflow-y-auto">
                                        <ul>
                                            <li v-for="type in assetTypes" :key="type.id"
                                                @click="selectedType = type.id; selectedSeri = null; selectedAsset = null"
                                                :class="[
                                                    'px-4 py-2 cursor-pointer hover:bg-gray-100',
                                                    selectedType === type.id ? 'bg-gray-200 font-bold' : '',
                                                ]">
                                                {{ type.tipe }}
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Kolom 2: Seri -->
                                    <div v-if="selectedType"
                                        class="w-full sm:w-1/4 border-b sm:border-b-0 sm:border-r border-gray-200 max-h-[250px] overflow-y-auto">
                                        <ul>
                                            <li v-for="asset in assetTypes.find(t => t.id === selectedType)?.assets || []"
                                                :key="asset.seri"
                                                @click="selectedSeri = asset.seri; selectedAsset = null" :class="[
                                                    'px-4 py-2 cursor-pointer hover:bg-gray-100',
                                                    selectedSeri === asset.seri ? 'bg-gray-200 font-semibold' : '',
                                                ]">
                                                {{ asset.seri }}
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Kolom 3: Nama Aset -->
                                    <div v-if="selectedSeri"
                                        class="w-full sm:w-1/4 border-b sm:border-b-0 sm:border-r border-gray-200 max-h-[250px] overflow-y-auto">
                                        <ul>
                                            <li v-for="asset in (assetTypes.find(t => t.id === selectedType)?.assets || []).filter(a => a.seri === selectedSeri)"
                                                :key="asset.serial_number" @click="selectedAsset = asset"
                                                class="px-4 py-2 cursor-pointer hover:bg-blue-100">
                                                {{ asset.nama }}
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Kolom 4: Serial Number -->
                                    <div v-if="selectedAsset" class="w-full sm:w-1/4 max-h-[250px] overflow-y-auto">
                                        <ul>
                                            <li @click="selectAsset(selectedAsset)"
                                                class="px-4 py-2 cursor-pointer hover:bg-blue-100 font-semibold text-gray-700">
                                                {{ selectedAsset.serial_number }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
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

                            <!-- Signature Pad -->
                            <div class="w-full md:w-full px-3 mb-6">
                                <label class="block uppercase tracking-wide text-gray-700 text-sm font-bold mb-2">
                                    Tanda Tangan
                                </label>

                                <SignaturePad @signatureSaved="handleSignatureSaved" />

                                <div v-if="signatureData" class="mt-4">
                                    <p class="text-gray-700">Preview Tanda Tangan:</p>
                                    <img :src="signatureData" alt="Preview Signature"
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
