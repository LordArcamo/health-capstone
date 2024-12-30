<template>
  <div class="chart-container">
    <apexchart 
      type="line" 
      :options="chartOptions" 
      :series="chartSeries" 
      class="vaccinations-chart"
    ></apexchart>
  </div>
</template>

<script>
import VueApexCharts from "vue3-apexcharts";

export default {
  name: "VaccinationsChart",
  components: {
    apexchart: VueApexCharts,
  },
  props: {
    vaccinationData: {
      type: Array,
      required: true,
      default: () => Array(12).fill(0)
    },
    filters: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      chartOptions: {
        chart: {
          id: "vaccinations-line-chart",
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
        stroke: {
          curve: "smooth",
          width: 3,
        },
        colors: ["#FF9F40"],
        markers: {
          size: 5,
        },
        title: {
          align: "center",
          style: {
            fontSize: "20px",
            fontWeight: "bold",
          },
        },
        tooltip: {
          enabled: true,
          theme: "dark",
          y: {
            formatter: function(val) {
              return val + " vaccinations"
            }
          }
        },
        grid: {
          borderColor: "#e7e7e7",
        },
      },
    };
  },
  computed: {
    chartSeries() {
      return [
        {
          name: "Monthly Vaccinations",
          data: this.vaccinationData || Array(12).fill(0)
        },
      ];
    },
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

.vaccinations-chart {
  height: 300px;
}
</style>
