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
  mounted() {
    const ctx = document.getElementById("referredPatientsChart").getContext("2d");
    new Chart(ctx, {
      type: "pie",
      data: {
        labels: ["Referred", "Not Referred"],
        datasets: [
          {
            data: [40, 60],
            backgroundColor: ["#FF6384", "#36A2EB"],
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
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
