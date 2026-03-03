<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import PrimaryButton from "@/Components/PrimaryButton.svelte";
  import TextInput from "@/Components/TextInput.svelte";
  import { page, router, Link } from "@inertiajs/svelte";
  import DeleteConfirmationModal from "@/Components/DeleteConfirmationModal.svelte";
  import Pagination from "@/Components/Pagination.svelte";
  import { __ } from "@/helpers.js";
  import ProductTable from "./ProductTable.svelte";
  import { useProductActions } from "./useProductActions";

  export let data = [];
  export let meta = {};

  $: permissions = $page.props.permissions || [];

  let search = "";

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
      { keyword: search },
      { preserveState: true, replace: true },
    );
  };

  const handleReset = () => {
    search = "";
    router.get("/products");
  };
</script>

<AdminLayout>
  <svelte:fragment slot="header">
    <h2
      class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem]"
    >
      {__("sidebar.product", "Products")}
    </h2>
  </svelte:fragment>

  <div
    class="mb-6 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between"
  >
    <div class="flex flex-1 gap-2 min-w-0">
      <TextInput
        type="text"
        placeholder={__("messages.search_item", "Search products...")}
        bind:value={search}
        on:keydown={(e) => e.key === "Enter" && handleSearch()}
        class="w-full"
      />
      <SecondaryButton on:click={handleSearch}>
        {__("messages.search", "Search")}
      </SecondaryButton>
      {#if search}
        <SecondaryButton
          class="bg-gray-100 dark:bg-gray-700"
          on:click={handleReset}
        >
          {__("messages.reset", "Clear")}
        </SecondaryButton>
      {/if}
    </div>
    <div class="flex-shrink-0">
      {#if permissions.includes("create products")}
        <Link href="/products/create">
          <PrimaryButton class="w-full sm:w-auto"
            >{__("product.create_product", "Create Product")}</PrimaryButton
          >
        </Link>
      {/if}
    </div>
  </div>

  <ProductTable {data} onConfirmDelete={confirmDelete} />

  <Pagination {meta} />

  <DeleteConfirmationModal
    show={$showDeleteModal}
    title={__("product.delete_title", "Delete Product")}
    message={__(
      "product.delete_message",
      `Are you sure you want to delete ${$productToDelete?.name}? This action cannot be undone.`,
    )}
    onConfirm={deleteProduct}
    onClose={closeDeleteModal}
  />
</AdminLayout>
