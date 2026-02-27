<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import PageHeader from "@/Components/PageHeader.svelte";
  import { Link } from "@inertiajs/svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";

  export let audit = {};

  const formatJson = (json) => {
    try {
      return JSON.stringify(JSON.parse(json), null, 2);
    } catch (e) {
      return json;
    }
  };
</script>

<AdminLayout>
  <PageHeader title="Audit Details">
    <Link href="/audits">
      <SecondaryButton>Back to Logs</SecondaryButton>
    </Link>
  </PageHeader>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-1 space-y-6">
      <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
        <h3
          class="text-lg font-bold mb-4 text-gray-900 dark:text-white border-b pb-2"
        >
          Information
        </h3>
        <div class="space-y-4 text-sm">
          <div>
            <span class="block text-gray-500">User</span>
            <span class="font-medium">{audit.user_name}</span>
          </div>
          <div>
            <span class="block text-gray-500">Event</span>
            <span
              class="px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800"
              >{audit.event}</span
            >
          </div>
          <div>
            <span class="block text-gray-500">Model</span>
            <span>{audit.auditable_type} (ID: {audit.auditable_id})</span>
          </div>
          <div>
            <span class="block text-gray-500">IP Address</span>
            <span>{audit.ip_address}</span>
          </div>
          <div>
            <span class="block text-gray-500">User Agent</span>
            <span class="text-xs break-words">{audit.user_agent}</span>
          </div>
          <div>
            <span class="block text-gray-500">Date</span>
            <span>{audit.created_at}</span>
          </div>
        </div>
      </div>
    </div>

    <div class="md:col-span-2 space-y-6">
      <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
        <h3
          class="text-lg font-bold mb-4 text-gray-900 dark:text-white border-b pb-2"
        >
          Changes
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h4 class="text-sm font-bold text-gray-400 uppercase mb-2">
              Old Values
            </h4>
            <pre
              class="p-4 bg-gray-50 dark:bg-gray-900 rounded-lg text-xs overflow-x-auto border border-gray-200 dark:border-gray-700">
                            {formatJson(audit.old_values)}
                        </pre>
          </div>
          <div>
            <h4 class="text-sm font-bold text-gray-400 uppercase mb-2">
              New Values
            </h4>
            <pre
              class="p-4 bg-gray-50 dark:bg-gray-900 rounded-lg text-xs overflow-x-auto border border-gray-200 dark:border-gray-700">
                            {formatJson(audit.new_values)}
                        </pre>
          </div>
        </div>
      </div>

      {#if audit.url}
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
          <h3
            class="text-lg font-bold mb-4 text-gray-900 dark:text-white border-b pb-2"
          >
            Context
          </h3>
          <div class="text-sm">
            <span class="block text-gray-500">URL</span>
            <span class="break-all">{audit.url}</span>
          </div>
        </div>
      {/if}
    </div>
  </div>
</AdminLayout>
