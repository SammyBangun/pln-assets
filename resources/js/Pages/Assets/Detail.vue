<script setup>
import { ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import formatDate from '@/functions/formatDate';

const props = defineProps({
  item: Object,
});

console.log(props.item);

const showModal = ref(false);
const selectedImage = ref('');

const openImage = (image) => {
  selectedImage.value = image;
  showModal.value = true;
}

const closeModal = () => {
  showModal.value = false;
}
</script>


<template>

  <AuthenticatedLayout>

    <div class="my-20 flex items-center justify-center">
      <div class="w-full max-w-3xl p-4 bg-white border border-gray-200 rounded-xl shadow-xl">
        <h1 class="text-2xl font-bold text-gray-800 mb-8 text-center">Detail Aset</h1>

        <div class="grid md:grid-cols-2 sm:grid-cols-1 gap-4">
          <!-- Gambar -->
          <div class="w-64 h-64 mb-4 mx-auto overflow-hidden rounded-lg" v-if="item.gambar">
            <img :src="`/storage/assets/${item.gambar}`" alt="gambar item"
              @click="openImage(`/storage/assets/${item.gambar}`)"
              class="w-full h-full object-cover rounded-lg shadow-md cursor-pointer">
          </div>
          <!-- Detail Aset -->
          <div class="w-full">
            <p class="text-gray-700 text-lg"><span class="font-semibold">Serial Number:</span> {{ item.serial_number }}
            </p>
            <p class="text-gray-700 text-lg"><span class="font-semibold">Diserahkan ke Divisi:</span> {{
              item.division.nama_divisi }}</p>
            <p class="text-gray-700 text-lg"><span class="font-semibold">Nama:</span> {{ item.nama }}</p>
            <p class="text-gray-700 text-lg"><span class="font-semibold">Tipe:</span> {{ item.tipe.tipe }}</p>
            <p class="text-gray-700 text-lg"><span class="font-semibold">Serial:</span> {{ item.seri }}</p>
            <p class="text-gray-700 text-lg"><span class="font-semibold">Tanggal Beli:</span> {{
              formatDate(item.tanggal_beli)
            }}</p>
            <p class="text-gray-700 text-lg">
              <span class="font-semibold">Terakhir Servis:</span>
              {{ formatDate(item.terakhir_servis) || 'Belum pernah service' }}
            </p>
          </div>
        </div>
        <div class="flex flex-col items-center">
          <div class="mt-6 flex gap-4">
            <button @click="$inertia.get('/item/' + item.tipe.tipe)"
              class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
              Kembali ke List
            </button>
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
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>

  </AuthenticatedLayout>

</template>
