<template>
  <div class="chart-container">
    <!-- Header Section -->
    <div class="chart-header">
      <h2 class="chart-title">Top 10 Barangays with Highest Cases</h2>
      <p class="chart-subtitle">
        View the top 10 barangays with the highest reported cases.
      </p>
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
import { defineComponent, ref, computed } from "vue";
import VueApexCharts from "vue3-apexcharts";

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

  // Compute chart data dynamically
  const chartSeries = computed(() => [{
    name: "Total Cases",
    data: props.topBarangays.map(b => b.total_cases)
  }]);

  const options = computed(() => ({
    chart: {
        type: "bar",
        toolbar: { show: true },
    },
    xaxis: {
        categories: props.topBarangays.map(b => b.barangay),
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

  return {
    chart,
    isReady,
    options,
    chartSeries,
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