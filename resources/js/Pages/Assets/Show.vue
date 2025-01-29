<template>
    <div>
      <h1>Detail Aset</h1>
      <div>
        <p>Nama: {{ asset.name }}</p>
        <p>Tipe: {{ asset.type }}</p>
        <p>Serial: {{ asset.series }}</p>
        <p>Tanggal Beli: {{ asset.tgl_beli }}</p>
        <p>Terakhir Service: {{ asset.last_service || 'Belum pernah service' }}</p>
      </div>
      <Link :href="route('assets.index')">Kembali</Link>
      <Link :href="route('assets.edit', asset.id)">Edit</Link>
    </div>
  </template>
  
  <script>
  import { Link } from '@inertiajs/inertia-vue3'
  
  export default {
    components: { Link },
    props: {
      asset: Object
    }
  }
  </script>