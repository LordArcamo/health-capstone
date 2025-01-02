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
import { ref, watch, computed } from 'vue';
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
      required: true,
      default: () => ({
        date: '',
        ageRange: '',
        gender: '',
        casesType: ''
      })
    }
  },
  setup(props) {
    const chartOptions = ref({
      chart: {
        id: "vaccinations-line-chart",
        toolbar: {
          show: true,
        },
        animations: {
          enabled: true,
          easing: 'easeinout',
          speed: 800,
          animateGradually: {
            enabled: true,
            delay: 150
          },
          dynamicAnimation: {
            enabled: true,
            speed: 350
          }
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
      subtitle: {
        text: computed(() => {
          const parts = [];
          if (props.filters.date) {
            parts.push(`Date: ${props.filters.date}`);
          }
          if (props.filters.ageRange) {
            parts.push(`Age: ${props.filters.ageRange}`);
          }
          if (props.filters.gender) {
            parts.push(`Gender: ${props.filters.gender}`);
          }
          return parts.join(' | ');
        }),
        align: 'center',
        style: {
          fontSize: '14px',
          color: '#666'
        }
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
    });

    const chartSeries = computed(() => [{
      name: "Monthly Vaccinations",
      data: props.vaccinationData
    }]);

    watch(() => props.vaccinationData, (newVal) => {
      chartSeries.value = [{
        name: "Monthly Vaccinations",
        data: newVal
      }];
    }, { deep: true });

    return {
      chartOptions,
      chartSeries
    };
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

.vaccinations-chart {
  height: 300px;
}
</style>
