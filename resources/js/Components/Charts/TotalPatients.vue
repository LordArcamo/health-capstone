<template>
  <div class="chart-container">
    <!-- Header Section -->
    <div class="chart-header">
      <h2 class="chart-title">Monthly Total Patients</h2>
      <p class="chart-subtitle">
        Analyze total monthly patients with trends and insights.
      </p>
    </div>

    <!-- Filters Section -->
    <div class="filters-container">
      <div class="filter-item">
        <label for="year" class="filter-label">Year</label>
        <select v-model="selectedYear" id="year" class="filter-select" @change="updateFilters">
          <option value="">All Years</option>
          <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
        </select>
      </div>
      <div class="filter-item">
        <label for="gender" class="filter-label">Gender</label>
        <select v-model="selectedGender" id="gender" class="filter-select" @change="updateFilters">
          <option value="">All Genders</option>
          <option v-for="gender in genders" :key="gender" :value="gender">{{ gender }}</option>
        </select>
      </div>
      <button @click="resetFilters" class="reset-button">
        Reset Filters
      </button>
    </div>

    <!-- Chart -->
    <div v-show="isReady">
      <apexchart
        ref="chart"
        type="line"
        :options="options"
        :series="chartSeries"
        class="patients-chart"
      />
    </div>
  </div>
</template>

<script>
import { defineComponent, ref, computed, watch } from "vue";
import VueApexCharts from "vue3-apexcharts";
// import axios from 'axios';

export default defineComponent({
  name: "TotalPatientsChart",
  components: {
    apexchart: VueApexCharts,
  },
  props: {
    monthlyStats: {
      type: Array,
      required: true,
      default: () => Array(12).fill(0),
    },
    filters: {
      type: Object,
      required: true,
      default: () => ({
        date: '',
        ageRange: '',
        gender: '',
        casesType: ''
      }),
    }
  },
  setup(props) {
    const chart = ref(null);
    const isReady = ref(true);
    const months = [
      "January", "February", "March", "April", "May", "June",
      "July", "August", "September", "October", "November", "December",
    ];

    const years = ref([]);
    const genders = ["Male", "Female"];
    const selectedYear = ref("");
    const selectedGender = ref("");
    const filteredData = ref([...props.monthlyStats]);

    // Watch for changes in filters and update data
    watch([selectedYear, selectedGender], async () => {
      try {
        const response = await axios.get('/total-patients-data', {
          params: {
            year: selectedYear.value,
            gender: selectedGender.value
          }
        });
        filteredData.value = response.data.monthlyData;
        years.value = response.data.years;
      } catch (error) {
        console.error('Error fetching filtered data:', error);
      }
    });

    // Watch for changes in monthlyStats prop
    watch(() => props.monthlyStats, (newVal) => {
      filteredData.value = [...newVal];
    }, { immediate: true });

    // Initialize years on component mount
    const initializeYears = async () => {
      try {
        const response = await axios.get('/total-patients-data');
        years.value = response.data.years;
      } catch (error) {
        console.error('Error fetching years:', error);
      }
    };
    initializeYears();

    // Compute filtered series based on filtered data
    const chartSeries = computed(() => [{
      name: "Total Patients",
      data: filteredData.value
    }]);

    const resetFilters = async () => {
      selectedYear.value = "";
      selectedGender.value = "";
      // try {
      //   const response = await axios.get('/total-patients-data');
      //   filteredData.value = response.data.monthlyData;
      // } catch (error) {
      //   console.error('Error resetting data:', error);
      // }
    };

    const updateFilters = () => {
      // This will trigger the watch handlers
    };

    const options = {
      chart: {
        type: "area",
        toolbar: { show: true },
        zoom: { enabled: true },
        animations: {
          enabled: true,
          easing: "easeinout",
          speed: 800,
        },
      },
      xaxis: {
        categories: months,
        labels: {
          style: {
            fontSize: "12px",
            fontWeight: "bold",
            colors: "#333",
          },
          rotate: -30,
        },
        axisBorder: {
          show: true,
          color: "#D1D5DB",
          height: 1,
        },
        axisTicks: {
          show: true,
          color: "#D1D5DB",
        },
      },
      yaxis: {
        labels: {
          style: {
            fontSize: "12px",
            fontWeight: "bold",
            colors: "#333",
          },
        },
        axisBorder: { show: false },
        axisTicks: { show: false },
        decimalsInFloat: 0,
      },
      colors: ["#1E90FF"],
      stroke: {
        curve: "smooth",
        width: 3,
      },
      markers: {
        size: 5,
        colors: ["#1E90FF"],
        strokeColors: "#fff",
        strokeWidth: 2,
        hover: { size: 7 },
      },
      fill: {
        type: "gradient",
        gradient: {
          shade: "light",
          gradientToColors: ["#87CEFA"],
          stops: [0, 100],
          opacityFrom: 0.7,
          opacityTo: 0.2,
        },
      },
      grid: {
        borderColor: "#E5E7EB",
        strokeDashArray: 4,
      },
      tooltip: {
        enabled: true,
        theme: "light",
        style: { fontSize: "14px" },
        marker: { show: true },
        y: {
          formatter: (val) => `${val} patients`,
          title: { formatter: () => "Patients: " },
        },
      },
    };

    return {
      chart,
      isReady,
      options,
      chartSeries,
      years,
      genders,
      selectedYear,
      selectedGender,
      resetFilters,
      updateFilters,
    };
  },
});
</script>

<style scoped>
.chart-container {
  max-width: 100%;
  margin: 20px auto;
  padding: 20px;
  background: #ffffff;
  border-radius: 12px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.chart-header {
  text-align: center;
  margin-bottom: 24px;
}

.chart-title {
  font-size: 24px;
  font-weight: 600;
  color: #1a1a1a;
  margin: 0;
}

.chart-subtitle {
  font-size: 14px;
  color: #666;
  margin: 8px 0 0;
}

.filters-container {
  display: flex;
  gap: 16px;
  margin-bottom: 24px;
  flex-wrap: wrap;
  align-items: center;
}

.filter-item {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.filter-label {
  font-size: 14px;
  font-weight: 500;
  color: #4a5568;
}

.filter-select {
  padding: 8px 12px;
  border: 1px solid #e2e8f0;
  border-radius: 6px;
  font-size: 14px;
  color: #1a1a1a;
  background-color: white;
  min-width: 120px;
}

.filter-select:focus {
  outline: none;
  border-color: #3182ce;
  box-shadow: 0 0 0 1px #3182ce;
}

.reset-button {
  padding: 8px 16px;
  background-color: #e2e8f0;
  border: none;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 500;
  color: #4a5568;
  cursor: pointer;
  transition: all 0.2s;
}

.reset-button:hover {
  background-color: #cbd5e0;
}

.patients-chart {
  height: 400px;
  margin-top: 16px;
}

@media (max-width: 768px) {
  .filters-container {
    flex-direction: column;
    align-items: stretch;
  }

  .filter-select {
    width: 100%;
  }
}
</style>
