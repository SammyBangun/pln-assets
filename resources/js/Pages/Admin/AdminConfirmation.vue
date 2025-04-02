<script setup>
import { ref, computed, watch } from 'vue';
import Navbar from '@/components/Navbar.vue';
import Footer from '@/components/Footer.vue';
import { useForm } from '@inertiajs/vue3';
import { Notify } from 'notiflix';
import formatDate from '@/functions/formatDate';


const showModal = ref(false);
const showOtherInput = ref(false);
const otherCategory = ref('');
const otherHardware = ref('');
const otherSoftware = ref('');
const otherJaringan = ref('');
const otherProblem = ref('');
const selectedImage = ref('');


const props = defineProps({
    report: {
        type: Object,
        required: true,
        validator: (value) => {
            return [
                "tindak_lanjut",
                "deskripsi_lanjut",
                "realisasi",
                "status",
                "gambar_konfirmasi",
            ].every((key) => key in value);
        },
    },
});

const form = useForm({
    tindak_lanjut: props.report.tindak_lanjut,
    deskripsi_lanjut: props.report.deskripsi_lanjut,
    realisasi: props.report.realisasi,
    status: props.report.status,
    gambar_konfirmasi: null,
    software: props.report.software || "",
    hardware: props.report.hardware || "",
    jaringan: props.report.jaringan || "",
    problem: props.report.problem || "",
    catatan: "",
});

const gambarLama = props.report.gambar_konfirmasi;
const assetName = ref(props.report.asset ? props.report.asset.name : 'Tidak ditemukan');

const realisasiComputed = computed(() => {
    if (form.realisasi === "Selesai dengan catatan" || form.realisasi === "Tidak dapat dilaksanakan") {
        return `${form.realisasi}: ${form.catatan || "Tidak ada catatan"}`;
    }
    return form.realisasi;
});

watch(() => form.tindak_lanjut, (newValue) => {
    showOtherInput.value = newValue === "other";

    if (!showOtherInput.value) {
        otherCategory.value = "";
    }
});

const deskripsiLanjutComputed = computed(() => {
    let descriptions = [];

    if (form.tindak_lanjut === "Gangguan Hardware" && form.hardware) {
        descriptions.push(`${form.hardware === "other_hardware" ? otherHardware.value : form.hardware}`);
    }

    if (form.tindak_lanjut === "Gangguan Software" && form.software) {
        descriptions.push(`${form.software === "other_software" ? otherSoftware.value : form.software}`);
    }

    if (form.tindak_lanjut === "Gangguan Jaringan" && form.jaringan) {
        descriptions.push(`${form.jaringan === "other_network" ? otherJaringan.value : form.jaringan}`);
    }

    if (form.tindak_lanjut === "other" && otherProblem.value) {
        descriptions.push(`${otherProblem.value}`);
    }

    return descriptions.join(" | ");
});

const showOtherSoftwareInput = computed(() => form.software === "other_software");
const showOtherHardwareInput = computed(() => form.hardware === "other_hardware");
const showOtherJaringanInput = computed(() => form.jaringan === "other_jaringan");


watch(deskripsiLanjutComputed, (newValue) => {
    form.deskripsi_lanjut = newValue;
});


const gambarPreview = computed(() => {
    if (form.gambar_konfirmasi instanceof File) {
        return URL.createObjectURL(form.gambar_konfirmasi);
    }
    return gambarLama ? `${gambarLama}` : null;
});

function handleFileUpload(event) {
    const file = event.target.files[0];
    if (file && ["image/png", "image/jpeg", "image/webp"].includes(file.type)) {
        form.gambar_konfirmasi = file;
    } else {
        Notify.failure("Format file tidak valid! Harap unggah PNG, JPEG, atau WebP.");
    }
}

const openImage = (image) => {
    selectedImage.value = image;
    showModal.value = true;
}

const closeModal = () => {
    showModal.value = false;
}

