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
                    <Link :href="route('admin.lookup.divisions')" class="block text-gray-700 hover:text-blue-600">
                    Divisi
                    </Link>
                    <Link :href="route('admin.lookup.operators')" class="block text-gray-700 hover:text-blue-600">
                    Petugas
                    </Link>
                </ul>
            </nav>
        </transition>

        <!-- Desktop SideNav -->
        <nav class="hidden md:block bg-white border-r border-gray-200 w-48 h-screen p-4">
            <h2 class="text-xl font-bold mb-6">Menu</h2>
            <ul class="space-y-3">
                <Link :href="route('admin.lookup.divisions')" class="block text-gray-700 hover:text-blue-600">
                Divisi
                </Link>
                <Link :href="route('admin.lookup.operators')" class="block text-gray-700 hover:text-blue-600">
                Petugas
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
