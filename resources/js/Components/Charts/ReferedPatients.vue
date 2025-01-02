<template>
  <div class="chart-container">
    <canvas ref="chartRef"></canvas>
  </div>
</template>

<script>
import { ref, onMounted, watch } from "vue";
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
  setup(props) {
    const chartRef = ref(null);
    let chart = null;

    const createChart = () => {
      if (chart) {
        chart.destroy();
      }

      const ctx = chartRef.value.getContext("2d");
      chart = new Chart(ctx, {
        type: "pie",
        data: {
          labels: ["Referred", "Not Referred"],
          datasets: [
            {
              data: [
                props.pieChart.referred || 0,
                props.pieChart.notReferred || 0,
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
            legend: {
              position: 'bottom'
            }
          },
        },
      });
    };

    onMounted(() => {
      createChart();
    });

    watch(() => props.pieChart, (newVal) => {
      if (chart) {
        chart.data.datasets[0].data = [
          newVal.referred || 0,
          newVal.notReferred || 0,
        ];
        chart.update();
      }
    }, { deep: true });

    return {
      chartRef
    };
  },
};
</script>

<style scoped>
.chart-container {
  height: 300px;
  position: relative;
}
</style>
