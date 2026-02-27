<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import PageHeader from "@/Components/PageHeader.svelte";
  import PrimaryButton from "@/Components/PrimaryButton.svelte";
  import CategoryTable from "./CategoryTable.svelte";
  import CategoryModal from "./CategoryModal.svelte";
  import DeleteConfirmationModal from "@/Components/DeleteConfirmationModal.svelte";
  import { router } from "@inertiajs/svelte";
  import TextInput from "@/Components/TextInput.svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import { __ } from "@/helpers.js";

  export let data = []; // Categories data
  export let meta = {};

  let showModal = false;
  let showDeleteModal = false;
  let editingCategory = null;
  let deletingCategory = null;
  let search = "";

  const openCreateModal = () => {
    editingCategory = null;
    showModal = true;
  };

  const openEditModal = (category) => {
    editingCategory = category;
    showModal = true;
  };

  const openDeleteModal = (category) => {
    deletingCategory = category;
    showDeleteModal = true;
  };

  const closeDeleteModal = () => {
    showDeleteModal = false;
  };

  const confirmDelete = () => {
    if (deletingCategory) {
      router.delete(`/categories/${deletingCategory.id}`, {
        onSuccess: () => {
          closeDeleteModal();
        },
      });
    }
  };

  const handleSearch = () => {
    router.get(
      "/categories",
      { keyword: search },
      { preserveState: true, replace: true },
    );
  };
</script>

<AdminLayout>
  <PageHeader title={__("sidebar.category", "Categories")}>
    <PrimaryButton slot="actions" on:click={openCreateModal}>
      {__("messages.create", "Add Category")}
    </PrimaryButton>
  </PageHeader>

  <div class="mb-6 flex justify-between items-center">
    <div class="flex gap-2 w-1/2">
      <TextInput
        type="text"
        placeholder={__("messages.search_item", "Search categories...")}
        bind:value={search}
        on:keydown={(e) => e.key === "Enter" && handleSearch()}
        class="w-full"
      />
      <SecondaryButton on:click={handleSearch}>
        {__("messages.search", "Search")}
      </SecondaryButton>
    </div>
  </div>

  <div class="mt-6">
    <CategoryTable
      categories={data}
      onEdit={openEditModal}
      onDelete={openDeleteModal}
    />
  </div>

  {#if meta && meta.links}
    <div class="mt-6 flex items-center justify-between">
      <div class="text-sm text-gray-700 dark:text-gray-400">
        {__("messages.showing", "Showing")}
        {meta.from}
        {__("messages.to", "to")}
        {meta.to}
        {__("messages.of", "of")}
        {meta.total}
        {__("messages.results", "results")}
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

  <CategoryModal bind:show={showModal} category={editingCategory} />

  <DeleteConfirmationModal
    show={showDeleteModal}
    onClose={closeDeleteModal}
    onConfirm={confirmDelete}
    title={__("messages.confirm_delete_title", "Delete Category")}
    message={__(
      "messages.confirm_delete_message",
      "Are you sure you want to delete this category? This action cannot be undone.",
    )}
  />
</AdminLayout>
