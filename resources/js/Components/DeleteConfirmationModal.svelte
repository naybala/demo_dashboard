<script>
  import Modal from "./Modal.svelte";
  import SecondaryButton from "./SecondaryButton.svelte";
  import DangerButton from "./DangerButton.svelte";

  export let show = false;
  export let title = "Confirm Deletion";
  export let message =
    "Are you sure you want to delete this item? This action cannot be undone.";
  export let confirmText = "Delete";
  export let onClose;
  export let onConfirm;
</script>

<Modal {show} on:close={onClose}>
  <div class="p-6">
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
      {title}
    </h2>

    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
      {message}
    </p>

    <div class="mt-6 flex justify-end gap-3">
      <SecondaryButton on:click={onClose}>Cancel</SecondaryButton>

      <DangerButton on:click={onConfirm}>
        {confirmText}
      </DangerButton>
    </div>
  </div>
</Modal>
