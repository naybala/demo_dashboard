<script>
  import { page, Link } from "@inertiajs/svelte";
  import { __, formatNumber } from "@/helpers.js";
  import BaseTable from "@/Components/BaseTable.svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";

  export let data = [];
  export let onConfirmDelete = (product) => {};

  $: permissions = $page.props.permissions || [];

  const headers = [
    { key: "image", label: __("table.photo", "Image") },
    { key: "name", label: __("table.name", "Name") },
    { key: "category", label: __("table.category", "Category") },
    { key: "unit", label: __("table.unit", "Unit") },
    { key: "price", label: __("table.price", "Price") },
    { key: "profit", label: __("table.profit", "Profit") },
    { key: "actions", label: __("table.action", "Actions") },
  ];
</script>

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
        {formatNumber(product.price)}
      </td>
      <td
        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
      >
        {formatNumber(product.profit)}
      </td>
      <td class="px-6 py-4 align-middle"><div class="flex gap-2 items-center">
        {#if permissions.includes("edit own-products")}
          <Link href={`/own-products/${product.id}/edit`}>
            <SecondaryButton>{__("messages.edit", "Edit")}</SecondaryButton>
          </Link>
        {/if}
        {#if permissions.includes("delete own-products")}
          <SecondaryButton
            variant="danger"
            on:click={() => onConfirmDelete(product)}
          >
            {__("messages.delete", "Delete")}
          </SecondaryButton>
        {/if}
      </div></td>
    </tr>
  {/each}
</BaseTable>
