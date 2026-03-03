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
  import { onMount } from "svelte";
  import MultiSelectUi from "../../Components/MultiSelectUi.svelte";
  import { __ } from "@/helpers";

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

  let quillEn;
  let quillOther;
  let fileInput;

  onMount(async () => {
    // Lazy-load Quill — only fetched when this page is visited
    const [{ default: Quill }] = await Promise.all([
      import("quill"),
      import("quill/dist/quill.snow.css"),
    ]);

    const toolbarOptions = [
      ["bold", "italic", "underline", "strike"],
      ["blockquote", "code-block"],
      [{ header: 1 }, { header: 2 }],
      [{ list: "ordered" }, { list: "bullet" }],
      [{ script: "sub" }, { script: "super" }],
      [{ indent: "-1" }, { indent: "+1" }],
      [{ direction: "rtl" }],
      [{ size: ["small", false, "large", "huge"] }],
      [{ header: [1, 2, 3, 4, 5, 6, false] }],
      [{ color: [] }, { background: [] }],
      [{ font: [] }],
      [{ align: [] }],
      ["image", "clean"],
    ];

    quillEn = new Quill("#editor-en", {
      theme: "snow",
      modules: { toolbar: toolbarOptions },
    });

    quillOther = new Quill("#editor-other", {
      theme: "snow",
      modules: { toolbar: toolbarOptions },
    });

    quillEn.root.innerHTML = $form.description;
    quillOther.root.innerHTML = $form.description_other;

    quillEn.on("text-change", () => {
      $form.description = quillEn.root.innerHTML;
    });

    quillOther.on("text-change", () => {
      $form.description_other = quillOther.root.innerHTML;
    });
  });
</script>

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
      <div>
        <InputLabel value={__("product.photo", "Product Photos")} />
        <div class="mt-2 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
          {#each $form.existing_photos as path}
            <div
              class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200"
            >
              <img
                src={path}
                alt="product"
                class="w-full h-full object-cover"
              />
              <button
                type="button"
                on:click={() => removeExistingPhoto(path)}
                class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity"
              >
                <svg
                  class="w-4 h-4"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                  ><path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                  /></svg
                >
              </button>
            </div>
          {/each}

          {#each $form.photos as file, i}
            <div
              class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200 bg-gray-50 flex items-center justify-center"
            >
              <img
                src={URL.createObjectURL(file)}
                alt="preview"
                class="w-full h-full object-cover"
                on:load={(e) => URL.revokeObjectURL(e.target.src)}
              />
              <button
                type="button"
                on:click={() => removeNewPhoto(i)}
                class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 shadow-sm"
              >
                <svg
                  class="w-4 h-4"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                  ><path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                  /></svg
                >
              </button>
            </div>
          {/each}

          <button
            type="button"
            on:click={() => fileInput.click()}
            class="aspect-square rounded-lg border-2 border-dashed border-gray-300 flex flex-col items-center justify-center text-gray-500 hover:border-indigo-500 hover:text-indigo-500 transition-colors"
          >
            <svg
              class="w-8 h-8"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              ><path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 4v16m8-8H4"
              /></svg
            >
            <span class="mt-1 text-xs"
              >{__("product.add_photo", "Add Photo")}</span
            >
          </button>
          <input
            type="file"
            multiple
            class="hidden"
            bind:this={fileInput}
            on:change={handleFileChange}
            accept="image/*"
          />
        </div>
        <InputError message={$form.errors.photos} />
      </div>

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

      <div>
        <InputLabel value={__("product.description", "Description (EN)")} />
        <div
          class="mt-1 bg-white dark:bg-gray-900 rounded-md border border-gray-300 dark:border-gray-700 min-h-[200px]"
          id="editor-en"
        ></div>
        <InputError message={$form.errors.description} />
      </div>

      <div>
        <InputLabel
          value={__("product.description_other", "Description (Other)")}
        />
        <div
          class="mt-1 bg-white dark:bg-gray-900 rounded-md border border-gray-300 dark:border-gray-700 min-h-[200px]"
          id="editor-other"
        ></div>
        <InputError message={$form.errors.description_other} />
      </div>

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
