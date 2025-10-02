<script setup>
import { reactive } from 'vue';
import { router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Notiflix from "notiflix";
import formatDate from '@/functions/formatDate';
import { useForm } from '@inertiajs/vue3';

const form = useForm();

const previousRoles = reactive({});

const props = defineProps({
  users: Array
})

console.log(props.users);

Notiflix.Confirm.init({
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
  Notiflix.Confirm.show(
    'Konfirmasi Perubahan Role',
    `Apakah Anda yakin ingin mengubah role ${user.name} menjadi "${user.role}"?`,
    'Ya, Ubah',
    'Batal',
    () => {
      updateRole(user);
    },
    () => {
      user.role = previousRoles[user.id] || 'user';
      Notiflix.Notify.info('Perubahan role dibatalkan', {
        position: "center-top",
        distance: "70px",
      });
    }
  );
};

const deleteUser = async (user) => {
  Notiflix.Confirm.show(
    'Hapus Pengguna',
    'Apakah Anda yakin ingin menghapus pengguna ini?',
    'Ya',
    'Tidak',
    () => {
      form.delete(`/users/${user.id}`, {
        onSuccess: () => {
          Notiflix.Notify.success('Pengguna berhasil dihapus', {
            position: 'center-top',
            distance: '70px',
          });
        },
        onError: () => {
          Notiflix.Notify.failure('Terjadi kesalahan saat menghapus pengguna', {
            position: 'center-top',
            distance: '70px',
          });
        }
      });
    },
    () => {
      Notiflix.Notify.warning('Pengguna tidak jadi dihapus', {
        position: 'center-top',
        distance: '70px',
      });
    }
  );
}
</script>

<template>

  <AuthenticatedLayout>

    <div class="py-6 min-h-screen">
      <div class="max-w-7xl mx-auto p-4 rounded-md shadow-lg">
        <div class="flex justify-between">
          <div class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md mr-2 mt-3 w-fit">
            <Link :href="route('admin.dashboard')">Kembali</Link>
          </div>
          <div class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md mr-2 mt-3 w-fit">
            <Link :href="route('register')">Buat Akun</Link>
          </div>
        </div>
        <div class="bg-white shadow rounded-lg overflow-hidden mt-4">

          <div class="overflow-x-auto">
            <table class="min-w-full text-sm bg-white border border-gray-200 shadow-lg rounded-lg">
              <thead class="bg-gray-800 text-white">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">NIP</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Nama</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Email</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Role</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Tanggal Register</th>
                  <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                <tr v-for="user in users" :key="user.id" class="hover:bg-indigo-50 transition-colors duration-200">

                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700">
                    {{ user.id }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    {{ user.name }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    {{ user.email }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <select v-model="user.role" @change="confirmRoleUpdate(user)" @focus="storePreviousRole(user)"
                      class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                      <option value="user">User</option>
                      <option value="admin">Admin</option>
                      <option value="petugas">Petugas</option>
                    </select>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ formatDate(user.created_at) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex justify-center space-x-3">
                      <Link :href="route('admin.users.show', user.id)"
                        class="p-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-full shadow transition duration-150 ease-in-out">
                      <i class="fas fa-eye"></i>
                      </Link>
                      <button @click.stop="deleteUser(user)"
                        class="p-2 bg-red-500 hover:bg-red-600 text-white rounded-full shadow transition duration-150 ease-in-out">
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>

  </AuthenticatedLayout>

</template>
