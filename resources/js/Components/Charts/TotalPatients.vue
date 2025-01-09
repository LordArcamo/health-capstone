<template>
  <div class="chart-container">
    <h2 class="chart-title text-gradient">Monthly Total Patients</h2>
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
      default: () => Array(12).fill(0),
    },
  },
  data() {
    return {
      chartOptions: {
        chart: {
          id: "patients-bar-chart",
          toolbar: {
            show: true, // Enable toolbar
            tools: {
              download: true, // Download button
              zoom: true, // Zoom button
              zoomin: true, // Zoom in
              zoomout: true, // Zoom out
              pan: true, // Pan mode
              reset: true, // Reset zoom
            },
            offsetX: 0,
            offsetY: -10, // Adjust toolbar to the top
          },
          zoom: {
            enabled: true,
          },
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
              fontWeight: "normal",
              colors: "#333",
            },
            rotate: -30,
          },
        },
        yaxis: {
          labels: {
            style: {
              fontSize: "12px",
              fontWeight: "normal",
              colors: "#333",
            },
          },
        },
        colors: ["#6EC591"],
        plotOptions: {
          bar: {
            borderRadius: 4,
            horizontal: false,
            columnWidth: "50%",
          },
        },
        dataLabels: {
          enabled: false,
        },
        grid: {
          borderColor: "#e5e5e5",
          strokeDashArray: 4,
        },
        tooltip: {
          enabled: true,
          theme: "light",
          y: {
            formatter: function (val) {
              return val + " patients";
            },
          },
        },
        title: {
          text: "",
        },
        responsive: [
          {
            breakpoint: 768,
            options: {
              chart: {
                height: 300,
              },
              xaxis: {
                labels: {
                  style: {
                    fontSize: "10px",
                  },
                },
              },
            },
          },
        ],
      },
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
  background: #ffffff;
  border-radius: 10px;
  border: 1px solid #e5e5e5;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
  position: relative;
}
.chart-title {
  text-align: center;
  font-size: 24px; /* Larger font size for emphasis */
  font-weight: bold;
  margin-bottom: 20px;
  background: linear-gradient(to right, #6fd190, #48a868); /* Green gradient */
  -webkit-background-clip: text; /* Clip gradient to text */
  -webkit-text-fill-color: transparent; /* Make background clip visible */
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
