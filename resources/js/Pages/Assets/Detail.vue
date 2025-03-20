<script>
import { Link } from '@inertiajs/vue3'
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';

export default {
  components: { Link, Navbar, Footer },
  props: {
    item: Object
  }
}
</script>

<template>
  <Navbar />
  <div>
    <h1>Detail Aset</h1>
    <div>
      <p>Nama: {{ item.name }}</p>
      <p>Tipe: {{ item.type }}</p>
      <p>Serial: {{ item.series }}</p>
      <p>Tanggal Beli: {{ item.tgl_beli }}</p>
      <p>Terakhir Service: {{ item.last_service || 'Belum pernah service' }}</p>
    </div>
    <!-- <Link :href="route('assets.index')">Kembali</Link>
    <Link :href="route('assets.edit', asset.id_asset)">Edit</Link> -->
  </div>
  <Footer />
</template>
