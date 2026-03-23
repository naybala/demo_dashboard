<script setup>
import Modal from "@/Components/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { usePage } from "@inertiajs/vue3";
import { useCategoryForm } from "./useCategoryForm";
import { __ } from "@/helpers.js";
import { computed, watch, ref } from "vue";

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  category: {
    type: Object,
    default: null,
  },
});

const emit = defineEmits(["update:show", "close"]);

const page = usePage();
const permissions = computed(() => page.props.permissions || []);

const close = () => {
  emit("update:show", false);
  emit("close");
};

const formValue = ref(null);
const submitFunc = ref(null);

// Initial form state
const initForm = () => {
  const { form, submit } = useCategoryForm(props.category, {
    onSuccess: () => close(),
  });
  formValue.value = form;
  submitFunc.value = submit;
};

initForm();

// Reactively update form when modal opens or category changes
watch(
  () => [props.show, props.category],
  ([newShow]) => {
    if (newShow) {
      initForm();
    }
  },
);
</script>

<template>
  <Modal :show="show" @close="close">
    <form @submit.prevent="submitFunc" class="p-6 dark:bg-slate-500">
      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{
          category
            ? __("category.update_category", "Update Category")
            : __("category.create_category", "Create Category Hello")
        }}
      </h2>

      <div class="mt-6">
        <InputLabel for="name" :value="__('category.name', 'Name')" />
        <TextInput
          id="name"
          class="mt-2 block w-3/4"
          v-model="formValue.name"
          autofocus
        />
        <InputError :message="formValue.errors.name" class="mt-2" />
      </div>

      <div class="mt-4">
        <InputLabel
          for="name_other"
          :value="__('category.name_other', 'Other Name')"
        />
        <TextInput
          id="name_other"
          class="mt-1 block w-3/4"
          v-model="formValue.name_other"
        />
        <InputError :message="formValue.errors.name_other" class="mt-2" />
      </div>

      <div class="mt-4">
        <InputLabel
          for="description"
          :value="__('category.description', 'Description')"
        />
        <textarea
          id="description"
          class="mt-1 block w-3/4 border-gray-400 border-2 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm dark:bg-gray-800 dark:text-gray-100"
          v-model="formValue.description"
        ></textarea>
        <InputError :message="formValue.errors.description" class="mt-2" />
      </div>

      <div class="mt-4">
        <label class="flex items-center">
          <input
            type="checkbox"
            v-model="formValue.is_show"
            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
          />
          <span class="ms-2 text-sm text-gray-600 dark:text-gray-400"
            >Show in Frontend</span
          >
        </label>
      </div>

      <div class="mt-6 flex justify-end gap-3">
        <SecondaryButton @click="close">Cancel</SecondaryButton>
        <template
          v-if="
            (category && permissions.includes('edit categories')) ||
            (!category && permissions.includes('create categories'))
          "
        >
          <PrimaryButton :disabled="formValue.processing">
            {{ category ? "Update" : "Save" }}
          </PrimaryButton>
        </template>
      </div>
    </form>
  </Modal>
</template>
