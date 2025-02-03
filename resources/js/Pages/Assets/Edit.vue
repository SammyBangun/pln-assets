<template>
  <Navbar />
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-lg p-6 bg-white rounded-lg shadow-md">
      <h1 class="text-2xl font-bold text-gray-700 mb-6 text-center">Edit Aset</h1>
      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-600">Nama</label>
          <input v-model="form.name" required>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-600">Tipe</label>
          <input v-model="form.type" required>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-600">Serial</label>
          <input v-model="form.series" required>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-600">Tanggal Beli</label>
          <input type="date" v-model="form.tgl_beli" required>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-600">Terakhir Service</label>
          <input type="date" v-model="form.last_service">
        </div>
        <div class="flex justify-between mt-6">
          <Link :href="route('assets.index')">Kembali</Link>
          <button type="submit">Update</button>
        </div>
      </form>
    </div>
  </div>
  <div class="mt-48"></div>
  <Footer />
</template>

<script>
import { Link } from '@inertiajs/vue3'
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';

export default {
  components: { Link, Navbar, Footer },
  props: {
    asset: Object
  },
  data() {
    return {
      form: { ...this.asset }
    }
  },
  methods: {
    submit() {
      this.$inertia.put(route('assets.update', this.asset.id), this.form)
    }
  }
}
</script>