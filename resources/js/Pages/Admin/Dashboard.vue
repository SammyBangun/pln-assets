<script setup>
import Index from '../Reports/Index.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    reports: Array
})
</script>

<template>

    <AuthenticatedLayout>
        <Index :reports="reports" />
    </AuthenticatedLayout>

</template>