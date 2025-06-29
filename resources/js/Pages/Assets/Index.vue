<script setup>
import { ref, onMounted } from 'vue'
import Navbar from '@/components/Navbar.vue';
import Footer from '@/components/Footer.vue';
import DropdownLink from '@/components/DropdownLink.vue';
import fetchLatestAssets from '@/functions/fetchItem';

const latestAssets = ref([]);

onMounted(async () => {
  latestAssets.value = await fetchLatestAssets();
});
</script>
<template>
  <Navbar />

  <div class="container mx-auto min-h-screen">
    <div class="grid grid-cols-3 gap-4 mt-8 p-8 mx-auto max-w-[40rem]">
      <div>
        <DropdownLink :href="route('Item.Show', { tipe: 'Proyektor' })">Proyektor
        </DropdownLink>
        <DropdownLink :href="route('Item.Show', { tipe: 'Keyboard' })">Keyboard
        </DropdownLink>
        <DropdownLink :href="route('Item.Show', { tipe: 'Kamera' })">Kamera
        </DropdownLink>
        <DropdownLink :href="route('Item.Show', { tipe: 'Printer' })">Printer
        </DropdownLink>
        <DropdownLink :href="route('Item.Show', { tipe: 'PC' })">PC</DropdownLink>
      </div>
      <div>
        <DropdownLink :href="route('Item.Show', { tipe: 'Monitor' })">Monitor
        </DropdownLink>
        <DropdownLink :href="route('Item.Show', { tipe: 'Switch' })">Switch
        </DropdownLink>
        <DropdownLink :href="route('Item.Show', { tipe: 'Mouse' })">Mouse
        </DropdownLink>
        <DropdownLink :href="route('Item.Show', { tipe: 'Audio' })">Audio
        </DropdownLink>
        <DropdownLink :href="route('Item.Show', { tipe: 'Hub' })">Hub</DropdownLink>
      </div>
      <div>
        <DropdownLink :href="route('Item.Show', { tipe: 'Access Point' })">Access
          Point</DropdownLink>
        <DropdownLink :href="route('Item.Show', { tipe: 'Laptop' })">Laptop
        </DropdownLink>
        <DropdownLink :href="route('Item.Show', { tipe: 'Router' })">Router
        </DropdownLink>
        <DropdownLink :href="route('Item.Show', { tipe: 'TV' })">TV</DropdownLink>
        <DropdownLink :href="route('Item.Show', { tipe: 'DLL' })">DLL</DropdownLink>
      </div>
    </div>
    <div class="mx-auto w-4/6">
      <h1 class="text-xl text-center font-bold mb-3">Aset Terbaru</h1>

      <p v-if="latestAssets.length === 0" class="text-center text-gray-500">Memuat
        data...</p>

      <div v-else class="grid sm:grid-cols-1 md:grid-cols-2 gap-4 justify-center">
        <Link v-for="asset in latestAssets" :key="asset.id_asset" :href="`/item/${asset.tipe}/${asset.serial_number}`"
          class="flex items-center mx-auto bg-white rounded-lg shadow-md p-4 cursor-pointer hover:bg-gray-100 transition duration-200">

        <img :src="`/storage/assets/${asset.gambar}`" alt="Asset Image"
          class="w-24 h-24 object-cover rounded-md border border-gray-200">

        <div class="flex flex-col ml-4">
          <p class="font-semibold text-gray-800 text-lg">{{ asset.nama }}</p>
          <p class="text-sm text-gray-500">{{ asset.seri }}</p>
        </div>
        </Link>
      </div>

    </div>
  </div>

  <Footer />
</template>
