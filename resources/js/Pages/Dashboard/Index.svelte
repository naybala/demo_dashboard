<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import PageHeader from "@/Components/PageHeader.svelte";
  import Chart from "@/Components/Chart.svelte";
  import { router } from "@inertiajs/svelte";
  import { __, formatNumber } from "@/helpers.js";

  export let stats = {};
  export let monthly_revenue = { labels: [], series: [] };
  export let filters = {};

  $: revenueChartOptions = {
    stroke: {
      curve: "smooth",
      width: 3,
    },
    colors: ["#4f46e5"],
    fill: {
      type: "gradient",
      gradient: {
        shadeIntensity: 1,
        opacityFrom: 0.45,
        opacityTo: 0.05,
        stops: [50, 100, 100],
      },
    },
    xaxis: {
      categories: monthly_revenue.labels,
      axisBorder: { show: false },
      axisTicks: { show: false },
    },
    yaxis: {
      labels: {
        formatter: (val) => formatNumber(val, 0),
      },
    },
    tooltip: {
      y: {
        formatter: (val) => formatNumber(val, 0),
      },
    },
    grid: {
      borderColor: "#f1f1f1",
      strokeDashArray: 4,
    },
  };

  let startDate = filters.start_date || "";
  let endDate = filters.end_date || "";
  let selectedYear = filters.year || "";

  const handleFilter = () => {
    router.get(
      "/dashboard",
      {
        start_date: startDate,
        end_date: endDate,
        year: selectedYear,
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
    selectedYear = "";
    router.get("/dashboard");
  };
</script>

<svelte:head>
  <title>{__("sidebar.dashboard")}</title>
</svelte:head>

<AdminLayout>
  <svelte:fragment slot="header">
    <h2
      class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem]"
    >
      {__("sidebar.dashboard", "Dashboard")}
    </h2>
  </svelte:fragment>

  <main class="space-y-6">
    <!-- Date Filter -->
    <div
      class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm"
    >
      <form
        on:submit|preventDefault={handleFilter}
        class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end"
      >
        <div>
          <label
            for="start_date"
            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
            >{__("messages.start_date", "Start Date")}</label
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
            >{__("messages.end_date", "End Date")}</label
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
            >{__("messages.filter", "Filter")}</button
          >
          <button
            type="button"
            on:click={handleReset}
            class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-lg hover:opacity-90 transition-opacity"
            >{__("messages.reset", "Reset")}</button
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
          {__("dashboard.total_own_product", "Own Product Types")}
        </p>
        <h4 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">
          {formatNumber(stats.total_products) || 0}
        </h4>
      </div>
      <div
        class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm"
      >
        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
          {__("dashboard.total_price", "Total Price")}
        </p>
        <h4 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">
          {formatNumber(stats.total_price) || 0}
        </h4>
      </div>
      <div
        class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm"
      >
        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
          {__("dashboard.total_investment", "Total Investment")}
        </p>
        <h4 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">
          {formatNumber(stats.total_investment) || 0}
        </h4>
      </div>
      <div
        class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm"
      >
        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
          {__("dashboard.total_profit", "Total Profit")}
        </p>
        <h4 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">
          {formatNumber(stats.total_profit) || 0}
        </h4>
      </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <Chart
        title={__(
          "dashboard.own_product_by_category",
          "Own Product By Category",
        )}
        type="donut"
        series={stats.product_distribution?.series || []}
        labels={stats.product_distribution?.labels || []}
        height={320}
      />
      <Chart
        title={__("dashboard.sales_by_category", "Sales By Category")}
        type="pie"
        series={stats.sales_distribution?.series || []}
        labels={stats.sales_distribution?.labels || []}
        height={320}
      />
    </div>

    <div class="w-4/12">
      <label
        for="year"
        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
        >{__("messages.year", "Year")}</label
      >
      <select
        id="year"
        bind:value={selectedYear}
        on:change={handleFilter}
        class="w-full py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
      >
        <option value=""
          >{__("messages.last_12_months", "Last 12 Months")}</option
        >
        {#each monthly_revenue.available_years as year}
          <option value={year}>{year}</option>
        {/each}
      </select>
    </div>

    <!-- Revenue Chart -->
    <div class="w-full">
      <Chart
        title={__("dashboard.monthly_revenue", "Monthly Revenue")}
        type="area"
        series={monthly_revenue.series}
        options={revenueChartOptions}
        height={350}
      />
    </div>
  </main>
</AdminLayout>
