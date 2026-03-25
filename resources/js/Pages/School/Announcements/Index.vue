<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import BaseTable from "@/Components/BaseTable.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import Modal from "@/Components/Modal.vue";
import Pagination from "@/Components/Pagination.vue";
import DeleteConfirmationModal from "@/Components/DeleteConfirmationModal.vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import { __ } from "@/helpers.js";
import { ref, computed } from "vue";

const props = defineProps({
  data: Array,
  meta: Object,
  filters: Object,
  departments: Array,
  destinations: Array,
});

const search = ref(props.filters.keyword || "");
const startDate = ref(props.filters.start_date || "");
const endDate = ref(props.filters.end_date || "");

const showCreateModal = ref(false);
const showDeleteModal = ref(false);
const isEditing = ref(false);
const announcementToDelete = ref(null);

const form = useForm({
  id: null,
  title: "",
  description: "",
  department: "",
  date: "",
  destination: "",
});

const headers = [
  { label: __("announcement.title_name", "Title name"), class: "text-left" },
  { label: __("announcement.description", "Description"), class: "text-left" },
  { label: __("announcement.date", "Date"), class: "text-left" },
  { label: __("announcement.to", "To"), class: "text-left" },
  { label: __("table.actions", "Actions"), class: "text-center" },
];

const handleFilter = () => {
  router.get(
    "/announcements",
    {
      keyword: search.value,
      start_date: startDate.value,
      end_date: endDate.value,
    },
    { preserveState: true, replace: true },
  );
};

const handleReset = () => {
  search.value = "";
  startDate.value = "";
  endDate.value = "";
  handleFilter();
};

const openCreateModal = () => {
  isEditing.value = false;
  form.reset();
  form.clearErrors();
  showCreateModal.value = true;
};

const openEditModal = (announcement) => {
  isEditing.value = true;
  form.clearErrors();
  form.id = announcement.id;
  form.title = announcement.title;
  form.description = announcement.description;
  form.department = announcement.department;
  form.date = announcement.date;
  form.destination = announcement.destination;
  showCreateModal.value = true;
};

const submit = () => {
  if (isEditing.value) {
    form.put("/announcements/" + form.id, {
      onSuccess: () => closeModal(),
    });
  } else {
    form.post("/announcements", {
      onSuccess: () => closeModal(),
    });
  }
};

const closeModal = () => {
  showCreateModal.value = false;
  form.reset();
};

const confirmDelete = (announcement) => {
  announcementToDelete.value = announcement;
  showDeleteModal.value = true;
};

const deleteAnnouncement = () => {
  router.delete("/announcements/" + announcementToDelete.value.id, {
    onSuccess: () => {
      showDeleteModal.value = false;
      announcementToDelete.value = null;
    },
  });
};
</script>

