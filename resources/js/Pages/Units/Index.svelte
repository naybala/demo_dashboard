<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import PageHeader from "@/Components/PageHeader.svelte";
  import BaseTable from "@/Components/BaseTable.svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import PrimaryButton from "@/Components/PrimaryButton.svelte";
  import TextInput from "@/Components/TextInput.svelte";
  import { page, router, Link } from "@inertiajs/svelte";
  import DeleteConfirmationModal from "@/Components/DeleteConfirmationModal.svelte";
  import Pagination from "@/Components/Pagination.svelte";
  import { __ } from "@/helpers.js";

  export let data = [];
  export let meta = {};

  $: permissions = $page.props.permissions || [];

  let search = "";
  let showDeleteModal = false;
  let unitToDelete = null;

  const headers = [
    { key: "name", label: __("unit.name", "Unit Name") },
    { key: "actions", label: __("table.action", "Actions") },
  ];

  const handleSearch = () => {
    router.get(
      "/units",
      { keyword: search },
      { preserveState: true, replace: true },
    );
  };

  const handleReset = () => {
    search = "";
    router.get("/units");
  };

  const confirmDelete = (unit) => {
    unitToDelete = unit;
    showDeleteModal = true;
  };

  const deleteUnit = () => {
    router.delete(`/units/${unitToDelete.id}`, {
      onSuccess: () => {
        showDeleteModal = false;
        unitToDelete = null;
      },
    });
  };
</script>

<svelte:head>
  <title>{__("sidebar.unit")}</title>
</svelte:head>

<AdminLayout>
  <svelte:fragment slot="header">
    <h2
      class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem]"
    >
      {__("sidebar.unit", "Units")}
    </h2>
  </svelte:fragment>

  <div class="mb-6 flex justify-between items-center">
    <div class="flex gap-2 w-1/2">
      <TextInput
        type="text"
        placeholder={__("messages.search_item", "Search units...")}
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
    {#if permissions.includes("create units")}
      <Link href="/units/create">
        <PrimaryButton>{__("messages.create", "Create Unit")}</PrimaryButton>
      </Link>
    {/if}
  </div>

  <BaseTable {headers}>
    {#each data as unit}
      <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
        <td
          class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"
        >
          {unit.name}
        </td>
        <td class="flex gap-2">
          {#if permissions.includes("edit units")}
            <Link href={`/units/${unit.id}/edit`}>
              <SecondaryButton>{__("messages.edit", "Edit")}</SecondaryButton>
            </Link>
          {/if}
          {#if permissions.includes("delete units")}
            <SecondaryButton
              variant="danger"
              on:click={() => confirmDelete(unit)}
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
    title={__("unit.delete_title", "Delete Unit")}
    message={__(
      "unit.delete_message",
      `Are you sure you want to delete ${unitToDelete?.name}? This action cannot be undone.`,
    )}
    onConfirm={deleteUnit}
    onClose={() => (showDeleteModal = false)}
  />
</AdminLayout>
