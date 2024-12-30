<template>
  <div class="chart-container">
    <apexchart 
      type="line" 
      :options="chartOptions" 
      :series="chartSeries" 
      class="cases-chart"
    ></apexchart>
  </div>
</template>

<script>
import VueApexCharts from "vue3-apexcharts";

export default {
  name: "Cases",
  components: {
    apexchart: VueApexCharts
  },
  data() {
    return {
      chartOptions: {
        chart: {
          id: "cases-chart",
          toolbar: {
            show: true,
          },
        },
        xaxis: {
          categories: [
            "January", "February", "March", "April", "May", "June", 
            "July", "August", "September", "October", "November", "December"
          ],
        },
        stroke: {
          curve: "smooth",
          width: 2,
        },
        markers: {
          size: 4,
        },
        colors: ["#FF6384"],
        title: {
          align: "center",
          margin: 10,
          style: {
            fontSize: "18px",
            fontWeight: "bold",
          },
        },
        grid: {
          borderColor: "#e7e7e7",
        },
      },
      chartSeries: [
        {
          name: "Cases",
          data: [65, 59, 80, 81, 56, 55, 70, 75, 85, 90, 60, 50],
        },
      ],
    };
  },
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
.cases-chart {
  height: 400px;
}
</style>
