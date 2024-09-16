<template>
  <div class="w-full h-full">
    <Bar :data="chartData" :options="chartOptions" />
  </div>
</template>

<script>
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

export default {
  name: 'BlueBarChart',
  components: { Bar },
  data() {
    return {
      chartData: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets: [
          {
            label: 'Sales',
            backgroundColor: '#42A5F5', // Light blue
            borderColor: '#1E88E5',     // Darker blue
            borderWidth: 1,
            data: [65, 59, 80, 81, 56, 55, 40]
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
                if (context.parsed.y !== null) {
                  label += `${context.parsed.y}`;
                }
                return label;
              }
            }
          }
        },
        scales: {
          x: {
            beginAtZero: true,
            grid: {
              display: false
            },
            ticks: {
              autoSkip: false, // Ensure all labels are shown
              maxRotation: 0,  // Set max rotation to 0 degrees for horizontal labels
              minRotation: 0   // Set min rotation to 0 degrees for horizontal labels
            }
          },
          y: {
            beginAtZero: true,
            grid: {
              borderColor: '#e0e0e0',
              borderWidth: 1
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
