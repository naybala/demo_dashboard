<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import PageHeader from "@/Components/PageHeader.vue";
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
const roleToDelete = ref(null);

const headers = [
  { key: "name", label: __("role.role_name", "Role Name") },
  {
    key: "allow_panel_status",
    label: __("role.can_access_panel", "Access Panel"),
  },
  { key: "actions", label: __("table.action", "Actions") },
];

const handleSearch = () => {
  router.get(
    "/roles",
    { keyword: search.value },
    { preserveState: true, replace: true },
  );
};

const handleReset = () => {
  search.value = "";
  router.get("/roles");
};

const confirmDelete = (role) => {
  roleToDelete.value = role;
  showDeleteModal.value = true;
};

const deleteRole = () => {
  router.delete(`/roles/${roleToDelete.value.id}`, {
    onSuccess: () => {
      showDeleteModal.value = false;
      roleToDelete.value = null;
    },
  });
};
</script>

<template>
  <Head>
    <title>{{ __("sidebar.role") }}</title>
  </Head>

  <AdminLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem]"
      >
        {{ __("sidebar.role", "Roles") }}
      </h2>
    </template>

    <div
      class="mb-6 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between"
    >
      <div class="flex flex-1 gap-2 min-w-0">
        <TextInput
          type="text"
          :placeholder="__('messages.search_item', 'Search roles...')"
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
        <Link v-if="permissions.includes('create roles')" href="/roles/create">
          <PrimaryButton>
            {{ __("messages.create", "Create Role") }}
          </PrimaryButton>
        </Link>
      </div>
    </div>

    <BaseTable :headers="headers">
      <tr
        v-for="role in data"
        :key="role.id"
        class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
      >
        <td
          class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"
        >
          {{ role.name }}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          <span
            class="px-2 py-1 rounded-full text-xs font-semibold"
            :class="
              role.can_access_panel
                ? 'bg-green-100 text-green-800'
                : 'bg-gray-100 text-gray-800'
            "
          >
            {{ role.allow_panel_status }}
          </span>
        </td>
        <td class="flex gap-2 px-6 py-4">
          <Link :href="`/roles/${role.id}`">
            <SecondaryButton>{{ __("messages.view", "View") }}</SecondaryButton>
          </Link>
          <Link
            v-if="permissions.includes('edit roles')"
            :href="`/roles/${role.id}/edit`"
          >
            <SecondaryButton>{{ __("messages.edit", "Edit") }}</SecondaryButton>
          </Link>
          <SecondaryButton
            v-if="permissions.includes('delete roles')"
            variant="danger"
            @click="confirmDelete(role)"
          >
            {{ __("messages.delete", "Delete") }}
          </SecondaryButton>
        </td>
      </tr>
    </BaseTable>

    <Pagination :meta="meta" />

    <DeleteConfirmationModal
      :show="showDeleteModal"
      :title="__('role.delete_title', 'Delete Role')"
      :message="
        __(
          'role.delete_message',
          `Are you sure you want to delete ${roleToDelete?.name}? This action cannot be undone.`,
        )
      "
      @confirm="deleteRole"
      @close="showDeleteModal = false"
    />
  </AdminLayout>
</template>
