<template>
  <div class="chart-container">
    <canvas id="mentalHealthChart"></canvas>
  </div>
</template>

<script>
import {
  Chart,
  RadarController,
  RadialLinearScale,
  PointElement,
  LineElement,
  Filler,
  Tooltip,
  Legend,
} from "chart.js";

// Register components for Radar Chart
Chart.register(RadarController, RadialLinearScale, PointElement, LineElement, Filler, Tooltip, Legend);

export default {
  name: "MentalHealthChart",
  mounted() {
    const ctx = document.getElementById("mentalHealthChart").getContext("2d");
    new Chart(ctx, {
      type: "radar",
      data: {
        labels: ["Anxiety", "Depression", "PTSD", "Bipolar", "OCD"],
        datasets: [
          {
            label: "Cases",
            data: [65, 59, 90, 81, 56],
            backgroundColor: "rgba(153, 102, 255, 0.2)",
            borderColor: "rgba(153, 102, 255, 1)",
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
