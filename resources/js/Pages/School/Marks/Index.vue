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

    <div
      class="mb-6 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between"
    >
      <div class="flex flex-1 gap-2 min-w-0">
        <TextInput
          type="text"
          :placeholder="__('messages.search_item', 'Search marks...')"
          v-model="search"
          @keydown.enter="handleSearch"
          class="w-full md:w-1/3"
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
        <PrimaryButton @click="openCreateModal">{{
          __("messages.create", "Record Mark")
        }}</PrimaryButton>
      </div>
    </div>

    <BaseTable :headers="headers">
      <tr
        v-for="mark in data"
        :key="mark.id"
        class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
      >
        <td
          class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"
        >
          {{ mark.student_name }}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          {{ mark.exam_name }}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          {{ mark.subject_name }}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-semibold"
        >
          {{ mark.marks_obtained }}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          {{ mark.grade }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
          <div class="flex gap-2 justify-end">
            <SecondaryButton @click="openEditModal(mark)">{{
              __("messages.edit", "Edit")
            }}</SecondaryButton>
            <button
              @click="confirmDelete(mark)"
              class="text-red-600 hover:text-red-900 font-medium ml-2"
            >
              {{ __("messages.delete", "Delete") }}
            </button>
          </div>
        </td>
      </tr>
    </BaseTable>

    <Pagination :meta="meta" />

    <!-- Create/Edit Modal -->
    <Modal :show="showCreateEditModal" @close="showCreateEditModal = false">
      <div class="p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
          {{ isEditing ? __("school.edit_mark", "Edit Mark") : __("school.record_mark", "Record Mark") }}
        </h3>

        <div class="mt-4 space-y-4">
          <div>
            <InputLabel for="student_id" :value="__('school.student', 'Student')" />
            <select
              id="student_id"
              v-model="form.student_id"
              class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
            >
              <option v-for="student in students" :key="student.id" :value="student.id">
                {{ student.name }}
              </option>
            </select>
            <InputError :message="errors.student_id" class="mt-2" />
          </div>

          <div>
            <InputLabel for="exam_id" :value="__('school.exam', 'Exam')" />
            <select
              id="exam_id"
              v-model="form.exam_id"
              class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
            >
              <option v-for="exam in exams" :key="exam.id" :value="exam.id">
                {{ exam.name }}
              </option>
            </select>
            <InputError :message="errors.exam_id" class="mt-2" />
          </div>

          <div>
            <InputLabel for="subject_id" :value="__('school.subject', 'Subject')" />
            <select
              id="subject_id"
              v-model="form.subject_id"
              class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
            >
              <option v-for="subject in subjects" :key="subject.id" :value="subject.id">
                {{ subject.name }}
              </option>
            </select>
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
