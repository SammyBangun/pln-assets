<template>
  <Navbar />
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Daftar Aset</h1>
    <a :href="route('assets.create')"
      class=" btn mb-4 rounded bg-blue-500 hover:bg-blue-600 px-4 py-2 text-white">Tambah
      Aset
      Baru</a>

    <table class="min-w-full mt-10 bg-white border border-gray-200">
      <thead>
        <tr class="bg-gray-100">
          <th class="border border-gray-300 px-4 py-2">Nama</th>
          <th class="border border-gray-300 px-4 py-2">Tipe</th>
          <th class="border border-gray-300 px-4 py-2">Serial</th>
          <th class="border border-gray-300 px-4 py-2">Tanggal Beli</th>
          <th class="border border-gray-300 px-4 py-2">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="asset in assets" :key="asset.id_asset" class="hover:bg-gray-50">
          <td class="border border-gray-300 px-4 py-2">{{ asset.name }}</td>
          <td class="border border-gray-300 px-4 py-2">{{ asset.type }}</td>
          <td class="border border-gray-300 px-4 py-2">{{ asset.series }}</td>
          <td class="border border-gray-300 px-4 py-2">{{ asset.tgl_beli }}</td>
          <td class="border border-gray-300 px-4 py-2">
            <a :href="route('assets.show', asset.id_asset)" class="text-blue-600 hover:underline mr-2">Detail</a>
            <a :href="route('assets.edit', asset.id_asset)" class="text-green-600 hover:underline mr-2">Edit</a>
            <button @click="destroy(asset.id_asset)" class="text-red-600 hover:underline">Hapus</button>
          </td>
        </tr>
      </tbody>
    </table>
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
    assets: Array
  },
  methods: {
    destroy(id) {
      this.$inertia.delete(route('assets.destroy', id))
    }
  }
}
</script>