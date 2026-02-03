import Chart from "chart.js/auto";

$(document).ready(function () {
  if (typeof window.pieChartData !== "undefined") {
    const used = window.pieChartData.used;
    const limit = window.pieChartData.limit;
    const remaining = Math.max(limit - used, 0);

    const ctx = document.getElementById("pie-chart");
    if (!ctx) return;

    ctx.innerHTML = "";

    const canvas = document.createElement("canvas");
    canvas.id = "groupUserPieChart";
    ctx.appendChild(canvas);

    new Chart(canvas.getContext("2d"), {
      type: "pie",
      data: {
        labels: ["Used", "Remaining"],
        datasets: [
          {
            label: "Group User Usage",
            data: [used, remaining],

            backgroundColor: ["#4f46e5", "#e5e7eb"],
            borderWidth: 1,
          },
        ],
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: "bottom",
            labels: {
              color: "#de1833",
            },
          },
          title: {
            display: true,
            text: "Group User Limit Usage",
            color: "#de1833",
          },
        },
      },
    });
  }
});
