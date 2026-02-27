<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import PageHeader from "@/Components/PageHeader.svelte";
  import InputLabel from "@/Components/InputLabel.svelte";
  import TextInput from "@/Components/TextInput.svelte";
  import InputError from "@/Components/InputError.svelte";
  import PrimaryButton from "@/Components/PrimaryButton.svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import { useForm, router } from "@inertiajs/svelte";

  export let ownProduct = null;
  export let categories = [];
  export let units = [];

  const form = useForm({
    name: ownProduct?.name || "",
    category_id: ownProduct?.category_id || "",
    unit_id: ownProduct?.unit_id || "",
    price: ownProduct?.price?.toString().replace(/,/g, "") || "",
    investment: ownProduct?.investment?.toString().replace(/,/g, "") || "",
    profit: ownProduct?.profit?.toString().replace(/,/g, "") || "",
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
  <PageHeader title={ownProduct ? "Edit Own Product" : "Create Own Product"} />

  <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
    <form on:submit|preventDefault={submit} class="space-y-6">
      <div class="flex flex-col items-center justify-center mb-6">
        <div
          class="relative group w-32 h-32 rounded-full overflow-hidden border-2 border-gray-300 dark:border-gray-700"
        >
          {#if imagePreview}
            <img
              src={imagePreview}
              alt="preview"
              class="w-full h-full object-cover"
            />
          {:else}
            <div
              class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400"
            >
              <svg
                class="w-12 h-12"
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

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
          <InputLabel for="category" value="Category" />
          <select
            id="category"
            bind:value={$form.category_id}
            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            required
          >
            <option value="">Select Category</option>
            {#each categories as category}
              <option value={category.id}>{category.name}</option>
            {/each}
          </select>
          <InputError message={$form.errors.category_id} />
        </div>

        <div>
          <InputLabel for="unit" value="Unit" />
          <select
            id="unit"
            bind:value={$form.unit_id}
            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            required
          >
            <option value="">Select Unit</option>
            {#each units as unit}
              <option value={unit.id}>{unit.name}</option>
            {/each}
          </select>
          <InputError message={$form.errors.unit_id} />
        </div>

        <div>
          <InputLabel for="price" value="Price" />
          <TextInput
            id="price"
            type="number"
            class="mt-1 block w-full"
            bind:value={$form.price}
            required
          />
          <InputError message={$form.errors.price} />
        </div>

        <div>
          <InputLabel for="investment" value="Investment" />
          <TextInput
            id="investment"
            type="number"
            class="mt-1 block w-full"
            bind:value={$form.investment}
            required
          />
          <InputError message={$form.errors.investment} />
        </div>

        <div>
          <InputLabel for="profit" value="Profit" />
          <TextInput
            id="profit"
            type="number"
            class="mt-1 block w-full"
            bind:value={$form.profit}
            required
          />
          <InputError message={$form.errors.profit} />
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
