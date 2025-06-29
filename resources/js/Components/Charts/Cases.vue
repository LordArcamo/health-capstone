<template>
  <div class="chart-container">
    <!-- Header Section -->
    <div class="chart-header">
      <h2 class="chart-title">Monthly Cases Overview</h2>
      <p class="chart-subtitle">
        {{ selectedFilters }}
      </p>
    </div>

    <!-- Filters Section -->
    <div class="filters-container">
      <!-- Case Type Filter -->
      <div class="filter">
        <label for="case-type" class="filter-label">Case Type:</label>
        <select id="case-type" v-model="selectedCaseType" @change="updateChart">
          <option value="">All Cases</option>
          <option v-for="type in caseTypes" :key="type" :value="type">
            {{ type }}
          </option>
        </select>
      </div>

      <!-- Age Group Filter -->
      <div class="filter">
        <label for="age-group" class="filter-label">Age Group:</label>
        <select id="age-group" v-model="selectedAgeGroup" @change="updateChart">
          <option value="">All Ages</option>
          <option v-for="age in ageGroups" :key="age" :value="age">
            {{ age }}
          </option>
        </select>
      </div>

      <!-- Gender Filter -->
      <div class="filter">
        <label for="gender" class="filter-label">Gender:</label>
        <select id="gender" v-model="selectedGender" @change="updateChart">
          <option value="">All Genders</option>
          <option v-for="gender in genders" :key="gender" :value="gender">
            {{ gender }}
          </option>
        </select>
      </div>

      <!-- Barangay Filter -->
       <div class="filter flex flex-col items-start">
        <label for="barangay-filter" class="filter-label">Barangay:</label>
        <select id="barangay-filter" v-model="selectedBarangay" class="filter-select">
          <option value="all">All Barangays</option>
          <option v-for="barangay in uniqueBarangays" :key="barangay" :value="barangay">
            {{ barangay }}
          </option>
        </select>
      </div>
    </div>

    <!-- Chart -->
    <apexchart
      type="line"
      :options="chartOptions"
      :series="chartSeries"
      class="cases-chart"
    ></apexchart>
  </div>
</template>

<script setup>
import apexchart from "vue3-apexcharts";
import { ref, computed } from "vue";

// Define props
const props = defineProps({
  monthly: {
    type: Array,
    required: true,
  },
});

console.log("Monthly Cases received", props.monthly);

// Filters
const selectedBarangay = ref("all");
const selectedCaseType = ref(""); // Default: All Cases
const selectedAgeGroup = ref(""); // Default: All Ages
const selectedGender = ref(""); // Default: All Genders

// Extract unique case types (diagnosis)
const caseTypes = Array.from(
  new Set(
    props.monthly.map((item) => item.visit_information?.diagnosis || "Unknown")
  )
);

// Predefined age groups and gender options
const ageGroups = ["0-18", "19-35", "36-60", "60+"];
const genders = ["Male", "Female"];

const uniqueBarangays = computed(() => {
  const barangays = props.monthly.map((record) =>
    record.barangay || record.personal_information?.barangay || "Unknown"
  );
  return [...new Set(barangays)].sort();
});

// Helper function to calculate age from birthdate
const getAge = (birthdate) => {
  if (!birthdate) return null;
  const birth = new Date(birthdate);
  const today = new Date();
  return today.getFullYear() - birth.getFullYear() -
    (today < new Date(today.getFullYear(), birth.getMonth(), birth.getDate()) ? 1 : 0);
};