function submit() {

    const formData = new FormData();

    formData.append("_method", "PUT");

    if (form.tindak_lanjut === "other") {
        form.tindak_lanjut = otherCategory.value || "other";
    }
    formData.append("tindak_lanjut", form.tindak_lanjut);

    formData.append("deskripsi_lanjut", form.deskripsi_lanjut);
    form.realisasi = form.realisasi === "Selesai dengan catatan" || form.realisasi === "Tidak dapat dilaksanakan"
        ? `${form.realisasi}: ${form.catatan}`
        : form.realisasi;
    formData.append("realisasi", realisasiComputed.value);

    formData.append("status", form.status);

    if (form.gambar_konfirmasi instanceof File) {
        formData.append("gambar_konfirmasi", form.gambar_konfirmasi);
    } else if (gambarLama) {
        formData.append("gambar_konfirmasi", gambarLama);
    }

    form.post(route("kirim", { id: props.report.id }), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: (response) => {
            Notify.success("Laporan berhasil diperbarui", {
                position: "center-top",
                distance: "70px",
            });
        },
        onError: (errors) => {
            Notify.failure(
                errors[Object.keys(errors)[0]] ??
                "Terjadi kesalahan saat memperbarui laporan",
                {
                    position: "center-top",
                    distance: "70px",
                }
            );
        },
    });
}
</script>

