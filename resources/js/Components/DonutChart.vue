<template>
    <div class="w-full h-96">
      <Doughnut :data="chartData" :options="chartOptions" />
    </div>
  </template>
  
  <script>
  import { Doughnut } from 'vue-chartjs';
  import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement, CategoryScale, LinearScale } from 'chart.js';
  
  ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale, LinearScale);
  
  export default {
    name: 'DoughnutChart',
    components: { Doughnut },
    data() {
      return {
        chartData: {
          labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
          datasets: [
            {
              label: 'My Dataset',
              data: [12, 19, 3, 5, 2, 3],
              backgroundColor: [
                '#FF6384',
                '#36A2EB',
                '#FFCE56',
                '#4BC0C0',
                '#9966FF',
                '#FF9F40'
              ],
              hoverOffset: 4
            }
          ]
        },
        chartOptions: {
          responsive: true,
          plugins: {
            legend: {
              position: 'top'
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  let label = context.dataset.label || '';
                  if (label) {
                    label += ': ';
                  }
                  if (context.parsed !== null) {
                    label += `${context.parsed}`;
                  }
                  return label;
                }
              }
            }
          }
        }
      }
    }
  }
  </script>
  
  <style scoped>
  /* Custom styles if needed */
  </style>
  