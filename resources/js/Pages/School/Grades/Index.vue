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
const gradeToDelete = ref(null);

const showCreateEditModal = ref(false);
const isEditing = ref(false);
const form = ref({
  id: null,
  name: "",
  code: "",
  status: "active",
});
const errors = ref({});

const headers = [
  __("school.grade_name", "Grade Name"),
  __("school.grade_code", "Code"),
  __("school.status", "Status"),
  { label: __("table.action", "Action"), class: "text-center" },
];

const handleSearch = () => {
  router.get(
    "/grades",
    { keyword: search.value },
    { preserveState: true, replace: true },
  );
};

const handleReset = () => {
  search.value = "";
  router.get("/grades");
};

const openCreateModal = () => {
  isEditing.value = false;
  form.value = { id: null, name: "", code: "", status: "active" };
  errors.value = {};
  showCreateEditModal.value = true;
};

const openEditModal = (grade) => {
  isEditing.value = true;
  form.value = { ...grade };
  errors.value = {};
  showCreateEditModal.value = true;
};

const submitForm = () => {
  const url = isEditing.value ? `/grades/${form.value.id}` : "/grades";
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

const confirmDelete = (grade) => {
  gradeToDelete.value = grade;
  showDeleteModal.value = true;
};

const deleteGrade = () => {
  router.delete(`/grades/${gradeToDelete.value.id}`, {
    onSuccess: () => {
      showDeleteModal.value = false;
      gradeToDelete.value = null;
    },
  });
};
</script>

<template>
  <Head>
    <title>{{ __("school.grades", "Grades") }}</title>
  </Head>

  <AdminLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem]"
      >
        {{ __("school.grades", "Grades") }}
      </h2>
    </template>

    <div
      class="mb-6 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between"
    >
      <div class="flex flex-1 gap-2 min-w-0">
        <TextInput
          type="text"
          :placeholder="__('messages.search_item', 'Search grades...')"
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
          __("messages.create", "Create Grade")
        }}</PrimaryButton>
      </div>
    </div>

    <BaseTable :headers="headers">
      <tr
        v-for="grade in data"
        :key="grade.id"
        class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
      >
        <td
          class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"
        >
          {{ grade.name }}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          {{ grade.code }}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          <span
            class="px-2 py-1 rounded-full text-xs font-semibold"
            :class="
              grade.status === 'active'
                ? 'bg-green-100 text-green-800'
                : 'bg-red-100 text-red-800'
            "
          >
            {{ grade.status }}
          </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
          <div class="flex gap-2 justify-center">
            <SecondaryButton @click="openEditModal(grade)">{{
              __("messages.edit", "Edit")
            }}</SecondaryButton>
            <button
              @click="confirmDelete(grade)"
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
          {{
            isEditing
              ? __("school.edit_grade", "Edit Grade")
              : __("school.create_grade", "Create Grade")
          }}
        </h3>

        <div class="mt-4 space-y-4">
          <div>
            <InputLabel
              for="name"
              :value="__('school.grade_name', 'Grade Name')"
            />
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
            <InputLabel
              for="code"
              :value="__('school.grade_code', 'Grade Code')"
            />
            <TextInput
              id="code"
              type="text"
              class="mt-1 block w-full"
              v-model="form.code"
              required
            />
            <InputError :message="errors.code" class="mt-2" />
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
      :title="__('school.delete_grade_title', 'Delete Grade')"
      :message="
        __(
          'school.delete_grade_message',
          `Are you sure you want to delete ${gradeToDelete?.name}? This action cannot be undone.`,
        )
      "
      @confirm="deleteGrade"
      @close="showDeleteModal = false"
    />
  </AdminLayout>
</template>
