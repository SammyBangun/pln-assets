<template>
    <nav class="flex items-center justify-between bg-blue-500 text-white p-4">
        <div class="text-xl font-bold">PT. PLN NUSANTARA</div>
        <ul class="flex space-x-6">
            <li>
                <router-link to="/" class="hover:text-gray-200 font-semibold">Form Laporan</router-link>
            </li>
            <li>
                <router-link to="/riwayat" class="hover:text-gray-200 font-semibold">Riwayat Laporan</router-link>
            </li>
        </ul>
        <div class="flex items-center space-x-3">
            <span class="font-medium">Nama User</span>
            <div class="w-8 h-8 bg-gray-300 rounded-full"></div>
        </div>
    </nav>
</template>

<script>
export default {
    name: 'Navbar',
};
</script>