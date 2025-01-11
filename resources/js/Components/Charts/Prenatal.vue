<template>
  <div class="chart-container">
    <!-- Header Section -->
    <div class="text-center bg-pink-100 py-6">
      <h1 class="text-2xl font-bold text-pink-700">Prenatal and Postpartum Records</h1>
      <p class="text-sm text-pink-600">{{ selectedFilters }}</p>
    </div>

    <div class="filters-container flex items-center gap-6 py-4">
      <!-- Period Filter -->
      <div class="filter flex flex-col items-start">
        <label for="period-filter" class="filter-label">Period:</label>
        <select id="period-filter" v-model="selectedPeriod" class="filter-select">
          <option value="all">All Periods</option>
          <option value="trimester1">Trimester 1</option>
          <option value="trimester2">Trimester 2</option>
          <option value="trimester3">Trimester 3</option>
          <option value="trimester4">Trimester 4</option>
          <option value="trimester5">Trimester 5</option>
          <option value="postpartum">Postpartum</option>
        </select>
      </div>
      <!-- Year Filter -->
      <div class="filter flex flex-col items-start">
        <label for="year-filter" class="filter-label">Year:</label>
        <select id="year-filter" v-model="selectedYear" class="filter-select">
          <option value="all">All Years</option>
          <option v-for="year in availableYears" :key="year" :value="year">{{ year }}</option>
        </select>
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
    <!--recorfd-->
    <div></div>
  </div>
</template>

<script setup>
import apexchart from "vue3-apexcharts";
import { ref, computed } from "vue";

// Define props
const props = defineProps({
  prenatal: {
    type: Array,
  },
});

// Helper functions
const uniquePrenatal = props.prenatal.reduce((acc, current) => {
  const exists = acc.find(
    (person) =>
      person.firstName === current.firstName &&
      person.lastName === current.lastName &&
      person.middleName === current.middleName
  );

  if (!exists) {
    acc.push(current);
  }
  return acc;
}, []);

const formatDate = (dateString) => {
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
  const date = new Date(dateString);
  const year = date.getFullYear();
  const month = months[date.getMonth()];
  const day = date.getDate();
  return `${month} ${day}, ${year}`;
};

// Filters
const selectedPeriod = ref("all");
const selectedAgeGroup = ref("all");
const selectedYear = ref("all");

// Age group ranges
const ageRanges = {
  "0-18": [0, 18],
  "19-35": [19, 35],
  "36-50": [36, 50],
  "50+": [51, Infinity],
};

// Get all available years from data
const availableYears = computed(() => {
  const years = props.prenatal.map((record) =>
    new Date(record.consultationDate).getFullYear()
  );
  return [...new Set(years)].sort((a, b) => a - b);
});

// Compute filtered prenatal data
const filteredPrenatal = computed(() => {
  let data = uniquePrenatal;

  // Apply age group filter
  if (selectedAgeGroup.value !== "all") {
    const [minAge, maxAge] = ageRanges[selectedAgeGroup.value];
    data = data.filter(
      (person) => person.age >= minAge && person.age <= maxAge
    );
  }

  // Apply year filter
  if (selectedYear.value !== "all") {
    data = data.filter((record) => {
      const recordYear = new Date(record.consultationDate).getFullYear();
      return recordYear === selectedYear.value;
    });
  }

  return data;
});

// Monthly counts based on filtered data
const monthlyCounts = computed(() => {
  const counts = {
    January: 0,
    February: 0,
    March: 0,
    April: 0,
    May: 0,
    June: 0,
    July: 0,
    August: 0,
    September: 0,
    October: 0,
    November: 0,
    December: 0,
  };

  // Iterate through the filtered prenatal data and count occurrences per month
  filteredPrenatal.value.forEach((record) => {
    const date = new Date(record.consultationDate);
    const month = date.toLocaleString("default", { month: "long" }); // Get month name
    if (counts[month] !== undefined) {
      counts[month]++;
    }
  });

  return counts;
});

// Create chart data based on monthly counts
const allData = computed(() => {
  // Extract monthly counts in order
  const countsArray = [
    monthlyCounts.value.January,
    monthlyCounts.value.February,
    monthlyCounts.value.March,
    monthlyCounts.value.April,
    monthlyCounts.value.May,
    monthlyCounts.value.June,
    monthlyCounts.value.July,
    monthlyCounts.value.August,
    monthlyCounts.value.September,
    monthlyCounts.value.October,
    monthlyCounts.value.November,
    monthlyCounts.value.December,
  ];

  return {
    all: countsArray, // Dynamically populate the chart data
  };
});

// Chart Options
const chartOptions = ref({
  chart: {
    id: "prenatal-chart",
    toolbar: { show: true },
    zoom: { enabled: true },
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

// Chart Series
const chartSeries = computed(() => {
  // Get base data or fallback to empty array
  const baseData = allData.value[selectedPeriod.value] || allData.value.all || [];

  return [
    {
      name: "Patients",
      data: baseData,
    },
  ];
});

const selectedFilters = computed(() => {
  const filters = [];
  if (selectedPeriod.value !== "all")
    filters.push(`Period: ${selectedPeriod.value}`);
  if (selectedAgeGroup.value !== "all")
    filters.push(`Age: ${selectedAgeGroup.value}`);
  if (selectedYear.value !== "all") filters.push(`Year: ${selectedYear.value}`);
  return filters.length ? filters.join(" | ") : "No filters applied";
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
