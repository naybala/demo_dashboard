<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import PrimaryButton from "@/Components/PrimaryButton.svelte";
  import TextInput from "@/Components/TextInput.svelte";
  import { page, router, Link } from "@inertiajs/svelte";
  import DeleteConfirmationModal from "@/Components/DeleteConfirmationModal.svelte";
  import Pagination from "@/Components/Pagination.svelte";
  import { __ } from "@/helpers.js";
  import OwnProductTable from "./OwnProductTable.svelte";
  import { useOwnProductActions } from "./useOwnProductActions";

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
  } = useOwnProductActions();

  const handleSearch = () => {
    router.get(
      "/own-products",
      { keyword: search },
      { preserveState: true, replace: true },
    );
  };

  const handleReset = () => {
    search = "";
    router.get("/own-products");
  };
</script>

<svelte:head>
  <title>{__("sidebar.own_product")}</title>
</svelte:head>
<AdminLayout>
  <svelte:fragment slot="header">
    <h2
      class="font-semibold text-[12px] md:text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.13rem]"
    >
      {__("sidebar.own_product")}
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
      {#if permissions.includes("create own-products")}
        <Link href="/own-products/create">
          <PrimaryButton class="w-full sm:w-auto"
            >{__(
              "ownProduct.create_own_product",
              "Create Own Product",
            )}</PrimaryButton
          >
        </Link>
      {/if}
    </div>
  </div>

  <OwnProductTable {data} onConfirmDelete={confirmDelete} />

  <Pagination {meta} />

  <DeleteConfirmationModal
    show={$showDeleteModal}
    title={__("ownProduct.delete_title", "Delete Own Product")}
    message={__(
      "ownProduct.delete_message",
      `Are you sure you want to delete ${$productToDelete?.name}? This action cannot be undone.`,
    )}
    onConfirm={deleteProduct}
    onClose={closeDeleteModal}
  />
</AdminLayout>
