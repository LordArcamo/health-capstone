<template>
  <div class="chart-container">
    <!-- Header Section -->
    <div class="chart-header">
      <h2 class="chart-title">Monthly Vaccinations Overviewss</h2>
      <p class="chart-subtitle">
        {{ selectedVaccine ? `Showing data for ${selectedVaccine}` : "Showing data for all vaccines" }}
      </p>
    </div>

    <!-- Filter Section -->
    <div class="filter-container">
      <label for="vaccine-filter" class="filter-label">Vaccine Type:</label>
      <select id="vaccine-filter" v-model="selectedVaccine" @change="updateChart">
        <option value="">All Vaccines</option>
        <option v-for="vaccine in vaccineTypes" :key="vaccine" :value="vaccine">
          {{ vaccine }}
        </option>
      </select>
    </div>

    <!-- Chart -->
    <apexchart
      type="line"
      :options="chartOptions"
      :series="chartSeries"
      class="vaccinations-chart"
    ></apexchart>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import apexchart from "vue3-apexcharts";

const props = defineProps({
  vaccinenatedPatients: Array,
});

console.log("Vaccine Data", props.vaccinenatedPatients);

// Helper function to count patients per month for each vaccine type
const countPatientsByVaccine = () => {
  const vaccineCounts = {};

  props.vaccinenatedPatients.forEach((patient) => {
    if (patient.history && patient.history.length > 0) {
      patient.history.forEach((entry) => {
        const date = new Date(entry.dateOfVisit);
        const month = date.getMonth(); // Month is 0-indexed (0 = January, 11 = December)
        const vaccineType = entry.vaccineType;

        if (!vaccineCounts[vaccineType]) {
          vaccineCounts[vaccineType] = Array(12).fill(0); // Initialize months for this vaccine type
        }
        vaccineCounts[vaccineType][month]++;
      });
    }
  });

  // Add aggregated data for "All" vaccines
  vaccineCounts["All"] = Array(12).fill(0);
  Object.keys(vaccineCounts).forEach((type) => {
    if (type !== "All") {
      vaccineCounts["All"] = vaccineCounts["All"].map(
        (total, month) => total + vaccineCounts[type][month]
      );
    }
  });

  return vaccineCounts;
};

// Reactive data for vaccine counts
const vaccinationData = ref(countPatientsByVaccine());

// Reactive data for the selected vaccine
const selectedVaccine = ref("");

// Available vaccine types
const vaccineTypes = computed(() =>
  Object.keys(vaccinationData.value).filter((type) => type !== "All")
);

// Chart options
const chartOptions = ref({
  chart: {
    id: "vaccinations-line-chart",
    toolbar: {
      show: true,
    },
    animations: {
      enabled: true,
      easing: "easeinout",
      speed: 800,
    },
    background: "#ffffff",
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
    labels: {
      style: {
        fontSize: "12px",
        fontWeight: "bold",
        colors: "#333",
      },
    },
  },
  stroke: {
    curve: "smooth",
    width: 3,
  },
  colors: ["#6EC591"], // Green theme for the chart line
  markers: {
    size: 5,
    colors: "#ffffff",
    strokeColors: "#6EC591",
    strokeWidth: 2,
  },
  grid: {
    borderColor: "#e7e7e7",
    strokeDashArray: 4,
  },
  tooltip: {
    enabled: true,
    theme: "light",
    y: {
      formatter: (val) => `${val} vaccinations`,
    },
  },
});

// Chart series dynamically updates based on the selected vaccine
const chartSeries = computed(() => [
  {
    name: "Monthly Vaccinations",
    data:
      selectedVaccine.value && selectedVaccine.value !== ""
        ? vaccinationData.value[selectedVaccine.value] || Array(12).fill(0)
        : vaccinationData.value["All"],
  },
]);

// Handle chart updates (reactivity ensures the chart updates automatically)
const updateChart = () => {
  console.log(`Filter updated to: ${selectedVaccine.value}`);
};
</script>

<style scoped>
.chart-container {
  max-width: 100%;
  margin: 20px auto;
  padding: 20px;
  background: #f9fdf9; /* Subtle green tint */
  border-radius: 12px;
  border: 1px solid #e5e5e5;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Light shadow */
}

.chart-header {
  text-align: center;
  margin-bottom: 20px;
}

.chart-title {
  font-size: 24px;
  font-weight: bold;
  color: #4CAF50; /* Green for the title */
}

.chart-subtitle {
  font-size: 14px;
  color: #666; /* Subtle gray for context */
  margin-top: 5px;
}

.filter-label {
  margin-right: 10px;
  font-size: 14px;
  font-weight: bold;
  color: #333;
}

select {
  padding: 8px 12px;
  border-radius: 8px;
  border: 1px solid #ddd;
  font-size: 14px;
  color: #333;
}

select:focus {
  outline: none;
  border-color: #6EC591;
}

.vaccinations-chart {
  height: 350px;
}

@media (max-width: 768px) {
  .vaccinations-chart {
    height: 250px;
  }

  .filter-container {
    flex-direction: column;
    gap: 10px;
  }
}
</style>
