<template>
  <div class="chart-container">
    <canvas id="vaccinationsChart"></canvas>
  </div>
</template>

<script>
import {
  Chart,
  LineController,
  LineElement,
  PointElement,
  CategoryScale,
  LinearScale,
  Tooltip,
  Legend,
} from "chart.js";

// Register components for Line Chart
Chart.register(LineController, LineElement, PointElement, CategoryScale, LinearScale, Tooltip, Legend);

export default {
  name: "VaccinationsChart",
  mounted() {
    const ctx = document.getElementById("vaccinationsChart").getContext("2d");
    new Chart(ctx, {
      type: "line",
      data: {
        labels: ["January", "February", "March", "April", "May", "June"],
        datasets: [
          {
            label: "Vaccinations",
            data: [20, 40, 60, 80, 100, 120],
            borderColor: "rgba(255, 159, 64, 0.6)",
            fill: false,
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
