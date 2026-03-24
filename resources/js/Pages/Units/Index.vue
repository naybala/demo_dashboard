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

defineProps({
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
const unitToDelete = ref(null);

const headers = [
  { key: "name", label: __("unit.name", "Unit Name") },
  { key: "actions", label: __("table.action", "Actions") },
];

const handleSearch = () => {
  router.get(
    "/units",
    { keyword: search.value },
    { preserveState: true, replace: true },
  );
};

const handleReset = () => {
  search.value = "";
  router.get("/units");
};

const confirmDelete = (unit) => {
  unitToDelete.value = unit;
  showDeleteModal.value = true;
};

const deleteUnit = () => {
  router.delete(`/units/${unitToDelete.value.id}`, {
    onSuccess: () => {
      showDeleteModal.value = false;
      unitToDelete.value = null;
    },
  });
};
</script>

<template>
  <Head>
    <title>{{ __("sidebar.unit") }}</title>
  </Head>

  <AdminLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem]"
      >
        {{ __("sidebar.unit", "Units") }}
      </h2>
    </template>

    <div
      class="mb-6 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between"
    >
      <div class="flex flex-1 gap-2 min-w-0">
        <TextInput
          type="text"
          :placeholder="__('messages.search_item', 'Search units...')"
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
        <Link v-if="permissions.includes('create units')" href="/units/create">
          <PrimaryButton>{{
            __("messages.create", "Create Unit")
          }}</PrimaryButton>
        </Link>
      </div>
    </div>

    <BaseTable :headers="headers">
      <tr
        v-for="unit in data"
        :key="unit.id"
        class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
      >
        <td
          class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"
        >
          {{ unit.name }}
        </td>
        <td class="flex gap-2 px-6 py-4">
          <Link
            v-if="permissions.includes('edit units')"
            :href="`/units/${unit.id}/edit`"
          >
            <SecondaryButton>{{ __("messages.edit", "Edit") }}</SecondaryButton>
          </Link>
          <SecondaryButton
            v-if="permissions.includes('delete units')"
            variant="danger"
            @click="confirmDelete(unit)"
          >
            {{ __("messages.delete", "Delete") }}
          </SecondaryButton>
        </td>
      </tr>
    </BaseTable>

    <Pagination :meta="meta" />

    <DeleteConfirmationModal
      :show="showDeleteModal"
      :title="__('unit.delete_title', 'Delete Unit')"
      :message="
        __(
          'unit.delete_message',
          `Are you sure you want to delete ${unitToDelete?.name}? This action cannot be undone.`,
        )
      "
      @confirm="deleteUnit"
      @close="showDeleteModal = false"
    />
  </AdminLayout>
</template>
