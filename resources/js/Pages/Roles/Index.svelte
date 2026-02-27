<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import PageHeader from "@/Components/PageHeader.svelte";
  import BaseTable from "@/Components/BaseTable.svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import PrimaryButton from "@/Components/PrimaryButton.svelte";
  import TextInput from "@/Components/TextInput.svelte";
  import { router, Link } from "@inertiajs/svelte";
  import DeleteConfirmationModal from "@/Components/DeleteConfirmationModal.svelte";
  import Pagination from "@/Components/Pagination.svelte";
  import { __ } from "@/helpers.js";

  export let data = [];
  export let meta = {};

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

  <div class="mb-6 flex justify-between items-center">
    <div class="flex gap-2 w-1/2">
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
    </div>
    <Link href="/roles/create">
      <PrimaryButton>{__("messages.create", "Create Role")}</PrimaryButton>
    </Link>
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
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
          <div class="flex gap-2">
            <Link href={`/roles/${role.id}`}>
              <SecondaryButton>View</SecondaryButton>
            </Link>
            <Link href={`/roles/${role.id}/edit`}>
              <SecondaryButton>{__("messages.edit", "Edit")}</SecondaryButton>
            </Link>
            <button
              on:click={() => confirmDelete(role)}
              class="text-red-600 hover:text-red-900 font-medium"
            >
              {__("messages.delete", "Delete")}
            </button>
          </div>
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
