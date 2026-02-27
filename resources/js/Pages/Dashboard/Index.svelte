<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import PageHeader from "@/Components/PageHeader.svelte";
  import Chart from "@/Components/Chart.svelte";
  import { router } from "@inertiajs/svelte";

  export let stats = {};
  export let filters = {};

  let startDate = filters.start_date || "";
  let endDate = filters.end_date || "";

  const handleFilter = () => {
    router.get(
      "/dashboard",
      {
        start_date: startDate,
        end_date: endDate,
      },
      {
        preserveState: true,
        replace: true,
      },
    );
  };

  const handleReset = () => {
    startDate = "";
    endDate = "";
    router.get("/dashboard");
  };
</script>

<AdminLayout>
  <PageHeader title="Dashboard" />

  <main class="space-y-6">
    <!-- Date Filter -->
    <div
      class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm"
    >
      <form
        on:submit|preventDefault={handleFilter}
        class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end"
      >
        <div>
          <label
            for="start_date"
            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
            >Start Date</label
          >
          <input
            type="date"
            id="start_date"
            bind:value={startDate}
            class="w-full py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
          />
        </div>
        <div>
          <label
            for="end_date"
            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
            >End Date</label
          >
          <input
            type="date"
            id="end_date"
            bind:value={endDate}
            class="w-full py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
          />
        </div>
        <div class="flex gap-2">
          <button
            type="submit"
            class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors"
            >Filter</button
          >
          <button
            type="button"
            on:click={handleReset}
            class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-lg hover:opacity-90 transition-opacity"
            >Reset</button
          >
        </div>
      </form>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div
        class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm"
      >
        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
          Own Product Types
        </p>
        <h4 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">
          {stats.total_products?.toLocaleString() || 0}
        </h4>
      </div>
      <div
        class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm"
      >
        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
          Total Price
        </p>
        <h4 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">
          {stats.total_price?.toLocaleString() || 0}
        </h4>
      </div>
      <div
        class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm"
      >
        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
          Total Investment
        </p>
        <h4 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">
          {stats.total_investment?.toLocaleString() || 0}
        </h4>
      </div>
      <div
        class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm"
      >
        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
          Total Profit
        </p>
        <h4 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">
          {stats.total_profit?.toLocaleString() || 0}
        </h4>
      </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <Chart
        title="Own Product By Category"
        type="donut"
        series={stats.product_distribution?.series || []}
        labels={stats.product_distribution?.labels || []}
        height={320}
      />
      <Chart
        title="Sales By Category"
        type="pie"
        series={stats.sales_distribution?.series || []}
        labels={stats.sales_distribution?.labels || []}
        height={320}
      />
    </div>
  </main>
</AdminLayout>
