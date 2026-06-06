<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import PrimaryButton from "@/Components/PrimaryButton.svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import DeleteConfirmationModal from "@/Components/DeleteConfirmationModal.svelte";
  import Pagination from "@/Components/Pagination.svelte";
  import TextInput from "@/Components/TextInput.svelte";
  import BaseTable from "@/Components/BaseTable.svelte";
  import WarehouseModal from "./WarehouseModal.svelte";
  import { page, router } from "@inertiajs/svelte";
  import { writable, get } from "svelte/store";

  export let data = [];
  export let meta = {};

  $: permissions = $page.props.permissions || [];

  // --- Modal state ---
  const showModal = writable(false);
  const showDeleteModal = writable(false);
  const editingWarehouse = writable(null);
  const deletingWarehouse = writable(null);

  const openCreate = () => { editingWarehouse.set(null); showModal.set(true); };
  const openEdit   = (w)  => { editingWarehouse.set(w);    showModal.set(true); };
  const openDelete = (w)  => { deletingWarehouse.set(w);   showDeleteModal.set(true); };
  const closeDelete = ()  => showDeleteModal.set(false);

  const confirmDelete = () => {
    const w = get(deletingWarehouse);
    if (w) router.delete(`/warehouses/${w.id}`, { onSuccess: closeDelete });
  };

  // --- Search ---
  let search = "";
  const handleSearch = () => router.get("/warehouses", { keyword: search }, { preserveState: true, replace: true });
  const handleReset  = () => { search = ""; router.get("/warehouses"); };

  const headers = ["#", "Name", "Location", "Description", "Action"];
</script>

<svelte:head><title>Warehouses</title></svelte:head>

<AdminLayout>
  <svelte:fragment slot="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem]">
      Warehouses
    </h2>
  </svelte:fragment>

  <div class="mb-6 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
    <div class="flex flex-1 gap-2 min-w-0">
      <TextInput type="text" placeholder="Search warehouses..." bind:value={search}
        on:keydown={(e) => e.key === "Enter" && handleSearch()} class="flex-1 min-w-0" />
      <SecondaryButton on:click={handleSearch}>Search</SecondaryButton>
      {#if search}
        <SecondaryButton class="bg-gray-100 dark:bg-gray-700" on:click={handleReset}>Clear</SecondaryButton>
      {/if}
    </div>
    <div class="flex-shrink-0">
      {#if permissions.includes("create warehouses")}
        <PrimaryButton on:click={openCreate} class="w-full sm:w-auto">Add Warehouse</PrimaryButton>
      {/if}
    </div>
  </div>

  <div class="mt-4">
    <BaseTable {headers}>
      {#each data as warehouse, i}
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
          <td class="px-6 py-4 text-gray-500 text-sm">{i + 1}</td>
          <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{warehouse.name}</td>
          <td class="px-6 py-4">{warehouse.location || "—"}</td>
          <td class="px-6 py-4">{warehouse.description || "—"}</td>
          <td class="px-6 py-4 align-middle">
            <div class="flex gap-2 items-center">
              {#if permissions.includes("edit warehouses")}
                <SecondaryButton on:click={() => openEdit(warehouse)}>Edit</SecondaryButton>
              {/if}
              {#if permissions.includes("delete warehouses")}
                <SecondaryButton variant="danger" on:click={() => openDelete(warehouse)}>Delete</SecondaryButton>
              {/if}
            </div>
          </td>
        </tr>
      {/each}

      {#if data.length === 0}
        <tr>
          <td colspan="5" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
            No warehouses found.
          </td>
        </tr>
      {/if}
    </BaseTable>
  </div>

  <Pagination {meta} />

  <WarehouseModal bind:show={$showModal} warehouse={$editingWarehouse} />

  <DeleteConfirmationModal
    show={$showDeleteModal}
    onClose={closeDelete}
    onConfirm={confirmDelete}
    title="Delete Warehouse"
    message="Are you sure you want to delete this warehouse? This cannot be undone."
  />
</AdminLayout>
