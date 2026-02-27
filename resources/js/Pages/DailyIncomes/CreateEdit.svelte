<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import PageHeader from "@/Components/PageHeader.svelte";
  import InputLabel from "@/Components/InputLabel.svelte";
  import TextInput from "@/Components/TextInput.svelte";
  import InputError from "@/Components/InputError.svelte";
  import PrimaryButton from "@/Components/PrimaryButton.svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import SearchableSelect from "@/Components/SearchableSelect.svelte";
  import { useForm, router } from "@inertiajs/svelte";

  export let dailyIncome = null;
  export let products = [];

  $: productOptions = products.map((p) => ({
    id: p.id,
    label: `${p.name} (${p.unit?.name || "No Unit"})`,
    searchKey: p.name,
  }));

  const form = useForm({
    date: dailyIncome?.date || new Date().toISOString().split("T")[0],
    is_instant: dailyIncome?.is_instant ?? true,
    note: dailyIncome?.note || "",
    items: dailyIncome?.items?.map((item) => ({
      own_product_id: item.own_product_id,
      amount: item.amount.toString().replace(/,/g, ""),
      price: item.price.toString().replace(/,/g, ""),
      investment: item.investment.toString().replace(/,/g, ""),
      profit: item.profit.toString().replace(/,/g, ""),
      id: item.id,
    })) || [
      { own_product_id: "", amount: "", price: "", investment: "", profit: "" },
    ],
  });

  const addItem = () => {
    $form.items = [
      ...$form.items,
      { own_product_id: "", amount: "", price: "", investment: "", profit: "" },
    ];
  };

  const removeItem = (index) => {
    if ($form.items.length > 1) {
      $form.items = $form.items.filter((_, i) => i !== index);
    }
  };

  const handleProductChange = (index) => {
    const product = products.find(
      (p) => p.id === $form.items[index].own_product_id,
    );
    if (product) {
      // Default amount to 1 if it's empty or 0
      if (
        !$form.items[index].amount ||
        parseFloat($form.items[index].amount) === 0
      ) {
        $form.items[index].amount = "1";
      }

      $form.items[index].price = product.price.toString().replace(/,/g, "");
      $form.items[index].investment = product.investment
        .toString()
        .replace(/,/g, "");
      $form.items[index].profit = product.profit.toString().replace(/,/g, "");
      calculateProfit(index);
    }
  };

  const calculateProfit = (index) => {
    const item = $form.items[index];
    const amount = parseFloat(item.amount) || 0;
    const product = products.find((p) => p.id === item.own_product_id);

    if (product) {
      const unitPrice =
        parseFloat(product.price.toString().replace(/,/g, "")) || 0;
      const unitInv =
        parseFloat(product.investment.toString().replace(/,/g, "")) || 0;

      $form.items[index].price = (amount * unitPrice).toFixed(2);
      $form.items[index].investment = (amount * unitInv).toFixed(2);
      $form.items[index].profit = (amount * (unitPrice - unitInv)).toFixed(2);
    }
  };

  const submit = () => {
    if (dailyIncome) {
      $form.put(`/daily-incomes/${dailyIncome.id}`);
    } else {
      $form.post("/daily-incomes");
    }
  };

  $: totalAmount = $form.items
    .reduce((sum, item) => sum + (parseFloat(item.price) || 0), 0)
    .toFixed(2);
</script>

