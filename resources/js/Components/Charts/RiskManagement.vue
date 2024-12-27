<template>
  <div class="chart-container">
    <canvas id="riskManagementChart"></canvas>
  </div>
</template>

<script>
import { Chart, DoughnutController, ArcElement, Tooltip, Legend } from "chart.js";

// Register components for Doughnut Chart
Chart.register(DoughnutController, ArcElement, Tooltip, Legend);

export default {
  name: "RiskManagementChart",
  mounted() {
    const ctx = document.getElementById("riskManagementChart").getContext("2d");
    new Chart(ctx, {
      type: "doughnut",
      data: {
        labels: ["Low Risk", "Medium Risk", "High Risk"],
        datasets: [
          {
            data: [50, 30, 20],
            backgroundColor: ["#4BC0C0", "#FFCE56", "#FF6384"],
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
