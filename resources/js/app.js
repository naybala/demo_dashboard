import Dropzone from "dropzone";
import "./bootstrap";
import "flowbite";
import jQuery from "jquery";
// import 'preline/dist/preline.js';
import "preline";
import swal from "sweetalert2";
window.$ = jQuery;
// HSFileUpload.autoInit();
import Alpine from "alpinejs";

// If you want Alpine's instance to be available globally
window.Alpine = Alpine;

import ApexCharts from "apexcharts";
window.ApexCharts = ApexCharts;

window.Swal = swal;
window.Dropzone = Dropzone;

// Chart Component for Alpine
Alpine.data("chartComponent", (config) => ({
  chart: null,
  init() {
    const isDark = document.documentElement.classList.contains("dark");
    const options = {
      chart: {
        type: config.type || "line",
        height: config.height || 350,
        toolbar: {
          show: false,
        },
        fontFamily: "Inter, sans-serif",
        foreColor: isDark ? "#9ca3af" : "#64748b",
        background: "transparent",
      },
      theme: {
        mode: isDark ? "dark" : "light",
      },
      series: config.series || [],
      labels: config.labels || [],
      colors: config.colors || [
        "#3b82f6",
        "#10b981",
        "#f59e0b",
        "#ef4444",
        "#8b5cf6",
      ],
      stroke: {
        curve: "smooth",
        width: config.type === "line" ? 3 : 1,
      },
      grid: {
        borderColor: isDark ? "#374151" : "#f1f5f9",
        strokeDashArray: 4,
      },
      ...config.options,
    };

    this.chart = new ApexCharts(this.$refs.chart, options);
    this.chart.render();
  },
}));

Alpine.start();
