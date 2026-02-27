<script>
  import Modal from "./Modal.svelte";
  import PrimaryButton from "./PrimaryButton.svelte";
  import SecondaryButton from "./SecondaryButton.svelte";

  export let show = false;
  export let title = "Delete Confirmation";
  export let message = "Are you sure you want to delete this item?";
  export let confirmText = "Delete";

  const close = () => {
    show = false;
  };

  const confirm = () => {
    // This will be handled by the parent
  };
</script>

<Modal {show} maxWidth="md" on:close={close}>
  <div class="p-6">
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
      {title}
    </h2>

    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
      {message}
    </p>

    <div class="mt-6 flex justify-end">
      <SecondaryButton on:click={close}>Cancel</SecondaryButton>

      <PrimaryButton
        class="ms-3 bg-red-600 hover:bg-red-500 focus:bg-red-500 active:bg-red-700"
        on:click
      >
        {confirmText}
      </PrimaryButton>
    </div>
  </div>
</Modal>
