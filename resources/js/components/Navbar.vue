<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

const showingNavigationDropdown = ref(false);

const toggleDropdown = () => {
    showingNavigationDropdown.value = !showingNavigationDropdown.value;
};
</script>

<template>
    <nav class="border-b border-gray-100 bg-yellow-400">
        <!-- Primary Navigation Menu -->
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 justify-between">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex shrink-0 items-center">
                        <Link href="/dashboard">
                        <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
                        </Link>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <NavLink href="/dashboard" :active="route().current('dashboard')">
                            Form Laporan
                        </NavLink>
                        <NavLink href="/riwayat" :active="route().current()?.startsWith('riwayat')">
                            Riwayat Laporan
                        </NavLink>
                        <!-- ini perubahannya di dalam assets -->
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button
                                    class="flex items-center rounded-md px-3 py-2 my-4 text-sm font-medium leading-4 text-gray-700 hover:bg-gray-200">
                                    Assets
                                    <svg class="ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                    </svg>
                                </button>
                            </template>

                            <template #content>
                                <div class="grid grid-cols-3 w-[48rem] bg-white shadow-lg rounded-lg p-4">
                                    <div>
                                        <DropdownLink href="/assets/proyektor">Proyektor</DropdownLink>
                                        <DropdownLink href="/assets/keyboard">Keyboard</DropdownLink>
                                        <DropdownLink href="/assets/kamera">Kamera</DropdownLink>
                                        <DropdownLink href="/assets/printer">Printer</DropdownLink>
                                        <DropdownLink href="/assets/pc">PC</DropdownLink>
                                    </div>
                                    <div>
                                        <DropdownLink href="/assets/proyektor">Switch/Hub</DropdownLink>
                                        <DropdownLink href="/assets/keyboard">Monitor</DropdownLink>
                                        <DropdownLink href="/assets/kamera">Mouse</DropdownLink>
                                        <DropdownLink href="/assets/printer">Audio</DropdownLink>
                                        <DropdownLink href="/assets/pc">DLL</DropdownLink>
                                    </div>
                                    <div>
                                        <DropdownLink href="/assets/proyektor">Access Point</DropdownLink>
                                        <DropdownLink href="/assets/keyboard">Laptop</DropdownLink>
                                        <DropdownLink href="/assets/kamera">Router</DropdownLink>
                                        <DropdownLink href="/assets/printer">TV</DropdownLink>
                                        <DropdownLink href="/assets/pc">➡️</DropdownLink>
                                    </div>
                                    <div class="mt-5">
                                        <h1 class="text-lg font-bold">Asset Terbaru</h1>
                                        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Porro molestiae
                                            inventore exercitationem enim. Porro architecto vitae tenetur rem assumenda
                                            in saepe quod nihil numquam delectus ducimus vero, omnis molestiae ut.</p>
                                    </div>
                                </div>
                            </template>

                        </Dropdown>

                    </div>
                </div>

                <div class="hidden sm:ms-6 sm:flex sm:items-center">
                    <!-- Settings Dropdown -->
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button
                                class="flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 hover:text-gray-700 focus:outline-none">
                                {{ $page.props.auth.user.name }}
                                <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                </svg>
                            </button>
                        </template>

                        <template #content>
                            <DropdownLink href="/profile">Profile</DropdownLink>
                            <DropdownLink href="/logout" method="post" as="button">
                                Log Out
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </div>

                <!-- Hamburger -->
                <div class="-me-2 flex items-center sm:hidden">
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
        <div v-show="showingNavigationDropdown" class="sm:hidden">
            <div class="space-y-1 pb-3 pt-2">
                <ResponsiveNavLink href="/dashboard" :active="route().current('dashboard')">
                    Form Laporan
                </ResponsiveNavLink>
                <ResponsiveNavLink href="/riwayat" :active="route().current()?.startsWith('riwayat')">
                    Riwayat Laporan
                </ResponsiveNavLink>
                <template v-if="$page.props.auth.user.role === 'admin'">
                    <ResponsiveNavLink href="/assets" :active="route().current()?.startsWith('assets')">
                        Assets
                    </ResponsiveNavLink>
                </template>
            </div>
            <!-- Responsive Settings Options -->
            <div class="border-t border-gray-200 pb-1 pt-4">
                <div class="px-4">
                    <div class="text-base font-medium text-gray-800">{{ $page.props.auth.user.name }}</div>
                    <div class="text-sm font-medium text-gray-500">{{ $page.props.auth.user.email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <ResponsiveNavLink href="/profile">Profile</ResponsiveNavLink>
                    <ResponsiveNavLink href="/logout" method="post" as="button">
                        Log Out
                    </ResponsiveNavLink>
                </div>
            </div>
        </div>
    </nav>
</template>
