<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import PageHeader from "@/Components/PageHeader.svelte";
  import { Link } from "@inertiajs/svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import PrimaryButton from "@/Components/PrimaryButton.svelte";

  export let role = {};
  export let getAllPermissions = {};
  export let getCurrentPermissions = [];
</script>

<AdminLayout>
  <PageHeader title={`Role: ${role.name}`}>
    <Link href="/roles">
      <SecondaryButton>Back to List</SecondaryButton>
    </Link>
  </PageHeader>

  <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
    <div class="flex justify-between items-start mb-8">
      <div>
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
          {role.name}
        </h3>
        <p class="mt-1 text-gray-500 dark:text-gray-400">
          Access Admin Panel:
          <span
            class={`font-semibold ${role.can_access_panel ? "text-green-600" : "text-gray-400"}`}
          >
            {role.allow_panel_status}
          </span>
        </p>
      </div>
      <Link href={`/roles/${role.id}/edit`}>
        <PrimaryButton>Edit Role</PrimaryButton>
      </Link>
    </div>

    <div class="space-y-6">
      <h4
        class="text-lg font-medium text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-700 pb-2"
      >
        Assigned Permissions
      </h4>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {#each Object.entries(getAllPermissions) as [feature, permissions]}
          {#if permissions.some((p) => getCurrentPermissions.includes(p.name))}
            <div
              class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg border border-gray-200 dark:border-gray-700"
            >
              <h5
                class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-3"
              >
                {feature}
              </h5>
              <ul class="space-y-1">
                {#each permissions as permission}
                  {#if getCurrentPermissions.includes(permission.name)}
                    <li
                      class="flex items-center text-sm text-gray-600 dark:text-gray-400"
                    >
                      <svg
                        class="w-4 h-4 text-green-500 me-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        ><path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M5 13l4 4L19 7"
                        /></svg
                      >
                      {permission.name}
                    </li>
                  {/if}
                {/each}
              </ul>
            </div>
          {/if}
        {/each}
      </div>
    </div>
  </div>
</AdminLayout>
