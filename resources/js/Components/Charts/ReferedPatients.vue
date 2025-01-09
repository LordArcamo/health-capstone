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
  name: "ReferredPatientsChart",
  components: {
    apexchart: VueApexCharts,
  },
  props: {
    pieChart: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      chartOptions: {
        chart: {
          id: "referred-patients-bar-chart",
          toolbar: {
            show: true,
          },
          zoom: {
            enabled: true
          }
        },
        xaxis: {
          categories: ["Referred", "Not Referred"],
        },
        colors: ["#FF6384", "#36A2EB"],
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
          text: "Referred Patients Overview",
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
              return val + " patients";
            }
          }
        },
      },
    };
  },
  computed: {
    chartSeries() {
      return [{
        name: 'Patients',
        data: [this.pieChart.referred || 0, this.pieChart.notReferred || 0]
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
