<script setup>
import Index from '../Reports/Index.vue';

const props = defineProps({
    reports: Array
})
</script>

<template>

    <Navbar />
    <Index :reports="reports" />

</template>