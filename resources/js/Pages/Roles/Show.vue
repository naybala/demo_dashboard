<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import PageHeader from "@/Components/PageHeader.vue";
import { Link, Head } from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { __ } from "@/helpers.js";

defineProps({
  role: {
    type: Object,
    default: () => ({}),
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
</script>

<template>
  <Head>
    <title>{{ __("role.role", "Role") }}: {{ role.name }}</title>
  </Head>

  <AdminLayout>
    <template #header>
      <PageHeader :title="`${__('role.role', 'Role')}: ${role.name}`">
        <Link href="/roles">
          <SecondaryButton>
            {{ __("role.back_to_list", "Back to List") }}
          </SecondaryButton>
        </Link>
      </PageHeader>
    </template>

    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
      <div class="flex justify-between items-start mb-8">
        <div>
          <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ role.name }}
          </h3>
          <p class="mt-1 text-gray-500 dark:text-gray-400">
            {{ __("role.can_access_panel", "Access Admin Panel") }}:
            <span
              class="font-semibold"
              :class="role.can_access_panel ? 'text-green-600' : 'text-gray-400'"
            >
              {{ role.allow_panel_status }}
            </span>
          </p>
        </div>
        <Link :href="`/roles/${role.id}/edit`">
          <PrimaryButton>{{ __("role.edit_role", "Edit Role") }}</PrimaryButton>
        </Link>
      </div>

      <div class="space-y-6">
        <h4
          class="text-lg font-medium text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-700 pb-2"
        >
          {{ __("role.assigned_permissions", "Assigned Permissions") }}
        </h4>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <template
            v-for="[feature, featurePermissions] in Object.entries(
              getAllPermissions,
            )"
            :key="feature"
          >
            <div
              v-if="
                featurePermissions.some((p) =>
                  getCurrentPermissions.includes(p.name),
                )
              "
              class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg border border-gray-200 dark:border-gray-700"
            >
              <h5
                class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-3"
              >
                {{ feature }}
              </h5>
              <ul class="space-y-1">
                <template
                  v-for="permission in featurePermissions"
                  :key="permission.name"
                >
                  <li
                    v-if="getCurrentPermissions.includes(permission.name)"
                    class="flex items-center text-sm text-gray-600 dark:text-gray-400"
                  >
                    <svg
                      class="w-4 h-4 text-green-500 me-2"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 13l4 4L19 7"
                      />
                    </svg>
                    {{ permission.name }}
                  </li>
                </template>
              </ul>
            </div>
          </template>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
