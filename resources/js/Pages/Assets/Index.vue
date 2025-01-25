<template>
    <div>
      <h1>Daftar Aset</h1>
      <Link :href="route('assets.create')" class="btn">Tambah Aset Baru</Link>
      
      <table>
        <thead>
          <tr>
            <th>Nama</th>
            <th>Tipe</th>
            <th>Serial</th>
            <th>Tanggal Beli</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="asset in assets" :key="asset.id">
            <td>{{ asset.name }}</td>
            <td>{{ asset.type }}</td>
            <td>{{ asset.series }}</td>
            <td>{{ asset.tgl_beli }}</td>
            <td>
              <Link :href="route('assets.show', asset.id)">Detail</Link>
              <Link :href="route('assets.edit', asset.id)">Edit</Link>
              <button @click="destroy(asset.id)">Hapus</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import { Link } from '@inertiajs/inertia-vue3'
  
  export default {
    components: { Link },
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