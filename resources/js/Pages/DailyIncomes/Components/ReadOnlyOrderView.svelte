<script>
  import { fade } from "svelte/transition";
  import { router } from "@inertiajs/svelte";
  import { __, formatNumber } from "@/helpers.js";

  export let dailyIncome;
  export let form; // The Inertia form store
  export let warehouses = [];
  export let getProductDetails;
  export let totalAmount;
  export let permissions = [];
  export let submit;
</script>

<form on:submit|preventDefault={submit} class="max-w-4xl mx-auto space-y-6" in:fade>
  <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-150 dark:border-gray-700 shadow-sm overflow-hidden">
    <!-- Header block -->
    <div class="p-6 border-b border-gray-150 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/10 flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div class="space-y-1">
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/30 dark:text-indigo-400">
          {dailyIncome.voucher_no}
        </span>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
          {__("dailyIncome.daily_income_details", "Daily Income Details")}
        </h3>
      </div>
      
      <div class="flex flex-wrap gap-4 text-sm text-gray-500 dark:text-gray-400">
        <div>
          <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">{__("dailyIncome.date", "Date")}</span>
          <span class="font-semibold text-gray-900 dark:text-white">{dailyIncome.date}</span>
        </div>
        <div>
          <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">{__("warehouse.warehouse", "Warehouse")}</span>
          <span class="font-semibold text-gray-900 dark:text-white">
            {warehouses.find(w => w.id === $form.warehouse_id)?.name || "Warehouse"}
          </span>
        </div>
      </div>
    </div>

    <!-- Items Table -->
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-gray-50/50 dark:bg-gray-900/5 text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider border-b border-gray-100 dark:border-gray-700/50">
            <th class="py-4 px-6">{__("product.product", "Product")}</th>
            <th class="py-4 px-4 text-right">{__("product.price", "Price")}</th>
            <th class="py-4 px-4 text-center">{__("product.quantity", "Quantity")}</th>
            <th class="py-4 px-6 text-right">{__("product.subtotal", "Subtotal")}</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50 text-sm">
          {#each $form.items as item, i (item.own_product_id || i)}
            {@const details = getProductDetails(item.own_product_id)}
            <tr class="hover:bg-gray-50/30 dark:hover:bg-gray-900/10 transition-colors">
              <td class="py-4 px-6 font-semibold text-gray-900 dark:text-white">
                <div class="flex items-center gap-3">
                  {#if details.image}
                    <img src={details.image} alt={details.name} class="w-10 h-10 rounded-lg object-cover bg-gray-100 border border-gray-200 dark:border-gray-700" />
                  {:else}
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-950/20 flex items-center justify-center text-indigo-500 dark:text-indigo-400">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5" />
                      </svg>
                    </div>
                  {/if}
                  <div>
                    <span class="block">{details.name}</span>
                    <span class="text-xs text-gray-400 font-medium">per {details.unit || "unit"}</span>
                  </div>
                </div>
              </td>
              <td class="py-4 px-4 text-right font-mono text-gray-900 dark:text-white">
                {formatNumber(details.price, 0)} MMK
              </td>
              <td class="py-4 px-4 text-center font-semibold text-gray-900 dark:text-white">
                {formatNumber(item.amount, 0)}
              </td>
              <td class="py-4 px-6 text-right font-mono font-bold text-indigo-600 dark:text-indigo-400">
                {formatNumber(parseFloat(item.price) || 0, 0)} MMK
              </td>
            </tr>
          {/each}
        </tbody>
      </table>
    </div>

    <!-- Edit Fields block -->
    <div class="p-6 border-t border-gray-150 dark:border-gray-700 bg-gray-50/20 dark:bg-gray-900/5 grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Left side: notes -->
      <div class="space-y-2">
        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
          {__("dailyIncome.note", "Order Notes / Description")}
        </label>
        <textarea
          bind:value={$form.note}
          rows="3"
          class="block w-full text-sm rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          placeholder="Add any additional details or description..."
        />
      </div>
      
      <!-- Right side: instant payment / status details -->
      <div class="flex flex-col justify-between space-y-4">
        <div class="space-y-2">
          <span class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
            {__("dailyIncome.payment_status", "Payment Status")}
          </span>
          <label class="inline-flex items-center cursor-pointer bg-white dark:bg-gray-900 border border-gray-250 dark:border-gray-700 px-4 py-3 rounded-xl shadow-sm hover:bg-gray-50/50 transition-all w-full">
            <input
              type="checkbox"
              class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 w-5 h-5"
              bind:checked={$form.is_instant}
            />
            <div class="ms-3">
              <span class="block text-sm font-bold text-gray-900 dark:text-white">Instant Payment</span>
              <span class="block text-[11px] text-gray-400">Mark this transaction as immediately paid</span>
            </div>
          </label>
        </div>

        <!-- Summary Totals -->
        <div class="p-4 bg-indigo-50/40 dark:bg-indigo-950/25 rounded-2xl border border-indigo-100/50 dark:border-indigo-900/30 space-y-1.5">
          <div class="flex justify-between items-center text-sm font-medium">
            <span class="text-gray-500">Items Total:</span>
            <span class="font-bold text-gray-900 dark:text-white">{totalAmount} MMK</span>
          </div>
          {#if permissions.includes("manage daily-incomes")}
            {@const totalProfit = $form.items.reduce((sum, item) => sum + (parseFloat(item.profit) || 0), 0)}
            <div class="flex justify-between items-center text-xs text-green-600 dark:text-green-400 font-medium">
              <span>Est. Profit:</span>
              <span class="font-bold font-mono">{formatNumber(totalProfit, 0)} MMK</span>
            </div>
          {/if}
        </div>
      </div>
    </div>

    <!-- Footer Actions -->
    <div class="p-6 border-t border-gray-150 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/10 flex justify-end gap-3">
      <button
        type="button"
        on:click={() => router.get("/daily-incomes")}
        class="px-6 py-3 text-sm font-bold bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-750 text-gray-700 dark:text-gray-200 border border-gray-250 dark:border-gray-700 rounded-xl transition-all"
      >
        Cancel
      </button>
      <button
        type="submit"
        disabled={$form.processing}
        class="px-8 py-3 text-sm font-bold bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-md hover:shadow-lg disabled:opacity-50 transition-all"
      >
        {$form.processing ? "Saving..." : "Save Changes"}
      </button>
    </div>
  </div>
</form>
