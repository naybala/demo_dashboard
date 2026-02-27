<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import PageHeader from "@/Components/PageHeader.svelte";
  import BaseTable from "@/Components/BaseTable.svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import TextInput from "@/Components/TextInput.svelte";
  import { router, Link } from "@inertiajs/svelte";

  export let data = [];
  export let meta = {};

  let search = "";

  const headers = [
    { key: "user", label: "User" },
    { key: "event", label: "Event" },
    { key: "auditable_type", label: "Model" },
    { key: "ip_address", label: "IP Address" },
    { key: "created_at", label: "Date" },
    { key: "actions", label: "Actions" },
  ];

  const handleSearch = () => {
    router.get(
      "/audits",
      { keyword: search },
      { preserveState: true, replace: true },
    );
  };

  const getEventColor = (event) => {
    switch (event) {
      case "created":
        return "bg-green-100 text-green-800";
      case "updated":
        return "bg-blue-100 text-blue-800";
      case "deleted":
        return "bg-red-100 text-red-800";
      default:
        return "bg-gray-100 text-gray-800";
    }
  };
</script>

<AdminLayout>
  <PageHeader title="Activity Logs" />

  <div class="mb-6 flex justify-between items-center">
    <div class="w-1/3">
      <TextInput
        type="text"
        placeholder="Search logs..."
        bind:value={search}
        on:input={handleSearch}
        class="w-full"
      />
    </div>
  </div>

  <BaseTable {headers}>
    {#each data as audit}
      <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
        <td
          class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"
        >
          {audit.user_name}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm">
          <span
            class={`px-2 py-1 rounded-full text-xs font-semibold ${getEventColor(audit.event)}`}
          >
            {audit.event}
          </span>
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          {audit.auditable_type?.split("\\").pop() || "---"}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          {audit.ip_address}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          {audit.created_at}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
          <Link href={`/audits/${audit.id}`}>
            <SecondaryButton>View Details</SecondaryButton>
          </Link>
        </td>
      </tr>
    {/each}
  </BaseTable>

  {#if meta && meta.links}
    <div class="mt-6 flex items-center justify-between">
      <div class="text-sm text-gray-700 dark:text-gray-400">
        Showing {meta.from} to {meta.to} of {meta.total} results
      </div>
      <div class="flex gap-1">
        {#each meta.links as link}
          <button
            class="px-3 py-1 rounded border {link.active
              ? 'bg-indigo-600 text-white border-indigo-600'
              : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600'}"
            on:click={() => link.url && router.visit(link.url)}
            disabled={!link.url}
          >
            {@html link.label}
          </button>
        {/each}
      </div>
    </div>
  {/if}
</AdminLayout>
