<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import PageHeader from "@/Components/PageHeader.svelte";
  import { Link } from "@inertiajs/svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import { formatNumber } from "@/helpers.js";

  export let dailyIncome = {};
</script>

<AdminLayout>
  <PageHeader title={`Voucher: ${dailyIncome.voucher_no || "N/A"}`}>
    <Link href="/daily-incomes">
      <SecondaryButton>Back to List</SecondaryButton>
    </Link>
  </PageHeader>

  <div
    class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden"
  >
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
          <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">
            Date
          </h4>
          <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
            {dailyIncome.date}
          </p>
        </div>
        <div>
          <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">
            Voucher No
          </h4>
          <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
            {dailyIncome.voucher_no || "N/A"}
          </p>
        </div>
        <div>
          <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">
            Payment Status
          </h4>
          <span
            class={`mt-1 inline-flex px-2 text-xs font-semibold rounded-full leading-5 ${dailyIncome.is_instant ? "bg-green-100 text-green-800" : "bg-yellow-100 text-yellow-800"}`}
          >
            {dailyIncome.is_instant ? "Instant" : "Pending"}
          </span>
        </div>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-sm text-left">
        <thead
          class="bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300 uppercase font-medium"
        >
          <tr>
            <th class="px-6 py-3">Product</th>
            <th class="px-6 py-3">Unit</th>
            <th class="px-6 py-3 text-right">Amount</th>
            <th class="px-6 py-3 text-right">Price</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
          {#each dailyIncome.items as item}
            <tr>
              <td class="px-6 py-4 font-medium text-gray-900 dark:text-white"
                >{item.own_product}</td
              >
              <td class="px-6 py-4 text-gray-500 dark:text-gray-400"
                >{item.unit || "N/A"}</td
              >
              <td class="px-6 py-4 text-right text-gray-500 dark:text-gray-400"
                >{item.amount}</td
              >
              <td class="px-6 py-4 text-right text-gray-900 dark:text-white"
                >{item.price}</td
              >
            </tr>
          {/each}
        </tbody>
        <tfoot class="bg-gray-50 dark:bg-gray-700 font-bold">
          <tr>
            <td colspan="3" class="px-6 py-4 text-right">Total Price:</td>
            <td class="px-6 py-4 text-right text-gray-900 dark:text-white"
              >{formatNumber(
                dailyIncome.items.reduce(
                  (sum, i) =>
                    sum + parseFloat(i.price.toString().replace(/,/g, "")),
                  0,
                ),
                2,
              )}</td
            >
          </tr>
        </tfoot>
      </table>
    </div>

    {#if dailyIncome.note}
      <div
        class="p-6 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700"
      >
        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">
          Note
        </h4>
        <p class="mt-2 text-gray-700 dark:text-gray-300 whitespace-pre-wrap">
          {dailyIncome.note}
        </p>
      </div>
    {/if}
  </div>
</AdminLayout>
