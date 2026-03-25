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
  grades: {
    type: Array,
    default: () => [],
  },
});

const page = usePage();
const permissions = computed(() => page.props.permissions || []);

const search = ref("");
const showDeleteModal = ref(false);
const classToDelete = ref(null);

const showCreateEditModal = ref(false);
const isEditing = ref(false);
const form = ref({
  id: null,
  name: "",
  grade_id: "",
  status: "active",
});
const errors = ref({});

const headers = [
  __("school.class_name", "Class Name"),
  __("school.grade", "Grade"),
  __("school.status", "Status"),
  __("table.action", "Action"),
];

const handleSearch = () => {
  router.get(
    "/classes",
    { keyword: search.value },
    { preserveState: true, replace: true },
  );
};

const handleReset = () => {
  search.value = "";
  router.get("/classes");
};

const openCreateModal = () => {
  isEditing.value = false;
  form.value = { id: null, name: "", grade_id: props.grades[0]?.id || "", status: "active" };
  errors.value = {};
  showCreateEditModal.value = true;
};

const openEditModal = (cls) => {
  isEditing.value = true;
  form.value = { ...cls };
  errors.value = {};
  showCreateEditModal.value = true;
};

const submitForm = () => {
  const url = isEditing.value ? `/classes/${form.value.id}` : "/classes";
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

const confirmDelete = (cls) => {
  classToDelete.value = cls;
  showDeleteModal.value = true;
};

const deleteClass = () => {
  router.delete(`/classes/${classToDelete.value.id}`, {
    onSuccess: () => {
      showDeleteModal.value = false;
      classToDelete.value = null;
    },
  });
};
</script>

<template>
  <Head>
    <title>{{ __("school.classes", "Classes") }}</title>
  </Head>

  <AdminLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem]"
      >
        {{ __("school.classes", "Classes") }}
      </h2>
    </template>

    <div
      class="mb-6 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between"
    >
      <div class="flex flex-1 gap-2 min-w-0">
        <TextInput
          type="text"
          :placeholder="__('messages.search_item', 'Search classes...')"
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
          __("messages.create", "Create Class")
        }}</PrimaryButton>
      </div>
    </div>

    <BaseTable :headers="headers">
      <tr
        v-for="cls in data"
        :key="cls.id"
        class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
      >
        <td
          class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"
        >
          {{ cls.name }}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          {{ cls.grade_name }}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          <span
            class="px-2 py-1 rounded-full text-xs font-semibold"
            :class="
              cls.status === 'active'
                ? 'bg-green-100 text-green-800'
                : 'bg-red-100 text-red-800'
            "
          >
            {{ cls.status }}
          </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
          <div class="flex gap-2 justify-end">
            <SecondaryButton @click="openEditModal(cls)">{{
              __("messages.edit", "Edit")
            }}</SecondaryButton>
            <button
              @click="confirmDelete(cls)"
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
          {{ isEditing ? __("school.edit_class", "Edit Class") : __("school.create_class", "Create Class") }}
        </h3>

        <div class="mt-4 space-y-4">
          <div>
            <InputLabel for="name" :value="__('school.class_name', 'Class Name')" />
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
            <InputLabel for="grade_id" :value="__('school.grade', 'Grade')" />
            <select
              id="grade_id"
              v-model="form.grade_id"
              class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
            >
              <option v-for="grade in grades" :key="grade.id" :value="grade.id">
                {{ grade.name }}
              </option>
            </select>
            <InputError :message="errors.grade_id" class="mt-2" />
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
      :title="__('school.delete_class_title', 'Delete Class')"
      :message="
        __(
          'school.delete_class_message',
          `Are you sure you want to delete ${classToDelete?.name}? This action cannot be undone.`,
        )
      "
      @confirm="deleteClass"
      @close="showDeleteModal = false"
    />
  </AdminLayout>
</template>
