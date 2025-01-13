<template>
  <div class="chart-container">
    <!-- Header Section -->
    <div class="chart-header">
      <h2 class="chart-title">Monthly Total Patients</h2>
      <p class="chart-subtitle">
        Analyze total monthly patients with trends and insights.
      </p>
    </div>

    <!-- Chart -->
    <div v-show="isReady">
      <apexchart
        ref="chart"
        type="line"
        :options="options"
        :series="series"
        class="patients-chart"
      />
    </div>
  </div>
</template>

<script>
import { defineComponent, ref, watch, onMounted, onBeforeUnmount } from 'vue';
import VueApexCharts from "vue3-apexcharts";

export default defineComponent({
  name: 'TotalPatientsChart',
  components: {
    apexchart: VueApexCharts
  },
  props: {
    monthlyData: {
      type: Array,
      required: true,
      default: () => Array(12).fill(0)
    }
  },
  setup(props) {
    const chart = ref(null);
    const isReady = ref(false);
    const months = [
      "January", "February", "March", "April", "May", "June",
      "July", "August", "September", "October", "November", "December"
    ];

    const options = {
      chart: {
        type: 'line',
        toolbar: { show: true },
        zoom: { enabled: true }
      },
      xaxis: {
        categories: months,
        labels: {
          style: {
            fontSize: "12px",
            fontWeight: "bold",
            colors: "#333"
          },
          rotate: -30
        }
      },
      yaxis: {
        labels: {
          style: {
            fontSize: "12px",
            fontWeight: "bold",
            colors: "#333"
          }
        }
      },
      colors: ["#6EC591"],
      stroke: { curve: "smooth", width: 3 },
      grid: { borderColor: "#e5e5e5", strokeDashArray: 4 },
      tooltip: {
        enabled: true,
        theme: "light",
        y: { formatter: (val) => `${val} patients` }
      }
    };

    const series = ref([{
      name: 'Total Patients',
      data: props.monthlyData
    }]);

    watch(() => props.monthlyData, (newVal) => {
      if (Array.isArray(newVal) && newVal.length === 12) {
        series.value = [{
          name: 'Total Patients',
          data: [...newVal]
        }];
      }
    }, { deep: true });

    onMounted(() => {
      isReady.value = true;
    });

    onBeforeUnmount(() => {
      isReady.value = false;
    });

    return {
      chart,
      isReady,
      options,
      series
    };
  }
});
</script>

<style scoped>
.chart-container {
  max-width: 100%;
  margin: 20px auto;
  padding: 20px;
  background: #ffffff;
  border-radius: 12px;
  border: 1px solid #e5e5e5;
}

.chart-header {
  margin-bottom: 20px;
}

.chart-title {
  font-size: 1.5rem;
  font-weight: bold;
  color: #333;
  margin-bottom: 5px;
}

.chart-subtitle {
  font-size: 1rem;
  color: #666;
}

.patients-chart {
  min-height: 400px;
}
</style>
