<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import PrimaryButton from "@/Components/PrimaryButton.svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import Pagination from "@/Components/Pagination.svelte";
  import TextInput from "@/Components/TextInput.svelte";
  import BaseTable from "@/Components/BaseTable.svelte";
  import StockTransactionModal from "./StockTransactionModal.svelte";
  import { page, router } from "@inertiajs/svelte";
  import { writable } from "svelte/store";

  export let data = [];
  export let meta = {};
  export let warehouses = [];
  export let products = [];
  export let filters = {};

  $: permissions = $page.props.permissions || [];

  const showModal = writable(false);

  // Filters
  let search = filters.keyword || "";
  let warehouseFilter = filters.warehouse_id || "";
  let typeFilter = filters.type || "";

  const handleSearch = () =>
    router.get(
      "/stock-transactions",
      { keyword: search, warehouse_id: warehouseFilter, type: typeFilter },
      { preserveState: true, replace: true },
    );

  const handleReset = () => {
    search = "";
    warehouseFilter = "";
    typeFilter = "";
    router.get("/stock-transactions");
  };

  const headers = ["#", "Warehouse", "Product", "Unit", "Qty", "Type", "Reference", "Note", "Date"];

  const typeClass = (type) =>
    type === "in"
      ? "bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400"
      : "bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400";
</script>

<svelte:head><title>Stock Transactions</title></svelte:head>

<AdminLayout>
  <svelte:fragment slot="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem]">
      Stock In / Out
    </h2>
  </svelte:fragment>

  <!-- Filters + Action -->
  <div class="mb-6 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
    <div class="flex flex-1 flex-wrap gap-2 min-w-0">
      <TextInput type="text" placeholder="Search product or note..." bind:value={search}
        on:keydown={(e) => e.key === "Enter" && handleSearch()} class="flex-1 min-w-[150px]" />

      <select bind:value={warehouseFilter}
        class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 text-sm">
        <option value="">All Warehouses</option>
        {#each warehouses as w}
          <option value={w.id}>{w.name}</option>
        {/each}
      </select>

      <select bind:value={typeFilter}
        class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 text-sm">
        <option value="">All Types</option>
        <option value="in">Stock IN</option>
        <option value="out">Stock OUT</option>
      </select>

      <SecondaryButton on:click={handleSearch}>Search</SecondaryButton>
      {#if search || warehouseFilter || typeFilter}
        <SecondaryButton class="bg-gray-100 dark:bg-gray-700" on:click={handleReset}>Clear</SecondaryButton>
      {/if}
    </div>

    <div class="flex-shrink-0">
      {#if permissions.includes("create stock-transactions")}
        <PrimaryButton on:click={() => showModal.set(true)} class="w-full sm:w-auto">
          + Record Transaction
        </PrimaryButton>
      {/if}
    </div>
  </div>

  <!-- Table -->
  <div class="mt-4">
    <BaseTable {headers}>
      {#each data as tx, i}
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
          <td class="px-6 py-4 text-gray-500 text-sm">{i + 1}</td>
          <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">{tx.warehouse_name}</td>
          <td class="px-6 py-4">{tx.own_product_name}</td>
          <td class="px-6 py-4 text-gray-500">{tx.unit || "—"}</td>
          <td class="px-6 py-4 font-mono font-bold">{tx.quantity}</td>
          <td class="px-6 py-4">
            <span class="px-2 py-1 text-xs font-semibold rounded-full uppercase {typeClass(tx.type)}">
              {tx.type === "in" ? "📦 IN" : "📤 OUT"}
            </span>
          </td>
          <td class="px-6 py-4 text-xs text-gray-500">{tx.reference_type}</td>
          <td class="px-6 py-4 text-sm">{tx.note || "—"}</td>
          <td class="px-6 py-4 text-xs text-gray-500 whitespace-nowrap">{tx.created_at || "—"}</td>
        </tr>
      {/each}

      {#if data.length === 0}
        <tr>
          <td colspan="9" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
            No stock transactions found.
          </td>
        </tr>
      {/if}
    </BaseTable>
  </div>

  <Pagination {meta} />

  <StockTransactionModal bind:show={$showModal} {warehouses} {products} />
</AdminLayout>
