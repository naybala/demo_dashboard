<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import BaseTable from "@/Components/BaseTable.svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import PrimaryButton from "@/Components/PrimaryButton.svelte";
  import TextInput from "@/Components/TextInput.svelte";
  import { page, router, Link } from "@inertiajs/svelte";
  import DeleteConfirmationModal from "@/Components/DeleteConfirmationModal.svelte";
  import Pagination from "@/Components/Pagination.svelte";
  import { __, formatNumber } from "@/helpers.js";

  export let data = [];
  export let meta = {};

  $: permissions = $page.props.permissions || [];

  let search = "";
  let fromDate = "";
  let toDate = "";
  let showDeleteModal = false;
  let incomeToDelete = null;

  const headers = [
    { key: "date", label: __("table.date", "Date") },
    { key: "voucher_no", label: __("table.voucher_no", "Voucher No") },
    { key: "own_product", label: __("table.product_name", "Product") },
    { key: "amount", label: __("table.amount", "Amount") },
    { key: "price", label: __("table.price", "Price") },
    { key: "profit", label: __("table.profit", "Profit") },
    { key: "actions", label: __("table.action", "Actions") },
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
    router.delete(`/daily-incomes/${incomeToDelete.id}`, {
      onSuccess: () => {
        showDeleteModal = false;
        incomeToDelete = null;
      },
    });
  };
</script>

<AdminLayout>
  <svelte:fragment slot="header">
    <h2
      class="font-semibold text-[12px] md:text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem]"
    >
      {__("sidebar.daily_income", "Daily Incomes")}
    </h2>
  </svelte:fragment>

  <div
    class="mb-6 bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm"
  >
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 items-end">
      <div>
        <label
          for="search"
          class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
          >{__("messages.search", "Search")}</label
        >
        <TextInput
          id="search"
          type="text"
          placeholder={__(
            "placeholder.voucher_or_product",
            "Voucher or Product...",
          )}
          bind:value={search}
          class="w-full"
        />
      </div>
      <div>
        <label
          for="from_date"
          class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
          >{__("messages.from_date", "From Date")}</label
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
          >{__("messages.to_date", "To Date")}</label
        >
        <input
          type="date"
          id="to_date"
          bind:value={toDate}
          class="w-full py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
        />
      </div>
      <div class="flex gap-2">
        <PrimaryButton on:click={handleFilter}
          >{__("messages.filter", "Filter")}</PrimaryButton
        >
        <SecondaryButton
          on:click={() => {
            search = "";
            fromDate = "";
            toDate = "";
            handleFilter();
          }}>{__("messages.reset", "Reset")}</SecondaryButton
        >
        {#if permissions.includes("create daily-incomes")}
          <Link href="/daily-incomes/create" class="md:ml-auto">
            <PrimaryButton
              >{__("messages.create", "Create Daily Income")}</PrimaryButton
            >
          </Link>
        {/if}
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
          {formatNumber(income.amount, 0)}
          {income.unit}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          {formatNumber(income.price, 0)}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          {formatNumber(income.profit, 0)}
        </td>
        <td class="flex gap-2">
          <Link href={`/daily-incomes/${income.id}`}>
            <SecondaryButton>{__("messages.view", "View")}</SecondaryButton>
          </Link>
          {#if permissions.includes("edit daily-incomes")}
            <Link href={`/daily-incomes/${income.id}/edit`}>
              <SecondaryButton>{__("messages.edit", "Edit")}</SecondaryButton>
            </Link>
          {/if}
          {#if permissions.includes("delete daily-incomes")}
            <SecondaryButton
              variant="danger"
              on:click={() => confirmDelete(income)}
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
    title={__("dailyIncome.delete_title", "Delete Daily Income")}
    message={__(
      "dailyIncome.delete_message",
      "Are you sure you want to delete this record? If it belongs to a voucher, the entire voucher will be deleted.",
    )}
    onConfirm={deleteIncome}
    onClose={() => (showDeleteModal = false)}
  />
</AdminLayout>
