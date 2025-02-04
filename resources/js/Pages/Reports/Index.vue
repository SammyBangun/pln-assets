<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

defineProps({
    reports: Array
});

const form = useForm({
    laporan_kerusakan: '',
    deskripsi: ''
});

const submit = () => {
    form.post('/reports', {
        onSuccess: () => form.reset()
    });
};
</script>

<template>
    <div>
        <h1>Laporan Kerusakan</h1>
        <form @submit.prevent="submit">
            <input v-model="form.laporan_kerusakan" placeholder="Judul Laporan" required />
            <textarea v-model="form.deskripsi" placeholder="Deskripsi" required></textarea>
            <button type="submit">Submit</button>
        </form>

        <ul>
            <li v-for="report in reports" :key="report.id">
                <p><strong>{{ report.laporan_kerusakan }}</strong></p>
                <p>{{ report.deskripsi }}</p>
                <p><i>Oleh: {{ report.user?.name }}</i></p>
            </li>
        </ul>
    </div>
</template>
