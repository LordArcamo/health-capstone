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
        <select v-model="selectedYear" id="year" class="filter-select">
          <option value="">Select Year</option>
          <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
        </select>
      </div>
      <div class="filter-item">
        <label for="gender" class="filter-label">Gender</label>
        <select v-model="selectedGender" id="gender" class="filter-select">
          <option value="">Select Gender</option>
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
        :series="filteredSeries"
        class="patients-chart"
      />
    </div>
  </div>
</template>

<script>
import { defineComponent, ref, watch, computed, onMounted, onBeforeUnmount } from "vue";
import VueApexCharts from "vue3-apexcharts";

export default defineComponent({
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
  setup(props) {
    const chart = ref(null);
    const isReady = ref(false);
    const months = [
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
    ];

    const years = ref([2021, 2022, 2023]);
    const genders = ref(["Male", "Female", "Other"]);

    const selectedYear = ref("");
    const selectedGender = ref("");

    const options = {
  chart: {
    type: "area", // Change to area chart
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
  colors: ["#1E90FF"], // Base color for the line and area
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
      gradientToColors: ["#87CEFA"], // Lighter shade for the top
      stops: [0, 100], // Gradient from bottom to top
      opacityFrom: 0.7,
      opacityTo: 0.2, // Softer opacity for lower parts
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


    const series = ref([{ name: "Total Patients", data: props.monthlyData }]);

    const filteredSeries = computed(() => {
      return series.value; // Placeholder for actual filtering logic.
    });

    const resetFilters = () => {
      selectedYear.value = "";
      selectedGender.value = "";
    };

    watch(
      () => props.monthlyData,
      (newVal) => {
        if (Array.isArray(newVal) && newVal.length === 12) {
          series.value = [{ name: "Total Patients", data: [...newVal] }];
        }
      },
      { deep: true }
    );

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
      series,
      filteredSeries,
      years,
      genders,
      selectedYear,
      selectedGender,
      resetFilters,
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
  border: 1px solid #e5e5e5;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.chart-header {
  margin-bottom: 20px;
}

.chart-title {
  font-size: 1.75rem;
  font-weight: bold;
  color: #333;
  margin-bottom: 5px;
}

.chart-subtitle {
  font-size: 1rem;
  color: #666;
}

.filters-container {
  display: flex;
  gap: 16px;
  margin-bottom: 20px;
  flex-wrap: wrap;
}

.filter-item {
  display: flex;
  flex-direction: column;
}

.filter-label {
  font-size: 0.875rem;
  font-weight: 500;
  margin-bottom: 4px;
  color: #555;
}

.filter-select {
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 0.875rem;
  transition: border-color 0.2s;
}

.filter-select:focus {
  border-color: #6EC591;
  outline: none;
}

.reset-button {
  background-color: #ff5252;
  color: #fff;
  padding: 8px 16px;
  font-size: 0.875rem;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.reset-button:hover {
  background-color: #ff3333;
}

.patients-chart {
  min-height: 400px;
}
</style>
