<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import BaseTable from "@/Components/BaseTable.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { usePage, router, Link, Head } from "@inertiajs/vue3";
import DeleteConfirmationModal from "@/Components/DeleteConfirmationModal.vue";
import Pagination from "@/Components/Pagination.vue";
import { __ } from "@/helpers.js";
import { ref, computed } from "vue";

defineProps({
  data: {
    type: Array,
    default: () => [],
  },
  meta: {
    type: Object,
    default: () => ({}),
  },
});

const page = usePage();
const permissions = computed(() => page.props.permissions || []);

const search = ref("");
const showDeleteModal = ref(false);
const userToDelete = ref(null);

const headers = [
  __("user.name", "Name"),
  __("user.email", "Email"),
  __("user.role", "Role"),
  __("user.status", "Status"),
  __("table.action", "Action"),
];

const handleSearch = () => {
  router.get(
    "/users",
    { keyword: search.value },
    { preserveState: true, replace: true },
  );
};

const handleReset = () => {
  search.value = "";
  router.get("/users");
};

const confirmDelete = (user) => {
  userToDelete.value = user;
  showDeleteModal.value = true;
};

const deleteUser = () => {
  router.delete(`/users/${userToDelete.value.id}`, {
    onSuccess: () => {
      showDeleteModal.value = false;
      userToDelete.value = null;
    },
  });
};
</script>

<template>
  <Head>
    <title>{{ __("sidebar.user") }}</title>
  </Head>

  <AdminLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem]"
      >
        {{ __("sidebar.user", "Users") }}
      </h2>
    </template>

    <div
      class="mb-6 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between"
    >
      <div class="flex flex-1 gap-2 min-w-0">
        <TextInput
          type="text"
          :placeholder="__('messages.search_item', 'Search users...')"
          v-model="search"
          @keydown.enter="handleSearch"
          class="w-full"
        />
        <SecondaryButton @click="handleSearch">
          {{ __("messages.search", "Search") }}
        </SecondaryButton>
        <SecondaryButton
          v-if="search"
          class="bg-gray-100 dark:bg-gray-700"
          @click="handleReset"
        >
          {{ __("messages.reset", "Clear") }}
        </SecondaryButton>
      </div>
      <div class="flex-shrink-0">
        <Link v-if="permissions.includes('create users')" href="/users/create">
          <PrimaryButton>{{ __("messages.create", "Create User") }}</PrimaryButton>
        </Link>
      </div>
    </div>

    <BaseTable :headers="headers">
      <tr v-for="user in data" :key="user.id" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
        <td
          class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"
        >
          <div class="flex items-center gap-3">
            <div
              class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-xs font-bold overflow-hidden flex-shrink-0"
            >
              <img
                v-if="user.avatar"
                :src="user.avatar"
                :alt="user.fullname"
                class="w-full h-full object-cover"
              />
              <template v-else>
                {{ user.fullname.charAt(0).toUpperCase() }}
              </template>
            </div>
            <span>{{ user.fullname }}</span>
          </div>
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          {{ user.email }}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          <span
            class="px-2 py-1 rounded-full bg-indigo-100 text-indigo-800 text-xs font-semibold"
          >
            {{ user.role_name }}
          </span>
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          <span
            class="px-2 py-1 rounded-full text-xs font-semibold"
            :class="user.status === 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
          >
            {{ user.status_text }}
          </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
          <div class="flex gap-2 justify-end">
            <Link :href="`/users/${user.id}`">
              <SecondaryButton>{{ __("messages.view", "View") }}</SecondaryButton>
            </Link>
            <Link v-if="permissions.includes('edit users')" :href="`/users/${user.id}/edit`">
              <SecondaryButton>{{ __("messages.edit", "Edit") }}</SecondaryButton>
            </Link>
            <button
              v-if="permissions.includes('delete users')"
              @click="confirmDelete(user)"
              class="text-red-600 hover:text-red-900 font-medium ml-2"
            >
              {{ __("messages.delete", "Delete") }}
            </button>
          </div>
        </td>
      </tr>
    </BaseTable>

    <Pagination :meta="meta" />

    <DeleteConfirmationModal
      :show="showDeleteModal"
      :title="__('user.delete_title', 'Delete User')"
      :message="
        __(
          'user.delete_message',
          `Are you sure you want to delete ${userToDelete?.fullname}? This action cannot be undone.`
        )
      "
      @confirm="deleteUser"
      @close="showDeleteModal = ref(false)"
    />
  </AdminLayout>
</template>
