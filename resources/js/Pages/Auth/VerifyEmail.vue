<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout>

        <Head title="Email Verification" />

        <div class="mb-4 text-sm text-gray-600">
            Terima kasih telah mendaftar! Sebelum memulai, bisakah Anda memverifikasi
            alamat email dengan mengeklik tautan yang baru saja kami kirimkan melalui email kepada Anda? Jika kamu
            tidak menerima email, dengan senang hati kami akan mengirimkan email lainnya.
        </div>

        <div class="mb-4 text-sm font-medium text-green-600" v-if="verificationLinkSent">
            Tautan verifikasi baru telah dikirimkan ke alamat email Anda
            diberikan pada saat pendaftaran.
        </div>

        <form @submit.prevent="submit">
            <div class="mt-4 flex items-center justify-between">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Kirim ulang email verifikasi
                </PrimaryButton>

                <Link :href="route('logout')" method="post" as="button"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Log Out</Link>
            </div>
        </form>
    </GuestLayout>
</template>
