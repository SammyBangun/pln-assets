<template>
  <Navbar />
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
    <Link :href="route('assets.edit', asset.id_asset)">Edit</Link>
  </div>
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
  }
}
</script>