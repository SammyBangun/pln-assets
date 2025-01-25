<template>
    <div>
      <h1>Edit Aset</h1>
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
        <button type="submit">Update</button>
        <Link :href="route('assets.index')">Kembali</Link>
      </form>
    </div>
  </template>
  
  <script>
  import { Link } from '@inertiajs/inertia-vue3'
  
  export default {
    components: { Link },
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