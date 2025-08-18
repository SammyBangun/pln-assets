<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
    icon: {
        type: String,
        default: '',
    }
});
</script>

<template>
    <Link :href="href"
        class="flex items-center justify-center text-center bg-gray-100 w-full h-12 px-4 text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-300 focus:bg-gray-300 rounded-lg focus:outline-none">

    <i v-if="icon" :class="[icon, 'mr-2']"></i>


    <slot />
    </Link>
</template>
