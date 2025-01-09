<template>
    <div class="chart-container">
      <div class="header-section text-center bg-pink-100 py-6">
        <h1 class="text-2xl font-bold text-pink-700">Prenatal and Postpartum Records</h1>
        <p class="text-sm text-pink-600">Analyze total prenatal patients with filters for trimesters and postpartum.</p>
      </div>
  
      <div class="filters flex justify-center gap-4 py-4">
        <label class="text-sm font-medium text-gray-700">
          Select Period:
          <select v-model="selectedPeriod" class="ml-2 border border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500">
            <option value="all">All</option>
            <option value="trimester1">Trimester 1</option>
            <option value="trimester2">Trimester 2</option>
            <option value="trimester3">Trimester 3</option>
            <option value="postpartum">Postpartum</option>
          </select>
        </label>
      </div>
  
      <apexchart 
        type="line" 
        :options="chartOptions" 
        :series="chartSeries" 
        class="prenatal-chart mt-6"
      ></apexchart>
    </div>
  </template>
  
  <script>
  import VueApexCharts from "vue3-apexcharts";
  
  export default {
    name: "PrenatalChart",
    components: {
      apexchart: VueApexCharts,
    },
    data() {
      return {
        selectedPeriod: "all",
        chartOptions: {
          chart: {
            id: "prenatal-chart",
            toolbar: {
              show: true,
            },
            zoom: {
              enabled: true
            }
          },
          xaxis: {
            categories: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
          },
          colors: ["#FF69B4"],
          dataLabels: {
            enabled: false,
          },
          title: {
            text: "Prenatal and Postpartum Trends",
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
            theme: "light",
            y: {
              formatter: function(val) {
                return val + " patients";
              }
            }
          },
        },
        allData: {
          all: [300, 400, 350, 500, 450, 600, 700, 800, 750, 650, 500, 400],
          trimester1: [100, 120, 110, 130, 140, 150, 160, 170, 180, 190, 200, 210],
          trimester2: [80, 90, 100, 110, 120, 130, 140, 150, 160, 170, 180, 190],
          trimester3: [70, 80, 90, 100, 110, 120, 130, 140, 150, 160, 170, 180],
          postpartum: [50, 60, 70, 80, 90, 100, 110, 120, 130, 140, 150, 160],
        },
      };
    },
    computed: {
      chartSeries() {
        return [{
          name: 'Patients',
          data: this.allData[this.selectedPeriod] || this.allData.all
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
    background-color: #fdf2f8;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  }
  .prenatal-chart {
    height: 300px;
  }
  .header-section {
    background-color: #ffe4e6;
    border-bottom: 2px solid #ffcad4;
  }
  .filters select {
    padding: 4px 8px;
    font-size: 14px;
  }
  </style>
  