<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
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
  let permissionToDelete = null;

  const headers = [
    { key: "name", label: __("permission.permission_name", "Permission Name") },
    { key: "guard_name", label: __("permission.guard_name", "Guard Name") },
    { key: "actions", label: __("table.action", "Actions") },
  ];

  const handleSearch = () => {
    router.get(
      "/permissions",
      { keyword: search },
      { preserveState: true, replace: true },
    );
  };

  const handleReset = () => {
    search = "";
    router.get("/permissions");
  };

  const confirmDelete = (permission) => {
    permissionToDelete = permission;
    showDeleteModal = true;
  };

  const deletePermission = () => {
    router.delete(`/permissions/${permissionToDelete.id}`, {
      onSuccess: () => {
        showDeleteModal = false;
        permissionToDelete = null;
      },
    });
  };
</script>

<svelte:head>
  <title>{__("sidebar.permission", "Permissions")}</title>
</svelte:head>

<AdminLayout>
  <svelte:fragment slot="header">
    <h2
      class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem]"
    >
      {__("sidebar.permission", "Permissions")}
    </h2>
  </svelte:fragment>

  <div
    class="mb-6 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between"
  >
    <div class="flex flex-1 gap-2 min-w-0">
      <TextInput
        type="text"
        placeholder={__("messages.search_item", "Search permissions...")}
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
    <div class="flex-shrink-0">
      {#if permissions.includes("create permissions")}
        <Link href="/permissions/create">
          <PrimaryButton
            >{__("messages.create", "Create Permission")}</PrimaryButton
          >
        </Link>
      {/if}
    </div>
  </div>

  <BaseTable {headers}>
    {#each data as permission}
      <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
        <td
          class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"
        >
          {permission.name}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          <span
            class="px-2 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800"
          >
            {permission.guard_name}
          </span>
        </td>
        <td class="flex gap-2">
          {#if permissions.includes("edit permissions")}
            <Link href={`/permissions/${permission.id}/edit`}>
              <SecondaryButton>{__("messages.edit", "Edit")}</SecondaryButton>
            </Link>
          {/if}
          {#if permissions.includes("delete permissions")}
            <SecondaryButton
              variant="danger"
              on:click={() => confirmDelete(permission)}
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
    title={__("permission.delete_title", "Delete Permission")}
    message={__(
      "permission.delete_message",
      `Are you sure you want to delete ${permissionToDelete?.name}? This action cannot be undone.`,
    )}
    onConfirm={deletePermission}
    onClose={() => (showDeleteModal = false)}
  />
</AdminLayout>
