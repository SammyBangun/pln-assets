<script setup>
import { defineProps } from 'vue';
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';

const props = defineProps({
    items: Array,
    type: String
});
</script>

<template>
    <Navbar />
    <div class="max-w-4xl mx-auto mt-10 bg-white shadow-lg rounded-lg p-6 min-h-screen">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ type }} List</h1>
        <div class="overflow-x-auto">
            <table v-if="items.length > 0" class="w-full border border-gray-200 rounded-lg shadow-md">
                <thead>
                    <tr class="bg-gray-800 text-white">
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Series</th>
                        <th class="px-4 py-2">Purchase Date</th>
                        <th class="px-4 py-2">Last Service</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in items" :key="item.id_asset" class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="px-4 py-2 text-center">{{ item.serial_number }}</td>
                        <td class="px-4 py-2">{{ item.name }}</td>
                        <td class="px-4 py-2">{{ item.series }}</td>
                        <td class="px-4 py-2 text-center">{{ item.tgl_beli }}</td>
                        <td class="px-4 py-2 text-center">{{ item.last_service }}</td>
                    </tr>
                </tbody>
            </table>
            <p v-else class="text-center text-gray-600 text-lg font-semibold py-6">Tidak ada item</p>
        </div>
    </div>
    <Footer />
</template>
