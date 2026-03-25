<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import BaseTable from "@/Components/BaseTable.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import Pagination from "@/Components/Pagination.vue";
import DeleteConfirmationModal from "@/Components/DeleteConfirmationModal.vue";
import { Head, router, usePage, Link } from "@inertiajs/vue3";
import { __ } from "@/helpers.js";
import { ref, computed } from "vue";

const props = defineProps({
  data: Array,
  meta: Object,
  filters: Object,
});

const page = usePage();
const permissions = computed(() => page.props.permissions || []);

const search = ref(props.filters.keyword || "");
const showDeleteModal = ref(false);
const userToDelete = ref(null);

const headers = [
  { label: __("user.fullname", "Full Name"), class: "text-left" },
  { label: __("user.staff_id", "Staff ID"), class: "text-left" },
  { label: __("user.type", "Type"), class: "text-center" },
  { label: __("user.gender", "Gender"), class: "text-center" },
  { label: __("user.email", "Email"), class: "text-left" },
  { label: __("table.actions", "Actions"), class: "text-center" },
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
  handleSearch();
};

const confirmDelete = (user) => {
  userToDelete.value = user;
  showDeleteModal.value = true;
};

const deleteUser = () => {
  router.delete("/users/" + userToDelete.value.id, {
    onSuccess: () => {
      showDeleteModal.value = false;
      userToDelete.value = null;
    },
  });
};

const getTypeColor = (type) => {
  switch (type) {
    case 1:
      return "bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400"; // Admin
    case 2:
      return "bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400"; // Teacher
    default:
      return "bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300";
  }
};
</script>

<template>
  <Head :title="__('sidebar.teacher_staff_list', 'Teacher & Staff List')" />

  <AdminLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
      >
        {{ __("sidebar.teacher_staff_list", "Teacher & Staff List") }}
      </h2>
    </template>

    <div class="py-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <!-- Header & Stats -->
        <div class="mb-6">
          <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ __("sidebar.teacher_staff_list", "Teacher & Staff List") }}
          </h3>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            {{
              __(
                "user.teacher_subtitle",
                "Manage your school teachers and staff.",
              )
            }}
          </p>
        </div>

        <!-- Filter Bar -->
        <div
          class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 mb-6"
        >
          <div class="flex flex-col md:flex-row gap-4 items-center">
            <div class="flex-1 w-full flex gap-2">
              <TextInput
                type="text"
                v-model="search"
                :placeholder="__('messages.search', 'Search...')"
                @keyup.enter="handleSearch"
                class="flex-1"
              />
              <PrimaryButton
                @click="handleSearch"
                class="bg-gray-700 hover:bg-gray-800 shrink-0"
              >
                {{ __("messages.search", "Search") }}
              </PrimaryButton>
            </div>

            <div class="flex gap-2 shrink-0 w-full md:w-auto">
              <SecondaryButton
                @click="handleReset"
                v-if="search"
                class="flex-1 md:flex-none"
              >
                {{ __("messages.reset", "Reset") }}
              </SecondaryButton>
              <Link href="/users/create">
                <PrimaryButton
                  class="bg-blue-600 hover:bg-blue-700 whitespace-nowrap w-full justify-center"
                >
                  <svg
                    class="w-4 h-4 mr-2"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 4v16m8-8H4"
                    />
                  </svg>
                  {{ __("user.create_teacher", "Create Teacher/Staff") }}
                </PrimaryButton>
              </Link>
            </div>
          </div>
        </div>

        <!-- Table -->
        <div
          class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden"
        >
          <div
            class="p-4 border-b border-gray-200 dark:border-gray-700 text-sm text-gray-500"
          >
            Showing {{ data.length }} of {{ meta.total }} records
          </div>

          <BaseTable :headers="headers">
            <tr
              v-for="user in data"
              :key="user.id"
              class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors border-b border-gray-100 dark:border-gray-700 last:border-0"
            >
              <td class="px-6 py-4">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <img
                      class="h-10 w-10 rounded-full object-cover border border-gray-200"
                      :src="user.avatar || '/upload/profile.png'"
                      alt=""
                    />
                  </div>
                  <div class="ml-4">
                    <div
                      class="text-sm font-medium text-gray-900 dark:text-white"
                    >
                      {{ user.fullname }}
                    </div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                {{ user.staff_id }}
              </td>
              <td class="px-6 py-4 text-center">
                <span
                  class="px-2.5 py-1 rounded-full text-xs font-semibold inline-flex items-center"
                  :class="getTypeColor(user.user_type)"
                >
                  {{ user.user_type_label }}
                </span>
              </td>
              <td
                class="px-6 py-4 text-center text-sm text-gray-600 dark:text-gray-400"
              >
                {{ user.gender_label }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                {{ user.email }}
              </td>
              <td class="px-6 py-4 text-sm text-center">
                <div class="flex items-center justify-center gap-2">
                  <Link
                    v-if="permissions.includes('show users')"
                    :href="`/users/${user.id}`"
                  >
                    <SecondaryButton>
                      {{ __("messages.view", "View") }}
                    </SecondaryButton>
                  </Link>
                  <Link
                    v-if="permissions.includes('edit users')"
                    :href="`/users/${user.id}/edit`"
                  >
                    <SecondaryButton>
                      {{ __("messages.edit", "Edit") }}
                    </SecondaryButton>
                  </Link>
                  <SecondaryButton
                    v-if="permissions.includes('delete users')"
                    variant="danger"
                    @click="confirmDelete(user)"
                  >
                    {{ __("messages.delete", "Delete") }}
                  </SecondaryButton>
                </div>
              </td>
            </tr>
            <tr v-if="data.length === 0">
              <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                {{ __("messages.no_data", "No records found.") }}
              </td>
            </tr>
          </BaseTable>

          <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
            <Pagination :meta="meta" />
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Modal -->
    <DeleteConfirmationModal
      :show="showDeleteModal"
      :title="__('user.delete_user_title', 'Delete User')"
      :message="
        __(
          'user.delete_user_message',
          'Are you sure you want to delete this record? This action cannot be undone.',
        )
      "
      @confirm="deleteUser"
      @close="showDeleteModal = false"
    />
  </AdminLayout>
</template>
