<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import Navbar from "@/components/Navbar.vue";
import Footer from "@/components/Footer.vue";
import { Notify } from "notiflix";
import { computed } from "vue";
import { router } from '@inertiajs/vue3';

const props = defineProps({
  item: {
    type: Object,
    required: true,
    validator: (value) => {
      return [
        "serial_number",
        "divisi",
        "nama",
        "tipe",
        "seri",
        "tanggal_beli",
        "terakhir_servis",
        "gambar",
        "lokasi",
        "status_aset",
      ].every((key) => key in value);
    },
  },
  divisions: Array
});

const form = useForm({
  serial_number: props.item.serial_number,
  divisi: props.item.divisi,
  nama: props.item.nama,
  tipe: props.item.tipe,
  seri: props.item.seri,
  tanggal_beli: props.item.tanggal_beli,
  terakhir_servis: props.item.terakhir_servis,
  gambar: null, // Awalnya null
  lokasi: props.item.lokasi,
  status_aset: props.item.status_aset,
});

// Variabel untuk menyimpan gambar lama
const gambarLama = props.item.gambar;

function submit() {
  const formData = new FormData();
  formData.append("_method", "PUT");
  formData.append("old_serial_number", props.item.serial_number); // Simpan serial_number lama
  formData.append("serial_number", form.serial_number); // Serial baru yang diedit
  formData.append("divisi", form.divisi);
  formData.append("nama", form.nama);
  formData.append("tipe", form.tipe);
  formData.append("seri", form.seri);
  formData.append("lokasi", form.lokasi);
  formData.append("status_aset", form.status_aset);
  formData.append("tanggal_beli", form.tanggal_beli);
  formData.append("terakhir_servis", form.terakhir_servis);

  if (form.gambar instanceof File) {
    formData.append("gambar", form.gambar);
  } else if (gambarLama) {
    formData.append("gambar", gambarLama); // Kirim path gambar lama jika tidak diubah
  }

  form.post(route("Item.Update", { serial_number: props.item.serial_number }), {
    preserveScroll: true,
    forceFormData: true,
    onSuccess: () => {
      Notify.success("Laporan berhasil diperbarui", {
        position: "center-top",
        distance: "70px",
      });
      router.get(route("Item.Show", { tipe: props.item.tipe }));
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

// Menentukan preview gambar
const gambarPreview = computed(() => {
  if (form.gambar instanceof File) {
    return URL.createObjectURL(form.gambar);
  }
  return gambarLama ? `/storage/assets/${gambarLama}` : null;
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
  <Navbar />
  <div class="container mx-auto my-8 min-h-screen">
    <div class="min-h-screen mx-auto py-8 px-4 sm:px-6 lg:px-8 max-w-3xl bg-white shadow-lg rounded-lg p-6">
      <h1 class="text-2xl font-bold text-center mb-6">Edit Aset</h1>
      <form @submit.prevent="submit" class="space-y-6" enctype="multipart/form-data">

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label for="serial_number" class="block text-sm font-medium">
              Serial Number
            </label>
            <div class="mt-1">
              <input type="text" id="serial_number" v-model="form.serial_number" class="input" required>
            </div>
            <div v-if="form.errors.serial_number" class="text-red-500 text-sm mt-1">
              {{ form.errors.serial_number }}
            </div>
          </div>

          <div>
            <label for="divisi" class="block text-sm font-medium text-gray-700">
              Divisi
            </label>
            <div class="mt-1">
              <select v-model="form.divisi" class="input">
                <option v-for="division in divisions" :key="division.id" :value="division.id">
                  {{ division.nama_divisi }}
                </option>
              </select>
            </div>
            <div v-if="form.errors.divisi" class="text-red-500 text-sm mt-1">
              {{ form.errors.divisi }}
            </div>
          </div>
        </div>

        <div class="grid grid-cols-3 gap-3">
          <div>
            <label for="nama" class="block text-sm font-medium text-gray-700">
              Nama Aset
            </label>
            <div class="mt-1">
              <input type="text" id="nama" v-model="form.nama" class="input" required />
            </div>
            <div v-if="form.errors.nama" class="text-red-500 text-sm mt-1">
              {{ form.errors.nama }}
            </div>
          </div>

          <div>
            <label for="tipe" class="block text-sm font-medium text-gray-700">
              Tipe
            </label>
            <div class="mt-1">
              <select v-model="form.tipe" class="input">
                <option value="Proyektor">Proyektor</option>
                <option value="Monitor">Monitor</option>
                <option value="Access Point">Access Point</option>
                <option value="Keyboard">Keyboard</option>
                <option value="Switch">Switch</option>
                <option value="Laptop">Laptop</option>
                <option value="Kamera">Kamera</option>
                <option value="Mouse">Mouse</option>
                <option value="Router">Router</option>
                <option value="Printer">Printer</option>
                <option value="Audio">Audio</option>
                <option value="TV">TV</option>
                <option value="PC">PC</option>
                <option value="Hub">Hub</option>
                <option value="DLL">DLL</option>
              </select>
            </div>
            <div v-if="form.errors.tipe" class="text-red-500 text-sm mt-1">
              {{ form.errors.tipe }}
            </div>
          </div>

          <div class="mb-6">
            <label for="seri" class="block text-sm font-medium text-gray-700">
              Series
            </label>
            <div class="mt-1">
              <input type="text" id="seri" v-model="form.seri" class="input" required />
            </div>
            <div v-if="form.errors.seri" class="text-red-500 text-sm mt-1">
              {{ form.errors.seri }}
            </div>
          </div>
        </div>

        <div>
          <label
            class="mx-auto cursor-pointer flex w-full max-w-lg flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-400 bg-white p-6 text-center"
            for="dropzone-file">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-800" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" strokeWidth="2">
              <path strokeLinecap="round" strokeLinejoin="round"
                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
            </svg>

            <h2 class="mt-4 text-xl font-medium text-gray-700 tracking-wide">Gambar Aset</h2>
            <p class="mt-2 text-gray-500 tracking-wide">Unggah atau seret & letakkan file Anda (SVG,
              PNG, JPG, GIF).</p>

            <input id="dropzone-file" type="file" class="hidden" name="gambar"
              accept="image/png, image/jpeg, image/webp" @change="handleFileUpload" />
          </label>
          <div v-if="form.errors.gambar" class="text-red-500 text-sm mt-1">
            {{ form.errors.gambar }}
          </div>
          <div v-if="gambarPreview" class="mt-4 mx-auto">
            <p class="text-gray-700">Preview Gambar:</p>
            <img :src="gambarPreview" alt="Preview Gambar"
              class="max-w-full h-auto mx-auto text-center rounded-lg shadow-md">
          </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label for="lokasi" class="block text-sm font-medium text-gray-700">
              Lokasi
            </label>
            <div class="mt-1">
              <input type="input" id="lokasi" v-model="form.lokasi" class="input" required />
            </div>
            <div v-if="form.errors.lokasi" class="text-red-500 text-sm mt-1">
              {{ form.errors.lokasi }}
            </div>
          </div>

          <div>
            <label for="status_aset" class="block text-sm font-medium text-gray-700">
              Status
            </label>
            <div class="mt-1">
              <select v-model="form.status_aset" class="input">
                <option value="Aktif">Aktif</option>
                <option value="Dalam Penanganan">Dalam Penanganan</option>
                <option value="Hilang">Hilang</option>
              </select>
            </div>
            <div v-if="form.errors.status_aset" class="text-red-500 text-sm mt-1">
              {{ form.errors.status_aset }}
            </div>
          </div>

        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label for="tanggal_beli" class="block text-sm font-medium text-gray-700">
              Tanggal Beli
            </label>
            <div class="mt-1">
              <input type="date" id="tanggal_beli" v-model="form.tanggal_beli" class="input" required />
            </div>
            <div v-if="form.errors.tanggal_beli" class="text-red-500 text-sm mt-1">
              {{ form.errors.tanggal_beli }}
            </div>
          </div>

          <div>
            <label for="terakhir_servis" class="block text-sm font-medium text-gray-700">
              Terakhir Servis
            </label>
            <div class="mt-1">
              <input type="date" id="terakhir_servis" v-model="form.terakhir_servis" class="input" />
            </div>
            <div v-if="form.errors.terakhir_servis" class="text-red-500 text-sm mt-1">
              {{ form.errors.terakhir_servis }}
            </div>
          </div>
        </div>

        <div class="flex justify-between">
          <Link :href="route('Item.Show', { tipe: form.tipe })"
            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md mr-2">
          Kembali
          </Link>
          <div class="flex space-x-3">
            <Link :href="route('Item.Show', { tipe: form.tipe })"
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
      <div v-if="form.errors.error" class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        {{ form.errors.error }}
      </div>
    </div>
  </div>
  <Footer />
</template>

<style scoped>
.input {
  width: 100%;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
}
</style>
