<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { Notify } from 'notiflix';

defineProps({
  divisions: Array,
  asset_types: Array
});

const gambarPreview = ref(null);

const form = useForm({
  serial_number: "",
  id_divisi: "",
  nama: "",
  tipe: "",
  seri: "",
  gambar: null,
  tanggal_beli: "",
  terakhir_servis: "",
  lokasi: "",
});

const handleFileUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.gambar = file;
    gambarPreview.value = URL.createObjectURL(file);
  }
};

const submit = () => {
  if (!form.gambar) {
    Notify.failure("Gambar harus diunggah terlebih dahulu.", {
      position: 'center-top',
      distance: '70px',
    });
    return;
  }
  const formData = new FormData();
  formData.append("serial_number", form.serial_number);
  formData.append("id_divisi", form.id_divisi);
  formData.append("name", form.name);
  formData.append("tipe", form.tipe);
  formData.append("seri", form.seri);
  formData.append("tanggal_beli", form.tgl_beli);
  formData.append("terkahir_servis", form.terakhir_servis);
  formData.append("lokasi", form.lokasi);
  if (form.gambar) {
    formData.append("gambar", form.gambar);
  }
  form.post(route("assets.store"), {
    forceFormData: true,
    onSuccess: () => {
      Notify.success('Aset berhasil tersimpan', {
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
      Notify.error('Gagal menyimpan aset. Silakan coba lagi nanti.', {
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
    <div class="container mx-auto p-6 md:w-6/12 sm:w-8/12 border border-gray-200 m-4 rounded">
      <h2 class="text-xl font-bold mb-4">Tambah Aset</h2>

      <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-4">

        <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-3">
          <div>
            <label class="block text-sm font-medium">Serial Number</label>
            <input v-model="form.serial_number" type="text" class="input" required />
          </div>

          <div>
            <label class="block text-sm font-medium">Divisi</label>
            <select v-model="form.id_divisi" class="input">
              <option v-for="division in divisions" :key="division.id" :value="division.id">
                {{ division.nama_divisi }}
              </option>
            </select>
          </div>

        </div>

        <div class="grid sm:grid-cols-1 lg:grid-cols-3 gap-3">
          <div>
            <label class="block text-sm font-medium">Tipe</label>
            <select v-model="form.tipe" name="tipe"
              class="appearance-none block w-full bg-white text-gray-900 font-medium border border-gray-400 rounded-lg py-3 px-3 leading-tight focus:outline-none focus:border-[#98c01d]">
              <option v-for="aset in asset_types" :key="aset.id" :value="aset.id">
                {{ aset.tipe }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium">Nama</label>
            <input v-model="form.nama" type="text" class="input" />
          </div>

          <div>
            <label class="block text-sm font-medium">Seri</label>
            <input v-model="form.seri" type="text" class="input" />
          </div>
        </div>

        <div class="w-full px-3 py-5">
          <label
            class="mx-auto cursor-pointer flex w-full max-w-lg flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-400 bg-white p-6 text-center"
            for="dropzone-file" required>
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

          <div v-if="gambarPreview" class="mt-4 mx-auto">
            <p class="text-gray-700">Preview Gambar:</p>
            <img :src="gambarPreview" alt="Preview Gambar"
              class="max-w-full h-auto mx-auto text-center rounded-lg shadow-md">
          </div>
        </div>

        <div class="w-8/12 mx-auto grid sm:grid-cols-1 md:grid-cols-1 gap-3">
          <div>
            <label class="block text-sm font-medium">Lokasi</label>
            <textarea v-model="form.lokasi" class="input"></textarea>
          </div>
        </div>

        <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-3">
          <div>
            <label class="block text-sm font-medium">Tanggal Beli</label>
            <input v-model="form.tanggal_beli" type="date" class="input" />
          </div>

          <div class="mb-5">
            <label class="block text-sm font-medium">Terakhir Servis</label>
            <input v-model="form.terakhir_servis" type="date" class="input" />
          </div>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah</button>
      </form>
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
