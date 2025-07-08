<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';

</script>

<template>
    <AuthenticatedLayout>
        <div class="bg-cover bg-center min-h-screen flex flex-col items-center justify-center text-center">
            <div class="bg-white p-8 rounded-lg shadow-lg w-3/4 md:w-1/2">
                <h1 class="text-3xl font-bold mb-4">Form Pelaporan</h1>
                <p class="text-gray-600 mb-6">Tekan tombol di bawah untuk membuat pelaporan</p>
                <!-- Menggunakan Inertia Link untuk navigasi -->
                <Link href="/form" class="bg-yellow-400 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                + Pelaporan
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
