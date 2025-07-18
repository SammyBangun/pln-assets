<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/components/InputError.vue';
import InputLabel from '@/components/InputLabel.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';
import TextInput from '@/components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Notiflix from 'notiflix';

defineProps({
    divisions: Array
});

const form = useForm({
    id: '',
    divisi: '',
    lokasi: '',
    name: '',
    email: '',
    password: '',
    // password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password'),
        onError: () => {
            Notiflix.Notify.failure(`Pendaftaran user gagal`, {
                position: 'center-top',
                distance: '70px',
            });
        },
        onSuccess: () => {
            Notiflix.Notify.success(`Pendaftaran user telah berhasil`, {
                position: 'center-top',
                distance: '70px',
            });
        },
    });
};
</script>

<template>
    <GuestLayout>

        <Head title="Register" />

        <form @submit.prevent="submit">

            <div class="mt-4 w-8/12 mx-auto">
                <InputLabel for="id" value="NIP" />

                <TextInput id="id" type="text" class="mt-1 block w-full" v-model="form.id" required autofocus
                    autocomplete="id" />

                <InputError class="mt-2" :message="form.errors.id" />
            </div>

            <div class="mt-4 w-8/12 mx-auto">
                <InputLabel for="name" value="Nama" />

                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required
                    autocomplete="name" />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>


            <div class="mt-4 w-8/12 mx-auto">
                <InputLabel for="divisi" value="Divisi" />

                <select id="divisi" v-model="form.divisi" class="mt-1 block w-full rounded border-gray-300">
                    <option v-for="division in divisions" :key="division.id" :value="division.id">
                        {{ division.nama_divisi }}
                    </option>
                </select>

                <InputError class="mt-2" :message="form.errors.divisi" />
            </div>

            <div class="mt-4 w-8/12 mx-auto">
                <InputLabel for="lokasi" value="Lokasi" />

                <textarea id="lokasi"
                    class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                    v-model="form.lokasi" rows="2" required autocomplete="lokasi"></textarea>

                <InputError class="mt-2" :message="form.errors.lokasi" />
            </div>

            <div class="mt-4 w-8/12 mx-auto">
                <InputLabel for="email" value="Email" />

                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                    autocomplete="username" />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4 w-8/12 mx-auto">
                <InputLabel for="password" value="Password" />

                <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required
                    autocomplete="new-password" />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <!-- <div class="mt-4 w-8/12 mx-auto mb-8">
                <InputLabel for="password_confirmation" value="Konfirmasi Password" />

                <TextInput id="password_confirmation" type="password" class="mt-1 block w-full"
                    v-model="form.password_confirmation" required autocomplete="new-password" />

                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div> -->

            <div class="mt-4 flex items-center justify-end">
                <!-- <Link :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Sudah Punya Akun?
                </Link> -->

                <Link :href="route('admin.dashboard')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Kembali
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Buat
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
