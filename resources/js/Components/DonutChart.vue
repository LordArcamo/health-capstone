<template>
<div>
  <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">
    Most Common Diagnoses ({{ new Date().getFullYear() }})
  </h2>

  <!-- Chart -->
  <apexchart
    v-if="chartOptions.series.length"
    type="donut"
    :width="'100%'"
    height="400"
    :options="chartOptions.options"
    :series="chartOptions.series"
  />
</div>
</template>

<script>
import { defineComponent, computed } from "vue";
import ApexCharts from "vue3-apexcharts";

export default defineComponent({
  components: {
    apexchart: ApexCharts,
  },
  props: {
    nonReferredData: {
      type: Array,
      required: true,
    },
  },
  setup(props) {
    const currentYear = new Date().getFullYear();

    // Process data to get the most common diagnoses for the current year
    const topDiagnoses = computed(() => {
      const diagnosisCounts = {};

      // Filter for current year before processing
      props.nonReferredData
        .filter(item => item.year === currentYear)
        .forEach((item) => {
          if (item.diagnosis) {
            diagnosisCounts[item.diagnosis] = (diagnosisCounts[item.diagnosis] || 0) + item.case_count;
          }
        });

      // Sort and take the top 5 diagnoses
      return Object.entries(diagnosisCounts)
        .sort((a, b) => b[1] - a[1])
        .slice(0, 5);
    });

    const chartOptions = computed(() => {
      const labels = topDiagnoses.value.map(([diagnosis]) => diagnosis);
      const data = topDiagnoses.value.map(([, count]) => count);

      return {
        options: {
          chart: {
            type: "donut",
            animations: {
              enabled: true,
              easing: "easeout",
              speed: 800,
            },
          },
          labels,
          colors: [
            "#66BB6A", // Light green
            "#42A5F5", // Light blue
            "#81C784", // Mint green
            "#90CAF9", // Sky blue
            "#A5D6A7", // Pale green
          ],
          legend: {
            position: "bottom",
            fontSize: "14px",
            markers: {
              radius: 10,
              offsetX: -4,
            },
            labels: {
              useSeriesColors: true,
            },
          },
          dataLabels: {
            enabled: false, // Disable on-chart labels
          },
          tooltip: {
            enabled: true,
            theme: "light",
            style: {
              fontSize: "14px",
            },
            y: {
              formatter: function (val) {
                return `${val} cases`;
              },
            },
          },
          plotOptions: {
            pie: {
              donut: {
                size: "75%",
                labels: {
                  show: true,
                  name: {
                    show: true,
                    fontSize: "16px",
                    fontWeight: 600,
                    color: "#4CAF50", // Highlighted green for center label
                    offsetY: -5,
                  },
                  value: {
                    show: true,
                    fontSize: "24px",
                    fontWeight: 700,
                    color: "#2196F3", // Light blue for the total value
                    offsetY: 5,
                  },
                  total: {
                    show: true,
                    showAlways: true,
                    label: "Total Cases",
                    fontSize: "16px",
                    fontWeight: 600,
                    color: "#555",
                    formatter: function (w) {
                      return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                    },
                  },
                },
              },
            },
          },
        },
        series: data,
      };
    });

    return {
      chartOptions,
    };
  },
});
</script>

<style scoped>
/* Improve donut chart responsiveness */
.apexcharts-legend {
  justify-content: center !important;
  margin-top: 10px;
}

.apexcharts-canvas {
  position: relative;
  z-index: 10 !important; /* Ensure it stays above most components */
}

.apexcharts-tooltip {
  z-index: 50 !important; /* Higher z-index for tooltips */
}
</style>
