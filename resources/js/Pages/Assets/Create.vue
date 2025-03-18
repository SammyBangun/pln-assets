<script setup>
import Navbar from "@/Components/Navbar.vue";
import Footer from "@/Components/Footer.vue";
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";

const gambarPreview = ref(null);

const form = useForm({
  serial_number: "",
  id_user: "",
  name: "",
  type: "",
  series: "",
  gambar: null,
  tgl_beli: "",
  last_service: "",
});

const handleFileUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.gambar = file; // Simpan file ke form
    gambarPreview.value = URL.createObjectURL(file); // Generate URL untuk preview
  }
};

const submit = () => {
  const formData = new FormData();
  formData.append("serial_number", form.serial_number);
  formData.append("id_user", form.id_user);
  formData.append("name", form.name);
  formData.append("type", form.type);
  formData.append("series", form.series);
  formData.append("tgl_beli", form.tgl_beli);
  formData.append("last_service", form.last_service);
  if (form.gambar) {
    formData.append("gambar", form.gambar);
  }

  console.log("Data yang dikirim:", formData);

  form.post(route("assets.store"), {
    forceFormData: true,
  });
};

</script>

<template>
  <Navbar />
  <div class="container mx-auto p-6 w-6/12 border border-gray-200 m-4 rounded">
    <h2 class="text-xl font-bold mb-4">Tambah Asset</h2>

    <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-4">

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-sm font-medium">Serial Number</label>
          <input v-model="form.serial_number" type="text" class="input" required />
        </div>

        <div>
          <label class="block text-sm font-medium">ID User</label>
          <input v-model="form.id_user" type="number" class="input" required />
        </div>
      </div>

      <div class="grid grid-cols-3 gap-3">
        <div>
          <label class="block text-sm font-medium">Nama</label>
          <input v-model="form.name" type="text" class="input" />
        </div>

        <div>
          <label class="block text-sm font-medium">Tipe</label>
          <input v-model="form.type" type="text" class="input" />
        </div>

        <div class="mb-5">
          <label class="block text-sm font-medium">Series</label>
          <input v-model="form.series" type="text" class="input" />
        </div>
      </div>

      <div class="w-full px-3 my-5">
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

          <input id="dropzone-file" type="file" class="hidden" name="gambar" accept="image/png, image/jpeg, image/webp"
            @change="handleFileUpload" />
        </label>

        <!-- Tampilkan preview gambar -->
        <div v-if="gambarPreview" class="mt-4">
          <p class="text-gray-700">Preview Gambar:</p>
          <img :src="gambarPreview" alt="Preview Gambar" class="max-w-full h-auto rounded-lg shadow-md">
        </div>
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-sm font-medium">Tanggal Beli</label>
          <input v-model="form.tgl_beli" type="date" class="input" />
        </div>

        <div class="mb-5">
          <label class="block text-sm font-medium">Last Service</label>
          <input v-model="form.last_service" type="date" class="input" />
        </div>
      </div>

      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah</button>
    </form>
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
