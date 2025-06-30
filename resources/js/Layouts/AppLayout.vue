<!-- <script setup>
import { ref, provide } from 'vue'
import SideNav from '@/components/SideNav.vue'
import Navbar from '@/components/Navbar.vue'
import Footer from '@/components/Footer.vue'

const isExpanded = ref(false);
provide('isExpanded', isExpanded)
provide('toggleSidebar', () => {
    isExpanded.value = !isExpanded.value
})
</script>

<template>
    <Navbar />

    <div class="relative min-h-screen">

        <SideNav />


        <div class="flex flex-col min-h-screen relative z-10">
            <main class="flex-1 p-4 bg-gray-100">
                <slot />
            </main>
        </div>
    </div>
    <Footer />

</template> -->
