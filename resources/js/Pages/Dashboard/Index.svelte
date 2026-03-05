<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import Chart from "@/Components/Chart.svelte";
  import { router } from "@inertiajs/svelte";
  import { __, formatNumber } from "@/helpers.js";
  import StatCard from "./Parts/StatCard.svelte";
  import DateFilter from "./Parts/DateFilter.svelte";
  import YearFilter from "./Parts/YearFilter.svelte";
  import SingleDateFilter from "./Parts/SingleDateFilter.svelte";

  export let stats = {};
  export let monthly_revenue = { labels: [], series: [] };
  export let daily_revenue = { labels: [], series: [] };
  export let filters = {};

  $: instantChartOptions = {
    stroke: { curve: "smooth", width: 2 },
    colors: ["#6366f1", "#10b981"],
    fill: {
      type: "gradient",
      gradient: {
        shadeIntensity: 0.5,
        opacityFrom: 1,
        opacityTo: 0.7,
        stops: [50, 100, 100],
      },
    },
    dataLabels: { enabled: false },
    xaxis: {
      labels: {
        show: true,
        style: { fontSize: "12px", colors: "#94a3b8" },
      },
      axisBorder: { show: false },
      axisTicks: { show: false },
    },
    yaxis: {
      labels: {
        formatter: (val) => formatNumber(val, 0),
        style: { colors: "#94a3b8" },
      },
    },
    markers: {
      size: 4,
    },
    grid: {
      borderColor: "#f1f1f1",
      strokeDashArray: 4,
      padding: { left: 10, right: 10 },
    },
    legend: {
      position: "top",
      horizontalAlign: "right",
    },
  };

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

  $: dailyRevenueChartOptions = {
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
      categories: daily_revenue.labels,
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

  $: pieChartOptions = {
    dataLabels: {
      formatter: (val, opts) =>
        formatNumber(opts.w.globals.series[opts.seriesIndex], 0),
    },
    tooltip: {
      y: {
        formatter: (val) => formatNumber(val, 0),
      },
    },
  };

  $: donutChartOptions = {
    dataLabels: {
      formatter: (val, opts) =>
        formatNumber(opts.w.globals.series[opts.seriesIndex], 0),
    },
    tooltip: {
      y: {
        formatter: (val) => formatNumber(val, 0),
      },
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
      <StatCard
        label={__("dashboard.total_price", "Total Price")}
        value={stats.total_price}
      />

      <StatCard
        label={__("dashboard.isInstant", "Instant")}
        value={stats.is_instant}
      />
      <StatCard
        label={__("dashboard.non_instant", "Non-Instant")}
        value={stats.total_price - stats.is_instant}
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
        title={__("dashboard.sales_by_category", "Sales By Category")}
        type="pie"
        series={stats.sales_distribution?.series || []}
        labels={stats.sales_distribution?.labels || []}
        height={320}
        options={pieChartOptions}
      />
      <Chart
        title={__("dashboard.sale_by_product", "Sale By Product")}
        type="donut"
        series={stats.product_sales_distribution?.series || []}
        labels={stats.product_sales_distribution?.labels || []}
        height={320}
        options={donutChartOptions}
      />
      <!-- Sale By Product -->
    </div>

    <!-- Instant And Non Instant -->
    <div class="grid grid-cols-1 md:grid-cols-1 gap-6 shadow-xl rounded-lg">
      <Chart
        title={__(
          "dashboard.instant_vs_non_instant",
          "Instant vs Non-Instant Sales",
        )}
        type="area"
        series={stats.instant_sales_distribution?.series || []}
        labels={stats.instant_sales_distribution?.labels || []}
        options={instantChartOptions}
        height={350}
      />
    </div>
    <hr class="" />
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
    <hr class="" />
    <div>
      <SingleDateFilter
        date={filters.date || ""}
        on:filter={handleDateFilter}
        on:reset={handleReset}
      />
      <Chart
        title={__("dashboard.daily_revenue", "Daily Revenue")}
        type="bar"
        series={daily_revenue.series}
        options={dailyRevenueChartOptions}
        height={350}
      />
    </div>
  </main>
</AdminLayout>
