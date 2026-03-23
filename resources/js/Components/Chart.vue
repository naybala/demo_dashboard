<script setup>
import { onMounted, onUnmounted, ref, watch } from "vue";

const props = defineProps({
  options: {
    type: Object,
    default: () => ({}),
  },
  type: {
    type: String,
    default: "line",
  },
  height: {
    type: Number,
    default: 350,
  },
  series: {
    type: Array,
    default: () => [],
  },
  labels: {
    type: Array,
    default: () => [],
  },
  title: {
    type: String,
    default: "",
  },
});

const chartNode = ref(null);
let chart = null;

const getChartOptions = () => ({
  chart: {
    type: props.type,
    height: props.height,
    toolbar: {
      show: false,
    },
  },
  series: props.series,
  labels: props.labels,
  title: {
    text: props.title,
    align: "left",
  },
  ...props.options,
});

onMounted(async () => {
  const { default: ApexCharts } = await import("apexcharts");
  chart = new ApexCharts(chartNode.value, getChartOptions());
  chart.render();
});

onUnmounted(() => {
  if (chart) {
    chart.destroy();
  }
});

watch(
  () => [
    props.options,
    props.type,
    props.height,
    props.series,
    props.labels,
    props.title,
  ],
  () => {
    if (chart) {
      chart.updateOptions(getChartOptions());
    }
  },
  { deep: true },
);
</script>

<template>
  <div
    class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm"
  >
    <div ref="chartNode"></div>
  </div>
</template>
