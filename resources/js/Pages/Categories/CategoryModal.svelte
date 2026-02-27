<script>
  import Modal from "@/Components/Modal.svelte";
  import InputLabel from "@/Components/InputLabel.svelte";
  import TextInput from "@/Components/TextInput.svelte";
  import InputError from "@/Components/InputError.svelte";
  import PrimaryButton from "@/Components/PrimaryButton.svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import { useCategoryForm } from "./useCategoryForm";
  import { __ } from "@/helpers.js";

  export let show = false;
  export let category = null;

  let { form, submit } = useCategoryForm(category);

  $: if (show) {
    ({ form, submit } = useCategoryForm(category));
  }

  const close = () => {
    show = false;
  };
</script>

<Modal {show} on:close={close}>
  <form on:submit|preventDefault={submit} class="p-6 dark:bg-slate-500">
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
      {category
        ? __("category.update_category", "Update Category")
        : __("category.create_category", "Create Category Hello")}
    </h2>

    <div class="mt-6">
      <InputLabel for="name" value={__("category.name", "Name")} />
      <TextInput
        id="name"
        class="mt-2 block w-3/4"
        bind:value={$form.name}
        required
        autofocus
      />
      <InputError message={$form.errors.name} class="mt-2" />
    </div>

    <div class="mt-4">
      <InputLabel
        for="name_other"
        value={__("category.name_other", "Other Name")}
      />
      <TextInput
        id="name_other"
        class="mt-1 block w-3/4"
        bind:value={$form.name_other}
      />
      <InputError message={$form.errors.name_other} class="mt-2" />
    </div>

    <div class="mt-4">
      <InputLabel
        for="description"
        value={__("category.description", "Description")}
      />
      <textarea
        id="description"
        class="mt-1 block w-3/4 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm dark:bg-gray-800 dark:text-gray-100"
        bind:value={$form.description}
      ></textarea>
      <InputError message={$form.errors.description} class="mt-2" />
    </div>

    <div class="mt-4">
      <label class="flex items-center">
        <input
          type="checkbox"
          bind:checked={$form.is_show}
          class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
        />
        <span class="ms-2 text-sm text-gray-600 dark:text-gray-400"
          >Show in Frontend</span
        >
      </label>
    </div>

    <div class="mt-6 flex justify-end gap-3">
      <SecondaryButton on:click={close}>Cancel</SecondaryButton>
      <PrimaryButton disabled={$form.processing}>
        {category ? "Update" : "Save"}
      </PrimaryButton>
    </div>
  </form>
</Modal>
