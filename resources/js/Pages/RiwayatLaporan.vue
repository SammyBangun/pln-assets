<template>
    <Navbar />
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold text-center mb-6">Data</h1>
        <div class="overflow-x-auto shadow-md rounded-lg">
            <table class="min-w-full divide-y divide-gray-300 bg-white text-sm text-gray-800">
                <thead>
                    <tr class="bg-blue-600 text-white text-left">
                        <th class="py-4 px-6 font-semibold">#</th>
                        <th class="py-4 px-6 font-semibold">Nama</th>
                        <th class="py-4 px-6 font-semibold">Email</th>
                        <th class="py-4 px-6 font-semibold">Role</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="(item, index) in data" :key="index" class="hover:bg-blue-50 transition-colors">
                        <td class="py-4 px-6">{{ index + 1 }}</td>
                        <td class="py-4 px-6">{{ item.name }}</td>
                        <td class="py-4 px-6">{{ item.email }}</td>
                        <td class="py-4 px-6">{{ item.role }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <Footer />
</template>

<script>
import Navbar from "../Components/Navbar.vue";
import Footer from "../Components/Footer.vue";

export default {
    name: "RiwayatLaporan",
    components: { Navbar, Footer },
    data() {
        return {
            data: [
                { name: "John Doe", email: "john@example.com", role: "Admin" },
                { name: "Jane Smith", email: "jane@example.com", role: "Editor" },
                { name: "Samuel Green", email: "samuel@example.com", role: "Viewer" },
                { name: "Alice Johnson", email: "alice@example.com", role: "Contributor" },
                { name: "James Brown", email: "jamersson@example.com", role: "Contributor" },
            ],
        };
    },
    methods: {
        createReport() {
            alert("Membuat laporan baru!");
        },
    },
};
</script>