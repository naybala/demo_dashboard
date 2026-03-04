<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import PageHeader from "@/Components/PageHeader.svelte";
  import { Link } from "@inertiajs/svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import PrimaryButton from "@/Components/PrimaryButton.svelte";
  import { __ } from "@/helpers.js";

  export let user = {};
</script>

<AdminLayout>
  <PageHeader title={`${__("user.user", "User")}: ${user.fullname}`}>
    <Link href="/users">
      <SecondaryButton
        >{__("user.back_to_list", "Back to List")}</SecondaryButton
      >
    </Link>
  </PageHeader>

  <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 max-w-2xl">
    <div class="flex items-center gap-6 mb-8">
      <div
        class="w-24 h-24 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-3xl font-bold overflow-hidden border-4 border-white shadow-sm"
      >
        {#if user.avatar}
          <img
            src={user.avatar}
            alt={user.fullname}
            class="w-full h-full object-cover"
          />
        {:else}
          {user.fullname.charAt(0).toUpperCase()}
        {/if}
      </div>
      <div>
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
          {user.fullname}
        </h3>
        <p class="text-gray-500 dark:text-gray-400">{user.email}</p>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <div class="space-y-4">
        <div>
          <h4
            class="text-sm font-medium text-gray-400 uppercase tracking-wider"
          >
            {__("user.role", "Role")}
          </h4>
          <p class="mt-1 text-lg text-gray-900 dark:text-white font-medium">
            {user.role_name}
          </p>
        </div>
        <div>
          <h4
            class="text-sm font-medium text-gray-400 uppercase tracking-wider"
          >
            {__("user.status", "Status")}
          </h4>
          <span
            class={`mt-1 inline-flex px-2.5 py-0.5 rounded-full text-sm font-semibold ${user.status === 1 ? "bg-green-100 text-green-800" : "bg-red-100 text-red-800"}`}
          >
            {user.status_text}
          </span>
        </div>
      </div>

      <div class="space-y-4">
        <div>
          <h4
            class="text-sm font-medium text-gray-400 uppercase tracking-wider"
          >
            {__("user.created_at", "Created At")}
          </h4>
          <p class="mt-1 text-lg text-gray-900 dark:text-white font-medium">
            {user.created_at}
          </p>
        </div>
        {#if user.last_login}
          <div>
            <h4
              class="text-sm font-medium text-gray-400 uppercase tracking-wider"
            >
              {__("user.last_login", "Last Login")}
            </h4>
            <p class="mt-1 text-lg text-gray-900 dark:text-white font-medium">
              {user.last_login}
            </p>
          </div>
        {/if}
      </div>
    </div>

    <div
      class="mt-8 pt-8 border-t border-gray-100 dark:border-gray-700 flex gap-4"
    >
      <Link href={`/users/${user.id}/edit`}>
        <PrimaryButton>{__("user.edit_user", "Edit User")}</PrimaryButton>
      </Link>
    </div>
  </div>
</AdminLayout>
