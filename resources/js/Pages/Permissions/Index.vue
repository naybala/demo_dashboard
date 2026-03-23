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
const permissionToDelete = ref(null);

const headers = [
  { key: "name", label: __("permission.permission_name", "Permission Name") },
  { key: "guard_name", label: __("permission.guard_name", "Guard Name") },
  { key: "actions", label: __("table.action", "Actions") },
];

const handleSearch = () => {
  router.get(
    "/permissions",
    { keyword: search.value },
    { preserveState: true, replace: true },
  );
};

const handleReset = () => {
  search.value = "";
  router.get("/permissions");
};

const confirmDelete = (permission) => {
  permissionToDelete.value = permission;
  showDeleteModal.value = true;
};

const deletePermission = () => {
  router.delete(`/permissions/${permissionToDelete.value.id}`, {
    onSuccess: () => {
      showDeleteModal.value = false;
      permissionToDelete.value = null;
    },
  });
};
</script>

<template>
  <Head>
    <title>{{ __("sidebar.permission", "Permissions") }}</title>
  </Head>

  <AdminLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem]"
      >
        {{ __("sidebar.permission", "Permissions") }}
      </h2>
    </template>

    <div
      class="mb-6 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between"
    >
      <div class="flex flex-1 gap-2 min-w-0">
        <TextInput
          type="text"
          :placeholder="__('messages.search_item', 'Search permissions...')"
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
        <Link
          v-if="permissions.includes('create permissions')"
          href="/permissions/create"
        >
          <PrimaryButton>
            {{ __("messages.create", "Create Permission") }}
          </PrimaryButton>
        </Link>
      </div>
    </div>

    <BaseTable :headers="headers">
      <tr
        v-for="permission in data"
        :key="permission.id"
        class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
      >
        <td
          class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"
        >
          {{ permission.name }}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          <span
            class="px-2 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800"
          >
            {{ permission.guard_name }}
          </span>
        </td>
        <td class="flex gap-2 px-6 py-4">
          <Link
            v-if="permissions.includes('edit permissions')"
            :href="`/permissions/${permission.id}/edit`"
          >
            <SecondaryButton>{{ __("messages.edit", "Edit") }}</SecondaryButton>
          </Link>
          <SecondaryButton
            v-if="permissions.includes('delete permissions')"
            variant="danger"
            @click="confirmDelete(permission)"
          >
            {{ __("messages.delete", "Delete") }}
          </SecondaryButton>
        </td>
      </tr>
    </BaseTable>

    <Pagination :meta="meta" />

    <DeleteConfirmationModal
      :show="showDeleteModal"
      :title="__('permission.delete_title', 'Delete Permission')"
      :message="
        __(
          'permission.delete_message',
          `Are you sure you want to delete ${permissionToDelete?.name}? This action cannot be undone.`,
        )
      "
      @confirm="deletePermission"
      @close="showDeleteModal = false"
    />
  </AdminLayout>
</template>
