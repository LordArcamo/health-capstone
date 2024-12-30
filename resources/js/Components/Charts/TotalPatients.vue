<template>
  <div class="chart-container">
    <apexchart 
      type="bar" 
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
  data() {
    return {
      chartOptions: {
        chart: {
          id: "patients-bar-chart",
          toolbar: {
            show: true,
          },
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
        },
        colors: ["#4BC0C0"],
        plotOptions: {
          bar: {
            columnWidth: "50%",
            borderRadius: 4,
          },
        },
        dataLabels: {
          enabled: false,
        },
        title: {
          align: "center",
          margin: 10,
          style: {
            fontSize: "20px",
            fontWeight: "bold",
          },
        },
        grid: {
          borderColor: "#e7e7e7",
        },
        tooltip: {
          enabled: true,
          theme: "dark",
        },
      },
      chartSeries: [
        {
          name: "Total Patients",
          data: [50, 60, 70, 85, 90, 100, 110, 120, 130, 140, 150, 160],
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
.patients-chart {
  height: 300px;
}
</style>
