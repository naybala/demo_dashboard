<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import PageHeader from "@/Components/PageHeader.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { useForm, router, usePage, Head } from "@inertiajs/vue3";
import { __ } from "@/helpers.js";
import { computed } from "vue";

const props = defineProps({
  permission: {
    type: Object,
    default: null,
  },
});

const page = usePage();
const permissions = computed(() => page.props.permissions || []);

const form = useForm({
  name: props.permission?.name || "",
});

const submit = () => {
  if (props.permission) {
    form.put(`/permissions/${props.permission.id}`);
  } else {
    form.post("/permissions");
  }
};
</script>

<template>
  <Head>
    <title>
      {{
        __(
          props.permission
            ? "permission.edit_permission"
            : "permission.create_permission",
        )
      }}
    </title>
  </Head>

  <AdminLayout>
    <template #header>
      <h2
        class="font-semibold text-[12px] md:text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem] hidden md:block"
      >
        {{ __("sidebar.permission", "Permission") }}
      </h2>
    </template>
    <PageHeader
      :title="
        props.permission
          ? __('permission.edit_permission', 'Edit Permission')
          : __('permission.create_permission', 'Create Permission')
      "
    />

    <div
      class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 max-w-2xl mx-auto"
    >
      <form @submit.prevent="submit" class="space-y-6">
        <div>
          <InputLabel
            for="name"
            :value="__('permission.permission_name', 'Permission Name')"
          />
          <TextInput
            id="name"
            type="text"
            class="mt-1 block w-full"
            v-model="form.name"
            required
            placeholder="e.g. create users"
          />
          <p class="mt-2 text-xs text-gray-500">
            Tip: Use space for feature separation (e.g. "view users", "edit
            roles").
          </p>
          <InputError :message="form.errors.name" />
        </div>

        <div
          class="flex items-center justify-end gap-4 border-t border-gray-100 dark:border-gray-700 pt-6"
        >
          <SecondaryButton @click="router.get('/permissions')">
            {{ __("messages.cancel", "Cancel") }}
          </SecondaryButton>
          <template
            v-if="
              (props.permission && permissions.includes('edit permissions')) ||
              (!props.permission && permissions.includes('create permissions'))
            "
          >
            <PrimaryButton type="submit" :disabled="form.processing">
              {{
                props.permission
                  ? __("messages.update", "Update Permission")
                  : __("messages.create", "Create Permission")
              }}
            </PrimaryButton>
          </template>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>
