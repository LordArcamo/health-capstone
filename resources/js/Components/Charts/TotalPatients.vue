<template>
  <div class="chart-container">
    <!-- Header Section -->
    <div class="chart-header">
      <h2 class="chart-title">Monthly Total Patients</h2>
      <p class="chart-subtitle">
        Analyze total monthly patients with trends and insights.
      </p>
    </div>

    <!-- Chart -->
    <apexchart
      v-if="chartSeries[0].data.length"
      type="line"
      :options="chartOptions"
      :series="chartSeries"
      class="patients-chart"
    ></apexchart>
  </div>
</template>

<script>
import VueApexCharts from "vue3-apexcharts";

export default {
  name: "TotalPatientsChart",
  components: {
    apexchart: VueApexCharts,
  },
  props: {
    monthlyData: {
      type: Array,
      required: true,
      default: () => Array(12).fill(0),
    },
  },
  data() {
    return {
        chartOptions: Object.freeze({
        chart: {
          id: "patients-line-chart",
          toolbar: { show: true },
          zoom: { enabled: true },
          background: "#ffffff",
        },
        xaxis: {
          categories: [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December",
          ],
          labels: {
            style: {
              fontSize: "12px",
              fontWeight: "bold",
              colors: "#333", // Darker text for better contrast
            },
            rotate: -30,
          },
        },
        yaxis: {
          labels: {
            style: {
              fontSize: "12px",
              fontWeight: "bold",
              colors: "#333",
            },
          },
        },
        colors: ["#6EC591"],
        stroke: { curve: "smooth", width: 3 },
        grid: { borderColor: "#e5e5e5", strokeDashArray: 4 },
        tooltip: {
          enabled: true,
          theme: "light",
          y: { formatter: (val) => `${val} patients` },
        },
        responsive: [
          {
            breakpoint: 768,
            options: {
                chart: { height: 300 },
                xaxis: { labels: { style: { fontSize: "10px" } } },
            },
          },
        ],
      }),
    };
  },
  computed: {
    chartSeries() {
      return [
        {
          name: "Total Patients",
          data: this.monthlyData,
        },
      ];
    },
  },
};
</script>

<style scoped>
.chart-container {
  max-width: 100%;
  margin: 20px auto;
  padding: 20px;
  background: #ffffff; /* White background for clarity */
  border-radius: 12px;
  border: 1px solid #e5e5e5;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.chart-header {
  text-align: center;
  margin-bottom: 20px;
}

.chart-title {
  font-size: 24px;
  font-weight: bold;
  color: #4CAF50; /* Green text for alignment with the theme */
  margin: 0;
}

.chart-subtitle {
  font-size: 14px;
  color: #666;
  margin: 5px 0 0;
}

.patients-chart {
  height: 350px;
  width: 100%;
}

@media (max-width: 768px) {
  .patients-chart {
    height: 250px;
  }
}
</style>
