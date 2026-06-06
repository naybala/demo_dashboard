<script>
  import Modal from "@/Components/Modal.svelte";
  import InputLabel from "@/Components/InputLabel.svelte";
  import TextInput from "@/Components/TextInput.svelte";
  import InputError from "@/Components/InputError.svelte";
  import PrimaryButton from "@/Components/PrimaryButton.svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import { page } from "@inertiajs/svelte";
  import { useWarehouseForm } from "./useWarehouseForm";

  export let show = false;
  export let warehouse = null;

  $: permissions = $page.props.permissions || [];

  let { form, submit } = useWarehouseForm(warehouse, { onSuccess: () => close() });

  $: if (show) {
    ({ form, submit } = useWarehouseForm(warehouse, { onSuccess: () => close() }));
  }

  const close = () => { show = false; };
</script>

<Modal {show} on:close={close}>
  <form on:submit|preventDefault={submit} class="p-6 dark:bg-slate-700">
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
      {warehouse ? "Update Warehouse" : "Create Warehouse"}
    </h2>

    <div class="mt-5">
      <InputLabel for="warehouse_name" value="Name *" />
      <TextInput id="warehouse_name" class="mt-1 block w-full" bind:value={$form.name} autofocus />
      <InputError message={$form.errors.name} class="mt-2" />
    </div>

    <div class="mt-4">
      <InputLabel for="warehouse_location" value="Location" />
      <TextInput id="warehouse_location" class="mt-1 block w-full" bind:value={$form.location} />
      <InputError message={$form.errors.location} class="mt-2" />
    </div>

    <div class="mt-4">
      <InputLabel for="warehouse_description" value="Description" />
      <textarea
        id="warehouse_description"
        rows="3"
        class="mt-1 block w-full border-gray-300 border-2 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm dark:bg-gray-800 dark:text-gray-100"
        bind:value={$form.description}
      ></textarea>
      <InputError message={$form.errors.description} class="mt-2" />
    </div>

    <div class="mt-6 flex justify-end gap-3">
      <SecondaryButton on:click={close}>Cancel</SecondaryButton>
      {#if (warehouse && permissions.includes("edit warehouses")) || (!warehouse && permissions.includes("create warehouses"))}
        <PrimaryButton disabled={$form.processing}>
          {warehouse ? "Update" : "Save"}
        </PrimaryButton>
      {/if}
    </div>
  </form>
</Modal>