// Filtered Data
const filteredData = computed(() => {
  return props.monthly.filter((item) => {
    // Case type filter
    const caseTypeMatch =
      !selectedCaseType.value || item.visit_information?.diagnosis === selectedCaseType.value;

    // Age group filter
    const age = getAge(item.personal_information?.birthdate);
    const ageGroupMatch =
      !selectedAgeGroup.value ||
      (selectedAgeGroup.value === "0-18" && age <= 18) ||
      (selectedAgeGroup.value === "19-35" && age >= 19 && age <= 35) ||
      (selectedAgeGroup.value === "36-60" && age >= 36 && age <= 60) ||
      (selectedAgeGroup.value === "60+" && age > 60);

    // Gender filter
    const genderMatch =
      !selectedGender.value || item.personal_information?.sex === selectedGender.value;

    const barangayMatch =
  selectedBarangay.value === "all" ||
  (item.barangay || item.personal_information?.barangay) === selectedBarangay.value;

    return caseTypeMatch && ageGroupMatch && genderMatch && barangayMatch;
  });
});

// Chart Data
const monthlyData = computed(() => {
  const data = Array(12).fill(0); // Initialize an array for 12 months
  filteredData.value.forEach((item) => {
    const month = new Date(item.consultationDate).getMonth();
    data[month] += 1; // Increment case count for the month
  });
  return data;
});

// Chart options
const chartOptions = ref({
  chart: {
    id: "cases-chart",
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
  colors: ["#FF9F40"], // Primary line color
  markers: {
    size: 5,
    colors: "#ffffff",
    strokeColors: "#FF9F40",
    strokeWidth: 2,
  },
  fill: {
    type: "gradient", // Use gradient for mountain-like background
    gradient: {
      shade: "#1C64F2",
      gradientToColors: ["#FFD8B5"], // Gradient transition to a lighter orange
      stops: [0, 100], // Gradient from bottom to top
      opacityFrom: 0.7, // More visible at the top
      opacityTo: 0.1, // Fades at the bottom
    },
  },
  grid: {
    borderColor: "#e7e7e7",
    strokeDashArray: 4,
  },
  tooltip: {
    enabled: true,
    theme: "light",
    y: {
      formatter: (val) => `${val} cases`,
    },
  },
});


// Chart series
const chartSeries = computed(() => [
  {
    name: "Monthly Cases",
    data: monthlyData.value, // Use the filtered data
  },
]);

// Display selected filters as text
const selectedFilters = computed(() => {
  const filters = [];
  if (selectedCaseType.value) filters.push(`Case Type: ${selectedCaseType.value}`);
  if (selectedAgeGroup.value) filters.push(`Age Group: ${selectedAgeGroup.value}`);
  if (selectedGender.value) filters.push(`Gender: ${selectedGender.value}`);
  if (selectedBarangay.value !== "all") filters.push(`Barangay: ${selectedBarangay.value}`)
  return filters.length ? filters.join(" | ") : "No filters applied";
});

// Update the chart when filters change
const updateChart = () => {
  // Reactivity ensures the chart updates automatically
  console.log("Filters updated");
};
</script>

<style scoped>
.chart-container {
  max-width: 100%;
  margin: 20px auto;
  padding: 20px;
  background: #fef9f6; /* Subtle light orange tint */
  border-radius: 12px;
  border: 1px solid #f5d4c3;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Light shadow */
}

.chart-header {
  text-align: center;
  margin-bottom: 20px;
}

.chart-title {
  font-size: 24px;
  font-weight: bold;
  color: #FF9F40; /* Orange for the title */
}

.chart-subtitle {
  font-size: 14px;
  color: #666; /* Subtle gray for context */
  margin-top: 5px;
}

.filters-container {
  display: flex;
  justify-content: space-between;
  gap: 20px;
  margin-bottom: 20px;
}

.filter {
  flex: 1;
}

.filter-label {
  display: block;
  font-size: 14px;
  font-weight: bold;
  color: #333;
  margin-bottom: 5px;
}

select {
  width: 100%;
  padding: 8px 12px;
  border-radius: 8px;
  border: 1px solid #ddd;
  font-size: 14px;
  color: #333;
}

select:focus {
  outline: none;
  border-color: #FF9F40;
}

.cases-chart {
  height: 350px;
}

@media (max-width: 768px) {
  .filters-container {
    flex-direction: column;
  }

  .cases-chart {
    height: 250px;
  }
}
</style>
