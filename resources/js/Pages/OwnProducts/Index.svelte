<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import PageHeader from "@/Components/PageHeader.svelte";
  import BaseTable from "@/Components/BaseTable.svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import PrimaryButton from "@/Components/PrimaryButton.svelte";
  import TextInput from "@/Components/TextInput.svelte";
  import { router, Link } from "@inertiajs/svelte";
  import DeleteConfirmationModal from "@/Components/DeleteConfirmationModal.svelte";

  export let data = [];
  export let meta = {};

  let search = "";
  let showDeleteModal = false;
  let productToDelete = null;

  const headers = [
    { key: "image", label: "Image" },
    { key: "name", label: "Name" },
    { key: "category", label: "Category" },
    { key: "unit", label: "Unit" },
    { key: "price", label: "Price" },
    { key: "profit", label: "Profit" },
    { key: "actions", label: "Actions" },
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
  <PageHeader title="Own Products">
    <div slot="actions">
      <Link href="/own-products/create">
        <PrimaryButton>Create Own Product</PrimaryButton>
      </Link>
    </div>
  </PageHeader>

  <div class="mb-6 flex justify-between items-center">
    <div class="w-1/3">
      <TextInput
        type="text"
        placeholder="Search products..."
        bind:value={search}
        on:input={handleSearch}
        class="w-full"
      />
    </div>
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
              <SecondaryButton>Edit</SecondaryButton>
            </Link>
            <button
              on:click={() => confirmDelete(product)}
              class="text-red-600 hover:text-red-900 font-medium"
            >
              Delete
            </button>
          </div>
        </td>
      </tr>
    {/each}
  </BaseTable>

  {#if meta && meta.links}
    <div class="mt-6 flex items-center justify-between">
      <div class="text-sm text-gray-700 dark:text-gray-400">
        Showing {meta.from} to {meta.to} of {meta.total} results
      </div>
      <div class="flex gap-1">
        {#each meta.links as link}
          <button
            class="px-3 py-1 rounded border {link.active
              ? 'bg-indigo-600 text-white border-indigo-600'
              : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600'}"
            on:click={() => link.url && router.visit(link.url)}
            disabled={!link.url}
          >
            {@html link.label}
          </button>
        {/each}
      </div>
    </div>
  {/if}

  <DeleteConfirmationModal
    show={showDeleteModal}
    title="Delete Own Product"
    message={`Are you sure you want to delete ${productToDelete?.name}? This action cannot be undone.`}
    on:confirm={deleteProduct}
    on:cancel={() => (showDeleteModal = false)}
  />
</AdminLayout>
