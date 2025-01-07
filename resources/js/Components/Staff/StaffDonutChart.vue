<script setup>
import { onMounted, ref } from 'vue';
import { Chart, registerables } from 'chart.js';

// Register all Chart.js components
Chart.register(...registerables);

const props = defineProps({
  distributionData: {
    type: Array,
    default: () => [],
  },
});

// Test data for immediate visualization
const testDonutData = [
  { department: 'Administration', count: 5 },
  { department: 'Nursing', count: 12 },
  { department: 'Laboratory', count: 3 },
  { department: 'Pharmacy', count: 4 },
];

const donutCanvas = ref(null);
let donutChartInstance = null;

onMounted(() => {
  initDonutChart();
});

function initDonutChart() {
  if (!donutCanvas.value) return;

  // Destroy old instance if any
  if (donutChartInstance) {
    donutChartInstance.destroy();
  }

  const ctx = donutCanvas.value.getContext('2d');

  // Use prop data or fallback to testDonutData
  const dataArr = props.distributionData.length
    ? props.distributionData
    : testDonutData;

  donutChartInstance = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: dataArr.map((item) => item.department),
      datasets: [
        {
          data: dataArr.map((item) => item.count),
          backgroundColor: [
            '#FF6384', // pink
            '#36A2EB', // sky
            '#FFCE56', // yellow
            '#4BC0C0', // teal
            '#9966FF', // purple
          ],
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
</script>

<template>
  <div>
    <h2 class="text-lg font-semibold mb-4">Distribution by Department (Example Data)</h2>
    <div class="h-64">
      <canvas ref="donutCanvas"></canvas>
    </div>
  </div>
</template>

<style scoped>
</style>
