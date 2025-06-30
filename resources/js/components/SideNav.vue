<!-- <script setup>
import { inject } from 'vue'

const isExpanded = inject('isExpanded')
const toggleSidebar = inject('toggleSidebar')
</script>

<template>
    <div v-show="isExpanded" class="fixed inset-0 bg-black bg-opacity-50 z-40" @click="toggleSidebar"></div>

    <aside :class="[
        'fixed top-0 left-0 h-full bg-yellow-500 text-white z-50 transition-transform duration-300 ease-in-out',
        isExpanded ? 'translate-x-0 w-64' : '-translate-x-full'
    ]">
        <div class="p-4 font-bold border-b border-white">
            SISPA
            <button @click="toggleSidebar" class="absolute top-4 right-4 text-white">
                ✕
            </button>
        </div>

        <ul class="p-4 space-y-4">
            <li>Dashboard</li>
            <li>Assets</li>
  
        </ul>
    </aside>
</template> -->
