<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";

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

const submit = () => {
  form.post(route("assets.store"));
};
</script>

<template>
  <div class="container mx-auto p-6">
    <h2 class="text-xl font-bold mb-4">Tambah Asset</h2>

    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <label class="block text-sm font-medium">Serial Number</label>
        <input v-model="form.serial_number" type="text" class="input" required />
      </div>

      <div>
        <label class="block text-sm font-medium">ID User</label>
        <input v-model="form.id_user" type="number" class="input" required />
      </div>

      <div>
        <label class="block text-sm font-medium">Nama</label>
        <input v-model="form.name" type="text" class="input" />
      </div>

      <div>
        <label class="block text-sm font-medium">Tipe</label>
        <input v-model="form.type" type="text" class="input" />
      </div>

      <div>
        <label class="block text-sm font-medium">Series</label>
        <input v-model="form.series" type="text" class="input" />
      </div>

      <div>
        <label class="block text-sm font-medium">Gambar</label>
        <input type="file" @change="e => form.gambar = e.target.files[0]" />
      </div>

      <div>
        <label class="block text-sm font-medium">Tanggal Beli</label>
        <input v-model="form.tgl_beli" type="date" class="input" />
      </div>

      <div>
        <label class="block text-sm font-medium">Last Service</label>
        <input v-model="form.last_service" type="date" class="input" />
      </div>

      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah</button>
    </form>
  </div>
</template>

<style scoped>
.input {
  width: 100%;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
}
</style>
