<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Register</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="user in users" :key="user.id">
                <td class="px-6 py-4 whitespace-nowrap">{{ user.id }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ user.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ user.email }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <select 
                    v-model="user.role"
                    @change="updateRole(user)"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  >
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                  </select>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  {{ new Date(user.created_at).toLocaleDateString() }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'

defineProps({
  users: Array
})

const updateRole = async (user) => {
  router.put(`/users/${user.id}/role`, {
    role: user.role
  }, {
    preserveScroll: true,
    onSuccess: () => {
      alert('Role berhasil diupdate')
    }
  })
}
</script>
