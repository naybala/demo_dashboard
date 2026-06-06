<script>
  import Modal from "@/Components/Modal.svelte";
  import InputLabel from "@/Components/InputLabel.svelte";
  import TextInput from "@/Components/TextInput.svelte";
  import InputError from "@/Components/InputError.svelte";
  import PrimaryButton from "@/Components/PrimaryButton.svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import { useForm } from "@inertiajs/svelte";
  import { get } from "svelte/store";

  import SearchableSelect from "@/Components/SearchableSelect.svelte";

  export let show = false;
  export let warehouses = [];
  export let products = [];

  $: productOptions = products.map((p) => ({
    id: p.id,
    label: `${p.name} (${p.unit || "No Unit"})`,
    searchKey: p.name,
  }));

  $: warehouseOptions = warehouses.map((w) => ({
    id: w.id,
    label: `${w.name}${w.location ? ` (${w.location})` : ""}`,
    searchKey: w.name,
  }));

  let form = useForm({
    warehouse_id: "",
    own_product_id: "",
    quantity: "",
    type: "in",
    note: "",
  });

  $: if (show) {
    form = useForm({ warehouse_id: "", own_product_id: "", quantity: "", type: "in", note: "" });
  }

  const submit = () => {
    get(form).post("/stock-transactions", {
      onSuccess: () => { show = false; },
    });
  };

  const close = () => { show = false; };
</script>

<Modal {show} on:close={close}>
  <form on:submit|preventDefault={submit} class="p-6 dark:bg-slate-700 space-y-4">
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
      Record Stock Transaction
    </h2>

    <!-- Type Toggle -->
    <div>
      <InputLabel value="Transaction Type *" />
      <div class="mt-2 flex gap-3">
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="radio" bind:group={$form.type} value="in"
            class="text-green-600 focus:ring-green-500" />
          <span class="text-sm font-medium text-green-700 dark:text-green-400">📦 Stock IN</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="radio" bind:group={$form.type} value="out"
            class="text-red-600 focus:ring-red-500" />
          <span class="text-sm font-medium text-red-700 dark:text-red-400">📤 Stock OUT</span>
        </label>
      </div>
      <InputError message={$form.errors.type} class="mt-1" />
    </div>

    <!-- Warehouse -->
    <div>
      <InputLabel for="tx_warehouse" value="Warehouse *" />
      <div class="mt-1">
        <SearchableSelect
          asyncUrl="/warehouses/search"
          options={warehouseOptions}
          bind:value={$form.warehouse_id}
          placeholder="Select Warehouse"
        />
      </div>
      <InputError message={$form.errors.warehouse_id} class="mt-1" />
    </div>

    <!-- Product -->
    <div>
      <InputLabel for="tx_product" value="Product *" />
      <div class="mt-1">
        <SearchableSelect
          asyncUrl="/own-products/search"
          options={productOptions}
          bind:value={$form.own_product_id}
          placeholder="Select Product"
        />
      </div>
      <InputError message={$form.errors.own_product_id} class="mt-1" />
    </div>

    <!-- Quantity -->
    <div>
      <InputLabel for="tx_qty" value="Quantity *" />
      <TextInput id="tx_qty" type="number" min="0.01" step="0.01"
        class="mt-1 block w-full" bind:value={$form.quantity} />
      <InputError message={$form.errors.quantity} class="mt-1" />
    </div>

    <!-- Note -->
    <div>
      <InputLabel for="tx_note" value="Note" />
      <textarea id="tx_note" rows="2"
        class="mt-1 block w-full border-gray-300 border focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700 text-sm"
        bind:value={$form.note}></textarea>
      <InputError message={$form.errors.note} class="mt-1" />
    </div>

    <div class="flex justify-end gap-3 pt-2">
      <SecondaryButton on:click={close}>Cancel</SecondaryButton>
      <PrimaryButton disabled={$form.processing}>Save Transaction</PrimaryButton>
    </div>
  </form>
</Modal>
