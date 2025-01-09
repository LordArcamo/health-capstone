<template>
  <div class="chart-container">
    <!-- Header Section -->
    <div class="chart-header">
      <h2 class="chart-title">Monthly Vaccinations Overview</h2>
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

<script>
import { ref, computed } from "vue";
import VueApexCharts from "vue3-apexcharts";

export default {
  name: "VaccinationsChart",
  components: {
    apexchart: VueApexCharts,
  },
  setup() {
    // Test data for vaccinations
    const vaccinationData = {
      vaccineTypes: {
        All: [100, 200, 300, 400, 500, 600, 700, 800, 900, 1000, 1100, 1200],
        Pfizer: [50, 100, 150, 200, 250, 300, 350, 400, 450, 500, 550, 600],
        Moderna: [30, 60, 90, 120, 150, 180, 210, 240, 270, 300, 330, 360],
        "Johnson & Johnson": [20, 40, 60, 80, 100, 120, 140, 160, 180, 200, 220, 240],
      },
    };

    // Reactive data for selected vaccine
    const selectedVaccine = ref(""); // Default is "All Vaccines"

    // Available vaccine types
    const vaccineTypes = computed(() => Object.keys(vaccinationData.vaccineTypes));

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

    // Dynamically update the chart data
    const chartSeries = computed(() => [
      {
        name: "Monthly Vaccinations",
        data: selectedVaccine.value
          ? vaccinationData.vaccineTypes[selectedVaccine.value] || Array(12).fill(0)
          : vaccinationData.vaccineTypes["All"],
      },
    ]);

    // Handle chart updates when the vaccine type changes
    const updateChart = () => {
      // Reactivity ensures chart updates automatically
    };

    return {
      vaccinationData,
      selectedVaccine,
      vaccineTypes,
      chartOptions,
      chartSeries,
      updateChart,
    };
  },
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

.filter-container {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 20px;
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
