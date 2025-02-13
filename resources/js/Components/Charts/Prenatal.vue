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
          <option v-for="year in availableYears" :key="year" :value="year.toString()">
            {{ year }}
          </option>
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
import { ref, computed, watch } from "vue";

// Define props
const props = defineProps({
  prenatal: {
    type: Array,
  },
});

// Helper functions
const uniquePrenatal = computed(() => {
  return props.prenatal.reduce((acc, current) => {
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
});

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
  const years = props.prenatal
    .map((record) => {
      const date = new Date(record.visit_date);
      return isNaN(date.getFullYear()) ? null : date.getFullYear();
    })
    .filter(year => year !== null);
  return [...new Set(years)].sort((a, b) => a - b);
});

// Compute filtered prenatal data
const filteredPrenatal = computed(() => {
  let data = [...props.prenatal];

  console.log('Filtering data with:', {
    selectedPeriod: selectedPeriod.value,
    availablePeriods: [...new Set(data.map(r => r.period_type))]
  });

  // Exclude "trimester_unknown" from valid filters
  if (selectedPeriod.value !== "all") {
    data = data.filter(record => record.period_type === selectedPeriod.value);
  }

  // Apply age group filter
  if (selectedAgeGroup.value !== "all") {
    const [minAge, maxAge] = ageRanges[selectedAgeGroup.value];
    data = data.filter(person => person.age >= minAge && person.age <= maxAge);
  }

  // Apply year filter
  if (selectedYear.value !== "all") {
    data = data.filter(record => {
      const date = new Date(record.visit_date);
      return date.getFullYear() === parseInt(selectedYear.value);
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

  console.log('Computing monthly counts for filtered data:', filteredPrenatal.value.length, 'records');

  // Iterate through the filtered prenatal data and count occurrences per month
  filteredPrenatal.value.forEach((record) => {
    if (record.visit_date) {
      const date = new Date(record.visit_date);
      if (!isNaN(date.getTime())) {
        const month = date.toLocaleString("default", { month: "long" });
        if (counts[month] !== undefined) {
          counts[month]++;
          console.log(`Added count for ${month}:`, {
            id: record.prenatalConsultationDetailsID,
            period_type: record.period_type,
            visit_date: record.visit_date
          });
        }
      }
    }
  });

  return counts;
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

// Chart Series
const chartSeries = computed(() => {
  return [{
    name: "Patients",
    data: Object.values(monthlyCounts.value),
  }];
});

// Display filters
const selectedFilters = computed(() => {
  const filters = [];
  if (selectedPeriod.value !== "all") {
    if (selectedPeriod.value === "postpartum") {
      filters.push("Period: Postpartum");
    } else if (selectedPeriod.value.startsWith("trimester")) {
      // Extract numeric part safely
      const trimesterNumber = selectedPeriod.value.match(/\d+/)?.[0] || "";
      if (trimesterNumber) {
        filters.push(`Period: ${trimesterNumber}${getOrdinalSuffix(trimesterNumber)} Trimester`);
      } else {
        filters.push(`Period: ${selectedPeriod.value}`); // Fallback if extraction fails
      }
    } else {
      filters.push(`Period: ${selectedPeriod.value}`); // Catch unexpected values
    }
  }
  if (selectedAgeGroup.value !== "all")
    filters.push(`Age: ${selectedAgeGroup.value}`);
  if (selectedYear.value !== "all") 
    filters.push(`Year: ${selectedYear.value}`);
  return filters.length ? filters.join(" | ") : "No filters applied";
});

// Helper function for ordinal suffixes
const getOrdinalSuffix = (num) => {
  const n = parseInt(num);
  return ["st", "nd", "rd"][((n + 90) % 100 - 10) % 10 - 1] || "th";
};

// Watch filters for debugging
watch([selectedPeriod, selectedAgeGroup, selectedYear], ([period, age, year], [oldPeriod]) => {
  if (period !== oldPeriod) {
    console.log('Period filter changed:', {
      from: oldPeriod,
      to: period,
      totalRecords: props.prenatal.length,
      postpartumRecords: props.prenatal.filter(r => r.period_type === 'postpartum').length,
      trimesterRecords: props.prenatal.filter(r => r.trimester).length
    });
  }
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
