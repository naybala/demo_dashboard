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
  students: {
    type: Array,
    default: () => [],
  },
  exams: {
    type: Array,
    default: () => [],
  },
  subjects: {
    type: Array,
    default: () => [],
  },
});

const page = usePage();
const permissions = computed(() => page.props.permissions || []);

const studentOptions = computed(() =>
  props.students.map((s) => ({ id: s.id, label: s.name })),
);

const examOptions = computed(() =>
  props.exams.map((e) => ({ id: e.id, label: e.name })),
);

const subjectOptions = computed(() =>
  props.subjects.map((s) => ({ id: s.id, label: s.name })),
);

const search = ref("");
const showDeleteModal = ref(false);
const markToDelete = ref(null);

const showCreateEditModal = ref(false);
const isEditing = ref(false);
const form = ref({
  id: null,
  student_id: "",
  exam_id: "",
  subject_id: "",
  marks_obtained: "",
  grade: "",
  status: "active",
});
const errors = ref({});

const headers = [
  __("school.student", "Student"),
  __("school.exam", "Exam"),
  __("school.subject", "Subject"),
  __("school.marks", "Marks"),
  __("school.grade", "Grade"),
  { label: __("table.action", "Action"), class: "text-center" },
];

const handleSearch = () => {
  router.get(
    "/marks",
    { keyword: search.value },
    { preserveState: true, replace: true },
  );
};

const handleReset = () => {
  search.value = "";
  router.get("/marks");
};

const openCreateModal = () => {
  isEditing.value = false;
  form.value = {
    id: null,
    student_id: props.students[0]?.id || "",
    exam_id: props.exams[0]?.id || "",
    subject_id: props.subjects[0]?.id || "",
    marks_obtained: "",
    grade: "",
    status: "active",
  };
  errors.value = {};
  showCreateEditModal.value = true;
};

const openEditModal = (mark) => {
  isEditing.value = true;
  form.value = { ...mark };
  errors.value = {};
  showCreateEditModal.value = true;
};

const submitForm = () => {
  const url = isEditing.value ? `/marks/${form.value.id}` : "/marks";
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

const confirmDelete = (mark) => {
  markToDelete.value = mark;
  showDeleteModal.value = true;
};

const deleteMark = () => {
  router.delete(`/marks/${markToDelete.value.id}`, {
    onSuccess: () => {
      showDeleteModal.value = false;
      markToDelete.value = null;
    },
  });
};
</script>

<template>
  <Head>
    <title>{{ __("school.marks", "Marks") }}</title>
  </Head>

  <AdminLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem]"
      >
        {{ __("school.marks", "Marks") }}
      </h2>
    </template>

    <div class="py-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <!-- Header & Stats -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
              {{ __("school.marks", "Marks") }}
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
              {{ __("school.marks_subtitle", "Manage student marks and exam results.") }}
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
              {{ __("messages.create", "Record Mark") }}
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
                :placeholder="__('messages.search_item', 'Search marks...')"
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
            Showing {{ data.length }} of {{ meta.total }} marks
          </div>
          
          <BaseTable :headers="headers">
            <tr
              v-for="mark in data"
              :key="mark.id"
              class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors border-b border-gray-100 dark:border-gray-700 last:border-0"
            >
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                {{ mark.student_name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-medium">
                {{ mark.exam_name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                {{ mark.subject_name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-bold">
                {{ mark.marks_obtained }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                 <span
                  class="px-2.5 py-1 rounded-full text-xs font-semibold inline-flex items-center mx-auto bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400"
                >
                  {{ mark.grade }}
                </span>
              </td>
              <td class="px-6 py-4 text-sm text-center">
                <div class="flex items-center justify-center gap-2">
                  <SecondaryButton
                    v-if="permissions.includes('edit marks')"
                    @click="openEditModal(mark)"
                  >
                    {{ __("messages.edit", "Edit") }}
                  </SecondaryButton>
                  <SecondaryButton
                    v-if="permissions.includes('delete marks')"
                    variant="danger"
                    @click="confirmDelete(mark)"
                  >
                    {{ __("messages.delete", "Delete") }}
                  </SecondaryButton>
                </div>
              </td>
            </tr>
            <tr v-if="data.length === 0">
              <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                {{ __("messages.no_data", "No marks found.") }}
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
            {{ isEditing ? __("school.edit_mark", "Edit Mark") : __("school.record_mark", "Record Mark") }}
          </h3>
          <button @click="showCreateEditModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <div class="mt-4 space-y-4">
          <div>
            <InputLabel for="student_id" :value="__('school.student', 'Student')" />
            <SearchableSelect
              id="student_id"
              v-model="form.student_id"
              :options="studentOptions"
              :placeholder="__('school.select_student', 'Select Student')"
              class="mt-1 block w-full"
              :error="form.errors.student_id"
            />
            <InputError :message="errors.student_id" class="mt-2" />
          </div>

          <div>
            <InputLabel for="exam_id" :value="__('school.exam', 'Exam')" />
            <SearchableSelect
              id="exam_id"
              v-model="form.exam_id"
              :options="examOptions"
              :placeholder="__('school.select_exam', 'Select Exam')"
              class="mt-1 block w-full"
              :error="form.errors.exam_id"
            />
            <InputError :message="errors.exam_id" class="mt-2" />
          </div>

          <div>
            <InputLabel for="subject_id" :value="__('school.subject', 'Subject')" />
            <SearchableSelect
              id="subject_id"
              v-model="form.subject_id"
              :options="subjectOptions"
              :placeholder="__('school.select_subject', 'Select Subject')"
              class="mt-1 block w-full"
              :error="form.errors.subject_id"
            />
            <InputError :message="errors.subject_id" class="mt-2" />
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <InputLabel for="marks_obtained" :value="__('school.marks_obtained', 'Marks')" />
              <TextInput
                id="marks_obtained"
                type="number"
                class="mt-1 block w-full"
                v-model="form.marks_obtained"
                required
              />
              <InputError :message="errors.marks_obtained" class="mt-2" />
            </div>

            <div>
              <InputLabel for="grade" :value="__('school.grade', 'Grade')" />
              <TextInput
                id="grade"
                type="text"
                class="mt-1 block w-full"
                v-model="form.grade"
              />
              <InputError :message="errors.grade" class="mt-2" />
            </div>
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
      :title="__('school.delete_mark_title', 'Delete Mark')"
      :message="
        __(
          'school.delete_mark_message',
          `Are you sure you want to delete this mark entry? This action cannot be undone.`,
        )
      "
      @confirm="deleteMark"
      @close="showDeleteModal = false"
    />
  </AdminLayout>
</template>
