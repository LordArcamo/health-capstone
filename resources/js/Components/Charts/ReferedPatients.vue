<template>
  <div class="chart-container">
    <canvas id="referredPatientsChart"></canvas>
  </div>
</template>

<script>
import { Chart, PieController, ArcElement, Tooltip, Legend } from "chart.js";

// Register components for Pie Chart
Chart.register(PieController, ArcElement, Tooltip, Legend);

export default {
  name: "ReferredPatientsChart",
  props: {
    pieChart: {
      type: Object,
      required: true,
    },
  },
  mounted() {
    const ctx = document.getElementById("referredPatientsChart").getContext("2d");
    new Chart(ctx, {
      type: "pie",
      data: {
        labels: ["Referred", "Not Referred"],
        datasets: [
          {
            data: [
              this.pieChart.referred || 0,
              this.pieChart.notReferred || 0,
            ],
            backgroundColor: ["#FF6384", "#36A2EB"],
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          tooltip: {
            callbacks: {
              label: function (context) {
                const label = context.label || "";
                const value = context.raw || 0;
                return `${label}: ${value}`;
              },
            },
          },
        },
      },
    });
  },
};
</script>

<style scoped>
.chart-container {
  height: 300px;
  position: relative;
}
</style>
