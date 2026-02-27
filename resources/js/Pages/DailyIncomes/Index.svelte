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
  let fromDate = "";
  let toDate = "";
  let showDeleteModal = false;
  let incomeToDelete = null;

  const headers = [
    { key: "date", label: "Date" },
    { key: "voucher_no", label: "Voucher No" },
    { key: "own_product", label: "Product" },
    { key: "amount", label: "Amount" },
    { key: "price", label: "Price" },
    { key: "profit", label: "Profit" },
    { key: "actions", label: "Actions" },
  ];

  const handleFilter = () => {
    router.get(
      "/daily-incomes",
      {
        keyword: search,
        from_date: fromDate,
        to_date: toDate,
      },
      { preserveState: true, replace: true },
    );
  };

  const confirmDelete = (income) => {
    incomeToDelete = income;
    showDeleteModal = true;
  };

  const deleteIncome = () => {
    router.delete("/daily-incomes", {
      data: { id: incomeToDelete.id },
      onSuccess: () => {
        showDeleteModal = false;
        incomeToDelete = null;
      },
    });
  };
</script>

<AdminLayout>
  <PageHeader title="Daily Incomes">
    <div slot="actions">
      <Link href="/daily-incomes/create">
        <PrimaryButton>Create Daily Income</PrimaryButton>
      </Link>
    </div>
  </PageHeader>

  <div
    class="mb-6 bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm"
  >
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
      <div>
        <label
          for="search"
          class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
          >Search</label
        >
        <TextInput
          id="search"
          type="text"
          placeholder="Voucher or Product..."
          bind:value={search}
          class="w-full"
        />
      </div>
      <div>
        <label
          for="from_date"
          class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
          >From Date</label
        >
        <input
          type="date"
          id="from_date"
          bind:value={fromDate}
          class="w-full py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
        />
      </div>
      <div>
        <label
          for="to_date"
          class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
          >To Date</label
        >
        <input
          type="date"
          id="to_date"
          bind:value={toDate}
          class="w-full py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
        />
      </div>
      <div class="flex gap-2">
        <PrimaryButton on:click={handleFilter}>Filter</PrimaryButton>
        <SecondaryButton
          on:click={() => {
            search = "";
            fromDate = "";
            toDate = "";
            handleFilter();
          }}>Reset</SecondaryButton
        >
      </div>
    </div>
  </div>

  <BaseTable {headers}>
    {#each data as income}
      <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          {income.date}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"
        >
          {income.voucher_no || "N/A"}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"
        >
          {income.own_product}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          {income.amount}
          {income.unit}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          {income.price}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          {income.profit}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
          <div class="flex gap-2">
            <Link href={`/daily-incomes/${income.id}/show`}>
              <SecondaryButton>View</SecondaryButton>
            </Link>
            <Link href={`/daily-incomes/${income.id}/edit`}>
              <SecondaryButton>Edit</SecondaryButton>
            </Link>
            <button
              on:click={() => confirmDelete(income)}
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
    title="Delete Daily Income"
    message={`Are you sure you want to delete this record? If it belongs to a voucher, the entire voucher will be deleted.`}
    on:confirm={deleteIncome}
    on:cancel={() => (showDeleteModal = false)}
  />
</AdminLayout>
