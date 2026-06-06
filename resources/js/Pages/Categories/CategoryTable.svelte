<script>
  import { page } from "@inertiajs/svelte";
  import { __ } from "@/helpers.js";
  import BaseTable from "@/Components/BaseTable.svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";

  export let categories = [];
  export let onEdit = () => {};
  export let onDelete = () => {};

  const headers = [
    __("category.name", "Name"),
    __("category.name_other", "Other Name"),
    __("category.description", "Description"),
    __("category.is_show", "Show"),
    __("table.action", "Action"),
  ];

  $: permissions = $page.props.permissions || [];
</script>

<BaseTable {headers}>
  {#each categories as category}
    <tr
      class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
    >
      <td
        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
      >
        {category.name}
      </td>
      <td class="px-6 py-4">{category.name_other || "-"}</td>
      <td class="px-6 py-4">{category.description || "-"}</td>
      <td class="px-6 py-4">
        <span
          class="px-2 py-1 text-xs font-semibold rounded-full {category.is_show
            ? 'bg-green-100 text-green-800'
            : 'bg-red-100 text-red-800'}"
        >
          {category.is_show ? "Yes" : "No"}
        </span>
      </td>
      <td class="px-6 py-4 align-middle"><div class="flex gap-2 items-center">
        {#if permissions.includes("edit categories")}
          <SecondaryButton on:click={() => onEdit(category)}>
            {__("messages.edit", "Edit")}
          </SecondaryButton>
        {/if}

        {#if permissions.includes("delete categories")}
          <SecondaryButton variant="danger" on:click={() => onDelete(category)}>
            {__("messages.delete", "Delete")}
          </SecondaryButton>
        {/if}
      </div></td>
    </tr>
  {/each}
</BaseTable>
