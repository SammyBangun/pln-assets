<script setup>
import { computed, onMounted } from 'vue'
import { Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Notify } from 'notiflix';
import { fetchAssets, assets } from '@/functions/fetchAssets';

onMounted(fetchAssets);

const props = defineProps({
    report: {
        type: Object,
        required: true,
        validator: (value) => {
            return [
                "aset",
                "identifikasi_masalah",
                "deskripsi",
                "gambar",
            ].every((key) => key in value);
        },
    },
    identifications: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    aset: props.report.aset,
    identifikasi_masalah: props.report.report_identifications.map((item) => item.identification?.id).filter(Boolean),
    deskripsi: props.report.deskripsi,
    gambar: null
});

const gambarLama = props.report.gambar;

function submit() {
    const formData = new FormData();
    formData.append("_method", "PUT");
    formData.append("aset", form.aset);
    form.identifikasi_masalah.forEach((id, index) => {
        formData.append(`identifikasi_masalah[${index}]`, id);
    });
    formData.append("deskripsi", form.deskripsi);

    if (form.gambar instanceof File) {
        formData.append("gambar", form.gambar);
    } else if (gambarLama) {
        formData.append("gambar", gambarLama); // Kirim path gambar lama jika tidak diubah
    }

    form.post(route('riwayat.update', props.report.id), {
        onSuccess: () => {
            Notify.success('Laporan berhasil diperbarui', {
                position: 'center-top',
                distance: '70px',
            });
        },
        onError: (errors) => {
            Notify.failure('Terjadi kesalahan saat memperbarui laporan', {
                position: 'center-top',
                distance: '70px',
            });
        }
    });
}

const gambarPreview = computed(() => {
    if (form.gambar instanceof File) {
        return URL.createObjectURL(form.gambar);
    }
    return gambarLama ? `${gambarLama}` : null;
});

function handleFileUpload(event) {
    const file = event.target.files[0];
    if (file && ["image/png", "image/jpeg", "image/webp"].includes(file.type)) {
        form.gambar = file;
    } else {
        Notify.failure("Format file tidak valid! Harap unggah PNG, JPEG, atau WebP.");
    }
}
</script>

<template>

    <AuthenticatedLayout>

        <div class="container mx-auto my-8 min-h-screen">
            <div class="min-h-screen mx-auto py-8 px-4 sm:px-6 lg:px-8 max-w-3xl bg-white shadow-lg rounded-lg p-6">
                <h1 class="text-2xl font-bold text-center mb-6">Edit</h1>
                <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-6">

                    <div class="w-8/12 mx-auto">
                        <label class="block text-gray-700 text-sm font-bold mb-1" for="aset">
                            Serial Number Aset
                        </label>

                        <!-- Jika data tersedia, tampilkan select -->
                        <select v-if="assets.length > 0" v-model="form.aset" name="aset" class="input">
                            <option v-for="asset in assets" :key="asset.serial_number" :value="asset.serial_number">
                                {{ asset.serial_number }} - {{ asset.nama }}
                            </option>
                        </select>

                        <!-- Jika data kosong, tampilkan pesan -->
                        <p v-else class="text-gray-500 text-sm italic">Memuat...</p>
                    </div>

                    <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="mx-auto">
                            <label for="identifikasi_masalah" class="block text-sm font-bold text-gray-700">
                                Identifikasi Masalah
                            </label>
                            <div class="mt-2 space-y-1">
                                <div v-for="item in identifications" :key="item.id" class="flex items-center space-x-2">
                                    <input type="checkbox" :value="item.id" v-model="form.identifikasi_masalah"
                                        class="rounded border-gray-300 text-[#98c01d] shadow-sm focus:ring-[#98c01d]" />
                                    <span class="text-gray-700">{{ item.identifikasi_masalah }}</span>
                                </div>
                            </div>

                            <div v-if="form.errors.identifikasi_masalah" class="text-red-500 text-sm mt-1">
                                {{ form.errors.identifikasi_masalah }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="block text-gray-700 text-sm font-bold mb-1">
                                Deskripsi
                            </label>
                            <div class="mt-1">
                                <textarea id="deskripsi" v-model="form.deskripsi" rows="4" class="input h-56"
                                    required></textarea>
                            </div>
                            <div v-if="form.errors.deskripsi" class="text-red-500 text-sm mt-1">
                                {{ form.errors.deskripsi }}
                            </div>
                        </div>
                    </div>

                    <div>
                        <label
                            class="mx-auto cursor-pointer flex w-full max-w-lg flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-400 bg-white p-6 text-center"
                            for="dropzone-file">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-800" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2">
                                <path strokeLinecap="round" strokeLinejoin="round"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>

                            <h2 class="mt-4 text-xl font-medium text-gray-700 tracking-wide">Gambar</h2>
                            <p class="mt-2 text-gray-500 tracking-wide">Unggah atau seret & letakkan file Anda (SVG,
                                PNG, JPG, GIF).</p>

                            <input id="dropzone-file" type="file" class="hidden" name="gambar"
                                accept="image/png, image/jpeg, image/webp" @change="handleFileUpload" />
                        </label>
                        <div v-if="form.errors.gambar" class="text-red-500 text-sm mt-1">
                            {{ form.errors.gambar }}
                        </div>
                        <div v-if="gambarPreview" class="mt-4 mx-auto">
                            <p class="text-gray-700 text-center">Preview Gambar:</p>
                            <img :src="gambarPreview" alt="Preview Gambar"
                                class="max-w-full h-auto mx-auto text-center rounded-lg shadow-md">
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <Link :href="route('riwayat.index')"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md mr-2">
                        Kembali
                        </Link>
                        <div class="flex space-x-3">
                            <Link :href="route('riwayat.index')"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Batalkan
                            </Link>
                            <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                :disabled="form.processing">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
                <div v-if="form.errors.error"
                    class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ form.errors.error }}
                </div>
            </div>
        </div>

    </AuthenticatedLayout>

</template>

<style scoped>
.input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}
</style>