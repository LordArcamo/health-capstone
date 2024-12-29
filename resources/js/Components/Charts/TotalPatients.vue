<template>
  <div class="chart-container">
    <canvas id="totalPatientsChart"></canvas>
  </div>
</template>

<script>
import {
  Chart,
  BarController,
  BarElement,
  CategoryScale,
  LinearScale,
  Title,
  Tooltip,
  Legend,
} from "chart.js";

// Register components for Bar Chart
Chart.register(BarController, BarElement, CategoryScale, LinearScale, Title, Tooltip, Legend);

export default {
  name: "TotalPatientsChart",
  mounted() {
    const ctx = document.getElementById("totalPatientsChart").getContext("2d");
    new Chart(ctx, {
      type: "bar",
      data: {
        labels: ["January", "February", "March", "April", "May", "June", "August", "September", "October", "November", "December"],
        datasets: [
          {
            label: "Total Patients",
            data: [50, 60, 70, 85, 90, 100],
            backgroundColor: "rgba(75, 192, 192, 0.6)",
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
