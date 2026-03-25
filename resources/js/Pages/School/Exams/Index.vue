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
import Modal from "@/Components/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
  data: {
    type: Array,
    default: () => [],
  },
  meta: {
    type: Object,
    default: () => ({}),
  },
  academic_sessions: {
    type: Array,
    default: () => [],
  },
});

const page = usePage();
const permissions = computed(() => page.props.permissions || []);

const search = ref("");
const showDeleteModal = ref(false);
const examToDelete = ref(null);

const showCreateEditModal = ref(false);
const isEditing = ref(false);
const form = ref({
  id: null,
  name: "",
  academic_session_id: "",
  term: "",
  status: "active",
});
const errors = ref({});

const headers = [
  __("school.exam_name", "Exam Name"),
  __("school.academic_session", "Academic Session"),
  __("school.term", "Term"),
  __("school.status", "Status"),
  { label: __("table.action", "Action"), class: "text-center" },
];

const handleSearch = () => {
  router.get(
    "/exams",
    { keyword: search.value },
    { preserveState: true, replace: true },
  );
};

const handleReset = () => {
  search.value = "";
  router.get("/exams");
};

const openCreateModal = () => {
  isEditing.value = false;
  form.value = {
    id: null,
    name: "",
    academic_session_id: props.academic_sessions[0]?.id || "",
    term: "",
    status: "active",
  };
  errors.value = {};
  showCreateEditModal.value = true;
};

const openEditModal = (exam) => {
  isEditing.value = true;
  form.value = { ...exam };
  errors.value = {};
  showCreateEditModal.value = true;
};

const submitForm = () => {
  const url = isEditing.value ? `/exams/${form.value.id}` : "/exams";
  const method = isEditing.value ? "put" : "post";

  router[method](url, form.value, {
    onSuccess: () => {
      showCreateEditModal.value = false;
    },
    onError: (err) => {
      errors.value = err;
    },
  });
};

const confirmDelete = (exam) => {
  examToDelete.value = exam;
  showDeleteModal.value = true;
};

const deleteExam = () => {
  router.delete(`/exams/${examToDelete.value.id}`, {
    onSuccess: () => {
      showDeleteModal.value = false;
      examToDelete.value = null;
    },
  });
};
</script>

<template>
  <Head>
    <title>{{ __("school.exams", "Exams") }}</title>
  </Head>

  <AdminLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem]"
      >
        {{ __("school.exams", "Exams") }}
      </h2>
    </template>

    <div class="py-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <!-- Header & Stats -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
              {{ __("school.exams", "Exams") }}
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
              {{ __("school.exams_subtitle", "Manage school exams and terms.") }}
            </p>
          </div>
          <div class="flex items-center gap-3">
            <PrimaryButton
              @click="openCreateModal"
              class="flex items-center gap-2 text-sm font-medium bg-blue-600 hover:bg-blue-700"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              {{ __("messages.create", "Create Exam") }}
            </PrimaryButton>
          </div>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 mb-6">
          <div class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
              <TextInput
                type="text"
                v-model="search"
                :placeholder="__('messages.search_item', 'Search exams...')"
                @keyup.enter="handleSearch"
                class="w-full"
              />
            </div>
            <div class="flex gap-2 shrink-0">
              <SecondaryButton @click="handleReset" v-if="search">
                {{ __("messages.reset", "Reset") }}
              </SecondaryButton>
              <PrimaryButton @click="handleSearch" class="bg-gray-700 hover:bg-gray-800">
                {{ __("messages.search", "Search") }}
              </PrimaryButton>
            </div>
          </div>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
          <div class="p-4 border-b border-gray-200 dark:border-gray-700 text-sm text-gray-500">
            Showing {{ data.length }} of {{ meta.total }} exams
          </div>
          
          <BaseTable :headers="headers">
            <tr
              v-for="exam in data"
              :key="exam.id"
              class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors border-b border-gray-100 dark:border-gray-700 last:border-0"
            >
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                {{ exam.name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-medium">
                {{ exam.academic_session_name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                {{ exam.term }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                 <span
                  class="px-2.5 py-1 rounded-full text-xs font-semibold inline-flex items-center mx-auto"
                  :class="
                    exam.status === 'active'
                      ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
                      : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'
                  "
                >
                  <span class="w-1.5 h-1.5 rounded-full mr-1.5" :class="exam.status === 'active' ? 'bg-green-500' : 'bg-red-500'"></span>
                  {{ exam.status }}
                </span>
              </td>
              <td class="px-6 py-4 text-sm text-center">
                <div class="flex items-center justify-center gap-2">
                  <SecondaryButton
                    v-if="permissions.includes('edit exams')"
                    @click="openEditModal(exam)"
                  >
                    {{ __("messages.edit", "Edit") }}
                  </SecondaryButton>
                  <SecondaryButton
                    v-if="permissions.includes('delete exams')"
                    variant="danger"
                    @click="confirmDelete(exam)"
                  >
                    {{ __("messages.delete", "Delete") }}
                  </SecondaryButton>
                </div>
              </td>
            </tr>
            <tr v-if="data.length === 0">
              <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                {{ __("messages.no_data", "No exams found.") }}
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
    <Modal :show="showCreateEditModal" @close="showCreateEditModal = false" maxWidth="2xl">
      <div class="p-6">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-lg font-bold text-gray-900 dark:text-white">
            {{ isEditing ? __("school.edit_exam", "Edit Exam") : __("school.create_exam", "Create Exam") }}
          </h3>
          <button @click="showCreateEditModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <div class="mt-4 space-y-4">
          <div>
            <InputLabel for="name" :value="__('school.exam_name', 'Exam Name')" />
            <TextInput
              id="name"
              type="text"
              class="mt-1 block w-full"
              v-model="form.name"
              required
            />
            <InputError :message="errors.name" class="mt-2" />
          </div>

          <div>
            <InputLabel for="academic_session_id" :value="__('school.academic_session', 'Academic Session')" />
            <select
              id="academic_session_id"
              v-model="form.academic_session_id"
              class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
            >
              <option v-for="session in academic_sessions" :key="session.id" :value="session.id">
                {{ session.name }}
              </option>
            </select>
            <InputError :message="errors.academic_session_id" class="mt-2" />
          </div>

          <div>
            <InputLabel for="term" :value="__('school.term', 'Term')" />
            <TextInput
              id="term"
              type="text"
              class="mt-1 block w-full"
              v-model="form.term"
              required
            />
            <InputError :message="errors.term" class="mt-2" />
          </div>

          <div>
            <InputLabel for="status" :value="__('school.status', 'Status')" />
            <select
              id="status"
              v-model="form.status"
              class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
            >
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
            <InputError :message="errors.status" class="mt-2" />
          </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
          <SecondaryButton @click="showCreateEditModal = false">
            {{ __("messages.cancel", "Cancel") }}
          </SecondaryButton>
          <PrimaryButton @click="submitForm">
            {{ __("messages.save", "Save") }}
          </PrimaryButton>
        </div>
      </div>
    </Modal>

    <DeleteConfirmationModal
      :show="showDeleteModal"
      :title="__('school.delete_exam_title', 'Delete Exam')"
      :message="
        __(
          'school.delete_exam_message',
          `Are you sure you want to delete ${examToDelete?.name}? This action cannot be undone.`,
        )
      "
      @confirm="deleteExam"
      @close="showDeleteModal = false"
    />
  </AdminLayout>
</template>
