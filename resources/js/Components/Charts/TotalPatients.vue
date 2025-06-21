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
        <label for="startDate" class="filter-label">Start Date</label>
        <input type="date" v-model="startDate" id="startDate" class="filter-input" />
      </div>
      <div class="filter-item">
        <label for="endDate" class="filter-label">End Date</label>
        <input type="date" v-model="endDate" id="endDate" class="filter-input" />
      </div>
    </div>

    <!-- Chart -->
    <apexchart ref="chart" type="line" :options="options" :series="chartSeries" class="patients-chart" />
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import apexchart from "vue3-apexcharts";
import moment from "moment";

// Props
const props = defineProps({
  monthlyStats: {
    type: Array,
    required: true,
    default: () => Array(12).fill(0),
  },
});

// Reactive state
const selectedGender = ref("");
const genders = ["Male", "Female"];

const months = [
  "January", "February", "March", "April", "May", "June",
  "July", "August", "September", "October", "November", "December",
];

const startDate = ref(null);  // Initially null (no filter applied)
const endDate = ref(null);

const filteredData = computed(() => {
  return props.monthlyStats.map((patients, index) => {
    // If we are receiving aggregated data, return it as is (this might be your issue)
    if (typeof patients === "number") {
      const currentMonth = moment().month(index);
      const isAfterStartDate = startDate.value
        ? currentMonth.isSameOrAfter(startDate.value, "month")
        : true;
      const isBeforeEndDate = endDate.value
        ? currentMonth.isSameOrBefore(endDate.value, "month")
        : true;

      if (!isAfterStartDate || !isBeforeEndDate) {
        return 0; // No data if outside the selected range
      }
      return patients;
    }

    // Handle the case where 'patients' is an array of patient records
    const filteredPatients = patients.filter(patient => {
      const consultationDate = moment(patient.consultationDate); // Assuming consultationDate exists on patient
      return (
        (!startDate.value || consultationDate.isSameOrAfter(startDate.value, "day")) &&
        (!endDate.value || consultationDate.isSameOrBefore(endDate.value, "day"))
      );
    });

    return filteredPatients.length; // Return the filtered count for this specific date range
  });
});

// Chart series
const chartSeries = computed(() => [
  {
    name: selectedGender.value ? `${selectedGender.value} Patients` : "Total Patients",
    data: filteredData.value,
  },
]);

// Chart options
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
