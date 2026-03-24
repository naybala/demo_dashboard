<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import CategoryTable from "./CategoryTable.vue";
import CategoryModal from "./CategoryModal.vue";
import DeleteConfirmationModal from "@/Components/DeleteConfirmationModal.vue";
import Pagination from "@/Components/Pagination.vue";
import { useCategoryActions } from "./useCategoryActions";
import { usePage, router, Head } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { __ } from "@/helpers.js";
import { ref, computed } from "vue";

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

const {
  showModal,
  showDeleteModal,
  editingCategory,
  openCreateModal,
  openEditModal,
  openDeleteModal,
  closeDeleteModal,
  confirmDelete,
} = useCategoryActions();

const search = ref("");

const handleSearch = () => {
  router.get(
    "/categories",
    { keyword: search.value },
    { preserveState: true, replace: true },
  );
};

const handleReset = () => {
  search.value = "";
  router.get("/categories");
};
</script>

<template>
  <Head>
    <title>{{ __("sidebar.category", "Categories") }}</title>
  </Head>

  <AdminLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem]"
      >
        {{ __("sidebar.category", "Categories") }}
      </h2>
    </template>

    <div
      class="mb-6 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between"
    >
      <div class="flex flex-1 gap-2 min-w-0">
        <TextInput
          type="text"
          :placeholder="__('messages.search_item', 'Search categories...')"
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
        <PrimaryButton
          v-if="permissions.includes('create categories')"
          @click="openCreateModal"
          class="w-full sm:w-auto"
        >
          {{ __("messages.create", "Add Category") }}
        </PrimaryButton>
      </div>
    </div>

    <div class="mt-6">
      <CategoryTable
        :categories="data"
        @edit="openEditModal"
        @delete="openDeleteModal"
      />
    </div>

    <Pagination :meta="meta" />

    <CategoryModal v-model:show="showModal" :category="editingCategory" />

    <DeleteConfirmationModal
      :show="showDeleteModal"
      @close="closeDeleteModal"
      @confirm="confirmDelete"
      :title="__('messages.confirm_delete_title', 'Delete Category')"
      :message="
        __(
          'messages.confirm_delete_message',
          'Are you sure you want to delete this category? This action cannot be undone.',
        )
      "
    />
  </AdminLayout>
</template>
