<script setup>
import { ref } from 'vue'
const isOpen = ref(false)
</script>

<template>
    <div>
        <!-- Mobile Toggle Button -->
        <button class="md:hidden p-2 m-4 bg-gray-100 rounded" @click="isOpen = !isOpen">
            ☰ Menu
        </button>

        <transition name="fade">
            <!-- Mobile SideNav -->
            <nav v-if="isOpen" class="md:hidden bg-white border border-gray-200 rounded mx-4 p-4 mb-4 shadow">
                <ul class="space-y-3">
                    <Link :href="route('riwayat.index')"
                        class="block bg-gray-300 rounded-md p-2 text-gray-700 hover:text-blue-600">
                    Aset IT
                    </Link>
                    <Link :href="route('riwayat.index')"
                        class="block bg-gray-300 rounded-md p-2 text-gray-700 hover:text-blue-600">
                    Tipe Aset
                    </Link>
                    <Link :href="route('riwayat.index')"
                        class="block bg-gray-300 rounded-md p-2 text-gray-700 hover:text-blue-600">
                    Identifikasi Masalah
                    </Link>
                    <Link :href="route('admin.lookup.divisions')"
                        class="block bg-gray-300 rounded-md p-2 text-gray-700 hover:text-blue-600">
                    Divisi
                    </Link>
                    <Link :href="route('riwayat.index')"
                        class="block bg-gray-300 rounded-md p-2 text-gray-700 hover:text-blue-600">
                    Gangguan
                    </Link>
                    <Link :href="route('riwayat.index')"
                        class="block bg-gray-300 rounded-md p-2 text-gray-700 hover:text-blue-600">
                    Detail Gangguan
                    </Link>
                    <Link :href="route('riwayat.index')"
                        class="block bg-gray-300 rounded-md p-2 text-gray-700 hover:text-blue-600">
                    Realisasi
                    </Link>
                </ul>
            </nav>
        </transition>

        <!-- Desktop SideNav -->
        <nav class="hidden md:block bg-gray-100 border-r border-gray-200 w-48 h-full p-4">
            <h2 class="text-xl font-bold text-center">Menu</h2>
            <div class="border-b border-gray-200 mb-4"></div>
            <ul class="space-y-3">
                <Link :href="route('riwayat.index')"
                    class="block bg-gray-300 rounded-md p-2 text-gray-700 hover:text-blue-600">
                Aset IT
                </Link>
                <Link :href="route('riwayat.index')"
                    class="block bg-gray-300 rounded-md p-2 text-gray-700 hover:text-blue-600">
                Tipe Aset
                </Link>
                <Link :href="route('riwayat.index')"
                    class="block bg-gray-300 rounded-md p-2 text-gray-700 hover:text-blue-600">
                Identifikasi Masalah
                </Link>
                <Link :href="route('admin.lookup.divisions')"
                    class="block bg-gray-300 rounded-md p-2 text-gray-700 hover:text-blue-600">
                Divisi
                </Link>
                <Link :href="route('riwayat.index')"
                    class="block bg-gray-300 rounded-md p-2 text-gray-700 hover:text-blue-600">
                Gangguan
                </Link>
                <Link :href="route('riwayat.index')"
                    class="block bg-gray-300 rounded-md p-2 text-gray-700 hover:text-blue-600">
                Detail Gangguan
                </Link>
                <Link :href="route('riwayat.index')"
                    class="block bg-gray-300 rounded-md p-2 text-gray-700 hover:text-blue-600">
                Realisasi
                </Link>
            </ul>
        </nav>
    </div>
</template>

<style scoped>
/* Simple fade animation for mobile */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
