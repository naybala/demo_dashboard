<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import PrimaryButton from "@/Components/PrimaryButton.svelte";
  import CategoryTable from "./CategoryTable.svelte";
  import CategoryModal from "./CategoryModal.svelte";
  import DeleteConfirmationModal from "@/Components/DeleteConfirmationModal.svelte";
  import Pagination from "@/Components/Pagination.svelte";
  import { useCategoryActions } from "./useCategoryActions";
  import { page, router } from "@inertiajs/svelte";
  import TextInput from "@/Components/TextInput.svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import { __ } from "@/helpers.js";

  export let data = []; // Categories data
  export let meta = {};

  $: permissions = $page.props.permissions || [];

  const {
    showModal,
    showDeleteModal,
    editingCategory,
    openCreateModal,
    openEditModal,
    openDeleteModal,
    closeDeleteModal,
    confirmDelete,
  } = useCategoryActions();

  let search = "";

  const handleSearch = () => {
    router.get(
      "/categories",
      { keyword: search },
      { preserveState: true, replace: true },
    );
  };

  const handleReset = () => {
    search = "";
    router.get("/categories");
  };
</script>

<AdminLayout>
  <svelte:fragment slot="header">
    <h2
      class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem]"
    >
      {__("sidebar.category", "Categories")}
    </h2>
  </svelte:fragment>

  <div
    class="mb-6 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between"
  >
    <div class="flex flex-1 gap-2 min-w-0">
      <TextInput
        type="text"
        placeholder={__("messages.search_item", "Search categories...")}
        bind:value={search}
        on:keydown={(e) => e.key === "Enter" && handleSearch()}
        class="flex-1 min-w-0"
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
      {#if permissions.includes("create categories")}
        <PrimaryButton on:click={openCreateModal} class="w-full sm:w-auto">
          {__("messages.create", "Add Category")}
        </PrimaryButton>
      {/if}
    </div>
  </div>

  <div class="mt-6">
    <CategoryTable
      categories={data}
      onEdit={openEditModal}
      onDelete={openDeleteModal}
    />
  </div>

  <Pagination {meta} />

  <CategoryModal bind:show={$showModal} category={$editingCategory} />

  <DeleteConfirmationModal
    show={$showDeleteModal}
    onClose={closeDeleteModal}
    onConfirm={confirmDelete}
    title={__("messages.confirm_delete_title", "Delete Category")}
    message={__(
      "messages.confirm_delete_message",
      "Are you sure you want to delete this category? This action cannot be undone.",
    )}
  />
</AdminLayout>