<template>
  <Head :title="__('announcement.management', 'Announcement Management')" />

  <AdminLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __("announcement.management", "Announcement Management") }}
      </h2>
    </template>

    <div class="py-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <!-- Header & Stats -->
        <div class="mb-6">
          <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ __("announcement.management", "Announcement Management") }}
          </h3>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            {{ __("announcement.subtitle", "Manage your school announcements.") }}
          </p>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 mb-6">
          <div class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:w-64">
              <InputLabel :value="__('announcement.duration', 'Duration')" class="mb-1" />
              <div class="flex gap-2">
                <TextInput
                  type="date"
                  v-model="startDate"
                  @change="handleFilter"
                  class="w-full text-xs"
                />
                <span class="self-center text-gray-400">to</span>
                <TextInput
                  type="date"
                  v-model="endDate"
                  @change="handleFilter"
                  class="w-full text-xs"
                />
              </div>
            </div>

            <div class="flex-1 w-full">
              <TextInput
                type="text"
                v-model="search"
                :placeholder="__('messages.search', 'Search...')"
                @keyup.enter="handleFilter"
                class="w-full"
              />
            </div>

            <div class="flex gap-2 shrink-0">
                <SecondaryButton @click="handleReset" v-if="search || startDate || endDate">
                    {{ __("messages.reset", "Reset") }}
                </SecondaryButton>
              <PrimaryButton @click="openCreateModal" class="bg-blue-600 hover:bg-blue-700 whitespace-nowrap">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __("announcement.create_btn", "Create Announce") }}
              </PrimaryButton>
            </div>
          </div>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
          <div class="p-4 border-b border-gray-200 dark:border-gray-700 text-sm text-gray-500">
            Showing {{ data.length }} of {{ meta.total }} announcements
          </div>
          
          <BaseTable :headers="headers">
            <tr v-for="announcement in data" :key="announcement.id" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors border-b border-gray-100 dark:border-gray-700 last:border-0">
              <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white max-w-xs truncate">
                {{ announcement.title }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 max-w-md truncate">
                {{ announcement.description }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                {{ announcement.date_formatted }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                {{ announcement.destination_label }}
              </td>
              <td class="px-6 py-4 text-sm text-center">
                <div class="flex items-center justify-center gap-3">
                  <button @click="openEditModal(announcement)" class="text-gray-400 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                  </button>
                  <button @click="openEditModal(announcement)" class="text-gray-400 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                  </button>
                  <button @click="confirmDelete(announcement)" class="text-gray-400 hover:text-red-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="data.length === 0">
              <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                {{ __("messages.no_data", "No announcements found.") }}
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
    <Modal :show="showCreateModal" @close="closeModal" maxWidth="2xl">
      <div class="p-6">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-lg font-bold text-gray-900 dark:text-white">
            {{ isEditing ? __("announcement.edit", "Edit Announcement") : __("announcement.create", "Create Announcement") }}
          </h3>
          <button @click="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
          <div>
            <InputLabel for="title" :value="__('announcement.title', 'Title') + ' *'" />
            <TextInput
              id="title"
              v-model="form.title"
              type="text"
              class="mt-1 block w-full"
              required
              :placeholder="__('announcement.title_placeholder', 'e.g. Water')"
            />
            <InputError :message="form.errors.title" class="mt-2" />
          </div>

          <div>
            <InputLabel for="description" :value="__('announcement.description', 'Description') + ' *'" />
            <textarea
              id="description"
              v-model="form.description"
              class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
              rows="4"
              required
              :placeholder="__('announcement.description_placeholder', 'Writing description...')"
            ></textarea>
            <InputError :message="form.errors.description" class="mt-2" />
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <InputLabel for="department" :value="__('announcement.department', 'Department') + ' *'" />
              <select
                id="department"
                v-model="form.department"
                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                required
              >
                <option value="" disabled>{{ __("announcement.choose_dept", "Choose department...") }}</option>
                <option v-for="dept in departments" :key="dept.value" :value="dept.value">
                  {{ dept.label }}
                </option>
              </select>
              <InputError :message="form.errors.department" class="mt-2" />
            </div>

            <div>
              <InputLabel for="date" :value="__('announcement.date', 'Date') + ' *'" />
              <TextInput
                id="date"
                v-model="form.date"
                type="date"
                class="mt-1 block w-full"
                required
              />
              <InputError :message="form.errors.date" class="mt-2" />
            </div>
          </div>

          <div>
            <InputLabel for="destination" :value="__('announcement.to', 'To') + ' *'" />
            <select
              id="destination"
              v-model="form.destination"
              class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
              required
            >
              <option value="" disabled>{{ __("announcement.choose_dest", "Choose destination...") }}</option>
              <option v-for="dest in destinations" :key="dest.value" :value="dest.value">
                {{ dest.label }}
              </option>
            </select>
            <InputError :message="form.errors.destination" class="mt-2" />
          </div>

          <div class="flex items-center justify-end gap-3 mt-6">
            <SecondaryButton @click="closeModal">{{ __("messages.cancel", "Cancel") }}</SecondaryButton>
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
              {{ isEditing ? __("messages.update", "Update") : __("messages.create", "Create") }}
            </PrimaryButton>
          </div>
        </form>
      </div>
    </Modal>

    <!-- Delete Modal -->
    <DeleteConfirmationModal
      :show="showDeleteModal"
      :title="__('announcement.delete_title', 'Delete Announcement')"
      :message="__('announcement.delete_message', 'Are you sure you want to delete this announcement? This action cannot be undone.')"
      @confirm="deleteAnnouncement"
      @close="showDeleteModal = false"
    />
  </AdminLayout>
</template>
