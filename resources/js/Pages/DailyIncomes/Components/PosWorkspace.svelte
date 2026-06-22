<script>
  import { fade, slide } from "svelte/transition";
  import { router } from "@inertiajs/svelte";
  import { __, formatNumber } from "@/helpers.js";

  export let form; // Svelte store
  export let selectedCategory = "all";
  export let searchQueryInput = "";
  export let posProductsList = [];
  export let isLoadingProducts = false;
  export let categories = [];
  export let warehouses = [];
  export let totalAmount;
  export let permissions = [];
  export let isStockSufficient;

  export let handleSearch;
  export let handleProductClick;
  export let updateQuantity;
  export let removeItem;
  export let clearCart;
  export let getProductDetails;
  export let calculateProfit;
  export let submit;
</script>

<form on:submit|preventDefault={submit} class="space-y-6" in:fade>
  <div class="flex flex-col lg:flex-row gap-6 items-start">
    <!-- Left Panel: Categories & Products Grid -->
    <div class="flex-1 w-full space-y-4">
      <!-- Filter & Search Bar Header -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-150 dark:border-gray-700 shadow-sm">
        <div class="flex items-center gap-3">
          <div class="bg-indigo-50 dark:bg-indigo-950/40 p-2 rounded-xl text-indigo-600 dark:text-indigo-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v5.25a.75.75 0 00.75.75z" />
            </svg>
          </div>
          <div>
            <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Active Warehouse</span>
            <span class="font-bold text-gray-900 dark:text-white text-base">
              {warehouses.find((w) => w.id === $form.warehouse_id)?.name || "Warehouse"}
            </span>
          </div>
          <button
            type="button"
            class="ms-2 px-2.5 py-1 text-xs font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/30 dark:hover:bg-indigo-900/40 rounded-lg transition-all"
            on:click={() => {
              form.update((d) => ({ ...d, warehouse_id: "" }));
              selectedCategory = "all";
              searchQueryInput = "";
            }}
          >
            Switch
          </button>
        </div>

        <!-- Search input -->
        <div class="flex items-center gap-2 w-full sm:w-auto">
          <div class="relative w-full sm:w-64">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
            <input
              type="text"
              bind:value={searchQueryInput}
              on:keydown={(e) => {
                if (e.key === "Enter") {
                  e.preventDefault();
                  handleSearch();
                }
              }}
              placeholder="Search products..."
              class="w-full pl-10 pr-4 py-2 text-sm rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
            />
          </div>
          <button
            type="button"
            on:click={handleSearch}
            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold shadow-sm transition-all"
          >
            Search
          </button>
        </div>
      </div>

      <!-- Categories Tabs Slider -->
      <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
        <button
          type="button"
          class="px-4 py-2 rounded-full text-sm font-semibold whitespace-nowrap transition-all duration-200 {selectedCategory === 'all'
            ? 'bg-indigo-600 text-white shadow-md'
            : 'bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/80 text-gray-700 dark:text-gray-300 border border-gray-150 dark:border-gray-700'}"
          on:click={() => (selectedCategory = "all")}
        >
          All Products
        </button>
        {#each categories as cat (cat.id)}
          <button
            type="button"
            class="px-4 py-2 rounded-full text-sm font-semibold whitespace-nowrap transition-all duration-200 {selectedCategory === cat.id
              ? 'bg-indigo-600 text-white shadow-md'
              : 'bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/80 text-gray-700 dark:text-gray-300 border border-gray-150 dark:border-gray-700'}"
            on:click={() => (selectedCategory = cat.id)}
          >
            {cat.name}
          </button>
        {/each}
      </div>

      <!-- Products Grid -->
      {#if isLoadingProducts}
        <div class="flex flex-col items-center justify-center py-20 space-y-3 bg-white dark:bg-gray-800 rounded-3xl border border-gray-150 dark:border-gray-700 shadow-sm">
          <div class="w-10 h-10 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
          <span class="text-sm text-gray-500 dark:text-gray-400">Loading products...</span>
        </div>
      {:else if posProductsList.length === 0}
        <div class="flex flex-col items-center justify-center py-20 text-center space-y-2 bg-white dark:bg-gray-800 rounded-3xl border border-gray-150 dark:border-gray-700 shadow-sm">
          <svg class="w-16 h-16 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
          </svg>
          <h4 class="text-lg font-semibold text-gray-900 dark:text-white">No products found</h4>
          <p class="text-sm text-gray-500 dark:text-gray-400">Try changing your filters or keyword query.</p>
        </div>
      {:else}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 pb-10">
          {#each posProductsList as prod (prod.id)}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-150 dark:border-gray-700 shadow-sm overflow-hidden hover:shadow-md hover:border-indigo-100 dark:hover:border-indigo-900/30 transition-all duration-200 flex flex-col justify-between group relative">
              <div class="relative w-full aspect-video bg-gray-50 dark:bg-gray-900/50 flex items-center justify-center overflow-hidden border-b border-gray-100 dark:border-gray-700">
                {#if prod.image}
                  <img src={prod.image} alt={prod.name} class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                {:else}
                  <div class="w-16 h-16 rounded-2xl bg-indigo-50 dark:bg-indigo-950/30 flex items-center justify-center text-indigo-500 dark:text-indigo-400">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5V18a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18V7.5m0-3h18M3 7.5h18M7.5 12h9m-9 3h9" />
                    </svg>
                  </div>
                {/if}

                {#if categories.find((c) => c.id === prod.category_id)}
                  <span class="absolute top-2 left-2 px-2.5 py-1 text-[10px] font-bold bg-white/90 dark:bg-gray-950/80 backdrop-blur shadow-sm rounded-full text-gray-700 dark:text-gray-300">
                    {categories.find((c) => c.id === prod.category_id).name}
                  </span>
                {/if}
              </div>

              <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                <div>
                  <h4 class="font-bold text-gray-900 dark:text-white line-clamp-2 text-base group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                    {prod.name}
                  </h4>
                  <div class="flex items-center justify-between mt-2">
                    <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400">
                      {formatNumber(prod.price, 0)} {__("currency.symbol", "MMK")}
                    </span>
                    <span class="text-xs text-gray-400 font-medium">per {prod.unit || "unit"}</span>
                  </div>
                </div>

                <div class="pt-2 border-t border-gray-50 dark:border-gray-700/50 flex items-center justify-between">
                  <span class="px-2 py-0.5 rounded-full text-xs font-bold {prod.stock > 10 ? 'bg-green-50 text-green-700 dark:bg-green-950/20 dark:text-green-400' : prod.stock > 0 ? 'bg-yellow-50 text-yellow-700 dark:bg-yellow-950/20 dark:text-yellow-400' : 'bg-red-50 text-red-700 dark:bg-red-950/20 dark:text-red-400'}">
                    {prod.stock > 0 ? `Stock: ${formatNumber(prod.stock, 0)}` : "Out of stock"}
                  </span>

                  <button
                    type="button"
                    class="p-2 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/40 dark:hover:bg-indigo-900/60 rounded-xl text-indigo-650 dark:text-indigo-400 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled={prod.stock <= 0}
                    on:click={() => handleProductClick(prod)}
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          {/each}
        </div>
      {/if}
    </div>

    <!-- Right Panel: POS Cart Sidebar -->
    <div class="w-full lg:w-[400px] xl:w-[440px] flex flex-col bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-3xl overflow-hidden shadow-sm lg:sticky lg:top-4">
      <!-- Sidebar Header -->
      <div class="p-4 border-b border-gray-150 dark:border-gray-700 space-y-3 bg-gray-50/50 dark:bg-gray-900/10">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-bold text-gray-900 dark:text-white">Current Order</h3>
          <button type="button" class="text-xs font-semibold text-red-600 dark:text-red-400 hover:underline" on:click={clearCart}>
            Clear All
          </button>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</label>
            <input
              type="date"
              bind:value={$form.date}
              class="block w-full text-xs rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mt-1"
              required
            />
          </div>
          <div class="flex items-end">
            <label class="inline-flex items-center mb-2.5 cursor-pointer">
              <input
                type="checkbox"
                class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500"
                bind:checked={$form.is_instant}
              />
              <span class="ms-2 text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Instant Payment</span>
            </label>
          </div>
        </div>
      </div>

      <!-- Sidebar Items List -->
      <div class="flex-1 overflow-y-auto p-4 space-y-3 min-h-[250px] max-h-[400px]">
        {#if $form.items.length === 0 || ($form.items.length === 1 && !$form.items[0].own_product_id)}
          <div class="flex flex-col items-center justify-center h-full text-center text-gray-400 py-16">
            <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
            </svg>
            <p class="text-sm">Your order is empty.</p>
            <p class="text-xs text-gray-400 mt-1">Select products from the left panel.</p>
          </div>
        {:else}
          {#each $form.items as item, i (item.own_product_id || i)}
            {@const details = getProductDetails(item.own_product_id)}
            <div class="p-3 bg-gray-50 dark:bg-gray-900/40 border border-gray-100 dark:border-gray-700/80 rounded-2xl flex gap-3 relative transition-all" transition:slide|local>
              {#if details.image}
                <img src={details.image} alt={details.name} class="w-12 h-12 rounded-lg object-cover bg-gray-100" />
              {:else}
                <div class="w-12 h-12 rounded-lg bg-indigo-50 dark:bg-indigo-950/40 flex items-center justify-center text-indigo-500">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5" />
                  </svg>
                </div>
              {/if}

              <div class="flex-1 flex flex-col justify-between min-w-0">
                <div>
                  <div class="flex justify-between items-start gap-2">
                    <h5 class="font-bold text-gray-900 dark:text-white text-sm truncate">{details.name}</h5>
                    <button type="button" class="text-red-500 hover:text-red-700" on:click={() => removeItem(i)}>
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    {formatNumber(details.price, 0)} {__("currency.symbol", "MMK")} / {details.unit || "unit"}
                  </p>
                </div>

                <div class="flex items-center justify-between mt-2 gap-2">
                  <!-- Quantity Controls -->
                  <div class="flex items-center gap-1 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-700 rounded-lg p-0.5 shadow-sm">
                    <button
                      type="button"
                      class="w-6 h-6 rounded-md hover:bg-gray-100 dark:hover:bg-gray-900 flex items-center justify-center font-bold text-gray-500 dark:text-gray-400 transition-colors"
                      on:click={() => updateQuantity(i, -1)}
                    >
                      -
                    </button>
                    <input
                      type="text"
                      value={item.amount}
                      class="w-10 border-0 p-0 text-center text-xs font-bold bg-transparent focus:ring-0 focus:outline-none dark:text-white"
                      on:input={(e) => {
                        const val = parseInt(e.target.value) || 0;
                        form.update((data) => {
                          const items = [...data.items];
                          items[i].amount = val.toString();
                          return { ...data, items };
                        });
                        calculateProfit(i);
                      }}
                    />
                    <button
                      type="button"
                      class="w-6 h-6 rounded-md hover:bg-gray-100 dark:hover:bg-gray-900 flex items-center justify-center font-bold text-gray-500 dark:text-gray-400 transition-colors"
                      on:click={() => updateQuantity(i, 1)}
                    >
                      +
                    </button>
                  </div>

                  <!-- Price & stock check -->
                  <div class="text-right">
                    <span class="font-bold text-sm text-gray-900 dark:text-white font-mono">
                      {formatNumber(item.price, 0)} MMK
                    </span>
                    {#if item.stock_left !== null}
                      <div class="text-[10px] mt-0.5">
                        {#if parseInt(item.amount || 0) > item.stock_left}
                          <span class="text-red-500 font-bold bg-red-50 dark:bg-red-950/20 px-1.5 py-0.5 rounded">
                            Exceeds Stock ({item.stock_left})
                          </span>
                        {:else}
                          <span class="text-green-600 dark:text-green-400 font-semibold">
                            In Stock: {item.stock_left}
                          </span>
                        {/if}
                      </div>
                    {/if}
                  </div>
                </div>
              </div>
            </div>
          {/each}
        {/if}
      </div>

      <!-- Sidebar Footer -->
      <div class="p-4 border-t border-gray-150 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/10 space-y-4">
        <div>
          <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Order Notes</label>
          <textarea
            bind:value={$form.note}
            class="block w-full text-sm rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            rows="2"
            placeholder="Voucher memo notes..."
          />
        </div>

        <div class="space-y-1.5">
          <div class="flex justify-between items-center text-sm">
            <span class="text-gray-500">Items Total:</span>
            <span class="font-bold text-gray-900 dark:text-white">{totalAmount} MMK</span>
          </div>
          {#if permissions.includes("manage daily-incomes")}
            {@const totalProfit = $form.items.reduce((sum, item) => sum + (parseFloat(item.profit) || 0), 0)}
            <div class="flex justify-between items-center text-xs text-green-600 dark:text-green-400">
              <span>Est. Profit:</span>
              <span class="font-bold font-mono">{formatNumber(totalProfit, 0)} MMK</span>
            </div>
          {/if}
        </div>

        {#if !$isStockSufficient}
          <div class="text-xs text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/20 px-3 py-2 rounded-xl font-semibold flex items-center gap-1.5">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>Cannot place order: some products exceed stock!</span>
          </div>
        {/if}

        <div class="flex gap-3">
          <button
            type="button"
            on:click={() => router.get("/daily-incomes")}
            class="flex-1 py-3 text-center text-sm font-bold bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-xl transition-all"
          >
            Cancel
          </button>
          <button
            type="submit"
            disabled={$form.processing || !$isStockSufficient || $form.items.length === 0 || ($form.items.length === 1 && !$form.items[0].own_product_id)}
            class="flex-[2] py-3 text-center text-sm font-bold bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-300 dark:disabled:bg-gray-700 text-white rounded-xl shadow-md hover:shadow-lg disabled:shadow-none disabled:cursor-not-allowed transition-all"
          >
            Place Order
          </button>
        </div>
      </div>
    </div>
  </div>
</form>
