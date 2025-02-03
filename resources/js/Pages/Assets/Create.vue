<template>
  <Navbar />
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-lg p-6 bg-white rounded-lg shadow-md">
      <h1 class="text-2xl font-bold text-gray-700 mb-6 text-center">Tambah Aset Baru</h1>
      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-600">Nama</label>
          <input v-model="form.name" required class="input-field" placeholder="Masukkan nama aset">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-600">Tipe</label>
          <input v-model="form.type" required class="input-field" placeholder="Masukkan tipe aset">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-600">Serial</label>
          <input v-model="form.series" required class="input-field" placeholder="Masukkan nomor seri">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-600">Tanggal Beli</label>
          <input type="date" v-model="form.tgl_beli" required class="input-field">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-600">Terakhir Service</label>
          <input type="date" v-model="form.last_service" class="input-field">
        </div>
        <div class="flex justify-between mt-6">
          <Link :href="route('assets.index')" class="btn-secondary">Kembali</Link>
          <button type="submit" class="btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
  <Footer />
</template>

<script>
import { Link } from '@inertiajs/vue3'
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';

export default {
  components: { Link, Navbar, Footer },
  data() {
    return {
      form: {
        name: '',
        type: '',
        series: '',
        tgl_beli: '',
        last_service: null
      }
    }
  },
  methods: {
    submit() {
      this.$inertia.post(route('assets.store'), this.form)
    }
  }
}
</script>