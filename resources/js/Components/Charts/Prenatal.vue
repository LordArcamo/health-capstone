<template>
  <div class="chart-container">
    <!-- Header Section -->
    <div class="text-center bg-pink-100 py-6">
      <h1 class="text-2xl font-bold text-pink-700">Prenatal and Postpartum Records</h1>
      <p class="text-sm text-pink-600">{{ selectedFilters }}</p>
    </div>

    <div class="filters-container flex items-center gap-6 py-4">
            <!-- Barangay Filter (NEW) -->
      <div class="filter flex flex-col items-start">
        <label for="barangay-filter" class="filter-label">Barangay:</label>
        <select id="barangay-filter" v-model="selectedBarangay" class="filter-select">
          <option value="all">All Barangays</option>
          <option v-for="barangay in uniqueBarangays" :key="barangay" :value="barangay">
            {{ barangay }}
          </option>
        </select>
      </div>
      <!-- Start Date Filter -->
      <div class="filter flex flex-col items-start">
        <label for="start-date" class="filter-label">Start Date:</label>
        <input type="date" id="start-date" v-model="startDate" class="filter-select" />
      </div>
      
      <!-- End Date Filter -->
      <div class="filter flex flex-col items-start">
        <label for="end-date" class="filter-label">End Date:</label>
        <input type="date" id="end-date" v-model="endDate" class="filter-select" />
      </div>
      <!-- Age Group Filter -->
      <div class="filter flex flex-col items-start">
        <label for="age-group" class="filter-label">Age Group:</label>
        <select id="age-group" v-model="selectedAgeGroup" class="filter-select">
          <option value="all">All Ages</option>
          <option value="0-18">0-18</option>
          <option value="19-35">19-35</option>
          <option value="36-50">36-50</option>
          <option value="50+">50+</option>
        </select>
      </div>
    </div>

    <!-- Chart Section -->
    <apexchart
      type="line"
      :options="chartOptions"
      :series="chartSeries"
      class="prenatal-chart mt-6"
    ></apexchart>
  </div>
  <div>
    <div></div>
  </div>
</template>

<script setup>
import apexchart from "vue3-apexcharts";
import { ref, computed, watch } from "vue";

const props = defineProps({
  prenatal: {
    type: Array,
  },
});

const selectedBarangay = ref("all");
const selectedAgeGroup = ref("all");
const startDate = ref("");
const endDate = ref("");

const ageRanges = {
  "0-18": [0, 18],
  "19-35": [19, 35],
  "36-50": [36, 50],
  "50+": [51, Infinity],
};

const uniqueBarangays = computed(() => {
  const barangays = props.prenatal.map((record) => record.barangay);
  return [...new Set(barangays)].sort();
});

watch(() => props.prenatal, (newValue) => {
  console.log("Prenatal Data from Laravel:", newValue);
}, { immediate: true });


const filteredPrenatal = computed(() => {
  let data = [...props.prenatal];

  if (selectedBarangay.value !== "all") {
    data = data.filter(person => person.barangay === selectedBarangay.value);
  }

  if (selectedAgeGroup.value !== "all") {
    const [minAge, maxAge] = ageRanges[selectedAgeGroup.value];
    data = data.filter(person => person.age >= minAge && person.age <= maxAge);
  }

  if (startDate.value) {
    data = data.filter(record => new Date(record.visit_date) >= new Date(startDate.value));
  }

  if (endDate.value) {
    data = data.filter(record => new Date(record.visit_date) <= new Date(endDate.value));
  }

  return data;
});

const monthlyCounts = computed(() => {
  const counts = {
    January: 0, February: 0, March: 0, April: 0, May: 0, June: 0,
    July: 0, August: 0, September: 0, October: 0, November: 0, December: 0
  };

  filteredPrenatal.value.forEach((record) => {
    if (record.visit_date) {
      const date = new Date(record.visit_date);
      if (!isNaN(date.getTime())) {
        const month = date.toLocaleString("default", { month: "long" });
        if (counts[month] !== undefined) {
          counts[month]++;
        }
      }
    }
  });

  return counts;
});

const chartSeries = computed(() => [{
  name: "Patients",
  data: Object.values(monthlyCounts.value),
}]);

const selectedFilters = computed(() => {
  const filters = [];
  if (selectedBarangay.value !== "all") filters.push(`Barangay: ${selectedBarangay.value}`)
  if (selectedAgeGroup.value !== "all") filters.push(`Age: ${selectedAgeGroup.value}`);
  if (startDate.value) filters.push(`Start Date: ${startDate.value}`);
  if (endDate.value) filters.push(`End Date: ${endDate.value}`);
  return filters.length ? filters.join(" | ") : "No filters applied";
});

const chartOptions = ref({
  chart: {
    id: "prenatal-chart",
    toolbar: { show: true },
    zoom: { enabled: true },
  },
  xaxis: {
    categories: [
      "January", "February", "March", "April", "May", "June",
      "July", "August", "September", "October", "November", "December"
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
  colors: ["#FF69B4"],
  dataLabels: { enabled: false },
  tooltip: {
    enabled: true,
    theme: "light",
    y: {
      formatter: (val) => `${val} patients`,
    },
  },
});
</script>

<style scoped>
.chart-container {
  max-width: 100%;
  margin: 0 auto;
  padding: 20px;
  background-color: #fdf2f8;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.chart-header {
  text-align: center;
  margin-bottom: 20px;
}

.chart-title {
  font-size: 24px;
  font-weight: bold;
  color: #ff69b4;
}

.filters-container {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 16px 0;
}

.filter {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 4px;
}

.filter-label {
  font-size: 14px;
  font-weight: 600;
  color: #333;
}

.filter-select {
  padding: 8px 12px;
  font-size: 14px;
  border-radius: 6px;
  border: 1px solid #ddd;
  width: 180px;
}

.filter-select:focus {
  border-color: #ff69b4;
  outline: none;
  box-shadow: 0 0 4px rgba(255, 105, 180, 0.5);
}

@media (max-width: 768px) {
  .filters-container {
    flex-direction: column;
    gap: 12px;
  }

  .filter-select {
    width: 100%;
  }
}

.prenatal-chart {
  height: 350px;
}
</style>
