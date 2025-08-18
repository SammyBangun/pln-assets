<script setup>
import { ref } from 'vue'
import SideNavLink from './SideNavLink.vue';

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
        <nav class="hidden md:block bg-yellow-400 text-white w-56 h-full p-4 pt-6 shadow-lg">
            <ul class="space-y-3">
                <li>
                    <SideNavLink :href="route('assets.index')" :active="route().current('assets.index')">
                        <i class="fas fa-desktop pr-5"></i> Aset IT
                    </SideNavLink>
                </li>
                <li>
                    <SideNavLink :href="route('admin.lookup.asset-types')"
                        :active="route().current('admin.lookup.asset-types')">
                        <i class="fas fa-tags pr-5"></i> Tipe Aset
                    </SideNavLink>
                </li>
                <li>
                    <SideNavLink :href="route('admin.lookup.identifications')"
                        :active="route().current('admin.lookup.identifications')">
                        <i class="fas fa-exclamation-triangle pr-5"></i> Identifikasi Masalah
                    </SideNavLink>
                </li>
                <li>
                    <SideNavLink :href="route('admin.lookup.divisions')"
                        :active="route().current('admin.lookup.divisions')">
                        <i class="fas fa-sitemap pr-5"></i> Divisi
                    </SideNavLink>
                </li>
                <li>
                    <SideNavLink :href="route('admin.lookup.disruptions')"
                        :active="route().current('admin.lookup.disruptions')">
                        <i class="fas fa-bolt pr-5"></i> Gangguan
                    </SideNavLink>
                </li>
                <li>
                    <SideNavLink :href="route('admin.lookup.detail-disruptions')"
                        :active="route().current('admin.lookup.detail-disruptions')">
                        <i class="fas fa-info-circle pr-5"></i> Detail Gangguan
                    </SideNavLink>
                </li>
                <li>
                    <SideNavLink :href="route('admin.lookup.deliverables')"
                        :active="route().current('admin.lookup.deliverables')">
                        <i class="fas fa-check-circle pr-5"></i> Realisasi
                    </SideNavLink>
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
