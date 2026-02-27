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
  let userToDelete = null;

  const headers = [
    { key: "name", label: "Name" },
    { key: "username", label: "Username" },
    { key: "role", label: "Role" },
    { key: "status", label: "Status" },
    { key: "actions", label: "Actions" },
  ];

  const handleSearch = () => {
    router.get(
      "/users",
      { keyword: search },
      { preserveState: true, replace: true },
    );
  };

  const confirmDelete = (user) => {
    userToDelete = user;
    showDeleteModal = true;
  };

  const deleteUser = () => {
    router.delete("/users", {
      data: { id: userToDelete.id },
      onSuccess: () => {
        showDeleteModal = false;
        userToDelete = null;
      },
    });
  };
</script>

<AdminLayout>
  <PageHeader title="Users">
    <Link href="/users/create">
      <PrimaryButton>Create User</PrimaryButton>
    </Link>
  </PageHeader>

  <div class="mb-6 flex justify-between items-center">
    <div class="w-1/3">
      <TextInput
        type="text"
        placeholder="Search users..."
        bind:value={search}
        on:input={handleSearch}
        class="w-full"
      />
    </div>
  </div>

  <BaseTable {headers}>
    {#each data as user}
      <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
        <td
          class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"
        >
          {user.name}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          {user.username}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          <span
            class="px-2 py-1 rounded-full bg-indigo-100 text-indigo-800 text-xs font-semibold"
          >
            {user.role}
          </span>
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          <span
            class={`px-2 py-1 rounded-full text-xs font-semibold ${user.active ? "bg-green-100 text-green-800" : "bg-red-100 text-red-800"}`}
          >
            {user.active ? "Active" : "Inactive"}
          </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
          <div class="flex gap-2">
            <Link href={`/users/${user.id}/show`}>
              <SecondaryButton>View</SecondaryButton>
            </Link>
            <Link href={`/users/${user.id}/edit`}>
              <SecondaryButton>Edit</SecondaryButton>
            </Link>
            {#if user.can_be_deleted}
              <button
                on:click={() => confirmDelete(user)}
                class="text-red-600 hover:text-red-900 font-medium"
              >
                Delete
              </button>
            {/if}
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
    title="Delete User"
    message={`Are you sure you want to delete ${userToDelete?.name}? This action cannot be undone.`}
    on:confirm={deleteUser}
    on:cancel={() => (showDeleteModal = false)}
  />
</AdminLayout>
