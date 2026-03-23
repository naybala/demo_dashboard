<script setup>
import Modal from "./Modal.vue";
import SecondaryButton from "./SecondaryButton.vue";
import DangerButton from "./DangerButton.vue";

defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  title: {
    type: String,
    default: "Confirm Deletion",
  },
  message: {
    type: String,
    default:
      "Are you sure you want to delete this item? This action cannot be undone.",
  },
  confirmText: {
    type: String,
    default: "Delete",
  },
  onClose: {
    type: Function,
    required: true,
  },
  onConfirm: {
    type: Function,
    required: true,
  },
});
</script>

<template>
  <Modal :show="show" @close="onClose">
    <div class="p-6">
      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ title }}
      </h2>

      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ message }}
      </p>

      <div class="mt-6 flex justify-end gap-3">
        <SecondaryButton @click="onClose">Cancel</SecondaryButton>

        <DangerButton @click="onConfirm">
          {{ confirmText }}
        </DangerButton>
      </div>
    </div>
  </Modal>
</template>