<template>
    <Navbar />

    <div class="container grid mx-auto md:grid-cols-2 my-8 sm:grid-cols-1">

        <div class="w-9/12 mx-auto my-8 min-h-screen">
            <div class="mt-6">
                <button @click="$inertia.get('/riwayat')"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md">
                    Kembali ke Riwayat
                </button>
            </div>
            <p class="text-xl text-center mt-8"><strong>Laporan</strong></p>


            <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-6">
                <div>
                    <p class="text-lg"><strong>Tanggal:</strong> {{ formatDate(report.created_at) }}</p>
                    <p class="text-lg"><strong>Pelapor:</strong>{{ report.user?.name }}</p>
                    <p class="text-lg"><strong>Serial Number:</strong> {{ report.aset }}</p>
                    <p class="text-lg"><strong>Aset:</strong> {{ assetName }}</p>
                    <p class="text-lg"><strong>Laporan Kerusakan:</strong> {{ report.laporan_kerusakan }}</p>
                    <div class="my-4 border border-gray-300 p-4 rounded-md bg-gray-50 text-center">
                        <p class="text-lg text-gray-700"><strong>Deskripsi:</strong> <br>{{ report.deskripsi }}</p>
                    </div>
                </div>

                <div class="mt-2">
                    <p class="text-lg"><strong>Gambar:</strong></p>
                    <div v-if="report.gambar" class="mt-2">
                        <img :src="report.gambar" alt="Gambar Laporan" @click="openImage(report.gambar)"
                            class="cursor-pointer object-cover max-w-50 max-h-80 rounded-md shadow-md mx-auto">
                    </div>
                    <span v-else class="text-gray-500">Tidak ada gambar</span>
                </div>

                <div class="border border-gray-300 p-6 my-8 rounded-lg bg-gray-50">
                    <div class="mb-4 flex justify-between">
                        <p class="text-xl"><strong>Konfirmasi Admin</strong></p>
                        <p class="text-lg"><strong>Status :</strong> {{ report.status }}</p>
                    </div>
                    <div>
                        <p class="text-lg ">
                            <strong>Tindak Lanjut : </strong>
                            {{ report.tindak_lanjut || `Belum dikonfirmasi
                            admin` }}
                        </p>
                        <p class="text-lg ">
                            <strong>Deskrpsi Tindak Lanjut : </strong>
                            {{ report.deskripsi_lanjut || `Belum dikonfirmasi
                            admin` }}
                        </p>
                        <p class="text-lg ">
                            <strong>Realisasi Hasil Pekerjaan : </strong>
                            {{ report.realisasi || `Belum dikonfirmasi
                            admin` }}
                        </p>
                    </div>

                    <div class="mt-2">
                        <p class="text-lg"><strong>Gambar Konfirmasi :</strong></p>
                        <div v-if="report.gambar_konfirmasi" class="mt-2">
                            <img :src="report.gambar_konfirmasi" alt="Gambar Laporan"
                                @click="openImage(report.gambar_konfirmasi)"
                                class="cursor-pointer object-cover max-w-50 max-h-80 rounded-md shadow-md mx-auto">
                        </div>
                        <span v-else class="text-gray-500">Tidak ada gambar</span>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50"
            @click.self="closeModal">
            <div class="relative">
                <img :src="selectedImage" alt="Gambar Diperbesar"
                    class="max-w-full max-h-screen rounded-lg shadow-lg" />
                <button @click="closeModal"
                    class="absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white rounded-full p-2">
                    &times;
                </button>
            </div>
        </div>

        <div class="min-w-fit min-h-screen mx-auto">
            <div class="mx-8 my-5 p-6 shadow-md rounded-md">
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-center">Konfirmasi Admin</h1>
                    <p class="text-md text-center">Tetapkan konfirmasi admin!</p>
                </div>

                <form @submit.prevent="submit" enctype="multipart/form-data">

                    <div class="w-full md:w-2/3 mx-auto px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-sm font-bold mb-2"
                            for="category_name">
                            Tindak Lanjut Pekerjaan
                        </label>
                        <select name="category_name" v-model="form.tindak_lanjut"
                            class="appearance-none block w-full bg-white text-gray-900 font-medium border border-gray-400 rounded-lg py-3 px-3 leading-tight focus:outline-none focus:border-[#98c01d]">
                            <option value="Gangguan Hardware">Gangguan Karena Hardware</option>
                            <option value="Gangguan Software">Gangguan Karena Software</option>
                            <option value="Gangguan Jaringan">Gangguan Karena Jaringan, Jaringan LAN, INTRANET, WIFI,
                                INTERNET
                            </option>
                            <option value="other">Gangguan Lainnya...</option>
                        </select>
                        <div v-if="showOtherInput">
                            <input type="text" placeholder="Masukkan tindak lanjut pekerjaan" v-model="otherCategory"
                                class="appearance-none w-full bg-white text-gray-900 font-medium border border-gray-400 rounded-lg py-3 px-3 leading-tight focus:outline-none focus:border-[#98c01d] mt-3" />
                        </div>
                    </div>

                    <div class="w-full md:w-2/3 mx-auto px-3 mb-6" v-if="form.tindak_lanjut === 'Gangguan Hardware'">
                        <label class="block uppercase tracking-wide text-gray-700 text-sm font-bold mb-2"
                            for="category_name">
                            Gangguan Karena Hardware
                        </label>
                        <select name="category_name" v-model="form.hardware"
                            class="appearance-none block w-full bg-white text-gray-900 font-medium border border-gray-400 rounded-lg py-3 px-3 leading-tight focus:outline-none focus:border-[#98c01d]">
                            <option value="HDD SATA">HDD SATA</option>
                            <option value="SSD SATA">SSD SATA</option>
                            <option value="Mouse USB">Mouse USB</option>
                            <option value="Mouse Wireless">Mouse Wireless</option>
                            <option value="Mobo Combo">Mobo Combo</option>
                            <option value="Fan Processor">Fan Processor</option>
                            <option value="Memory/RAM">Memory/RAM</option>
                            <option value="Power Supply">Power Supply</option>
                            <option value="Monitor">Monitor</option>
                            <option value="Kabel HDMI">Kabel HDMI</option>
                            <option value="Hubswitch">Hubswitch</option>
                            <option value="FO Hubswitch">FO Hubswitch</option>
                            <option value="WIFI Router">WIFI Router</option>
                            <option value="VGA Card">VGA Card</option>
                            <option value="Printer">Printer</option>
                            <option value="WIFI Card">WIFI Card</option>
                            <option value="WIFI USB">WIFI USB</option>
                            <option value="LAN Card">LAN Card</option>
                            <option value="Radio Access Point">Radio Access Point</option>
                            <option value="Kabel Patch Cord">Kabel Patch Cord</option>
                            <option value="Keyboard USB">Keyboard USB</option>
                            <option value="Keyboard Combo">Keyboard Combo</option>
                            <option value="Kabel LAN UTP">Kabel LAN UTP</option>
                            <option value="Kabel Fiber Optic">Kabel Fiber Optic</option>
                            <option value="Konektor RJ45">Konektor RJ45</option>
                            <option value="other_hardware">Lainnya...</option>
                        </select>
                        <input v-if="showOtherHardwareInput" v-model="otherHardware" type="text"
                            placeholder="Masukkan gangguan lain"
                            class="w-full bg-white text-gray-900 border border-gray-400 rounded-lg py-3 px-3 mt-3 focus:outline-none focus:border-[#98c01d]" />
                    </div>

                    <div class="w-full md:w-2/3 mx-auto px-3 mb-6" v-if="form.tindak_lanjut === 'Gangguan Software'">
                        <label class="block uppercase tracking-wide text-gray-700 text-sm font-bold mb-2"
                            for="category_name">
                            Gangguan Karena Software
                        </label>
                        <select name="category_name" v-model="form.software"
                            class="appearance-none block w-full bg-white text-gray-900 font-medium border border-gray-400 rounded-lg py-3 px-3 leading-tight focus:outline-none focus:border-[#98c01d]">
                            <option value="Format/Install HDD">Format/Install HDD</option>
                            <option value="Re-Install Sistem Operasi">Re-Install Sistem Operasi</option>
                            <option value="Re-Install Software">Re-Install Software</option>
                            <option value="Cache dan Bersihkan Virus">Cache dan Bersihkan Virus</option>
                            <option value="other_software">Lainnya...</option>
                        </select>
                        <input v-if="showOtherSoftwareInput" v-model="otherSoftware" type="text"
                            placeholder="Masukkan gangguan lain"
                            class="w-full bg-white text-gray-900 border border-gray-400 rounded-lg py-3 px-3 mt-3 focus:outline-none focus:border-[#98c01d]" />
                    </div>

                    <div class="w-full md:w-2/3 mx-auto px-3 mb-6" v-if="form.tindak_lanjut === 'Gangguan Jaringan'">
                        <label class="block uppercase tracking-wide text-gray-700 text-sm font-bold mb-2"
                            for="category_name">
                            Gangguan Karena Jaringan LAN, INTRANET, WIFI, INTERNET
                        </label>
                        <select name="category_name" v-model="form.jaringan"
                            class="appearance-none block w-full bg-white text-gray-900 font-medium border border-gray-400 rounded-lg py-3 px-3 leading-tight focus:outline-none focus:border-[#98c01d]">
                            <option value="Check dan Perbaikan Kabel LAN yang Putus">Check dan Perbaikan Kabel LAN yang
                                Putus</option>
                            <option value="Check Kabel LAN dari PC ke Hubswitch">Check Kabel LAN dari PC ke Hubswitch
                            </option>
                            <option value="Check Koneksi Jaringan LAN ke Server">Check Koneksi Jaringan LAN ke Server
                            </option>
                            <option value="Check Configuration TCP/IP dan DNS">Check Configuration TCP/IP dan DNS
                            </option>
                            <option value="Check Koneksi Layanan Jaringan Intranet">Check Koneksi Layanan Jaringan
                                Intranet
                            </option>
                            <option value="Check Koneksi Jaringan WIFI Router ke HUB">Check Koneksi Jaringan WIFI Router
                                ke
                                HUB</option>
                            <option value="Check Sinyal WIFI">Check Sinyal WIFI</option>
                            <option value="Check Koneksi Layanan Jaringan ISP Internet">Check Koneksi Layanan Jaringan
                                ISP
                                Internet</option>
                            <option value="other_network">Lainnya...</option>
                        </select>
                        <input v-if="showOtherJaringanInput" v-model="otherJaringan" type="text"
                            placeholder="Masukkan gangguan lain"
                            class="w-full bg-white text-gray-900 border border-gray-400 rounded-lg py-3 px-3 mt-3 focus:outline-none focus:border-[#98c01d]" />
                    </div>

                    <div class="w-full md:w-2/3 mx-auto px-3 mb-6" v-if="form.tindak_lanjut === 'other'">
                        <label class="block uppercase tracking-wide text-gray-700 text-sm font-bold"
                            for="category_name">
                            Gangguan Lainnya
                        </label>
                        <input v-model="otherProblem" type="text" placeholder="Masukkan gangguan lain"
                            class="w-full bg-white text-gray-900 border border-gray-400 rounded-lg py-3 px-3 mt-1 focus:outline-none focus:border-[#98c01d]" />
                    </div>

                    <div class="grid md:grid-cols-2 sm:grid-cols-1 gap-8 mx-12">
                        <!-- Realisasi Hasil Pekerjaan -->
                        <div class="mb-5 p-5 border rounded-lg shadow-md bg-white">
                            <label class="block text-lg font-semibold mb-3">Realisasi Hasil Pekerjaan</label>
                            <div class="space-y-2">
                                <label class="flex items-center gap-2">
                                    <input type="radio" id="selesai" name="realisasi"
                                        value="100% pekerjaan telah selesai" class="form-radio text-green-500"
                                        v-model="form.realisasi">
                                    <span>100% pekerjaan telah selesai</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" id="selesai_catatan" name="realisasi"
                                        value="Selesai dengan catatan" class="form-radio text-blue-500"
                                        v-model="form.realisasi">
                                    <span>Selesai dengan catatan</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" id="tidak_dapat" name="realisasi"
                                        value="Tidak dapat dilaksanakan" class="form-radio text-red-500"
                                        v-model="form.realisasi">
                                    <span>Tidak dapat dilaksanakan</span>
                                </label>
                            </div>
                            <div v-if="form.realisasi === 'Selesai dengan catatan' || form.realisasi === 'Tidak dapat dilaksanakan'"
                                class="mt-4">
                                <label class="block text-sm font-medium">Catatan:</label>
                                <textarea v-model="form.catatan" class="w-full p-2 border rounded-lg"
                                    placeholder="Masukkan catatan..." required></textarea>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mb-5 p-5 border rounded-lg shadow-md bg-white">
                            <label class="block text-lg font-semibold mb-3">Status</label>
                            <div class="space-y-2">
                                <label class="flex items-center gap-2">
                                    <input type="radio" id="Diproses" name="status" value="Diproses"
                                        class="form-radio text-yellow-500" v-model="form.status">
                                    <span>Diproses</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" id="Selesai" name="status" value="Selesai"
                                        class="form-radio text-blue-500" v-model="form.status">
                                    <span>Selesai</span>
                                </label>
                            </div>
                        </div>
                    </div>


                    <div class="w-full px-3 mt-8">
                        <label
                            class="mx-auto cursor-pointer flex w-full max-w-lg flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-400 bg-white p-6 text-center"
                            for="dropzone-file">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-800" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2">
                                <path strokeLinecap="round" strokeLinejoin="round"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>

                            <h2 class="mt-4 text-xl font-medium text-gray-700 tracking-wide">Konfirmasi Admin</h2>
                            <p class="mt-2 text-gray-500 tracking-wide">Unggah atau seret & letakkan file Anda (SVG,
                                PNG, JPG, GIF).</p>

                            <input id="dropzone-file" type="file" class="hidden" name="gambar_konfirmasi"
                                accept="image/png, image/jpeg, image/webp" @change="handleFileUpload" />
                        </label>

                        <!-- Tampilkan preview gambar -->
                        <div v-if="gambarPreview" class="mt-6">
                            <p class="text-gray-700 text-center mb-4">Preview Gambar:</p>
                            <img :src="gambarPreview" alt="Preview Gambar"
                                class="max-w-full h-auto mx-auto rounded-lg shadow-md">
                        </div>
                    </div>

                    <div class="w-full md:w-1/3 mx-auto px-3 mt-8">
                        <button
                            class="appearance-none block w-full bg-blue-700 text-gray-100 font-bold border border-gray-200 rounded-lg py-3 px-3 leading-tight hover:bg-blue-600 focus:outline-none focus:bg-white focus:border-gray-500">
                            Kirim
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <Footer />
</template>
