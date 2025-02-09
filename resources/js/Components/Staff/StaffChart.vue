<script setup>
import { onMounted, ref, watch } from 'vue';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

const props = defineProps({
  countUsers: {
    type: Array,
    default: () => [], 
  },
});

const chartCanvas = ref(null);
let staffChartInstance = null;

onMounted(() => {
  if (props.countUsers.length > 0) { 
    initStaffChart(); // Only initialize if data exists
  }
});

// ✅ Watch for data changes and only update if data is different
watch(
  () => props.countUsers,
  (newVal, oldVal) => {
    if (JSON.stringify(newVal) !== JSON.stringify(oldVal)) {
      initStaffChart();
    }
  },
  { deep: true, immediate: true } // Trigger on first load
);

function initStaffChart() {
  if (!chartCanvas.value) {
    return;
  }

  if (!Array.isArray(props.countUsers) || props.countUsers.length === 0) {
    return;
  }

  if (staffChartInstance) {
    staffChartInstance.destroy();
  }

  const ctx = chartCanvas.value.getContext('2d');

  const allMonths = [
    'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
  ];

  const dataMap = props.countUsers.reduce((acc, item) => {
    acc[item.month] = item.count;
    return acc;
  }, {});

  const data = allMonths.map(month => dataMap[month] || 0);

  console.log('Chart Data:', data); // ✅ Debugging output

  staffChartInstance = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: allMonths,
      datasets: [
        {
          label: 'Total Users',
          data: data,
          backgroundColor: 'rgba(75, 192, 192, 0.4)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 2,
          borderRadius: 4,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            precision: 0,
          },
        },
      },
    },
  });
}
</script>

<template>
  <div>
    <h2 class="text-lg font-semibold mb-4">Total Users (Monthly)</h2>
    <div class="h-64">
      <canvas ref="chartCanvas"></canvas>
    </div>
  </div>
</template>
