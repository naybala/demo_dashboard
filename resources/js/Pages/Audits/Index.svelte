<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import PageHeader from "@/Components/PageHeader.svelte";
  import BaseTable from "@/Components/BaseTable.svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import TextInput from "@/Components/TextInput.svelte";
  import { router, Link } from "@inertiajs/svelte";
  import Pagination from "@/Components/Pagination.svelte";
  import { __ } from "@/helpers.js";

  export let data = [];
  export let meta = {};

  let search = "";

  const headers = [
    { key: "user", label: __("audit.user", "User") },
    { key: "event", label: __("audit.event", "Event") },
    { key: "auditable_type", label: __("audit.model", "Model") },
    { key: "ip_address", label: __("audit.ip_address", "IP Address") },
    { key: "created_at", label: __("audit.created_at", "Date") },
    { key: "actions", label: __("table.action", "Actions") },
  ];

  const handleSearch = () => {
    router.get(
      "/audits",
      { keyword: search },
      { preserveState: true, replace: true },
    );
  };

  const handleReset = () => {
    search = "";
    router.get("/audits");
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

<svelte:head>
  <title>{__("sidebar.audit")}</title>
</svelte:head>

<AdminLayout>
  <svelte:fragment slot="header">
    <h2
      class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem]"
    >
      {__("sidebar.audit", "Activity Logs")}
    </h2>
  </svelte:fragment>

  <div class="mb-6 flex justify-between items-center">
    <div class="flex gap-2 w-1/2">
      <TextInput
        type="text"
        placeholder={__("messages.search_item", "Search logs...")}
        bind:value={search}
        on:keydown={(e) => e.key === "Enter" && handleSearch()}
        class="w-full"
      />
      <SecondaryButton on:click={handleSearch}>
        {__("messages.search", "Search")}
      </SecondaryButton>
      {#if search}
        <SecondaryButton
          class="bg-gray-100 dark:bg-gray-700"
          on:click={handleReset}
        >
          {__("messages.reset", "Clear")}
        </SecondaryButton>
      {/if}
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
        <td class="flex gap-2">
          <Link href={`/audits/${audit.id}`}>
            <SecondaryButton
              >{__("messages.view", "View Details")}</SecondaryButton
            >
          </Link>
        </td>
      </tr>
    {/each}
  </BaseTable>

  <Pagination {meta} />
</AdminLayout>
