<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { usePage, router, Link, Head } from "@inertiajs/vue3";
import DeleteConfirmationModal from "@/Components/DeleteConfirmationModal.vue";
import Pagination from "@/Components/Pagination.vue";
import { __ } from "@/helpers.js";
import OwnProductTable from "./OwnProductTable.vue";
import { useOwnProductActions } from "./useOwnProductActions";
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
  filters: {
    type: Object,
    default: () => ({}),
  },
});

const page = usePage();
const permissions = computed(() => page.props.permissions || []);

const search = ref(props.filters.keyword || "");

const {
  showDeleteModal,
  productToDelete,
  confirmDelete,
  deleteProduct,
  closeDeleteModal,
} = useOwnProductActions();

const handleSearch = () => {
  router.get(
    "/own-products",
    { keyword: search.value },
    { preserveState: true, replace: true },
  );
};

const handleReset = () => {
  search.value = "";
  router.get("/own-products");
};
</script>

<template>
  <Head>
    <title>{{ __("sidebar.own_product") }}</title>
  </Head>

  <AdminLayout>
    <template #header>
      <h2
        class="font-semibold text-[12px] md:text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.13rem]"
      >
        {{ __("sidebar.own_product") }}
      </h2>
    </template>

    <div
      class="mb-6 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between"
    >
      <div class="flex flex-1 gap-2 min-w-0">
        <TextInput
          type="text"
          :placeholder="__('messages.search_item', 'Search products...')"
          v-model="search"
          @keydown.enter="handleSearch"
          class="w-full"
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
        <Link
          v-if="permissions.includes('create own-products')"
          href="/own-products/create"
        >
          <PrimaryButton class="w-full sm:w-auto">
            {{ __("ownProduct.create_own_product", "Create Own Product") }}
          </PrimaryButton>
        </Link>
      </div>
    </div>

    <OwnProductTable :data="data" @confirm-delete="confirmDelete" />

    <Pagination :meta="meta" />

    <DeleteConfirmationModal
      :show="showDeleteModal"
      :title="__('ownProduct.delete_title', 'Delete Own Product')"
      :message="
        __(
          'ownProduct.delete_message',
          `Are you sure you want to delete ${productToDelete?.name}? This action cannot be undone.`,
        )
      "
      @confirm="deleteProduct"
      @close="closeDeleteModal"
    />
  </AdminLayout>
</template>
