<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import PageHeader from "@/Components/PageHeader.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { __ } from "@/helpers.js";
import { useForm, router, usePage, Head } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
  unit: {
    type: Object,
    default: null,
  },
});

const page = usePage();
const permissions = computed(() => page.props.permissions || []);

const form = useForm({
  name: props.unit?.name || "",
  description: props.unit?.description || "",
});

const submit = () => {
  if (props.unit) {
    form.put(`/units/${props.unit.id}`);
  } else {
    form.post("/units");
  }
};
</script>

<template>
  <Head>
    <title>{{ __(props.unit ? "unit.edit_unit" : "unit.create_unit") }}</title>
  </Head>

  <AdminLayout>
    <template #header>
      <h2
        class="font-semibold text-[12px] md:text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem] hidden md:block"
      >
        {{ __("sidebar.unit", "Unit") }}
      </h2>
    </template>
    <PageHeader
      :title="
        props.unit
          ? __('unit.edit_unit', 'Edit Unit')
          : __('unit.create_unit', 'Create Unit')
      "
    />

    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 max-w-xl">
      <form @submit.prevent="submit" class="space-y-6">
        <div>
          <InputLabel for="name" :value="__('unit.unit_name', 'Unit Name')" />
          <TextInput
            id="name"
            type="text"
            class="mt-1 block w-full"
            v-model="form.name"
            required
          />
          <InputError :message="form.errors.name" />
        </div>
        <div>
          <InputLabel
            for="description"
            :value="__('unit.description', 'Description')"
          />
          <TextInput
            id="description"
            type="text"
            class="mt-1 block w-full"
            v-model="form.description"
            required
          />
          <InputError :message="form.errors.description" />
        </div>

        <div class="flex items-center justify-end gap-4">
          <SecondaryButton @click="router.get('/units')">
            {{ __("messages.cancel", "Cancel") }}
          </SecondaryButton>
          <template
            v-if="
              (props.unit && permissions.includes('edit units')) ||
              (!props.unit && permissions.includes('create units'))
            "
          >
            <PrimaryButton type="submit" :disabled="form.processing">
              {{
                props.unit
                  ? __("messages.update", "Update Unit")
                  : __("messages.create", "Create Unit")
              }}
            </PrimaryButton>
          </template>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>
