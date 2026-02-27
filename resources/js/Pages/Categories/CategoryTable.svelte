<script>
  import BaseTable from "@/Components/BaseTable.svelte";
  import { useCategoryActions } from "./useCategoryActions";

  export let categories = [];
  export let onEdit = () => {};

  const { deleteCategory } = useCategoryActions();

  const headers = ["Name", "Other Name", "Description", "Show"];
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
      <td class="px-6 py-4 text-right space-x-2">
        <button
          on:click={() => onEdit(category)}
          class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
        >
          Edit
        </button>
        <button
          on:click={() => deleteCategory(category.id)}
          class="font-medium text-red-600 dark:text-red-500 hover:underline"
        >
          Delete
        </button>
      </td>
    </tr>
  {/each}
</BaseTable>
