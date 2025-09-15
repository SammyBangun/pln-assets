<script setup>
import Index from '../Reports/Index.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    reports: Array,
    users: Array
})

console.log(props.users);
</script>

<template>

    <AuthenticatedLayout>
        <Index :reports="reports" :users="users" />
    </AuthenticatedLayout>

</template>