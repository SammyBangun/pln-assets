<template>
    <Navbar />
    <div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Data dari Database</h1>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-gray-100 border border-gray-300 text-sm text-gray-800">
            <thead>
                <tr class="bg-gray-300 text-gray-900">
                    <th class="py-3 border-b border-gray-400 text-left">#</th>
                    <th class="py-3 border-b border-gray-400 text-left">Nama</th>
                    <th class="py-3 border-b border-gray-400 text-left">Email</th>
                    <th class="py-3 border-b border-gray-400 text-left">Role</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in data" :key="index" class="hover:bg-gray-200">
                    <td class="py-3 border-b border-gray-300">{{ index + 1 }}</td>
                    <td class="py-3 border-b border-gray-300">{{ item.name }}</td>
                    <td class="py-3 border-b border-gray-300">{{ item.email }}</td>
                    <td class="py-3 border-b border-gray-300">{{ item.role }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

    <Footer />
</template>

<script>
import Navbar from '../components/Navbar.vue';
import Footer from '../components/Footer.vue';

export default {
    name: 'RiwayatLaporan',
    components: { Navbar, Footer },
    methods: {
        createReport() {
            alert('Membuat laporan baru!');
        },
    },
};
</script>