<template>
    <div>
      <h1>Tambah Aset Baru</h1>
      <form @submit.prevent="submit">
        <div>
          <label>Nama</label>
          <input v-model="form.name" required>
        </div>
        <div>
          <label>Tipe</label>
          <input v-model="form.type" required>
        </div>
        <div>
          <label>Serial</label>
          <input v-model="form.series" required>
        </div>
        <div>
          <label>Tanggal Beli</label>
          <input type="date" v-model="form.tgl_beli" required>
        </div>
        <div>
          <label>Terakhir Service</label>
          <input type="date" v-model="form.last_service">
        </div>
        <button type="submit">Simpan</button>
        <Link :href="route('assets.index')">Kembali</Link>
      </form>
    </div>
  </template>
  
  <script>
  import { Link } from '@inertiajs/inertia-vue3'
  
  export default {
    components: { Link },
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