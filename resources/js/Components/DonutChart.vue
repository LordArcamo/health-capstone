<template>
  <div class="max-w-xs mx-auto relative">
    <!-- Donut Chart -->
    <ChartjsDonut :data="chartData" :options="chartOptions" />
  
  </div>
</template>

<script>
import { defineComponent } from 'vue';
import { Doughnut } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, ArcElement);

export default defineComponent({
  components: {
    ChartjsDonut: Doughnut
  },
  data() {
    return {
      chartData: {
        labels: ['Bronchitis', 'Dengue X', 'Flu', 'Pneumonia', 'Asthma'],
        datasets: [
          {
            data: [1200, 850, 1500, 950, 1300], // Example data for the diseases
            backgroundColor: [
              'rgba(75, 192, 192, 0.5)', // Low-opacity green
              '#FF8D1A',
              '#FFB74D',
              '#F57C00',
              '#2196F3'
            ],
            hoverBackgroundColor: [
              'rgba(75, 192, 192, 0.8)',
              '#FF7A13',
              '#FF9C38',
              '#F56A00',
              '#1976D2'
            ]
          }
        ]
      },
      chartOptions: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top',
            labels: {
              font: {
                size: 14
              }
            }
          },
          tooltip: {
            callbacks: {
              label: function(tooltipItem) {
                return `${tooltipItem.label}: ${tooltipItem.raw} cases`;
              }
            }
          }
        },
        cutout: '70%', // Makes it a donut chart with a larger hole
      }
    };
  }
});
</script>

<style scoped>
/* Adjust the center text */
.text-lg {
  font-size: 1.125rem;
}
</style>
