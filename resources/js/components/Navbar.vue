<template>
    <nav class="flex items-center justify-between bg-yellow-500 text-white p-4 h-16">
        <div class="text-xl font-bold">
            <img src="../../../public/img/logopln.png" width="200" alt="">
        </div>
        <ul class="flex space-x-6 mr-32">
            <li>
                <router-link to="/" class="hover:text-gray-200 font-semibold">Form Laporan</router-link>
            </li>
            <li>
                <router-link to="/riwayat" class="hover:text-gray-200 font-semibold">Riwayat Laporan</router-link>
            </li>
        </ul>
        <div class="relative">
            <!-- Trigger untuk dropdown -->
            <div @click="toggleDropdown"
                class="flex items-center space-x-2 cursor-pointer mr-14 border-2 border-white rounded-full px-4 py-2">
                <span class="font-medium">{{ $page.props.auth.user.name }}</span>
            </div>

            <!-- Dropdown menu -->
            <div v-if="isDropdownOpen" class="absolute right-0 mt-2 w-40 bg-white text-black rounded shadow-lg">
                <ul>
                    <li>
                        <router-link to="/profile" class="block px-4 py-2 hover:bg-gray-100">
                            Profile
                        </router-link>
                    </li>
                    <li>
                        <router-link to="/settings" class="block px-4 py-2 hover:bg-gray-100">
                            Settings
                        </router-link>
                    </li>
                    <li>
                        <button @click="logout" class="block w-full text-left px-4 py-2 hover:bg-gray-100">
                            Logout
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
export default {
    name: 'Navbar',
    data() {
        return {
            isDropdownOpen: false,
            username: "Nama User",
        };
    },
    methods: {
        toggleDropdown() {
            this.isDropdownOpen = !this.isDropdownOpen;
        },
        logout() {
            this.$inertia.post('/logout');
            this.isDropdownOpen = false;
            this.$page.props.auth.user = null;
            this.$page.props.auth.role = null;
            this.$page.props.auth.name = null;
        },
    },
};
</script>

<style scoped>
/* Opsional: tambahkan transisi untuk dropdown */
</style>
