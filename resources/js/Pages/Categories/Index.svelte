<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import PageHeader from "@/Components/PageHeader.svelte";
  import PrimaryButton from "@/Components/PrimaryButton.svelte";
  import CategoryTable from "./CategoryTable.svelte";
  import CategoryModal from "./CategoryModal.svelte";
  import { router } from "@inertiajs/svelte";
  import TextInput from "@/Components/TextInput.svelte";

  export let data = []; // Categories data
  export let meta = {};

  let showModal = false;
  let editingCategory = null;
  let search = "";

  const openCreateModal = () => {
    editingCategory = null;
    showModal = true;
  };

  const openEditModal = (category) => {
    editingCategory = category;
    showModal = true;
  };

  const handleSearch = () => {
    router.get(
      "/categories",
      { search: search },
      { preserveState: true, replace: true },
    );
  };
</script>

<AdminLayout>
  <PageHeader title="Categories">
    <PrimaryButton slot="actions" on:click={openCreateModal}>
      Add Category
    </PrimaryButton>
  </PageHeader>

  <div class="mb-6 flex justify-between items-center">
    <div class="w-1/3">
      <TextInput
        type="text"
        placeholder="Search categories..."
        bind:value={search}
        on:input={handleSearch}
        class="w-full"
      />
    </div>
  </div>

  <div class="mt-6">
    <CategoryTable categories={data} onEdit={openEditModal} />
  </div>

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

  <CategoryModal bind:show={showModal} category={editingCategory} />
</AdminLayout>
