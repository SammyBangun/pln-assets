<script setup>
import { ref, watch } from 'vue';
import Navbar from '../Components/Navbar.vue';
import Footer from '../Components/Footer.vue';

const selectedCategory = ref('');
const showOtherInput = ref(false);
const otherCategory = ref('');

watch(selectedCategory, (newValue) => {
    showOtherInput.value = newValue === 'other';
    if (!showOtherInput.value) {
        otherCategory.value = '';
    }
});

const createReport = () => {
    alert('Membuat laporan baru!');
};
</script>

<template>
    <Navbar />
    <div class="">
        <div class="flex flex-col mx-3 mt-6 lg:flex-row">
            <div class="w-full lg:w-1/3 mx-auto my-8">
                <form class="w-full bg-white shadow-md p-6">
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-full px-3 mb-6">
                            <label class="block uppercase tracking-wide text-gray-700 text-sm font-bold mb-2"
                                for="category_name">
                                Laporan Kerusakan
                            </label>
                            <select v-model="selectedCategory" @change="toggleOtherInput"
                                class="appearance-none block w-full bg-white text-gray-900 font-medium border border-gray-400 rounded-lg py-3 px-3 leading-tight focus:outline-none focus:border-[#98c01d]">
                                <option value="">Pilih Laporan Kerusakan</option>
                                <option value="hardware">Gangguan Hardware</option>
                                <option value="software">Gangguan Software</option>
                                <option value="virus">Gangguan Virus</option>
                                <option value="sistem_operasi">Gangguan Sistem Operasi</option>
                                <option value="lan">Gangguan Jaringan LAN</option>
                                <option value="wifi">Gangguan Jaringan WIFI</option>
                                <option value="internet">Gangguan Jaringan Internet</option>
                                <option value="intranet">Gangguan Jaringan Intranet</option>
                                <option value="other">Lainnya...</option>
                            </select>
                            <input v-if="showOtherInput" v-model="otherCategory" type="text"
                                placeholder="Masukkan jenis gangguan"
                                class="appearance-none w-full bg-white text-gray-900 font-medium border border-gray-400 rounded-lg py-3 px-3 leading-tight focus:outline-none focus:border-[#98c01d] mt-3" />
                        </div>
                        <div class="w-full px-3 mb-6">
                            <label class="block uppercase tracking-wide text-gray-700 text-sm font-bold mb-2"
                                htmlFor="category_name">Deskripsi</label>
                            <textarea textarea rows="4"
                                class="appearance-none block w-full bg-white text-gray-900 font-medium border border-gray-400 rounded-lg py-3 px-3 leading-tight focus:outline-none focus:border-[#98c01d]"
                                type="text" name="description" required> </textarea>
                        </div>

                        <div class="w-full px-3 mb-8">
                            <label
                                class="mx-auto cursor-pointer flex w-full max-w-lg flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-400 bg-white p-6 text-center"
                                htmlFor="dropzone-file">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-800" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2">
                                    <path strokeLinecap="round" strokeLinejoin="round"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>

                                <h2 class="mt-4 text-xl font-medium text-gray-700 tracking-wide">Gambar Laporan</h2>

                                <p class="mt-2 text-gray-500 tracking-wide">Unggah atau seret & letakkan file Anda SVG,
                                    PNG, JPG atau GIF. </p>

                                <input id="dropzone-file" type="file" class="hidden" name="category_image"
                                    accept="image/png, image/jpeg, image/webp" />
                            </label>
                        </div>

                        <div class="w-full md:w-full px-3 mb-6">
                            <button
                                class="appearance-none block w-full bg-blue-700 text-gray-100 font-bold border border-gray-200 rounded-lg py-3 px-3 leading-tight hover:bg-blue-600 focus:outline-none focus:bg-white focus:border-gray-500">Kirim</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
    <Footer />
</template>
