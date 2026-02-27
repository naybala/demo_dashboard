<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import PageHeader from "@/Components/PageHeader.svelte";
  import { Link } from "@inertiajs/svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import PrimaryButton from "@/Components/PrimaryButton.svelte";

  export let user = {};
</script>

<AdminLayout>
  <PageHeader title={`User: ${user.name}`}>
    <Link href="/users">
      <SecondaryButton>Back to List</SecondaryButton>
    </Link>
  </PageHeader>

  <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 max-w-2xl">
    <div class="flex items-center gap-6 mb-8">
      <div
        class="w-24 h-24 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-3xl font-bold"
      >
        {user.name.charAt(0).toUpperCase()}
      </div>
      <div>
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
          {user.name}
        </h3>
        <p class="text-gray-500 dark:text-gray-400">@{user.username}</p>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <div class="space-y-4">
        <div>
          <h4
            class="text-sm font-medium text-gray-400 uppercase tracking-wider"
          >
            Role
          </h4>
          <p class="mt-1 text-lg text-gray-900 dark:text-white font-medium">
            {user.role}
          </p>
        </div>
        <div>
          <h4
            class="text-sm font-medium text-gray-400 uppercase tracking-wider"
          >
            Status
          </h4>
          <span
            class={`mt-1 inline-flex px-2.5 py-0.5 rounded-full text-sm font-semibold ${user.active ? "bg-green-100 text-green-800" : "bg-red-100 text-red-800"}`}
          >
            {user.active ? "Active" : "Inactive"}
          </span>
        </div>
      </div>

      <div class="space-y-4">
        <div>
          <h4
            class="text-sm font-medium text-gray-400 uppercase tracking-wider"
          >
            Created At
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
              Last Login
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
        <PrimaryButton>Edit User</PrimaryButton>
      </Link>
    </div>
  </div>
</AdminLayout>
