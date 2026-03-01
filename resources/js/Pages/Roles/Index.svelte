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
  let roleToDelete = null;

  const headers = [
    { key: "name", label: __("role.role_name", "Role Name") },
    {
      key: "allow_panel_status",
      label: __("role.can_access_panel", "Access Panel"),
    },
    { key: "actions", label: __("table.action", "Actions") },
  ];

  const handleSearch = () => {
    router.get(
      "/roles",
      { keyword: search },
      { preserveState: true, replace: true },
    );
  };

  const handleReset = () => {
    search = "";
    router.get("/roles");
  };

  const confirmDelete = (role) => {
    roleToDelete = role;
    showDeleteModal = true;
  };

  const deleteRole = () => {
    router.delete(`/roles/${roleToDelete.id}`, {
      onSuccess: () => {
        showDeleteModal = false;
        roleToDelete = null;
      },
    });
  };
</script>

<AdminLayout>
  <svelte:fragment slot="header">
    <h2
      class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem]"
    >
      {__("sidebar.role", "Roles")}
    </h2>
  </svelte:fragment>

  <div
    class="mb-6 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between"
  >
    <div class="flex flex-1 gap-2 min-w-0">
      <TextInput
        type="text"
        placeholder={__("messages.search_item", "Search roles...")}
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
      {#if permissions.includes("create roles")}
        <Link href="/roles/create">
          <PrimaryButton>{__("messages.create", "Create Role")}</PrimaryButton>
        </Link>
      {/if}
    </div>
  </div>

  <BaseTable {headers}>
    {#each data as role}
      <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
        <td
          class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"
        >
          {role.name}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          <span
            class={`px-2 py-1 rounded-full text-xs font-semibold ${role.can_access_panel ? "bg-green-100 text-green-800" : "bg-gray-100 text-gray-800"}`}
          >
            {role.allow_panel_status}
          </span>
        </td>
        <td class="flex gap-2">
          <Link href={`/roles/${role.id}`}>
            <SecondaryButton>{__("messages.view", "View")}</SecondaryButton>
          </Link>
          {#if permissions.includes("edit roles")}
            <Link href={`/roles/${role.id}/edit`}>
              <SecondaryButton>{__("messages.edit", "Edit")}</SecondaryButton>
            </Link>
          {/if}
          {#if permissions.includes("delete roles")}
            <SecondaryButton
              variant="danger"
              on:click={() => confirmDelete(role)}
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
    title={__("role.delete_title", "Delete Role")}
    message={__(
      "role.delete_message",
      `Are you sure you want to delete ${roleToDelete?.name}? This action cannot be undone.`,
    )}
    onConfirm={deleteRole}
    onClose={() => (showDeleteModal = false)}
  />
</AdminLayout>
