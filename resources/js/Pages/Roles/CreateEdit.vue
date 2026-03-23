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
  role: {
    type: Object,
    default: null,
  },
  getAllPermissions: {
    type: Object,
    default: () => ({}),
  },
  getCurrentPermissions: {
    type: Array,
    default: () => [],
  },
});

const page = usePage();
const permissions = computed(() => page.props.permissions || []);

const form = useForm({
  name: props.role?.name || "",
  can_access_panel: props.role?.can_access_panel || false,
  permissions: props.getCurrentPermissions || [],
});

const togglePermission = (permissionName) => {
  if (form.permissions.includes(permissionName)) {
    form.permissions = form.permissions.filter((p) => p !== permissionName);
  } else {
    form.permissions = [...form.permissions, permissionName];
  }
};

const toggleFeaturePermissions = (featureName, featurePermissions) => {
  const featurePermissionNames = featurePermissions.map((p) => p.name);
  const allSelected = featurePermissionNames.every((p) =>
    form.permissions.includes(p),
  );

  if (allSelected) {
    form.permissions = form.permissions.filter(
      (p) => !featurePermissionNames.includes(p),
    );
  } else {
    form.permissions = [
      ...new Set([...form.permissions, ...featurePermissionNames]),
    ];
  }
};

const submit = () => {
  if (props.role) {
    form.put(`/roles/${props.role.id}`);
  } else {
    form.post("/roles");
  }
};
</script>

<template>
  <Head>
    <title>{{ __(props.role ? "role.edit_role" : "role.create_role") }}</title>
  </Head>

  <AdminLayout>
    <template #header>
      <h2
        class="font-semibold text-[12px] md:text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem] hidden md:block"
      >
        {{ __("sidebar.role", "Role") }}
      </h2>
    </template>
    <PageHeader
      :title="
        props.role
          ? __('role.edit_role', 'Edit Role')
          : __('role.create_role', 'Create Role')
      "
    />

    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
      <form @submit.prevent="submit" class="space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
          <div>
            <InputLabel for="name" :value="__('role.role_name', 'Role Name')" />
            <TextInput
              id="name"
              type="text"
              class="mt-1 block w-full"
              v-model="form.name"
              required
            />
            <InputError :message="form.errors.name" />
          </div>

          <div class="flex items-center pb-2">
            <label class="inline-flex items-center">
              <input
                type="checkbox"
                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                v-model="form.can_access_panel"
              />
              <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">
                {{ __("role.can_access_panel", "Can Access Admin Panel") }}
              </span>
            </label>
          </div>
        </div>

        <div class="space-y-4">
          <h3
            class="text-lg font-medium text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-700 pb-2"
          >
            {{ __("role.permissions", "Permissions") }}
          </h3>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
              v-for="[feature, featurePermissions] in Object.entries(
                getAllPermissions,
              )"
              :key="feature"
              class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg border border-gray-200 dark:border-gray-700"
            >
              <div class="flex justify-between items-center mb-3">
                <h4
                  class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider"
                >
                  {{ feature }}
                </h4>
                <button
                  type="button"
                  @click="
                    toggleFeaturePermissions(feature, featurePermissions)
                  "
                  class="text-xs text-indigo-600 hover:text-indigo-800 font-medium"
                >
                  {{ __("messages.toggle_all", "Toggle All") }}
                </button>
              </div>
              <div class="space-y-2">
                <label
                  v-for="permission in featurePermissions"
                  :key="permission.name"
                  class="flex items-center text-sm"
                >
                  <input
                    type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                    :checked="form.permissions.includes(permission.name)"
                    @change="togglePermission(permission.name)"
                  />
                  <span class="ms-2 text-gray-600 dark:text-gray-400">
                    {{ permission.name }}
                  </span>
                </label>
              </div>
            </div>
          </div>
          <InputError :message="form.errors.permissions" />
        </div>

        <div
          class="flex items-center justify-end gap-4 border-t border-gray-100 dark:border-gray-700 pt-6"
        >
          <SecondaryButton @click="router.get('/roles')">
            {{ __("messages.cancel", "Cancel") }}
          </SecondaryButton>
          <template
            v-if="
              (props.role && permissions.includes('edit roles')) ||
              (!props.role && permissions.includes('create roles'))
            "
          >
            <PrimaryButton type="submit" :disabled="form.processing">
              {{
                props.role
                  ? __("messages.update", "Update Role")
                  : __("messages.create", "Create Role")
              }}
            </PrimaryButton>
          </template>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>
