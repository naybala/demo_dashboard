<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import Chart from "@/Components/Chart.svelte";
  import { router } from "@inertiajs/svelte";
  import { __, formatNumber } from "@/helpers.js";
  import StatCard from "./Parts/StatCard.svelte";
  import DateFilter from "./Parts/DateFilter.svelte";
  import YearFilter from "./Parts/YearFilter.svelte";

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

  const handleDateFilter = (e) => {
    router.get(
      "/dashboard",
      {
        ...filters,
        ...e.detail,
      },
      {
        preserveState: true,
        replace: true,
      },
    );
  };

  const handleYearChange = (e) => {
    router.get(
      "/dashboard",
      {
        ...filters,
        year: e.detail,
      },
      {
        preserveState: true,
        replace: true,
      },
    );
  };

  const handleReset = () => {
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
    <DateFilter
      startDate={filters.start_date || ""}
      endDate={filters.end_date || ""}
      on:filter={handleDateFilter}
      on:reset={handleReset}
    />

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <StatCard
        label={__("dashboard.total_own_product", "Own Product Types")}
        value={stats.total_products}
      />
      <StatCard
        label={__("dashboard.total_price", "Total Price")}
        value={stats.total_price}
      />
      <StatCard
        label={__("dashboard.total_investment", "Total Investment")}
        value={stats.total_investment}
      />
      <StatCard
        label={__("dashboard.total_profit", "Total Profit")}
        value={stats.total_profit}
      />
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

    <!-- Revenue Chart -->
    <div class="w-full">
      <YearFilter
        selectedYear={filters.year || ""}
        availableYears={monthly_revenue.available_years || []}
        on:change={handleYearChange}
      />
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
