<script setup>
import { ref, watch, onMounted } from 'vue';
import Navbar from '@/components/Navbar.vue';
import Footer from '@/components/Footer.vue';
import { fetchAssets, assets } from '@/functions/fetchAssets';
import { useForm } from '@inertiajs/vue3';
import { Notify } from 'notiflix';

const showOtherInput = ref(false);
const otherCategory = ref('');
const gambarPreview = ref(null);


const form = useForm({
    aset: '',
    laporan_kerusakan: '',
    deskripsi: '',
    gambar: null
});

watch(() => form.laporan_kerusakan, (newValue) => {
    showOtherInput.value = newValue === 'other';
    if (!showOtherInput.value) {
        otherCategory.value = '';
    }
});

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    form.gambar = file;

    if (file) {
        gambarPreview.value = URL.createObjectURL(file);
    }
};

onMounted(fetchAssets);

const submit = () => {
    if (showOtherInput.value) {
        form.laporan_kerusakan = otherCategory.value;
    }

    // Gunakan FormData untuk mengirim file
    const formData = new FormData();
    formData.append('aset', form.aset);
    formData.append('laporan_kerusakan', form.laporan_kerusakan);
    formData.append('deskripsi', form.deskripsi);
    if (form.gambar) {
        formData.append('gambar', form.gambar);
    }

    form.post(route("riwayat.store"), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            Notify.success('Laporan berhasil tersimpan', {
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
            Notify.error('Gagal menyimpan laporan. Silakan coba lagi nanti.', {
                position: 'center-top',
                distance: '70px',
            });
            console.error("Error:", error);
        }
    });

};
</script>

<template>
    <Navbar />
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
                                <option v-for="asset in assets" :key="asset.serial_number" :value="asset.serial_number">
                                    {{ asset.serial_number }} - {{ asset.name }}
                                </option>
                            </select>

                            <!-- Jika data kosong, tampilkan pesan -->
                            <p v-else class="text-gray-500 text-sm italic">Aset tidak tersedia</p>
                        </div>

                        <div class="w-full md:w-full px-3 mb-6">
                            <label class="block uppercase tracking-wide text-gray-700 text-sm font-bold mb-2"
                                for="category_name">
                                Identifikasi Masalah
                            </label>
                            <select v-model="form.laporan_kerusakan" name="category_name"
                                class="appearance-none block w-full bg-white text-gray-900 font-medium border border-gray-400 rounded-lg py-3 px-3 leading-tight focus:outline-none focus:border-[#98c01d]">
                                <option value="Gangguan Hardware">Gangguan Hardware</option>
                                <option value="Gangguan Software">Gangguan Software</option>
                                <option value="Gangguan Virus">Gangguan Virus</option>
                                <option value="Gangguan Sistem Operasi">Gangguan Sistem Operasi</option>
                                <option value="Gangguan Jaringan LAN">Gangguan Jaringan LAN</option>
                                <option value="Gangguan Jaringan WIFI">Gangguan Jaringan WIFI</option>
                                <option value="Gangguan Jaringan Internet">Gangguan Jaringan Internet</option>
                                <option value="Gangguan Jaringan Intranet">Gangguan Jaringan Intranet</option>
                                <option value="other">Lainnya...</option>
                            </select>
                            <input v-if="showOtherInput" v-model="otherCategory" type="text"
                                placeholder="Masukkan jenis gangguan"
                                class="appearance-none w-full bg-white text-gray-900 font-medium border border-gray-400 rounded-lg py-3 px-3 leading-tight focus:outline-none focus:border-[#98c01d] mt-3" />
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

                                <h2 class="mt-4 text-xl font-medium text-gray-700 tracking-wide">Gambar Pelaporan</h2>
                                <p class="mt-2 text-gray-500 tracking-wide">Unggah atau seret & letakkan file Anda (SVG,
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

                <!-- <ul>
                    <li v-for="report in reports" :key="report.id">
                        <p><strong>{{ report.laporan_kerusakan }}</strong></p>
                        <p>{{ report.deskripsi }}</p>
                        <p><i>Oleh: {{ report.user?.name }}</i></p>
                        <img v-if="report.gambar" :src="report.gambar" alt="Gambar Laporan"
                            class="mt-2 max-w-full h-auto" />
                    </li>
                </ul> -->

            </div>
        </div>
    </div>
    <Footer />
</template>
