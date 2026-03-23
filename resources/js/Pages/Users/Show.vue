<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import PageHeader from "@/Components/PageHeader.vue";
import { Link, Head } from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { __ } from "@/helpers.js";

const props = defineProps({
  user: {
    type: Object,
    default: () => ({}),
  },
});
</script>

<template>
  <Head>
    <title>{{ __("user.user", "User") }}: {{ user.fullname }}</title>
  </Head>

  <AdminLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem]"
      >
        {{ __("sidebar.user", "User") }}
      </h2>
    </template>
    
    <PageHeader :title="`${__('user.user', 'User')}: ${user.fullname}`">
      <Link href="/users">
        <SecondaryButton>
          {{ __("user.back_to_list", "Back to List") }}
        </SecondaryButton>
      </Link>
    </PageHeader>

    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 max-w-2xl">
      <div class="flex items-center gap-6 mb-8">
        <div
          class="w-24 h-24 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-3xl font-bold overflow-hidden border-4 border-white shadow-sm"
        >
          <img
            v-if="user.avatar"
            :src="user.avatar"
            :alt="user.fullname"
            class="w-full h-full object-cover"
          />
          <template v-else>
            {{ user.fullname.charAt(0).toUpperCase() }}
          </template>
        </div>
        <div>
          <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ user.fullname }}
          </h3>
          <p class="text-gray-500 dark:text-gray-400">{{ user.email }}</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="space-y-4">
          <div>
            <h4
              class="text-sm font-medium text-gray-400 uppercase tracking-wider"
            >
              {{ __("user.role", "Role") }}
            </h4>
            <p class="mt-1 text-lg text-gray-900 dark:text-white font-medium">
              {{ user.role_name }}
            </p>
          </div>
          <div>
            <h4
              class="text-sm font-medium text-gray-400 uppercase tracking-wider"
            >
              {{ __("user.status", "Status") }}
            </h4>
            <span
              class="mt-1 inline-flex px-2.5 py-0.5 rounded-full text-sm font-semibold"
              :class="user.status === 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
            >
              {{ user.status_text }}
            </span>
          </div>
        </div>

        <div class="space-y-4">
          <div>
            <h4
              class="text-sm font-medium text-gray-400 uppercase tracking-wider"
            >
              {{ __("user.created_at", "Created At") }}
            </h4>
            <p class="mt-1 text-lg text-gray-900 dark:text-white font-medium">
              {{ user.created_at }}
            </p>
          </div>
          <div v-if="user.last_login">
            <h4
              class="text-sm font-medium text-gray-400 uppercase tracking-wider"
            >
              {{ __("user.last_login", "Last Login") }}
            </h4>
            <p class="mt-1 text-lg text-gray-900 dark:text-white font-medium">
              {{ user.last_login }}
            </p>
          </div>
        </div>
      </div>

      <div
        class="mt-8 pt-8 border-t border-gray-100 dark:border-gray-700 flex gap-4"
      >
        <Link :href="`/users/${user.id}/edit`">
          <PrimaryButton>{{ __("user.edit_user", "Edit User") }}</PrimaryButton>
        </Link>
      </div>
    </div>
  </AdminLayout>
</template>
