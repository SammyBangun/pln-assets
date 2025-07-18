<script setup>
import { reactive } from 'vue';
import { router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Notify, Confirm } from 'notiflix';
import formatDate from '@/functions/formatDate';

const previousRoles = reactive({});

defineProps({
  users: Array
})

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
      <div class="max-w-7xl mx-auto px-4 rounded-md shadow-md">
        <div class="flex justify-between">
          <div class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md mr-2 mt-3 w-fit">
            <Link :href="route('admin.dashboard')">Kembali</Link>
          </div>
          <div class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md mr-2 mt-3 w-fit">
            <Link :href="route('register')">Buat Akun</Link>
          </div>
        </div>
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIP</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal
                    Register</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="user in users" :key="user.id" class="hover:bg-gray-200 ">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <Link :href="route('admin.users.show', user.id)"
                      class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">
                    {{ user.id }}
                    </Link>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ user.name }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ user.email }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <select v-model="user.role" @change="confirmRoleUpdate(user)" @focus="storePreviousRole(user)"
                      class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                      <option value="user">User</option>
                      <option value="admin">Admin</option>
                      <option value="petugas">Petugas</option>
                    </select>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    {{ formatDate(user.created_at) }}
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
