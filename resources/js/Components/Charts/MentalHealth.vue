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
  props: {
    mentalHealthStats: {
      type: Object,
      required: true,
      default: () => ({
        labels: [],
        data: []
      })
    },
    filters: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      chart: null
    };
  },
  mounted() {
    this.createChart();
  },
  methods: {
    createChart() {
      const ctx = document.getElementById("mentalHealthChart").getContext("2d");
      
      if (this.chart) {
        this.chart.destroy();
      }

      this.chart = new Chart(ctx, {
        type: "radar",
        data: {
          labels: this.mentalHealthStats.labels,
          datasets: [
            {
              label: "Mental Health",
              data: this.mentalHealthStats.data,
              backgroundColor: "rgba(153, 102, 255, 0.2)",
              borderColor: "rgba(153, 102, 255, 1)",
              borderWidth: 2,
              pointBackgroundColor: "rgba(153, 102, 255, 1)",
              pointBorderColor: "#fff",
              pointHoverBackgroundColor: "#fff",
              pointHoverBorderColor: "rgba(153, 102, 255, 1)"
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            r: {
              beginAtZero: true,
              ticks: {
                stepSize: 1
              }
            }
          },
          plugins: {
            legend: {
              position: 'bottom',
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  return `${context.label}: ${context.raw} cases`;
                }
              }
            }
          }
        },
      });
    }
  },
  watch: {
    mentalHealthStats: {
      handler() {
        this.createChart();
      },
      deep: true
    }
  }
};
</script>

<style scoped>
.chart-container {
  max-width: 100%;
  margin: 0 auto;
  padding: 20px;
  background-color: #f9f9f9;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

#mentalHealthChart {
  height: 300px;
}
</style>
