<template>
    <div>
      <!-- Year and Month Filters -->
      <div class="flex flex-wrap justify-between items-center mb-4 gap-4">
        <!-- Year Filter -->
        <div class="flex items-center space-x-2">
          <label for="year" class="font-semibold text-gray-700">Year:</label>
          <select v-model="selectedYear" @change="filterData" class="border rounded px-4 py-2">
            <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
          </select>
        </div>
  
        <!-- Month Filter -->
        <div class="flex items-center space-x-2">
          <label for="month" class="font-semibold text-gray-700">Month:</label>
          <select v-model="selectedMonth" @change="filterData" class="border rounded px-4 py-2">
            <option value="">All</option>
            <option v-for="(month, index) in months" :key="index" :value="month">{{ month }}</option>
          </select>
        </div>
      </div>
  
      <!-- Combined Chart Section -->
      <div class="h-80">
        <h3 class="text-lg font-semibold text-center mb-2">Outpatient and Referred Cases</h3>
        <Bar ref="combinedChart" :data="filteredCombinedData" :options="chartOptions" />
      </div>
    </div>
  </template>
  
  <script>
  import { Bar } from 'vue-chartjs';
  import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
  } from 'chart.js';
  
  ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);
  
  export default {
    name: "OutpatientReferredChart",
    components: { Bar },
    data() {
      return {
        years: [2022, 2023, 2024],
        months: [
          "January", "February", "March", "April", "May", "June",
          "July", "August", "September", "October", "November", "December",
        ],
        selectedYear: 2023,
        selectedMonth: "",
        outpatientData: {
          labels: [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December",
          ],
          datasets: [
            {
              label: "Outpatient Cases",
              data: [100, 120, 130, 110, 150, 170, 160, 180, 200, 190, 210, 220],
              backgroundColor: "#42A5F5", // Blue for outpatient
              borderRadius: 10,
            },
          ],
        },
        referredData: {
          labels: [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December",
          ],
          datasets: [
            {
              label: "Referred Cases",
              data: [30, 40, 35, 50, 45, 60, 55, 70, 65, 80, 75, 90],
              backgroundColor: "#66BB6A", // Green for referred
              borderRadius: 10,
            },
          ],
        },
        chartOptions: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: "bottom",
            },
          },
          scales: {
            x: {
              title: {
                display: true,
                text: "Month",
              },
            },
            y: {
              title: {
                display: true,
                text: "Number of Cases",
              },
              beginAtZero: true,
            },
          },
        },
      };
    },
    computed: {
      filteredCombinedData() {
        // If a month is selected, filter data for the selected month
        if (this.selectedMonth) {
          const monthIndex = this.months.indexOf(this.selectedMonth);
          return {
            labels: [this.selectedMonth],
            datasets: [
              {
                ...this.outpatientData.datasets[0],
                data: [this.outpatientData.datasets[0].data[monthIndex]],
              },
              {
                ...this.referredData.datasets[0],
                data: [this.referredData.datasets[0].data[monthIndex]],
              },
            ],
          };
        }
        // If no month is selected, return the full data for both datasets
        return {
          labels: this.outpatientData.labels,
          datasets: [
            ...this.outpatientData.datasets,
            ...this.referredData.datasets,
          ],
        };
      },
    },
    methods: {
      filterData() {
        // You can add logic here if needed to filter data by year or other parameters
      },
    },
  };
  </script>
  