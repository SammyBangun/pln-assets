<script setup>
import { ref, onMounted, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import fetchLatestAssets from '@/functions/fetchItem';
import ApplicationLogo from '@/components/ApplicationLogo.vue';
import Dropdown from '@/components/Dropdown.vue';
import DropdownLink from '@/components/DropdownLink.vue';
import NavLink from '@/components/NavLink.vue';
import ResponsiveNavLink from '@/components/ResponsiveNavLink.vue';

const showingNavigationDropdown = ref(false);
const latestAssets = ref([]);
const page = usePage();
const auth = computed(() => page.props.auth);

const toggleDropdown = () => {
    showingNavigationDropdown.value = !showingNavigationDropdown.value;
};

onMounted(async () => {
    latestAssets.value = await fetchLatestAssets();
});
</script>

<template>
    <nav class="border-b border-gray-100 bg-yellow-400 sticky top-0 w-full">
        <!-- Primary Navigation Menu -->
        <div class="mx-auto max-w-7xl px-4 md:px-6 lg:px-8">
            <div class="flex h-16 justify-between">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex shrink-0 items-center w-48">
                        <Link :href="auth.user?.role === 'admin' ? '/admin/dashboard' : '/dashboard'">
                        <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
                        </Link>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <template v-if="auth.user.role === 'user'">
                            <NavLink href="/dashboard" :active="route().current('dashboard')">
                                Dashboard
                            </NavLink>
                            <NavLink href="/riwayat" :active="route().current()?.startsWith('riwayat')">
                                Riwayat
                            </NavLink>
                        </template>
                        <template v-if="auth.user.role === 'admin'">
                            <NavLink href="/admin/dashboard" :active="route().current('admin.dashboard')">
                                Dashboard
                            </NavLink>
                            <!-- Tambah Aset dipindahkan ke luar grid -->
                            <NavLink :href="route('assets.create')" :active="route().current('assets.create')">
                                Tambah Aset
                            </NavLink>
                        </template>
                        <!-- ini perubahannya di dalam assets -->
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button
                                    class="flex items-center rounded-md px-3 py-2 my-4 text-sm font-medium leading-4 text-gray-700 hover:bg-gray-200">
                                    Aset IT
                                    <svg class="ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                    </svg>
                                </button>
                            </template>

                            <template #content>
                                <div class="w-[42rem] bg-white shadow-lg rounded-lg p-4 mx-auto">
                                    <div class="grid grid-cols-3">
                                        <div>
                                            <DropdownLink :href="route('Item.Show', { type: 'Proyektor' })">Proyektor
                                            </DropdownLink>
                                            <DropdownLink :href="route('Item.Show', { type: 'Keyboard' })">Keyboard
                                            </DropdownLink>
                                            <DropdownLink :href="route('Item.Show', { type: 'Kamera' })">Kamera
                                            </DropdownLink>
                                            <DropdownLink :href="route('Item.Show', { type: 'Printer' })">Printer
                                            </DropdownLink>
                                            <DropdownLink :href="route('Item.Show', { type: 'PC' })">PC</DropdownLink>
                                        </div>
                                        <div>
                                            <DropdownLink :href="route('Item.Show', { type: 'Monitor' })">Monitor
                                            </DropdownLink>
                                            <DropdownLink :href="route('Item.Show', { type: 'Switch' })">Switch
                                            </DropdownLink>
                                            <DropdownLink :href="route('Item.Show', { type: 'Mouse' })">Mouse
                                            </DropdownLink>
                                            <DropdownLink :href="route('Item.Show', { type: 'Audio' })">Audio
                                            </DropdownLink>
                                            <DropdownLink :href="route('Item.Show', { type: 'Hub' })">Hub</DropdownLink>
                                        </div>
                                        <div>
                                            <DropdownLink :href="route('Item.Show', { type: 'Access Point' })">Access
                                                Point</DropdownLink>
                                            <DropdownLink :href="route('Item.Show', { type: 'Laptop' })">Laptop
                                            </DropdownLink>
                                            <DropdownLink :href="route('Item.Show', { type: 'Router' })">Router
                                            </DropdownLink>
                                            <DropdownLink :href="route('Item.Show', { type: 'TV' })">TV</DropdownLink>
                                            <DropdownLink :href="route('Item.Show', { type: 'DLL' })">DLL</DropdownLink>
                                        </div>
                                    </div>

                                    <!-- Aset Terbaru -->
                                    <div class="mt-6 w-[40rem] mx-auto">
                                        <h1 class="text-xl font-bold mb-3">Aset Terbaru</h1>

                                        <p v-if="latestAssets.length === 0" class="text-center text-gray-500">Memuat
                                            data...</p>

                                        <div v-else class="grid sm:grid-cols-1 md:grid-cols-2 gap-4 justify-center">
                                            <Link v-for="asset in latestAssets" :key="asset.id_asset"
                                                :href="`/item/${asset.type}/${asset.serial_number}`"
                                                class="flex items-center mx-auto bg-white rounded-lg shadow-md p-4 cursor-pointer hover:bg-gray-100 transition duration-200">

                                            <img :src="`/storage/assets/${asset.gambar}`" alt="Asset Image"
                                                class="w-24 h-24 object-cover rounded-md border border-gray-200">

                                            <div class="flex flex-col ml-4">
                                                <p class="font-semibold text-gray-800 text-lg">{{ asset.name }}</p>
                                                <p class="text-sm text-gray-500">{{ asset.series }}</p>
                                            </div>
                                            </Link>
                                        </div>

                                    </div>
                                </div>
                            </template>

                        </Dropdown>
                    </div>
                </div>

                <div class="hidden md:ms-6 md:flex md:items-center">
                    <template v-if="auth.user.role === 'admin'">
                        <div class=" mr-8">
                            <NavLink href="/admin/users" :active="route().current('admin.users')">
                                Manajemen Pengguna
                            </NavLink>
                        </div>
                    </template>
                    <!-- Settings Dropdown -->
                    <Dropdown align="right" width="32">
                        <template #trigger>
                            <button
                                class="flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 hover:text-gray-700 focus:outline-none">
                                {{ auth.user.name }}
                                <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                </svg>
                            </button>
                        </template>

                        <template #content>
                            <div class="mt-4">
                                <DropdownLink href="/profile">Profile</DropdownLink>
                                <DropdownLink href="/logout" method="post" as="button">
                                    Log Out
                                </DropdownLink>
                            </div>
                        </template>
                    </Dropdown>
                </div>

                <!-- Hamburger -->
                <div class="-me-2 flex items-center md:hidden">
                    <button @click="toggleDropdown"
                        class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path v-show="!showingNavigationDropdown" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path v-show="showingNavigationDropdown" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div class="relative">
            <transition name="fade">
                <div v-show="showingNavigationDropdown"
                    class="md:hidden absolute top-full left-0 w-full bg-yellow-400 z-50">
                    <div class="space-y-1 pb-3 pt-2">
                        <template v-if="auth.user.role === 'user'">
                            <ResponsiveNavLink href="/dashboard" :active="route().current('dashboard')">
                                Dashboard
                            </ResponsiveNavLink>
                            <ResponsiveNavLink href="/riwayat" :active="route().current()?.startsWith('riwayat')">
                                Riwayat
                            </ResponsiveNavLink>
                        </template>
                        <template v-if="auth.user.role === 'admin'">
                            <ResponsiveNavLink href="/admin/dashboard" :active="route().current('admin.dashboard')">
                                Dashboard
                            </ResponsiveNavLink>
                            <!-- Tambah Aset dipindahkan ke luar grid -->
                            <ResponsiveNavLink :href="route('assets.create')"
                                :active="route().current('assets.create')">
                                Tambah Aset
                            </ResponsiveNavLink>
                        </template>
                        <ResponsiveNavLink href="/assets" :active="route().current('assets')">
                            Aset IT
                        </ResponsiveNavLink>
                    </div>
                    <!-- Responsive Settings Options -->
                    <div class="border-t border-gray-200 pb-1 pt-4">
                        <div class="px-4">
                            <div class="text-base font-medium text-gray-800">{{ auth.user.name }}</div>
                            <div class="text-sm font-medium text-gray-500">{{ auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink href="/profile">Profile</ResponsiveNavLink>
                            <ResponsiveNavLink href="/logout" method="post" as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </transition>
        </div>

    </nav>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
