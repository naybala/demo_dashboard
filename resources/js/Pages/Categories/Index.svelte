<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import PageHeader from "@/Components/PageHeader.svelte";
  import PrimaryButton from "@/Components/PrimaryButton.svelte";
  import CategoryTable from "./CategoryTable.svelte";
  import CategoryModal from "./CategoryModal.svelte";
  import DeleteConfirmationModal from "@/Components/DeleteConfirmationModal.svelte";
  import Pagination from "@/Components/Pagination.svelte";
  import { useCategoryActions } from "./useCategoryActions";
  import { router } from "@inertiajs/svelte";
  import TextInput from "@/Components/TextInput.svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import { __ } from "@/helpers.js";

  export let data = []; // Categories data
  export let meta = {};

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
