<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import PageHeader from "@/Components/PageHeader.svelte";
  import BaseTable from "@/Components/BaseTable.svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import PrimaryButton from "@/Components/PrimaryButton.svelte";
  import TextInput from "@/Components/TextInput.svelte";
  import { router, Link } from "@inertiajs/svelte";
  import DeleteConfirmationModal from "@/Components/DeleteConfirmationModal.svelte";
  import Pagination from "@/Components/Pagination.svelte";
  import { __ } from "@/helpers.js";

  export let data = [];
  export let meta = {};

  let search = "";
  let showDeleteModal = false;
  let productToDelete = null;

  const headers = [
    { key: "image", label: __("ownProduct.image", "Image") },
    { key: "name", label: __("ownProduct.name", "Name") },
    { key: "category", label: __("ownProduct.category", "Category") },
    { key: "unit", label: __("ownProduct.unit", "Unit") },
    { key: "price", label: __("ownProduct.price", "Price") },
    { key: "profit", label: __("ownProduct.profit", "Profit") },
    { key: "actions", label: __("table.action", "Actions") },
  ];

  const handleSearch = () => {
    router.get(
      "/own-products",
      { keyword: search },
      { preserveState: true, replace: true },
    );
  };

  const confirmDelete = (product) => {
    productToDelete = product;
    showDeleteModal = true;
  };

  const deleteProduct = () => {
    router.delete("/own-products", {
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
      {__("sidebar.own_product", "Own Products")}
    </h2>
  </svelte:fragment>

  <div class="mb-6 flex justify-between items-center">
    <div class="flex gap-2 w-1/2">
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
    </div>
    <Link href="/own-products/create">
      <PrimaryButton
        >{__("messages.create", "Create Own Product")}</PrimaryButton
      >
    </Link>
  </div>

  <BaseTable {headers}>
    {#each data as product}
      <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
        <td class="px-6 py-4 whitespace-nowrap">
          {#if product.image}
            <img
              src={product.image}
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
          {product.category || "N/A"}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          {product.unit || "N/A"}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          {product.price}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          {product.profit}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
          <div class="flex gap-2">
            <Link href={`/own-products/${product.id}/edit`}>
              <SecondaryButton>{__("messages.edit", "Edit")}</SecondaryButton>
            </Link>
            <button
              on:click={() => confirmDelete(product)}
              class="text-red-600 hover:text-red-900 font-medium"
            >
              {__("messages.delete", "Delete")}
            </button>
          </div>
        </td>
      </tr>
    {/each}
  </BaseTable>

  <Pagination {meta} />

  <DeleteConfirmationModal
    show={showDeleteModal}
    title={__("ownProduct.delete_title", "Delete Own Product")}
    message={__(
      "ownProduct.delete_message",
      `Are you sure you want to delete ${productToDelete?.name}? This action cannot be undone.`,
    )}
    onConfirm={deleteProduct}
    onClose={() => (showDeleteModal = false)}
  />
</AdminLayout>
