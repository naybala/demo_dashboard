<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Chart from "@/Components/Chart.vue";
import { router, Head } from "@inertiajs/vue3";
import { __, formatNumber } from "@/helpers.js";
import StatCard from "./Parts/StatCard.vue";
import DateFilter from "./Parts/DateFilter.vue";
import YearFilter from "./Parts/YearFilter.vue";
import { computed } from "vue";

const props = defineProps({
  stats: {
    type: Object,
    default: () => ({}),
  },
  monthly_revenue: {
    type: Object,
    default: () => ({ labels: [], series: [] }),
  },
  daily_revenue: {
    type: Object,
    default: () => ({ labels: [], series: [] }),
  },
  filters: {
    type: Object,
    default: () => ({}),
  },
});

const instantChartOptions = computed(() => ({
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
      formatter: (val) =>
        formatNumber(val, 0) + " " + __("dashboard.kyats", "Kyats"),
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
}));

const revenueChartOptions = computed(() => ({
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
    categories: props.monthly_revenue.labels,
    axisBorder: { show: false },
    axisTicks: { show: false },
  },
  yaxis: {
    labels: {
      formatter: (val) =>
        formatNumber(val, 0) + " " + __("dashboard.kyats", "Kyats"),
    },
  },
  tooltip: {
    y: {
      formatter: (val) =>
        formatNumber(val, 0) + " " + __("dashboard.kyats", "Kyats"),
    },
  },
  grid: {
    borderColor: "#f1f1f1",
    strokeDashArray: 4,
  },
}));

const dailyRevenueChartOptions = computed(() => ({
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
    categories: props.daily_revenue.labels,
    axisBorder: { show: false },
    axisTicks: { show: false },
  },
  yaxis: {
    labels: {
      formatter: (val) =>
        formatNumber(val, 0) + " " + __("dashboard.kyats", "Kyats"),
    },
  },
  tooltip: {
    y: {
      formatter: (val) =>
        formatNumber(val, 0) + " " + __("dashboard.kyats", "Kyats"),
    },
  },
  grid: {
    borderColor: "#f1f1f1",
    strokeDashArray: 4,
  },
}));

const pieChartOptions = computed(() => ({
  tooltip: {
    y: {
      formatter: (val) =>
        formatNumber(val, 0) + " " + __("dashboard.kyats", "Kyats"),
    },
  },
}));

const donutChartOptions = computed(() => ({
  tooltip: {
    y: {
      formatter: (val) =>
        formatNumber(val, 0) + " " + __("dashboard.kyats", "Kyats"),
    },
  },
}));

const handleDateFilter = (data) => {
  router.get(
    "/dashboard",
    {
      ...props.filters,
      ...data,
    },
    {
      preserveState: true,
      replace: true,
    },
  );
};

const handleYearChange = (year) => {
  router.get(
    "/dashboard",
    {
      ...props.filters,
      year: year,
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

<template>
  <Head>
    <title>{{ __("sidebar.dashboard") }}</title>
  </Head>

  <AdminLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem]"
      >
        {{ __("sidebar.dashboard", "Dashboard") }}
      </h2>
    </template>

    <main class="space-y-6">
      <DateFilter
        :start-date="props.filters.start_date || ''"
        :end-date="props.filters.end_date || ''"
        @filter="handleDateFilter"
        @reset="handleReset"
      />

      <!-- Stat Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <StatCard
          :label="__('dashboard.total_price', 'Total Price')"
          :value="props.stats.total_price"
        />
        <StatCard
          :label="__('dashboard.isInstant', 'Instant')"
          :value="props.stats.is_instant"
        />
        <StatCard
          :label="__('dashboard.non_instant', 'Non-Instant')"
          :value="props.stats.total_price - props.stats.is_instant"
        />
        <StatCard
          :label="__('dashboard.total_investment', 'Total Investment')"
          :value="props.stats.total_investment"
        />
        <StatCard
          :label="__('dashboard.total_profit', 'Total Profit')"
          :value="props.stats.total_profit"
        />
      </div>

      <!-- Charts -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <Chart
          :title="__('dashboard.sales_by_category', 'Sales By Category')"
          type="pie"
          :series="props.stats.sales_distribution?.series || []"
          :labels="props.stats.sales_distribution?.labels || []"
          :height="320"
          :options="pieChartOptions"
        />
        <Chart
          :title="__('dashboard.sale_by_product', 'Sale By Product')"
          type="donut"
          :series="props.stats.product_sales_distribution?.series || []"
          :labels="props.stats.product_sales_distribution?.labels || []"
          :height="320"
          :options="donutChartOptions"
        />
      </div>

      <!-- Instant And Non Instant -->
      <div class="grid grid-cols-1 md:grid-cols-1 gap-6 shadow-xl rounded-lg">
        <Chart
          :title="
            __('dashboard.instant_vs_non_instant', 'Instant vs Non-Instant Sales')
          "
          type="area"
          :series="props.stats.instant_sales_distribution?.series || []"
          :labels="props.stats.instant_sales_distribution?.labels || []"
          :options="instantChartOptions"
          :height="350"
        />
      </div>
      <hr />
      <!-- Revenue Chart -->
      <div class="w-full">
        <YearFilter
          :selected-year="props.filters.year || ''"
          :available-years="props.monthly_revenue.available_years || []"
          @change="handleYearChange"
        />
        <Chart
          :title="__('dashboard.monthly_revenue', 'Monthly Revenue')"
          type="area"
          :series="props.monthly_revenue.series"
          :options="revenueChartOptions"
          :height="350"
        />
      </div>
    </main>
  </AdminLayout>
</template>
