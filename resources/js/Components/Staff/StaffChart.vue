<script setup>
import { onMounted, ref } from 'vue';
import { Chart, registerables } from 'chart.js';

// Register all Chart.js components so they can be used
Chart.register(...registerables);

const props = defineProps({
  onLeaveData: {
    type: Array,
    default: () => [],
  },
});

// We’ll override the props with test data if it’s empty or just to demonstrate
// so you see a chart immediately
const testData = [
  { month: 'Jan', count: 3 },
  { month: 'Feb', count: 5 },
  { month: 'Mar', count: 2 },
  { month: 'Apr', count: 6 },
  { month: 'May', count: 10 },
  { month: 'Jun', count: 7 },
];

const chartCanvas = ref(null);
let staffChartInstance = null;

onMounted(() => {
  initStaffChart();
});

function initStaffChart() {
  if (!chartCanvas.value) return;

  // If there’s an existing chart instance, destroy it before re-init
  if (staffChartInstance) {
    staffChartInstance.destroy();
  }

  const ctx = chartCanvas.value.getContext('2d');

  // Build dataset using either prop data or fallback to testData
  const chartData = props.onLeaveData.length ? props.onLeaveData : testData;

  staffChartInstance = new Chart(ctx, {
    type: 'bar', // You can change to 'line' if you prefer
    data: {
      labels: chartData.map((item) => item.month),
      datasets: [
        {
          label: 'Staff On Leave',
          data: chartData.map((item) => item.count),
          backgroundColor: 'rgba(75, 192, 192, 0.4)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 2,
          borderRadius: 4, // bar border radius
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          ticks: {
            precision: 0,
          },
          beginAtZero: true,
        },
      },
    },
  });
}
</script>

<template>
  <div>
    <h2 class="text-lg font-semibold mb-4">Staff Over Time (Example Data)</h2>
    <!-- Set a fixed height or use a CSS class so you can see the chart properly -->
    <div class="h-64">
      <canvas ref="chartCanvas"></canvas>
    </div>
  </div>
</template>

<style scoped>
/* Adjust chart container styling as needed */
</style>
