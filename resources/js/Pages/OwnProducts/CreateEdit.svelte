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
  import CurrencyInput from "@/Components/CurrencyInput.svelte";

  export let ownProduct = null;
  export let categories = [];
  export let units = [];

  $: categoryOptions = categories.map((c) => ({ id: c.id, label: c.name }));
  $: unitOptions = units.map((u) => ({ id: u.id, label: u.name }));

  const form = useForm({
    name: ownProduct?.name || "",
    category_id: ownProduct?.category_id || "",
    unit_id: ownProduct?.unit_id || "",
    price: ownProduct?.price || "",
    investment: ownProduct?.investment || "",
    profit: ownProduct?.profit || "",
    image: null,
  });

  let imagePreview = ownProduct?.image || null;

  const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      $form.image = file;
      imagePreview = URL.createObjectURL(file);
    }
  };

  const submit = () => {
    if (ownProduct) {
      $form
        .transform((data) => ({
          ...data,
          _method: "PUT",
        }))
        .post(`/own-products/${ownProduct.id}`);
    } else {
      $form.post("/own-products");
    }
  };
</script>

<AdminLayout>
  <svelte:fragment slot="header">
    <h2
      class="font-semibold text-[12px] md:text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem] hidden md:block"
    >
      {ownProduct ? "Edit Own Product" : "Create Own Product"}
    </h2>
  </svelte:fragment>
  <PageHeader
    class="block md:hidden"
    title={ownProduct ? "Edit Own Product" : "Create Own Product"}
  />

  <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
    <form on:submit|preventDefault={submit} class="space-y-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="flex flex-col items-center justify-center mb-6">
          <div
            class="relative group w-72 h-72 rounded-md overflow-hidden border-2 border-gray-300 dark:border-gray-700"
          >
            {#if imagePreview}
              <img
                src={imagePreview}
                alt="preview"
                class="w-72 h-72 object-cover"
              />
            {:else}
              <div
                class="w-72 h-72 bg-gray-100 flex items-center justify-center text-gray-400"
              >
                <svg
                  class="w-72 h-72"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                  ><path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                  /></svg
                >
              </div>
            {/if}
            <label
              class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer text-white text-xs font-bold"
            >
              Change Image
              <input
                type="file"
                class="hidden"
                on:change={handleImageChange}
                accept="image/*"
              />
            </label>
          </div>
          <InputError message={$form.errors.image} class="mt-2" />
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 col-span-2">
          <div>
            <InputLabel for="name" value="Product Name" />
            <TextInput
              id="name"
              type="text"
              class="mt-1 block w-full"
              bind:value={$form.name}
              required
            />
            <InputError message={$form.errors.name} />
          </div>
          <div>
            <InputLabel for="price" value="Price" />
            <CurrencyInput
              id="price"
              class="mt-1 block w-full"
              bind:value={$form.price}
              required
            />
            <InputError message={$form.errors.price} />
          </div>

          <div>
            <InputLabel for="investment" value="Investment" />
            <CurrencyInput
              id="investment"
              class="mt-1 block w-full"
              bind:value={$form.investment}
              required
            />
            <InputError message={$form.errors.investment} />
          </div>

          <div>
            <InputLabel for="profit" value="Profit" />
            <CurrencyInput
              id="profit"
              class="mt-1 block w-full"
              bind:value={$form.profit}
              required
            />
            <InputError message={$form.errors.profit} />
          </div>
          <div>
            <InputLabel for="category" value="Category" />
            <div class="mt-1">
              <SearchableSelect
                options={categoryOptions}
                bind:value={$form.category_id}
                placeholder="Select Category"
                required
              />
            </div>
            <InputError message={$form.errors.category_id} />
          </div>

          <div>
            <InputLabel for="unit" value="Unit" />
            <div class="mt-1">
              <SearchableSelect
                options={unitOptions}
                bind:value={$form.unit_id}
                placeholder="Select Unit"
                required
              />
            </div>
            <InputError message={$form.errors.unit_id} />
          </div>
        </div>
      </div>

      <div class="flex items-center justify-end gap-4 mt-6">
        <SecondaryButton on:click={() => router.get("/own-products")}
          >Cancel</SecondaryButton
        >
        <PrimaryButton type="submit" disabled={$form.processing}>
          {ownProduct ? "Update Own Product" : "Create Own Product"}
        </PrimaryButton>
      </div>
    </form>
  </div>
</AdminLayout>
