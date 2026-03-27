<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import BaseTable from "@/Components/BaseTable.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { usePage, router, Link, Head, useForm } from "@inertiajs/vue3";
import DeleteConfirmationModal from "@/Components/DeleteConfirmationModal.vue";
import Pagination from "@/Components/Pagination.vue";
import { __ } from "@/helpers.js";
import { ref, computed } from "vue";
import Modal from "@/Components/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import SearchableSelect from "@/Components/SearchableSelect.vue";

const props = defineProps({
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
const sessionToDelete = ref(null);

const showCreateEditModal = ref(false);
const isEditing = ref(false);
const form = useForm({
  id: null,
  name: "",
  start_date: "",
  end_date: "",
  status: "active",
});

const headers = [
  __("school.session_name", "Session Name"),
  __("school.start_date", "Start Date"),
  __("school.end_date", "End Date"),
  __("school.status", "Status"),
  { label: __("table.action", "Action"), class: "text-center" },
];

const handleSearch = () => {
  router.get(
    "/academic-sessions",
    { keyword: search.value },
    { preserveState: true, replace: true },
  );
};

const handleReset = () => {
  search.value = "";
  router.get("/academic-sessions");
};

const openCreateModal = () => {
  isEditing.value = false;
  form.reset();
  form.clearErrors();
  showCreateEditModal.value = true;
};

const openEditModal = (session) => {
  isEditing.value = true;
  form.clearErrors();
  form.id = session.id;
  form.name = session.name;
  form.start_date = session.start_date;
  form.end_date = session.end_date;
  form.status = session.status;
  showCreateEditModal.value = true;
};

const submitForm = () => {
  const url = isEditing.value
    ? `/academic-sessions/${form.id}`
    : "/academic-sessions";
  const method = isEditing.value ? "put" : "post";

  form[method](url, {
    onSuccess: () => {
      showCreateEditModal.value = false;
      form.reset();
    },
  });
};

const confirmDelete = (session) => {
  sessionToDelete.value = session;
  showDeleteModal.value = true;
};

const deleteSession = () => {
  router.delete(`/academic-sessions/${sessionToDelete.value.id}`, {
    onSuccess: () => {
      showDeleteModal.value = false;
      sessionToDelete.value = null;
    },
  });
};
</script>

<template>
  <Head>
    <title>{{ __("school.academic_sessions", "Academic Sessions") }}</title>
  </Head>

  <AdminLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem]"
      >
        {{ __("school.academic_sessions", "Academic Sessions") }}
      </h2>
    </template>

    <div class="py-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <!-- Header & Stats -->
        <div
          class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4"
        >
          <div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
              {{ __("school.academic_sessions", "Academic Sessions") }}
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
              {{
                __(
                  "school.academic_sessions_subtitle",
                  "Manage academic sessions and durations.",
                )
              }}
            </p>
          </div>
          <div class="flex items-center gap-3">
            <PrimaryButton
              @click="openCreateModal"
              class="flex items-center gap-2 text-sm font-medium bg-blue-600 hover:bg-blue-700"
            >
              <svg
                class="w-4 h-4"
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
              {{ __("messages.create", "Create Session") }}
            </PrimaryButton>
          </div>
        </div>

        <!-- Filter Bar -->
        <div
          class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 mb-6"
        >
          <div class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
              <TextInput
                type="text"
                v-model="search"
                :placeholder="__('messages.search_item', 'Search sessions...')"
                @keyup.enter="handleSearch"
                class="w-full"
              />
            </div>
            <div class="flex gap-2 shrink-0">
              <SecondaryButton @click="handleReset" v-if="search">
                {{ __("messages.reset", "Reset") }}
              </SecondaryButton>
              <PrimaryButton
                @click="handleSearch"
                class="bg-gray-700 hover:bg-gray-800"
              >
                {{ __("messages.search", "Search") }}
              </PrimaryButton>
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
            Showing {{ data.length }} of {{ meta.total }} sessions
          </div>

          <BaseTable :headers="headers">
            <tr
              v-for="session in data"
              :key="session.id"
              class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors border-b border-gray-100 dark:border-gray-700 last:border-0"
            >
              <td
                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"
              >
                {{ session.name }}
              </td>
              <td
                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-medium"
              >
                {{ session.start_date }}
              </td>
              <td
                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-medium"
              >
                {{ session.end_date }}
              </td>
              <td
                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
              >
                <span
                  class="px-2.5 py-1 rounded-full text-xs font-semibold inline-flex items-center"
                  :class="
                    session.status === 'active'
                      ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
                      : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'
                  "
                >
                  <span
                    class="w-1.5 h-1.5 rounded-full mr-1.5"
                    :class="
                      session.status === 'active'
                        ? 'bg-green-500'
                        : 'bg-red-500'
                    "
                  ></span>
                  {{ session.status }}
                </span>
              </td>
              <td class="px-6 py-4 text-sm text-center">
                <div class="flex items-center justify-center gap-2">
                  <SecondaryButton
                    v-if="permissions.includes('edit academic-sessions')"
                    @click="openEditModal(session)"
                  >
                    {{ __("messages.edit", "Edit") }}
                  </SecondaryButton>
                  <SecondaryButton
                    v-if="permissions.includes('delete academic-sessions')"
                    variant="danger"
                    @click="confirmDelete(session)"
                  >
                    {{ __("messages.delete", "Delete") }}
                  </SecondaryButton>
                </div>
              </td>
            </tr>
            <tr v-if="data.length === 0">
              <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                {{ __("messages.no_data", "No academic sessions found.") }}
              </td>
            </tr>
          </BaseTable>

          <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
            <Pagination :meta="meta" />
          </div>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <Modal
      :show="showCreateEditModal"
      @close="showCreateEditModal = false"
      maxWidth="2xl"
    >
      <div class="p-6">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-lg font-bold text-gray-900 dark:text-white">
            {{
              isEditing
                ? __("school.edit_session", "Edit Session")
                : __("school.create_session", "Create Session")
            }}
          </h3>
          <button
            @click="showCreateEditModal = false"
            class="text-gray-400 hover:text-gray-600 transition-colors"
          ></button>
        </div>

        <div class="mt-4 space-y-4">
          <div>
            <InputLabel
              for="name"
              :value="__('school.session_name', 'Session Name') + ' *'"
            />
            <TextInput
              id="name"
              type="text"
              class="mt-1 block w-full"
              v-model="form.name"
              required
            />
            <InputError :message="form.errors.name" class="mt-2" />
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <InputLabel
                for="start_date"
                :value="__('school.start_date', 'Start Date') + ' *'"
              />
              <TextInput
                id="start_date"
                type="date"
                class="mt-1 block w-full"
                v-model="form.start_date"
                required
              />
              <InputError :message="form.errors.start_date" class="mt-2" />
            </div>

            <div>
              <InputLabel
                for="end_date"
                :value="__('school.end_date', 'End Date') + ' *'"
              />
              <TextInput
                id="end_date"
                type="date"
                class="mt-1 block w-full"
                v-model="form.end_date"
                required
              />
              <InputError :message="form.errors.end_date" class="mt-2" />
            </div>
          </div>

          <div>
            <InputLabel
              for="status"
              :value="__('school.status', 'Status') + ' *'"
            />
            <SearchableSelect
              id="status"
              v-model="form.status"
              :options="[
                { id: 'active', label: 'Active' },
                { id: 'inactive', label: 'Inactive' },
              ]"
              :placeholder="__('messages.select', 'Select...')"
              class="mt-1 block w-full"
              required
              :error="form.errors.status"
            />
            <InputError :message="form.errors.status" class="mt-2" />
          </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
          <SecondaryButton @click="showCreateEditModal = false">
            {{ __("messages.cancel", "Cancel") }}
          </SecondaryButton>
          <PrimaryButton
            @click="submitForm"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          >
            {{ __("messages.save", "Save") }}
          </PrimaryButton>
        </div>
      </div>
    </Modal>

    <DeleteConfirmationModal
      :show="showDeleteModal"
      :title="__('school.delete_session_title', 'Delete Session')"
      :message="
        __(
          'school.delete_session_message',
          `Are you sure you want to delete ${sessionToDelete?.name}? This action cannot be undone.`,
        )
      "
      @confirm="deleteSession"
      @close="showDeleteModal = false"
    />
  </AdminLayout>
</template>
