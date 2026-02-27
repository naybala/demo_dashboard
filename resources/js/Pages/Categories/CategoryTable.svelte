<script>
  import { __ } from "@/helpers.js";
  import BaseTable from "@/Components/BaseTable.svelte";

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
      <td class="px-6 py-4 space-x-2">
        <button
          on:click={() => onEdit(category)}
          class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
        >
          {__("messages.edit", "Edit")}
        </button>
        <button
          on:click={() => onDelete(category)}
          class="font-medium text-red-600 dark:text-red-500 hover:underline"
        >
          {__("messages.delete", "Delete")}
        </button>
      </td>
    </tr>
  {/each}
</BaseTable>
