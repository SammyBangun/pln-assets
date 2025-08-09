<script setup>
import { ref } from 'vue'
const isOpen = ref(false)
</script>

<template>
    <div>
        <!-- Mobile Toggle Button -->
        <button
            class="md:hidden flex items-center gap-2 p-2 m-4 bg-yellow-400 text-white rounded shadow hover:bg-yellow-500"
            @click="isOpen = !isOpen">
            <i class="fas fa-bars"></i> Menu
        </button>

        <!-- Mobile SideNav -->
        <transition name="slide">
            <nav v-if="isOpen" class="md:hidden bg-white border border-gray-200 rounded mx-4 p-4 mb-4 shadow-lg">
                <ul class="space-y-3">
                    <li>
                        <Link :href="route('assets.index')"
                            class="flex items-center gap-2 p-2 bg-blue-100 rounded-md hover:bg-yellow-300 transition">
                        <i class="fas fa-desktop text-blue-600"></i> Aset IT
                        </Link>
                    </li>
                    <li>
                        <Link :href="route('admin.lookup.asset-types')"
                            class="flex items-center gap-2 p-2 bg-blue-100 rounded-md hover:bg-yellow-300 transition">
                        <i class="fas fa-tags text-green-600"></i> Tipe Aset
                        </Link>
                    </li>
                    <li>
                        <Link :href="route('admin.lookup.identifications')"
                            class="flex items-center gap-2 p-2 bg-blue-100 rounded-md hover:bg-yellow-300 transition">
                        <i class="fas fa-exclamation-triangle text-red-500"></i> Identifikasi Masalah
                        </Link>
                    </li>
                    <li>
                        <Link :href="route('admin.lookup.divisions')"
                            class="flex items-center gap-2 p-2 bg-blue-100 rounded-md hover:bg-yellow-300 transition">
                        <i class="fas fa-sitemap text-purple-500"></i> Divisi
                        </Link>
                    </li>
                    <li>
                        <Link :href="route('admin.lookup.disruptions')"
                            class="flex items-center gap-2 p-2 bg-blue-100 rounded-md hover:bg-yellow-300 transition">
                        <i class="fas fa-bolt text-yellow-500"></i> Gangguan
                        </Link>
                    </li>
                    <li>
                        <Link :href="route('admin.lookup.detail-disruptions')"
                            class="flex items-center gap-2 p-2 bg-blue-100 rounded-md hover:bg-yellow-300 transition">
                        <i class="fas fa-info-circle text-blue-500"></i> Detail Gangguan
                        </Link>
                    </li>
                    <li>
                        <Link :href="route('admin.lookup.deliverables')"
                            class="flex items-center gap-2 p-2 bg-blue-100 rounded-md hover:bg-yellow-300 transition">
                        <i class="fas fa-check-circle text-green-500"></i> Realisasi
                        </Link>
                    </li>
                </ul>
            </nav>
        </transition>

        <!-- Desktop SideNav -->
        <nav class="hidden md:block bg-yellow-400 text-white w-56 h-full p-4 shadow-lg">
            <h2 class="text-xl font-bold text-center mb-4">Menu</h2>
            <ul class="space-y-3">
                <li>
                    <Link :href="route('assets.index')"
                        class="flex items-center gap-2 p-2 rounded-md hover:bg-yellow-500 transition">
                    <i class="fas fa-desktop"></i> Aset IT
                    </Link>
                </li>
                <li>
                    <Link :href="route('admin.lookup.asset-types')"
                        class="flex items-center gap-2 p-2 rounded-md hover:bg-yellow-500 transition">
                    <i class="fas fa-tags"></i> Tipe Aset
                    </Link>
                </li>
                <li>
                    <Link :href="route('admin.lookup.identifications')"
                        class="flex items-center gap-2 p-2 rounded-md hover:bg-yellow-500 transition">
                    <i class="fas fa-exclamation-triangle"></i> Identifikasi Masalah
                    </Link>
                </li>
                <li>
                    <Link :href="route('admin.lookup.divisions')"
                        class="flex items-center gap-2 p-2 rounded-md hover:bg-yellow-500 transition">
                    <i class="fas fa-sitemap"></i> Divisi
                    </Link>
                </li>
                <li>
                    <Link :href="route('admin.lookup.disruptions')"
                        class="flex items-center gap-2 p-2 rounded-md hover:bg-yellow-500 transition">
                    <i class="fas fa-bolt"></i> Gangguan
                    </Link>
                </li>
                <li>
                    <Link :href="route('admin.lookup.detail-disruptions')"
                        class="flex items-center gap-2 p-2 rounded-md hover:bg-yellow-500 transition">
                    <i class="fas fa-info-circle"></i> Detail Gangguan
                    </Link>
                </li>
                <li>
                    <Link :href="route('admin.lookup.deliverables')"
                        class="flex items-center gap-2 p-2 rounded-md hover:bg-yellow-500 transition">
                    <i class="fas fa-check-circle"></i> Realisasi
                    </Link>
                </li>
            </ul>
        </nav>
    </div>
</template>

<style scoped>
.slide-enter-active,
.slide-leave-active {
    transition: all 0.3s ease;
}

.slide-enter-from {
    opacity: 0;
    transform: translateX(-20px);
}

.slide-leave-to {
    opacity: 0;
    transform: translateX(-20px);
}
</style>
