<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import BaseTable from "@/Components/BaseTable.svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import PrimaryButton from "@/Components/PrimaryButton.svelte";
  import TextInput from "@/Components/TextInput.svelte";
  import { page, router, Link } from "@inertiajs/svelte";
  import DeleteConfirmationModal from "@/Components/DeleteConfirmationModal.svelte";
  import Pagination from "@/Components/Pagination.svelte";
  import { __, formatNumber } from "@/helpers.js";

  export let data = [];
  export let meta = {};

  $: permissions = $page.props.permissions || [];

  let search = "";
  let showDeleteModal = false;
  let productToDelete = null;

  const headers = [
    { key: "primary_photo", label: __("table.photo", "Photo") },
    { key: "name", label: __("table.name", "Name") },
    { key: "price", label: __("table.price", "Price") },
    { key: "category_names", label: __("table.category", "Categories") },
    { key: "actions", label: __("table.action", "Actions") },
  ];

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

  const confirmDelete = (product) => {
    productToDelete = product;
    showDeleteModal = true;
  };

  const deleteProduct = () => {
    router.delete("/products", {
      data: { id: productToDelete.id },
      onSuccess: () => {
        showDeleteModal = false;
        productToDelete = null;
      },
    });
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
            >{__("messages.create", "Create Product")}</PrimaryButton
          >
        </Link>
      {/if}
    </div>
  </div>

  <BaseTable {headers}>
    {#each data as product}
      <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
        <td class="px-6 py-4 whitespace-nowrap">
          {#if product.primary_photo}
            <img
              src={product.primary_photo}
              alt={product.name}
              class="h-10 w-10 rounded-full object-cover border border-gray-200"
            />
          {:else}
            <div
              class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-400"
            >
              <svg
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                />
              </svg>
            </div>
          {/if}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"
        >
          {product.name}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          {formatNumber(product.price)}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          <div class="flex flex-wrap gap-1">
            {#each product.category_names as category}
              <span
                class="px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-800 text-xs"
                >{category}</span
              >
            {/each}
          </div>
        </td>
        <td class="flex gap-2">
          {#if permissions.includes("edit products")}
            <Link href={`/products/${product.id}/edit`}>
              <SecondaryButton>{__("messages.edit", "Edit")}</SecondaryButton>
            </Link>
          {/if}
          {#if permissions.includes("delete products")}
            <SecondaryButton
              variant="danger"
              on:click={() => confirmDelete(product)}
            >
              {__("messages.delete", "Delete")}
            </SecondaryButton>
          {/if}
        </td>
      </tr>
    {/each}
  </BaseTable>

  <Pagination {meta} />

  <DeleteConfirmationModal
    show={showDeleteModal}
    title={__("product.delete_title", "Delete Product")}
    message={__(
      "product.delete_message",
      `Are you sure you want to delete ${productToDelete?.name}? This action cannot be undone.`,
    )}
    onConfirm={deleteProduct}
    onClose={() => (showDeleteModal = false)}
  />
</AdminLayout>
