<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import Pagination from "@/Components/Pagination.svelte";
  import TextInput from "@/Components/TextInput.svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import BaseTable from "@/Components/BaseTable.svelte";
  import { page, router } from "@inertiajs/svelte";

  export let data = [];
  export let meta = {};
  export let warehouses = [];
  export let filters = {};

  $: permissions = $page.props.permissions || [];

  let search = filters.keyword || "";
  let warehouseFilter = filters.warehouse_id || "";

  const handleSearch = () => router.get("/inventories", { keyword: search, warehouse_id: warehouseFilter }, { preserveState: true, replace: true });
  const handleReset  = () => { search = ""; warehouseFilter = ""; router.get("/inventories"); };

  const getStockClass = (qty) => {
    if (qty <= 0)  return "bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400";
    if (qty <= 10) return "bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400";
    return "bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400";
  };

  const headers = ["#", "Warehouse", "Product", "Unit", "Stock Level", "Status"];
</script>

<svelte:head><title>Stock Levels</title></svelte:head>

<AdminLayout>
  <svelte:fragment slot="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem]">
      Stock Levels
    </h2>
  </svelte:fragment>

  <div class="mb-6 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
    <div class="flex flex-1 flex-wrap gap-2 min-w-0">
      <TextInput type="text" placeholder="Search product..." bind:value={search}
        on:keydown={(e) => e.key === "Enter" && handleSearch()} class="flex-1 min-w-[160px]" />

      <select
        bind:value={warehouseFilter}
        class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
      >
        <option value="">All Warehouses</option>
        {#each warehouses as w}
          <option value={w.id}>{w.name}</option>
        {/each}
      </select>

      <SecondaryButton on:click={handleSearch}>Search</SecondaryButton>
      {#if search || warehouseFilter}
        <SecondaryButton class="bg-gray-100 dark:bg-gray-700" on:click={handleReset}>Clear</SecondaryButton>
      {/if}
    </div>
  </div>

  <div class="mt-4">
    <BaseTable {headers}>
      {#each data as inv, i}
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
          <td class="px-6 py-4 text-gray-500 text-sm">{i + 1}</td>
          <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{inv.warehouse_name}</td>
          <td class="px-6 py-4">{inv.own_product_name}</td>
          <td class="px-6 py-4 text-gray-500">{inv.unit || "—"}</td>
          <td class="px-6 py-4 font-mono font-bold text-lg">{inv.quantity}</td>
          <td class="px-6 py-4">
            <span class="px-2 py-1 text-xs font-semibold rounded-full {getStockClass(inv.quantity)}">
              {inv.quantity <= 0 ? "Out of Stock" : inv.quantity <= 10 ? "Low Stock" : "In Stock"}
            </span>
          </td>
        </tr>
      {/each}

      {#if data.length === 0}
        <tr>
          <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
            No inventory records found.
          </td>
        </tr>
      {/if}
    </BaseTable>
  </div>

  <Pagination {meta} />
</AdminLayout>
