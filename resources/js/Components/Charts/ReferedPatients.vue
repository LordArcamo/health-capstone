<template>
  <div class="chart-container">
    <!-- Header Section -->
    <div class="chart-header">
      <h2 class="chart-title">Referred Patients Overview</h2>
      <p class="chart-subtitle">
        Analyze total referred patients with comparisons for referred and not referred.
      </p>
    </div>

    <!-- Filter Section -->
    <div class="filter-container">
      <label for="reason-filter" class="filter-label">Reason for Referral:</label>
      <select id="reason-filter" v-model="selectedReason" @change="updateChart">
        <option value="">All Reasons</option>
        <option v-for="reason in reasons" :key="reason" :value="reason">
          {{ reason }}
        </option>
      </select>
    </div>

    <!-- Chart -->
    <apexchart 
      type="bar" 
      :options="chartOptions" 
      :series="chartSeries" 
      class="patients-chart"
    ></apexchart>
  </div>
</template>

<script>
import VueApexCharts from "vue3-apexcharts";

export default {
  name: "ReferredPatientsChart",
  components: {
    apexchart: VueApexCharts,
  },
  props: {
    pieChart: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      selectedReason: "", // Default filter is "All Reasons"
      reasons: ["Cardiology", "Neurology", "Orthopedics", "Pediatrics"], // Example reasons for referral
      chartOptions: {
        chart: {
          id: "referred-patients-bar-chart",
          toolbar: {
            show: true,
          },
          zoom: {
            enabled: false,
          },
          background: "#ffffff",
        },
        xaxis: {
          categories: ["Referred", "Not Referred"],
          labels: {
            style: {
              fontSize: "14px",
              fontWeight: "bold",
              colors: "#333",
            },
          },
        },
        colors: ["#6EC591", "#A4D7A0"], // Different shades of green
        plotOptions: {
          bar: {
            borderRadius: 6,
            horizontal: false,
            columnWidth: "50%",
          },
        },
        dataLabels: {
          enabled: false,
        },
        grid: {
          borderColor: "#e5e5e5",
          strokeDashArray: 4,
        },
        tooltip: {
          enabled: true,
          theme: "light",
          y: {
            formatter: function (val) {
              return val + " patients";
            },
          },
        },
      },
    };
  },
  computed: {
    chartSeries() {
      // Update chart data based on the selected reason
      const referred = this.selectedReason
        ? this.pieChart.reasons[this.selectedReason]?.referred || 0
        : this.pieChart.referred || 0;

      const notReferred = this.selectedReason
        ? this.pieChart.reasons[this.selectedReason]?.notReferred || 0
        : this.pieChart.notReferred || 0;

      return [
        {
          name: "Patients",
          data: [referred, notReferred],
        },
      ];
    },
  },
  methods: {
    updateChart() {
      // Trigger reactivity when the filter changes
    },
  },
};
</script>

<style scoped>
.chart-container {
  max-width: 100%;
  margin: 20px auto;
  padding: 20px;
  background: #f9fdf9; /* Slight green tint for the background */
  border-radius: 12px; /* Rounded corners */
  border: 1px solid #e5e5e5; /* Subtle border */
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Light shadow */
}

.chart-header {
  text-align: center;
  margin-bottom: 20px;
}

.chart-title {
  font-size: 24px;
  font-weight: bold;
  color: #4CAF50; /* Green color for the title */
  margin: 0;
}

.chart-subtitle {
  font-size: 14px;
  color: #666; /* Subtle gray for the subtitle */
  margin: 5px 0 0;
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

.patients-chart {
  height: 350px;
}

@media (max-width: 768px) {
  .patients-chart {
    height: 250px;
  }

  .filter-container {
    flex-direction: column;
    gap: 10px;
  }
}
</style>
