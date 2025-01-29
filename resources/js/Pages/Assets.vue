<template>
    <div class="assets-layout">
        <!-- Navbar -->
        <Navbar />

        <!-- Main Content -->
        <main class="container mx-auto py-6 px-4">
            <h1 class="text-2xl font-bold mb-4">Manajemen Aset</h1>

            <!-- Description Section -->
            <p class="mb-6 text-gray-700">
                Ini adalah halaman manajemen aset. Di sini Anda dapat melihat, menambah, atau mengelola semua aset Anda.
            </p>

            <!-- Table of Assets -->
            <div class="overflow-x-auto bg-white rounded shadow-md">
                <table class="min-w-full border-collapse border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-4 border border-gray-300 text-left">ID</th>
                            <th class="py-2 px-4 border border-gray-300 text-left">Name</th>
                            <th class="py-2 px-4 border border-gray-300 text-left">Category</th>
                            <th class="py-2 px-4 border border-gray-300 text-left">Status</th>
                            <th class="py-2 px-4 border border-gray-300 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="asset in assets" :key="asset.id" class="hover:bg-gray-50">
                            <td class="py-2 px-4 border border-gray-300">{{ asset.id }}</td>
                            <td class="py-2 px-4 border border-gray-300">{{ asset.name }}</td>
                            <td class="py-2 px-4 border border-gray-300">{{ asset.category }}</td>
                            <td class="py-2 px-4 border border-gray-300">{{ asset.status }}</td>
                            <td class="py-2 px-4 border border-gray-300">
                                <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Edit</button>
                                <button
                                    class="bg-red-500 text-white px-3 py-1 rounded ml-2 hover:bg-red-600">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>

        <!-- Footer -->
        <Footer />
    </div>
</template>

<script>
import Navbar from '../Components/Navbar.vue';
import Footer from '../Components/Footer.vue';

export default {
    name: 'Assets',
    components: {
        Navbar,
        Footer,
    },
    data() {
        return {
            assets: [
                { id: 1, name: 'Laptop', category: 'Electronics', status: 'Available' },
                { id: 2, name: 'Office Chair', category: 'Furniture', status: 'In Use' },
                { id: 3, name: 'Projector', category: 'Electronics', status: 'Available' },
                { id: 4, name: 'Conference Table', category: 'Furniture', status: 'In Use' },
            ],
        };
    },
};
</script>

<style scoped>
.assets-layout {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

main {
    flex-grow: 1;
}
</style>
