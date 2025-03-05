<template>
    <div class="chart-container">
      <!-- Header Section -->
      <div class="chart-header">
        <h2 class="chart-title">Top 10 Barangays with Highest Cases</h2>
        <p class="chart-subtitle">
          View the top 10 barangays with the highest reported cases.
        </p>
      </div>
  
      <!-- Filters Section -->
      <div class="filters-container">
        <div class="filter-item">
          <label for="timeframe" class="filter-label">Timeframe</label>
          <select 
            v-model="selectedTimeframe" 
            id="timeframe" 
            class="filter-select smooth-dropdown"
            @change="updateFilters"
            >
            <option value="today">Today</option>
            <option value="this_week">This Week</option>
            <option value="this_month">This Month</option>
            <option value="this_year">This Year</option>
            </select>
        </div>
      </div>
  
      <!-- Chart -->
      <div v-if="isReady" class="fade-enter-active">
        <apexchart
            ref="chart"
            type="bar"
            :options="options"
            :series="chartSeries"
            class="patients-chart"
        />
        </div>
    </div>
  </template>
  
  <script>
  import { defineComponent, ref, computed, watch } from "vue";
  import VueApexCharts from "vue3-apexcharts";
  import moment from "moment";
  
  export default defineComponent({
    name: "TopBarangayCasesChart",
    components: {
      apexchart: VueApexCharts,
    },
    props: {
        topBarangays: {
        type: Array,
        required: true
      }
    },
    setup(props) {
      const chart = ref(null);
      const isReady = ref(true);
      const selectedTimeframe = ref(props.filters?.timeframe || "this_year");
  
      // Function to filter data based on selected timeframe
      const filteredBarangays = computed(() => {
        const now = moment();
  
        return props.topBarangays.filter(b => {
          const consultationDate = moment(b.consultationDate);
  
          switch (selectedTimeframe.value) {
            case "today":
              return consultationDate.isSame(now, "day");
            case "this_week":
              return consultationDate.isSame(now, "week");
            case "this_month":
              return consultationDate.isSame(now, "month");
            case "this_year":
              return consultationDate.isSame(now, "year");
            default:
              return true;
          }
        });
      });
  
      // Compute chart data dynamically based on filtered data
      const chartSeries = computed(() => [{
        name: "Total Cases",
        data: filteredBarangays.value
          .reduce((acc, curr) => {
            const index = acc.findIndex(b => b.barangay === curr.barangay);
            if (index !== -1) {
              acc[index].total_cases += curr.total_cases;
            } else {
              acc.push({ barangay: curr.barangay, total_cases: curr.total_cases });
            }
            return acc;
          }, [])
          .sort((a, b) => b.total_cases - a.total_cases) // Sort by highest cases
          .slice(0, 10) // Get top 10
          .map(b => b.total_cases)
      }]);

  
      const options = computed(() => ({
        chart: {
          type: "bar",
          toolbar: { show: true },
        },
        xaxis: {
          categories: filteredBarangays.value
            .reduce((acc, curr) => {
              if (!acc.includes(curr.barangay)) acc.push(curr.barangay);
              return acc;
            }, [])
            .slice(0, 10),
          labels: {
            style: {
              fontSize: "12px",
              fontWeight: "bold",
              colors: "#333",
            },
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
        },
        colors: ["#1E90FF"],
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: "60%",
            endingShape: "rounded",
          },
        },
        dataLabels: {
          enabled: false,
        },
        tooltip: {
          enabled: true,
          theme: "light",
          y: {
            formatter: (val) => `${val} cases`,
          },
        },
      }));
  
      const updateFilters = () => {
        isReady.value = false; // Hide chart
        setTimeout(() => {
            isReady.value = true; // Show chart after animation
        }, 500); // Adjust duration for smoother transition
        };
  
  
      watch(() => selectedTimeframe.value, updateFilters);
  
      return {
        chart,
        isReady,
        options,
        chartSeries,
        selectedTimeframe,
        updateFilters,
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
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }
  
  .chart-header {
    text-align: center;
    margin-bottom: 24px;
  }
  
  .chart-title {
    font-size: 24px;
    font-weight: 600;
  }
  
  .chart-subtitle {
    font-size: 14px;
    color: #666;
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
  
  .patients-chart {
    height: 400px;
    margin-top: 16px;
  }

  .fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out;
}

.fade-enter-from, .fade-leave-to {
  opacity: 0;
  transform: translateY(20px);
}

.smooth-dropdown {
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 8px 12px;
  font-size: 14px;
  transition: all 0.3s ease-in-out;
  box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
  cursor: pointer;
  outline: none;
}

.smooth-dropdown:hover {
  box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.15);
}

.smooth-dropdown:focus {
  border-color: #1E90FF;
  box-shadow: 0px 4px 8px rgba(30, 144, 255, 0.3);
}

.smooth-dropdown option {
  padding: 10px;
}
  </style>