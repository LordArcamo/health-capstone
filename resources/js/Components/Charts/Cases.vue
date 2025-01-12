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

  <script>
  import VueApexCharts from "vue3-apexcharts";
  import { ref, computed, watch } from 'vue';

  export default {
    name: "CasesChart",
    components: {
      apexchart: VueApexCharts,
    },
    props: {
      casesData: {
      type: Array,
      required: true,
      default: () => Array(12).fill([])
      }
    },
    setup(props) {
      // Use props
      const rawData = computed(() => props.casesData);

      // Filters
      const selectedCaseType = ref(""); // Default: All Cases
      const selectedAgeGroup = ref(""); // Default: All Ages
      const selectedGender = ref(""); // Default: All Genders

      const caseTypes = ["COVID-19", "Flu", "Dengue"]; // Available case types
      const ageGroups = ["0-18", "19-35", "36-60", "60+"]; // Age groups
      const genders = ["Male", "Female"]; // Gender options

      // Chart options remain unchanged
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
        colors: ["#FF9F40"],
        markers: {
          size: 5,
          colors: "#ffffff",
          strokeColors: "#FF9F40",
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
            formatter: (val) => `${val} cases`,
          },
        },
      });

      // Filter the data based on selected filters
      const filteredData = computed(() => {
        const data = rawData.value;

        if (!data || !data.cases || !Array.isArray(data.cases)) {
          return Array(12).fill(0);
        }

        return data.cases.map(monthData => {
          if (!Array.isArray(monthData)) {
            return 0;
          }

          return monthData.filter(item => {
            if (!item || !item.diagnosis || !item.personalInformation) {
              return false;
            }

            let matches = true;

            // Filter by case type
            if (selectedCaseType.value) {
              matches = matches && item.diagnosis.toLowerCase().includes(selectedCaseType.value.toLowerCase());
            }

            // Filter by gender
            if (selectedGender.value) {
              matches = matches && item.personalInformation.sex === selectedGender.value;
            }

            // Filter by age group
            if (selectedAgeGroup.value) {
              const age = calculateAge(item.personalInformation.birthdate);
              if (selectedAgeGroup.value === '60+') {
                matches = matches && age >= 60;
              } else {
                const [minAge, maxAge] = selectedAgeGroup.value.split('-').map(Number);
                matches = matches && age >= minAge && age <= maxAge;
              }
            }

            return matches;
          }).length;
        });
      });

      // Helper function to calculate age
      const calculateAge = (birthdate) => {
        if (!birthdate) return 0;
        const birth = new Date(birthdate);
        const today = new Date();
        let age = today.getFullYear() - birth.getFullYear();
        const monthDiff = today.getMonth() - birth.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
          age--;
        }
        return age;
      };

      // Compute the chart data based on filtered data
      const chartSeries = computed(() => [{
        name: "Monthly Cases",
        data: filteredData.value
      }]);

      // Display selected filters as text
      const selectedFilters = computed(() => {
        const filters = [];
        if (selectedCaseType.value) filters.push(`Case Type: ${selectedCaseType.value}`);
        if (selectedAgeGroup.value) filters.push(`Age Group: ${selectedAgeGroup.value}`);
        if (selectedGender.value) filters.push(`Gender: ${selectedGender.value}`);
        return filters.length ? filters.join(" | ") : "No filters applied";
      });

      // Watch for filter changes
      watch([selectedCaseType, selectedAgeGroup, selectedGender], () => {
        updateChart();
      });

      // Update the chart when filters change
      const updateChart = () => {
        // The chart will update automatically due to reactivity
      };

      return {
        selectedCaseType,
        selectedAgeGroup,
        selectedGender,
        caseTypes,
        ageGroups,
        genders,
        chartOptions,
        chartSeries,
        selectedFilters,
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

