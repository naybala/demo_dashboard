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
});

const page = usePage();
const permissions = computed(() => page.props.permissions || []);

const search = ref("");
const showDeleteModal = ref(false);
const studentToDelete = ref(null);

const showCreateEditModal = ref(false);
const isEditing = ref(false);
const form = ref({
  id: null,
  student_code: "",
  first_name: "",
  last_name: "",
  email: "",
  gender: "male",
  dob: "",
  registration_date: "",
});
const errors = ref({});

const headers = [
  __("school.student_code", "Code"),
  __("school.student_name", "Full Name"),
  __("school.gender", "Gender"),
  __("school.dob", "DOB"),
  { label: __("table.action", "Action"), class: "text-center" },
];

const handleSearch = () => {
  router.get(
    "/students",
    { keyword: search.value },
    { preserveState: true, replace: true },
  );
};

const handleReset = () => {
  search.value = "";
  router.get("/students");
};

const openCreatePage = () => {
  router.get("/students/create");
};

const openEditPage = (student) => {
  router.get(`/students/${student.id}/edit`);
};

const confirmDelete = (student) => {
  studentToDelete.value = student;
  showDeleteModal.value = true;
};

const deleteStudent = () => {
  router.delete(`/students/${studentToDelete.value.id}`, {
    onSuccess: () => {
      showDeleteModal.value = false;
      studentToDelete.value = null;
    },
  });
};
</script>

<template>
  <Head>
    <title>{{ __("school.students", "Students") }}</title>
  </Head>

  <AdminLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem]"
      >
        {{ __("school.students", "Students") }}
      </h2>
    </template>

    <div class="py-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <!-- Header & Stats -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
              {{ __("school.students", "Students") }}
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
              {{ __("school.students_subtitle", "Manage your school students.") }}
            </p>
          </div>
          <div class="flex items-center gap-3">
            <PrimaryButton
              @click="openCreatePage"
              class="flex items-center gap-2 text-sm font-medium bg-blue-600 hover:bg-blue-700"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              {{ __("messages.create", "Add Student") }}
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
                :placeholder="__('messages.search_item', 'Search students...')"
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
            Showing {{ data.length }} of {{ meta.total }} students
          </div>
          
          <BaseTable :headers="headers">
            <tr
              v-for="student in data"
              :key="student.id"
              class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors border-b border-gray-100 dark:border-gray-700 last:border-0"
            >
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                {{ student.student_code }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-medium">
                {{ student.full_name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 capitalize">
                {{ student.gender }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                {{ student.dob }}
              </td>
              <td class="px-6 py-4 text-sm text-center">
                <div class="flex items-center justify-center gap-2">
                  <Link
                    v-if="permissions.includes('edit students')"
                    :href="`/students/${student.id}/edit`"
                  >
                    <SecondaryButton>{{ __("messages.edit", "Edit") }}</SecondaryButton>
                  </Link>
                  <SecondaryButton
                    v-if="permissions.includes('delete students')"
                    variant="danger"
                    @click="confirmDelete(student)"
                  >
                    {{ __("messages.delete", "Delete") }}
                  </SecondaryButton>
                </div>
              </td>
            </tr>
            <tr v-if="data.length === 0">
              <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                {{ __("messages.no_data", "No students found.") }}
              </td>
            </tr>
          </BaseTable>

          <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
            <Pagination :meta="meta" />
          </div>
        </div>
      </div>
    </div>

    <DeleteConfirmationModal
      :show="showDeleteModal"
      :title="__('school.delete_student_title', 'Delete Student')"
      :message="
        __(
          'school.delete_student_message',
          `Are you sure you want to delete ${studentToDelete?.fullname}? This action cannot be undone.`,
        )
      "
      @confirm="deleteStudent"
      @close="showDeleteModal = false"
    />
  </AdminLayout>
</template>
