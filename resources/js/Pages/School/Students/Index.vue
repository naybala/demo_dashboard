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
  __("table.action", "Action"),
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

const openCreateModal = () => {
  isEditing.value = false;
  form.value = {
    id: null,
    student_code: "",
    first_name: "",
    last_name: "",
    email: "",
    gender: "male",
    dob: "",
    registration_date: "",
  };
  errors.value = {};
  showCreateEditModal.value = true;
};

const openEditModal = (student) => {
  isEditing.value = true;
  form.value = { ...student };
  errors.value = {};
  showCreateEditModal.value = true;
};

const submitForm = () => {
  const url = isEditing.value ? `/school/students/${form.value.id}` : "/school/students";
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

    <div
      class="mb-6 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between"
    >
      <div class="flex flex-1 gap-2 min-w-0">
        <TextInput
          type="text"
          :placeholder="__('messages.search_item', 'Search students...')"
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
          __("messages.create", "Add Student")
        }}</PrimaryButton>
      </div>
    </div>

    <BaseTable :headers="headers">
      <tr
        v-for="student in data"
        :key="student.id"
        class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
      >
        <td
          class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"
        >
          {{ student.student_code }}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"
        >
          {{ student.fullname }}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 uppercase"
        >
          {{ student.gender }}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          {{ student.dob }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
          <div class="flex gap-2 justify-end">
            <SecondaryButton @click="openEditModal(student)">{{
              __("messages.edit", "Edit")
            }}</SecondaryButton>
            <button
              @click="confirmDelete(student)"
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
          {{ isEditing ? __("school.edit_student", "Edit Student") : __("school.create_student", "Add Student") }}
        </h3>

        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <InputLabel for="student_code" :value="__('school.student_code', 'Student Code')" />
            <TextInput
              id="student_code"
              type="text"
              class="mt-1 block w-full"
              v-model="form.student_code"
              required
            />
            <InputError :message="errors.student_code" class="mt-2" />
          </div>

          <div>
            <InputLabel for="first_name" :value="__('school.first_name', 'First Name')" />
            <TextInput
              id="first_name"
              type="text"
              class="mt-1 block w-full"
              v-model="form.first_name"
              required
            />
            <InputError :message="errors.first_name" class="mt-2" />
          </div>

          <div>
            <InputLabel for="last_name" :value="__('school.last_name', 'Last Name')" />
            <TextInput
              id="last_name"
              type="text"
              class="mt-1 block w-full"
              v-model="form.last_name"
              required
            />
            <InputError :message="errors.last_name" class="mt-2" />
          </div>

          <div>
            <InputLabel for="email" :value="__('school.email', 'Email')" />
            <TextInput
              id="email"
              type="email"
              class="mt-1 block w-full"
              v-model="form.email"
            />
            <InputError :message="errors.email" class="mt-2" />
          </div>

          <div>
            <InputLabel for="gender" :value="__('school.gender', 'Gender')" />
            <select
              id="gender"
              v-model="form.gender"
              class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
            >
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
            <InputError :message="errors.gender" class="mt-2" />
          </div>

          <div>
            <InputLabel for="dob" :value="__('school.dob', 'Date of Birth')" />
            <TextInput
              id="dob"
              type="date"
              class="mt-1 block w-full"
              v-model="form.dob"
            />
            <InputError :message="errors.dob" class="mt-2" />
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
