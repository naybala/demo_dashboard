<script>
  import { onMount, onDestroy } from "svelte";
  import ApexCharts from "apexcharts";

  export let options = {};
  export let type = "line";
  export let height = 350;
  export let series = [];
  export let labels = [];
  export let title = "";

  let chartNode;
  let chart;

  $: chartOptions = {
    chart: {
      type: type,
      height: height,
      toolbar: {
        show: false,
      },
    },
    series: series,
    labels: labels,
    title: {
      text: title,
      align: "left",
    },
    ...options,
  };

  $: if (chart && chartOptions) {
    chart.updateOptions(chartOptions);
  }

  onMount(() => {
    chart = new ApexCharts(chartNode, chartOptions);
    chart.render();
  });

  onDestroy(() => {
    if (chart) {
      chart.destroy();
    }
  });
</script>

<div
  class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm"
>
  <div bind:this={chartNode}></div>
</div>
