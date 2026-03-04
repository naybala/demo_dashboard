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
  let userToDelete = null;

  const headers = [
    __("user.name", "Name"),
    __("user.email", "Email"),
    __("user.role", "Role"),
    __("user.status", "Status"),
    __("table.action", "Action"),
  ];

  const handleSearch = () => {
    router.get(
      "/users",
      { keyword: search },
      { preserveState: true, replace: true },
    );
  };

  const handleReset = () => {
    search = "";
    router.get("/users");
  };

  const confirmDelete = (user) => {
    userToDelete = user;
    showDeleteModal = true;
  };

  const deleteUser = () => {
    router.delete(`/users/${userToDelete.id}`, {
      onSuccess: () => {
        showDeleteModal = false;
        userToDelete = null;
      },
    });
  };
</script>

<svelte:head>
  <title>{__("sidebar.user")}</title>
</svelte:head>

<AdminLayout>
  <svelte:fragment slot="header">
    <h2
      class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem]"
    >
      {__("sidebar.user", "Users")}
    </h2>
  </svelte:fragment>

  <div
    class="mb-6 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between"
  >
    <div class="flex flex-1 gap-2 min-w-0">
      <TextInput
        type="text"
        placeholder={__("messages.search_item", "Search users...")}
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
      {#if permissions.includes("create users")}
        <Link href="/users/create">
          <PrimaryButton>{__("messages.create", "Create User")}</PrimaryButton>
        </Link>
      {/if}
    </div>
  </div>

  <BaseTable {headers}>
    {#each data as user}
      <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
        <td
          class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"
        >
          <div class="flex items-center gap-3">
            <div
              class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-xs font-bold overflow-hidden flex-shrink-0"
            >
              {#if user.avatar}
                <img
                  src={user.avatar}
                  alt={user.fullname}
                  class="w-full h-full object-cover"
                />
              {:else}
                {user.fullname.charAt(0).toUpperCase()}
              {/if}
            </div>
            <span>{user.fullname}</span>
          </div>
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          {user.email}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          <span
            class="px-2 py-1 rounded-full bg-indigo-100 text-indigo-800 text-xs font-semibold"
          >
            {user.role_name}
          </span>
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          <span
            class={`px-2 py-1 rounded-full text-xs font-semibold ${user.status === 1 ? "bg-green-100 text-green-800" : "bg-red-100 text-red-800"}`}
          >
            {user.status_text}
          </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
          <div class="flex gap-2 justify-end">
            <Link href={`/users/${user.id}`}>
              <SecondaryButton>{__("messages.view", "View")}</SecondaryButton>
            </Link>
            {#if permissions.includes("edit users")}
              <Link href={`/users/${user.id}/edit`}>
                <SecondaryButton>{__("messages.edit", "Edit")}</SecondaryButton>
              </Link>
            {/if}
            {#if permissions.includes("delete users")}
              <!-- {#if user.can_be_deleted} -->
              <button
                on:click={() => confirmDelete(user)}
                class="text-red-600 hover:text-red-900 font-medium ml-2"
              >
                {__("messages.delete", "Delete")}
              </button>
              <!-- {/if} -->
            {/if}
          </div>
        </td>
      </tr>
    {/each}
  </BaseTable>

  <Pagination {meta} />

  <DeleteConfirmationModal
    show={showDeleteModal}
    title={__("user.delete_title", "Delete User")}
    message={__(
      "user.delete_message",
      `Are you sure you want to delete ${userToDelete?.name}? This action cannot be undone.`,
    )}
    onConfirm={deleteUser}
    onClose={() => (showDeleteModal = false)}
  />
</AdminLayout>
