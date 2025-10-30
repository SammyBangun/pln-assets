<script setup>
import { reactive } from 'vue';
import { router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Notify, Confirm } from 'notiflix';
import formatDate from '@/functions/formatDate';

const previousRoles = reactive({});

const props = defineProps({
    user: Array
})

console.log(props.user);

Confirm.init({
    width: "400px",
    borderRadius: "10px",
    titleColor: "#D32F2F",
    okButtonBackground: "#D32F2F",
    okButtonColor: "#FFF",
    cancelButtonBackground: "#757575",
    cancelButtonColor: "#FFF",
    backgroundColor: "#FFF",
    titleFontSize: "18px",
    messageFontSize: "16px",
    cssAnimationStyle: "zoom",
})

const storePreviousRole = (user) => {
    previousRoles[user.id] = user.role;
};

const updateRole = async (user) => {
    router.put(`/users/${user.id}/role`, {
        role: user.role
    }, {
        preserveScroll: true,
        onSuccess: () => {
            Notify.success('Role berhasil diupdate', {
                position: "center-top",
                distance: "70px",
            });
        }
    })
}

const confirmRoleUpdate = (user) => {
    Confirm.show(
        'Konfirmasi Perubahan Role',
        `Apakah Anda yakin ingin mengubah role ${user.name} menjadi "${user.role}"?`,
        'Ya, Ubah',
        'Batal',
        () => {
            updateRole(user);
        },
        () => {
            user.role = previousRoles[user.id] || 'user';
            Notify.info('Perubahan role dibatalkan', {
                position: "center-top",
                distance: "70px",
            });
        }
    );
};
</script>

<template>

    <AuthenticatedLayout>

        <div class="py-6 min-h-screen">
            <div class="flex justify-between">
                <div class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md mr-2 mt-3 w-fit">
                    <Link :href="route('admin.users')">Kembali</Link>
                </div>
            </div>
            <div class="w-2/6 mx-auto px-4 rounded-md shadow-md">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h1 class="text-2xl text-center font-bold mb-4">Detail Pengguna</h1>
                        <p class="text-lg text-gray-600"><strong>NIP:</strong> {{ user.id }}</p>
                        <p class="text-lg text-gray-600"><strong>Nama:</strong> {{ user.name }}</p>
                        <p class="text-lg text-gray-600"><strong>Divisi:</strong> {{ user.division.nama_divisi }}</p>
                        <p class="text-lg text-gray-600"><strong>Email:</strong> {{ user.email }}</p>
                        <p class="text-lg text-gray-600"><strong>Role:</strong> {{ user.role }}</p>
                        <p class="text-lg text-gray-600"><strong>Tanggal Register:</strong> {{
                            formatDate(user.created_at) }}</p>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>

</template>
