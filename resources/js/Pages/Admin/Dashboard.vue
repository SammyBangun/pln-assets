<script setup>
import Index from '../Reports/Index.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    reports: Array
})
</script>

<template>

    <AdminLayout>
        <Index :reports="reports" />
    </AdminLayout>

</template>