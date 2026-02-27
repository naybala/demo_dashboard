<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import PageHeader from "@/Components/PageHeader.svelte";
  import BaseTable from "@/Components/BaseTable.svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import PrimaryButton from "@/Components/PrimaryButton.svelte";
  import TextInput from "@/Components/TextInput.svelte";
  import { router, Link } from "@inertiajs/svelte";
  import DeleteConfirmationModal from "@/Components/DeleteConfirmationModal.svelte";

  export let data = [];
  export let meta = {};

  let search = "";
  let showDeleteModal = false;
  let roleToDelete = null;

  const headers = [
    { key: "name", label: "Role Name" },
    { key: "allow_panel_status", label: "Access Panel" },
    { key: "actions", label: "Actions" },
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
  <PageHeader title="Roles">
    <div slot="actions">
      <Link href="/roles/create">
        <PrimaryButton>Create Role</PrimaryButton>
      </Link>
    </div>
  </PageHeader>

  <div class="mb-6 flex justify-between items-center">
    <div class="w-1/3">
      <TextInput
        type="text"
        placeholder="Search roles..."
        bind:value={search}
        on:input={handleSearch}
        class="w-full"
      />
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
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
          <div class="flex gap-2">
            <Link href={`/roles/${role.id}`}>
              <SecondaryButton>View</SecondaryButton>
            </Link>
            <Link href={`/roles/${role.id}/edit`}>
              <SecondaryButton>Edit</SecondaryButton>
            </Link>
            <button
              on:click={() => confirmDelete(role)}
              class="text-red-600 hover:text-red-900 font-medium"
            >
              Delete
            </button>
          </div>
        </td>
      </tr>
    {/each}
  </BaseTable>

  {#if meta && meta.links}
    <div class="mt-6 flex items-center justify-between">
      <div class="text-sm text-gray-700 dark:text-gray-400">
        Showing {meta.from} to {meta.to} of {meta.total} results
      </div>
      <div class="flex gap-1">
        {#each meta.links as link}
          <button
            class="px-3 py-1 rounded border {link.active
              ? 'bg-indigo-600 text-white border-indigo-600'
              : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600'}"
            on:click={() => link.url && router.visit(link.url)}
            disabled={!link.url}
          >
            {@html link.label}
          </button>
        {/each}
      </div>
    </div>
  {/if}

  <DeleteConfirmationModal
    show={showDeleteModal}
    title="Delete Role"
    message={`Are you sure you want to delete ${roleToDelete?.name}? This action cannot be undone.`}
    on:confirm={deleteRole}
    on:cancel={() => (showDeleteModal = false)}
  />
</AdminLayout>