<AdminLayout>
  <PageHeader
    title={dailyIncome ? "Edit Daily Income" : "Create Daily Income"}
  />

  <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
    <form on:submit|preventDefault={submit} class="space-y-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <InputLabel for="date" value="Date" />
          <input
            type="date"
            id="date"
            bind:value={$form.date}
            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            required
          />
          <InputError message={$form.errors.date} />
        </div>

        <div class="flex items-end">
          <label class="inline-flex items-center mb-2">
            <input
              type="checkbox"
              class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
              bind:checked={$form.is_instant}
            />
            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400"
              >Is Instant Payment</span
            >
          </label>
        </div>
      </div>

      <div class="space-y-4">
        <div class="flex justify-between items-center">
          <h3 class="text-lg font-medium text-gray-900 dark:text-white">
            Items
          </h3>
          <SecondaryButton type="button" on:click={addItem}
            >Add Item</SecondaryButton
          >
        </div>

        <div class="space-y-4">
          <!-- Desktop Table View -->
          <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm text-left">
              <thead
                class="bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300 uppercase font-medium"
              >
                <tr>
                  <th class="px-4 py-2 w-1/4">Product</th>
                  <th class="px-4 py-2 w-24">Amount</th>
                  <th class="px-4 py-2 w-32">Price</th>
                  <th class="px-4 py-2 w-32">Profit</th>
                  <th class="px-4 py-2 w-16"></th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                {#each $form.items as item, i}
                  <tr>
                    <td class="px-4 py-2">
                      <SearchableSelect
                        options={productOptions}
                        bind:value={item.own_product_id}
                        on:change={() => handleProductChange(i)}
                        placeholder="Select Product"
                      />
                    </td>
                    <td class="px-4 py-2">
                      <input
                        id="amount_{i}"
                        type="number"
                        bind:value={item.amount}
                        on:input={() => calculateProfit(i)}
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 text-sm"
                        step="0.01"
                        required
                      />
                    </td>
                    <td class="px-4 py-2 text-right">
                      <div
                        class="px-3 py-2 bg-gray-50 dark:bg-gray-900 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm font-mono"
                      >
                        {item.price || "0.00"}
                      </div>
                    </td>
                    <td class="px-4 py-2 text-right">
                      <div
                        class="px-3 py-2 bg-gray-50 dark:bg-gray-900 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm font-mono"
                      >
                        {item.profit || "0.00"}
                      </div>
                    </td>
                    <td class="px-4 py-2">
                      <button
                        type="button"
                        on:click={() => removeItem(i)}
                        class="text-red-600 hover:text-red-900"
                      >
                        <svg
                          class="w-5 h-5"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                          />
                        </svg>
                      </button>
                    </td>
                  </tr>
                {/each}
              </tbody>
            </table>
          </div>

          <!-- Mobile Card View -->
          <div class="md:hidden space-y-4">
            {#each $form.items as item, i}
              <div
                class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600 space-y-4 relative"
              >
                <button
                  type="button"
                  on:click={() => removeItem(i)}
                  class="absolute top-2 right-2 text-red-600 p-1"
                >
                  <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                    />
                  </svg>
                </button>

                <div class="space-y-2">
                  <InputLabel value="Product" />
                  <SearchableSelect
                    options={productOptions}
                    bind:value={item.own_product_id}
                    on:change={() => handleProductChange(i)}
                    placeholder="Select Product"
                  />
                </div>

                <div class="grid grid-cols-2 gap-4">
                  <div class="space-y-2">
                    <InputLabel value="Amount" />
                    <input
                      type="number"
                      bind:value={item.amount}
                      on:input={() => calculateProfit(i)}
                      class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 text-sm"
                      step="0.01"
                      required
                    />
                  </div>
                  <div class="space-y-2">
                    <InputLabel value="Price" />
                    <div
                      class="px-3 py-2 bg-white dark:bg-gray-900 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm font-mono truncate"
                    >
                      {item.price || "0.00"}
                    </div>
                  </div>
                </div>

                <div
                  class="flex justify-between items-center text-sm pt-2 border-t border-gray-200 dark:border-gray-600"
                >
                  <span class="text-gray-500">Profit:</span>
                  <span class="font-bold text-indigo-600 dark:text-indigo-400"
                    >{item.profit || "0.00"}</span
                  >
                </div>
              </div>
            {/each}
          </div>

          <!-- Footer/Totals -->
          <div
            class="pt-4 border-t border-gray-200 dark:border-gray-600 flex justify-end"
          >
            <div
              class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-4"
            >
              <span>Total:</span>
              <span
                class="text-indigo-600 p-2 bg-indigo-50 dark:bg-indigo-900/30 rounded"
                >{totalAmount}</span
              >
            </div>
          </div>
        </div>
      </div>

      <div>
        <InputLabel for="note" value="Note" />
        <textarea
          id="note"
          bind:value={$form.note}
          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          rows="3"
        ></textarea>
        <InputError message={$form.errors.note} />
      </div>

      <div class="flex items-center justify-end gap-4">
        <SecondaryButton on:click={() => router.get("/daily-incomes")}
          >Cancel</SecondaryButton
        >
        <PrimaryButton type="submit" disabled={$form.processing}>
          {dailyIncome ? "Update Record" : "Save Record"}
        </PrimaryButton>
      </div>
    </form>
  </div>
</AdminLayout>
