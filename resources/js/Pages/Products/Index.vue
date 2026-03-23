<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { usePage, router, Link, Head } from "@inertiajs/vue3";
import DeleteConfirmationModal from "@/Components/DeleteConfirmationModal.vue";
import Pagination from "@/Components/Pagination.vue";
import { __ } from "@/helpers.js";
import ProductTable from "./ProductTable.vue";
import { useProductActions } from "./useProductActions";
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

const {
  showDeleteModal,
  productToDelete,
  confirmDelete,
  deleteProduct,
  closeDeleteModal,
} = useProductActions();

const handleSearch = () => {
  router.get(
    "/products",
    { keyword: search.value },
    { preserveState: true, replace: true },
  );
};

const handleReset = () => {
  search.value = "";
  router.get("/products");
};
</script>

<template>
  <Head>
    <title>{{ __("sidebar.product", "Products") }}</title>
  </Head>

  <AdminLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem]"
      >
        {{ __("sidebar.product", "Products") }}
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
          v-if="permissions.includes('create products')"
          href="/products/create"
        >
          <PrimaryButton class="w-full sm:w-auto">
            {{ __("product.create_product", "Create Product") }}
          </PrimaryButton>
        </Link>
      </div>
    </div>

    <ProductTable :data="data" @confirm-delete="confirmDelete" />

    <Pagination :meta="meta" />

    <DeleteConfirmationModal
      :show="showDeleteModal"
      :title="__('product.delete_title', 'Delete Product')"
      :message="
        __(
          'product.delete_message',
          `Are you sure you want to delete ${productToDelete?.name}? This action cannot be undone.`,
        )
      "
      @confirm="deleteProduct"
      @close="closeDeleteModal"
    />
  </AdminLayout>
</template>
