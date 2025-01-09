<template>
  <div class="chart-container">
    <!-- Header Section -->
    <div class="text-center bg-pink-100 py-6">
      <h1 class="text-2xl font-bold text-pink-700">Prenatal and Postpartum Records</h1>
      <p class="text-sm text-pink-600">{{ selectedFilters }}</p>
    </div>

    <div class="filters-container flex justify-between items-center gap-6 py-4">
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

  <!-- Gender Filter -->
  <div class="filter flex flex-col items-start">
    <label for="gender" class="filter-label">Gender:</label>
    <select id="gender" v-model="selectedGender" class="filter-select">
      <option value="all">All Genders</option>
      <option value="female">Female</option>
      <option value="male">Male</option>
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
</template>

---

### JavaScript

```javascript
<script>
import VueApexCharts from "vue3-apexcharts";
import { ref, computed } from "vue";

export default {
  name: "PrenatalChart",
  components: {
    apexchart: VueApexCharts,
  },
  setup() {
    // Filters
    const selectedPeriod = ref("all");
    const selectedAgeGroup = ref("all");
    const selectedGender = ref("all");

    // Test Data
    const allData = {
      all: [300, 400, 350, 500, 450, 600, 700, 800, 750, 650, 500, 400],
      trimester1: [100, 120, 110, 130, 140, 150, 160, 170, 180, 190, 200, 210],
      trimester2: [80, 90, 100, 110, 120, 130, 140, 150, 160, 170, 180, 190],
      trimester3: [70, 80, 90, 100, 110, 120, 130, 140, 150, 160, 170, 180],
      trimester4: [50, 60, 70, 80, 90, 100, 110, 120, 130, 140, 150, 160],
      trimester5: [40, 50, 60, 70, 80, 90, 100, 110, 120, 130, 140, 150],
      postpartum: [30, 40, 50, 60, 70, 80, 90, 100, 110, 120, 130, 140],
    };

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

    const chartSeries = computed(() => {
      const baseData = allData[selectedPeriod.value] || allData.all;

      return [
        {
          name: "Patients",
          data: baseData.map((value) => {
            if (selectedAgeGroup.value !== "all") value *= 0.9;
            if (selectedGender.value !== "all") value *= 0.8;
            return Math.round(value);
          }),
        },
      ];
    });

    const selectedFilters = computed(() => {
      const filters = [];
      if (selectedPeriod.value !== "all")
        filters.push(`Period: ${selectedPeriod.value}`);
      if (selectedAgeGroup.value !== "all")
        filters.push(`Age: ${selectedAgeGroup.value}`);
      if (selectedGender.value !== "all")
        filters.push(`Gender: ${selectedGender.value}`);
      return filters.length ? filters.join(" | ") : "No filters applied";
    });

    return {
      selectedPeriod,
      selectedAgeGroup,
      selectedGender,
      chartOptions,
      chartSeries,
      selectedFilters,
    };
  },
};
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
  justify-content: space-between;
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
