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
  props: {
    monthlyData: {
      type: Array,
      required: true,
      default: () => Array(12).fill(0)
    },
  },
  data() {
    return {
      chartOptions: {
        chart: {
          id: "patients-bar-chart",
          toolbar: {
            show: true,
          },
          zoom: {
            enabled: true
          }
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
            borderRadius: 4,
            horizontal: false,
            columnWidth: '70%',
          }
        },
        dataLabels: {
          enabled: false,
        },
        title: {
          align: "center",
          margin: 10,
          style: {
            fontSize: '18px',
            fontWeight: 'bold',
          }
        },
        grid: {
          borderColor: "#e7e7e7",
        },
        tooltip: {
          enabled: true,
          theme: "dark",
          y: {
            formatter: function(val) {
              return val + " patients"
            }
          }
        },
      },
    };
  },
  computed: {
    chartSeries() {
      console.log("Chart Series Data:", this.monthlyData);
      return [{
        name: 'Total Patients',
        data: this.monthlyData
      }];
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
.patients-chart {
  height: 300px;
}
</style>
