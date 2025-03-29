<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import Navbar from '@/Components/Navbar.vue'
import Footer from '@/Components/Footer.vue'

const showModal = ref(false);
const selectedImage = ref('');

const openImage = (image) => {
  selectedImage.value = image;
  showModal.value = true;
}

const closeModal = () => {
  showModal.value = false;
}

defineProps({
  item: Object
})
</script>


<template>
  <Navbar />
  <div class="min-h-screen flex items-center justify-center bg-gray-100 p-6">
    <div class="w-full max-w-lg p-8 bg-white border border-gray-200 rounded-xl shadow-xl">
      <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Detail Aset</h1>

      <div class="flex flex-col items-center">
        <!-- Gambar -->
        <div class="w-64 h-64 mb-4 overflow-hidden rounded-lg" v-if="item.gambar">
          <img :src="`/storage/assets/${item.gambar}`" alt="gambar item"
            @click="openImage(`/storage/assets/${item.gambar}`)"
            class="w-full h-full object-cover rounded-lg shadow-md cursor-pointer">
        </div>

        <!-- Detail Aset -->
        <div class="w-full">
          <p class="text-gray-700 text-lg"><span class="font-semibold">Serial Number:</span> {{ item.serial_number }}
          </p>
          <p class="text-gray-700 text-lg"><span class="font-semibold">Nama:</span> {{ item.name }}</p>
          <p class="text-gray-700 text-lg"><span class="font-semibold">Tipe:</span> {{ item.type }}</p>
          <p class="text-gray-700 text-lg"><span class="font-semibold">Serial:</span> {{ item.series }}</p>
          <p class="text-gray-700 text-lg"><span class="font-semibold">Tanggal Beli:</span> {{ item.tgl_beli }}</p>
          <p class="text-gray-700 text-lg">
            <span class="font-semibold">Terakhir Service:</span>
            {{ item.last_service || 'Belum pernah service' }}
          </p>
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-6 flex gap-4">
          <button @click="$inertia.get('/item/' + item.type)"
            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
            Kembali ke List
          </button>
          <!-- <button @click="$inertia.get('/riwayat')"
            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
            Kembali ke Riwayat
          </button> -->
        </div>
      </div>
    </div>
  </div>

  <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50"
    @click.self="closeModal">
    <div class="relative">
      <img :src="selectedImage" alt="Gambar Diperbesar" class="max-w-full max-h-screen rounded-lg shadow-lg" />
      <button @click="closeModal"
        class="absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white rounded-full p-2">
        &times;
      </button>
    </div>
  </div>

  <Footer />
</template>
