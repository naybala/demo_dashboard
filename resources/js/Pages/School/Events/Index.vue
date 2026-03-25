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
import SearchableSelect from "@/Components/SearchableSelect.vue";
import { Head, useForm, router, usePage, Link } from "@inertiajs/vue3";
import { __ } from "@/helpers.js";
import { ref, computed } from "vue";

const props = defineProps({
  data: Array,
  meta: Object,
  filters: Object,
  types: Array,
});

const page = usePage();
const permissions = computed(() => page.props.permissions || []);

const typeOptions = computed(() =>
  props.types.map((type) => ({ id: type.value, label: type.label })),
);

const search = ref(props.filters.keyword || "");
const startDate = ref(props.filters.start_date || "");
const endDate = ref(props.filters.end_date || "");

const showCreateModal = ref(false);
const showDeleteModal = ref(false);
const isEditing = ref(false);
const eventToDelete = ref(null);

const form = useForm({
  id: null,
  title: "",
  description: "",
  type: "",
  date: "",
  start_time: "",
  end_time: "",
  location: "",
});

const headers = [
  { label: __("event.title", "Title"), class: "text-left" },
  { label: __("event.time", "Time"), class: "text-left" },
  { label: __("event.location", "Location"), class: "text-left" },
  { label: __("event.date", "Date"), class: "text-left" },
  { label: __("event.type", "Type"), class: "text-center" },
  { label: __("table.actions", "Actions"), class: "text-center" },
];

