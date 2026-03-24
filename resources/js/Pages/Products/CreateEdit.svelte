<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import PageHeader from "@/Components/PageHeader.svelte";
  import InputLabel from "@/Components/InputLabel.svelte";
  import TextInput from "@/Components/TextInput.svelte";
  import InputError from "@/Components/InputError.svelte";
  import PrimaryButton from "@/Components/PrimaryButton.svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import { useProductForm } from "./useProductForm";
  import { page, router } from "@inertiajs/svelte";
  import CurrencyInput from "@/Components/CurrencyInput.svelte";
  import MultiSelectUi from "../../Components/MultiSelectUi.svelte";
  import { __ } from "@/helpers";
  import RichTextEditor from "@/Components/RichTextEditor.svelte";
  import ProductPhotoGallery from "./Partials/ProductPhotoGallery.svelte";

  export let product = null;
  export let categories = [];

  $: permissions = $page.props.permissions || [];

  const {
    form,
    handleFileChange,
    removeNewPhoto,
    removeExistingPhoto,
    submit,
  } = useProductForm(product);
</script>

<svelte:head>
  <title
    >{__(product ? "product.edit_product" : "product.create_product")}</title
  >
</svelte:head>

<AdminLayout>
  <svelte:fragment slot="header">
    <h2
      class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem] hidden md:block"
    >
      {__("sidebar.product", "Products")}
    </h2>
  </svelte:fragment>
  <PageHeader
    class="block md:hidden"
    title={__(
      product ? "product.edit_product" : "product.create_product",
      product ? "Edit Product" : "Create Product",
    )}
  />

  <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
    <form on:submit|preventDefault={submit} class="space-y-6">
      <!-- Image Upload -->
      <ProductPhotoGallery
        photos={$form.photos}
        existing_photos={$form.existing_photos}
        {handleFileChange}
        {removeNewPhoto}
        {removeExistingPhoto}
        error={$form.errors.photos}
      />

      <div
        class="grid grid-cols-1 md:grid-cols-3 gap-6 border border-gray-200 p-3 rounded-2xl"
      >
        <div>
          <InputLabel for="name" value={__("product.name", "Name (EN)")} />
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
          <InputLabel
            for="name_other"
            value={__("product.name_other", "Name (Other)")}
          />
          <TextInput
            id="name_other"
            type="text"
            class="mt-1 block w-full"
            bind:value={$form.name_other}
            required
          />
          <InputError message={$form.errors.name_other} />
        </div>

        <div>
          <InputLabel for="price" value={__("product.price", "Price")} />
          <CurrencyInput
            id="price"
            class="mt-1 block w-full"
            bind:value={$form.price}
            required
          />
          <InputError message={$form.errors.price} />
        </div>
      </div>
      <MultiSelectUi
        options={categories}
        label={__("product.category", "Categories")}
        bind:value={$form.categories}
        error={$form.errors.categories}
        placeholder={__("messages.search_item", "Select categories...")}
      />

      <div class="flex gap-6">
        <label class="inline-flex items-center">
          <input
            type="checkbox"
            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
            bind:checked={$form.is_banner}
          />
          <span class="ms-2 text-sm text-gray-600 dark:text-gray-400"
            >{__("product.is_banner", "Is Banner")}</span
          >
        </label>
        <label class="inline-flex items-center">
          <input
            type="checkbox"
            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
            bind:checked={$form.is_mini_banner}
          />
          <span class="ms-2 text-sm text-gray-600 dark:text-gray-400"
            >{__("product.is_mini_banner", "Is Mini Banner")}</span
          >
        </label>
      </div>

      <RichTextEditor
        bind:value={$form.description}
        uploadedPath="products/quill"
        label={__("product.description", "Description (EN)")}
        error={$form.errors.description}
      />

      <RichTextEditor
        bind:value={$form.description_other}
        uploadedPath="products/quill"
        label={__("product.description_other", "Description (Other)")}
        error={$form.errors.description_other}
      />

      <div class="flex items-center justify-end gap-4">
        <SecondaryButton on:click={() => router.get("/products")}
          >{__("messages.cancel", "Cancel")}</SecondaryButton
        >
        {#if (product && permissions.includes("edit products")) || (!product && permissions.includes("create products"))}
          <PrimaryButton type="submit" disabled={$form.processing}>
            {product
              ? __("product.edit_product", "Update Product")
              : __("product.create_product", "Create Product")}
          </PrimaryButton>
        {/if}
      </div>
    </form>
  </div>
</AdminLayout>
