<script setup>
import { onMounted, ref, watchEffect } from 'vue';
import { Chart, registerables } from 'chart.js';

// Register Chart.js components
Chart.register(...registerables);

// Define props
const props = defineProps({
  distributionData: {
    type: Array,
    default: () => [],
  },
});

// Canvas reference
const donutCanvas = ref(null);
let donutChartInstance = null;

// Function to initialize the chart
function initDonutChart() {
  if (!donutCanvas.value || props.distributionData.length === 0) return;

  if (donutChartInstance) {
    donutChartInstance.destroy();
  }

  const ctx = donutCanvas.value.getContext('2d');

  const labels = props.distributionData.map((item) => item.role || "Unknown");
  const dataValues = props.distributionData.map((item) => item.count || 0);

  console.log("Chart Labels:", labels);
  console.log("Chart Data:", dataValues);

  donutChartInstance = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: labels, // Ensure department names exist
      datasets: [
        {
          data: dataValues, // Ensure count values exist
          backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
          hoverOffset: 8,
          borderColor: '#fff',
          borderWidth: 2,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
        },
      },
    },
  });
}


// Watch for prop updates and refresh the chart
watchEffect(() => {
  console.log("Distribution Data:", props.distributionData);
  if (props.distributionData.length) {
    initDonutChart();
  }
});


// Initialize the chart on mount
onMounted(() => {
  initDonutChart();
});
</script>

<template>
  <div>
    <h2 class="text-lg font-semibold mb-4">Distribution of Users by Roles</h2>
    <div class="h-64">
      <canvas ref="donutCanvas"></canvas>
    </div>
  </div>
</template>

<style scoped>
</style>