const handleFilter = () => {
  router.get(
    "/events",
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

const openEditModal = (event) => {
  isEditing.value = true;
  form.clearErrors();
  form.id = event.id;
  form.title = event.title;
  form.description = event.description;
  form.type = event.type;
  form.date = event.date;
  form.start_time = event.start_time;
  form.end_time = event.end_time;
  form.location = event.location;
  showCreateModal.value = true;
};

const submit = () => {
  if (isEditing.value) {
    form.put("/events/" + form.id, {
      onSuccess: () => closeModal(),
    });
  } else {
    form.post("/events", {
      onSuccess: () => closeModal(),
    });
  }
};

const closeModal = () => {
  showCreateModal.value = false;
  form.reset();
};

const confirmDelete = (event) => {
  eventToDelete.value = event;
  showDeleteModal.value = true;
};

const deleteEvent = () => {
  router.delete("/events/" + eventToDelete.value.id, {
    onSuccess: () => {
      showDeleteModal.value = false;
      eventToDelete.value = null;
    },
  });
};

const getTypeColor = (type) => {
  switch (type) {
    case "meeting":
      return "bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400";
    case "sport":
      return "bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400";
    case "academic":
      return "bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400";
    case "culture":
      return "bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400";
    default:
      return "bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300";
  }
};
</script>

<template>
  <Head :title="__('event.management', 'Event Management')" />

  <AdminLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
      >
        {{ __("event.management", "Event Management") }}
      </h2>
    </template>

    <div class="py-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <!-- Header & Stats -->
        <div class="mb-6">
          <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ __("event.management", "Event Management") }}
          </h3>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            {{ __("event.subtitle", "Manage your school events.") }}
          </p>
        </div>

        <!-- Filter Bar -->
        <div
          class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 mb-6"
        >
          <div class="flex flex-col md:flex-row gap-4 items-end">
            <!-- Duration Section -->
            <div class="shrink-0 w-full md:w-auto">
              <InputLabel
                :value="__('event.duration', 'Duration')"
                class="mb-1"
              />
              <div class="flex items-center gap-2">
                <TextInput
                  type="date"
                  v-model="startDate"
                  @change="handleFilter"
                  class="w-full md:w-36 text-sm"
                />
                <span class="text-gray-400">to</span>
                <TextInput
                  type="date"
                  v-model="endDate"
                  @change="handleFilter"
                  class="w-full md:w-36 text-sm"
                />
              </div>
            </div>

            <!-- Search Section -->
            <div class="flex-1 w-full flex gap-2">
              <TextInput
                type="text"
                v-model="search"
                :placeholder="__('messages.search', 'Search...')"
                @keyup.enter="handleFilter"
                class="flex-1"
              />
              <PrimaryButton
                @click="handleFilter"
                class="bg-gray-700 hover:bg-gray-800 shrink-0"
              >
                {{ __("messages.search", "Search") }}
              </PrimaryButton>
            </div>

            <!-- Actions Section -->
            <div class="flex gap-2 shrink-0 w-full md:w-auto mt-4 md:mt-0">
              <SecondaryButton
                @click="handleReset"
                v-if="search || startDate || endDate"
                class="flex-1 md:flex-none"
              >
                {{ __("messages.reset", "Reset") }}
              </SecondaryButton>
              <PrimaryButton
                v-if="permissions.includes('create events')"
                @click="openCreateModal"
                class="bg-blue-600 hover:bg-blue-700 whitespace-nowrap flex-1 md:flex-none justify-center"
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
                {{ __("event.create_btn", "Create Event") }}
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
            Showing {{ data.length }} of {{ meta.total }} events
          </div>

          <BaseTable :headers="headers">
            <tr
              v-for="event in data"
              :key="event.id"
              class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors border-b border-gray-100 dark:border-gray-700 last:border-0"
            >
              <td
                class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white max-w-xs truncate"
              >
                {{ event.title }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                {{ event.time_formatted }}
              </td>
              <td
                class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 max-w-xs truncate"
              >
                {{ event.location }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                {{ event.date_formatted }}
              </td>
              <td class="px-6 py-4 text-sm text-center">
                <span
                  class="px-2.5 py-1 rounded-full text-xs font-semibold inline-flex items-center"
                  :class="getTypeColor(event.type)"
                >
                  {{ event.type_label }}
                </span>
              </td>
              <td class="px-6 py-4 text-sm text-center">
                <div class="flex items-center justify-center gap-2">
                  <SecondaryButton
                    v-if="permissions.includes('show events')"
                    @click="openEditModal(event)"
                  >
                    {{ __("messages.view", "View") }}
                  </SecondaryButton>
                  <SecondaryButton
                    v-if="permissions.includes('edit events')"
                    @click="openEditModal(event)"
                  >
                    {{ __("messages.edit", "Edit") }}
                  </SecondaryButton>
                  <SecondaryButton
                    v-if="permissions.includes('delete events')"
                    variant="danger"
                    @click="confirmDelete(event)"
                  >
                    {{ __("messages.delete", "Delete") }}
                  </SecondaryButton>
                </div>
              </td>
            </tr>
            <tr v-if="data.length === 0">
              <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                {{ __("messages.no_data", "No events found.") }}
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
            {{
              isEditing
                ? __("event.edit", "Edit Event")
                : __("event.create", "Create Event")
            }}
          </h3>
          <button
            @click="closeModal"
            class="text-gray-400 hover:text-gray-600 transition-colors"
          ></button>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
          <div>
            <InputLabel for="type" :value="__('event.type', 'Type') + ' *'" />
            <SearchableSelect
              id="type"
              v-model="form.type"
              :options="typeOptions"
              :placeholder="__('event.choose_type', 'Select type...')"
              class="mt-1 block w-full"
              required
              :error="form.errors.type"
            />
            <InputError :message="form.errors.type" class="mt-2" />
          </div>

          <div>
            <InputLabel
              for="title"
              :value="__('event.title', 'Title') + ' *'"
            />
            <TextInput
              id="title"
              v-model="form.title"
              type="text"
              class="mt-1 block w-full"
              required
              :placeholder="__('event.title_placeholder', 'e.g. Winter Break')"
            />
            <InputError :message="form.errors.title" class="mt-2" />
          </div>

          <div>
            <InputLabel
              for="description"
              :value="__('event.description', 'Description') + ' *'"
            />
            <textarea
              id="description"
              v-model="form.description"
              class="p-1 mt-1 block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
              rows="3"
              required
              :placeholder="
                __('event.description_placeholder', 'Writing description...')
              "
            ></textarea>
            <InputError :message="form.errors.description" class="mt-2" />
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <InputLabel
                for="start_time"
                :value="__('event.start_time', 'Start Time') + ' *'"
              />
              <TextInput
                id="start_time"
                v-model="form.start_time"
                type="time"
                class="mt-1 block w-full"
                required
              />
              <InputError :message="form.errors.start_time" class="mt-2" />
            </div>
            <div>
              <InputLabel
                for="end_time"
                :value="__('event.end_time', 'End Time') + ' *'"
              />
              <TextInput
                id="end_time"
                v-model="form.end_time"
                type="time"
                class="mt-1 block w-full"
                required
              />
              <InputError :message="form.errors.end_time" class="mt-2" />
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <InputLabel for="date" :value="__('event.date', 'Date') + ' *'" />
              <TextInput
                id="date"
                v-model="form.date"
                type="date"
                class="mt-1 block w-full"
                required
              />
              <InputError :message="form.errors.date" class="mt-2" />
            </div>
            <div>
              <InputLabel
                for="location"
                :value="__('event.location', 'Location') + ' *'"
              />
              <TextInput
                id="location"
                v-model="form.location"
                type="text"
                class="mt-1 block w-full"
                required
                :placeholder="
                  __('event.location_placeholder', 'Enter location...')
                "
              />
              <InputError :message="form.errors.location" class="mt-2" />
            </div>
          </div>

          <div class="flex items-center justify-end gap-3 mt-6">
            <SecondaryButton @click="closeModal">{{
              __("messages.cancel", "Cancel")
            }}</SecondaryButton>
            <PrimaryButton
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
            >
              {{
                isEditing
                  ? __("messages.update", "Update")
                  : __("messages.create", "Create")
              }}
            </PrimaryButton>
          </div>
        </form>
      </div>
    </Modal>

    <!-- Delete Modal -->
    <DeleteConfirmationModal
      :show="showDeleteModal"
      :title="__('event.delete_title', 'Delete Event')"
      :message="
        __(
          'event.delete_message',
          'Are you sure you want to delete this event? This action cannot be undone.',
        )
      "
      @confirm="deleteEvent"
      @close="showDeleteModal = false"
    />
  </AdminLayout>
</template>
