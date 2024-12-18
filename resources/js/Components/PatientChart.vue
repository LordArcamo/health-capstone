<template>
  <div class="oten">
    <h2 class="text-xl font-semibold text-gray-700 mb-6">
      Monthly Trending Cases by Diagnosis
    </h2>

    <!-- Chart -->
    <apexchart
      v-if="chartOptions.series.length"
      type="bar"
      height="400"
      :options="chartOptions.options"
      :series="chartOptions.series"
    />
    <div v-else class="text-center text-gray-500">
      No data available to display.
    </div>
  </div>
</template>

<script>
import { defineComponent, computed } from "vue";
import ApexCharts from "vue3-apexcharts";

export default defineComponent({
  components: {
    apexchart: ApexCharts,
  },
  props: {
    nonReferredData: {
      type: Array,
      required: true,
    },
  },
  setup(props) {
    const months = [
      "January", "February", "March", "April", "May",
      "June", "July", "August", "September", "October",
      "November", "December",
    ];

    const trendingCases = computed(() => {
      const data = props.nonReferredData;
      const monthlyTrending = Array(12).fill(null);

      data.forEach((item) => {
        const monthIndex = item.month - 1;
        if (
          !monthlyTrending[monthIndex] ||
          monthlyTrending[monthIndex].case_count < item.case_count
        ) {
          monthlyTrending[monthIndex] = {
            diagnosis: item.diagnosis,
            case_count: item.case_count,
          };
        }
      });

      return monthlyTrending.map((entry, index) => ({
        month: months[index],
        diagnosis: entry ? entry.diagnosis : "No Data",
        case_count: entry ? entry.case_count : 0,
      }));
    });

    const chartOptions = computed(() => {
      const labels = trendingCases.value.map((item) => item.month);
      const data = trendingCases.value.map((item) => (item ? item.case_count : 0));
      const diagnoses = trendingCases.value.map((item) => (item ? item.diagnosis : "No Data"));

      return {
        options: {
          chart: {
            type: "bar",
            height: 400,
            toolbar: {
              show: false,
            },
          },
          plotOptions: {
            bar: {
              horizontal: false,
              columnWidth: "40%", // Thinner bars
              endingShape: "rounded",
            },
          },
          fill: {
            type: "gradient",
            gradient: {
              shade: "light",
              type: "vertical",
              gradientToColors: ["#66fcf1"], // Gradient end color
              stops: [0, 100],
            },
          },
          dataLabels: {
            enabled: false, // Removed labels on bars
          },
          xaxis: {
            categories: labels,
            title: {
              text: "Month",
              style: {
                fontSize: "14px",
                fontWeight: "bold",
              },
            },
          },
          yaxis: {
            title: {
              text: "Number of Cases",
              style: {
                fontSize: "14px",
                fontWeight: "bold",
              },
            },
          },
          tooltip: {
            shared: true,
            intersect: false,
            y: {
              formatter: function (value, { seriesIndex, dataPointIndex }) {
                return `${value} cases (${diagnoses[dataPointIndex]})`;
              },
            },
          },
          colors: ["#1f77b4"], // Start color of gradient
        },
        series: [
          {
            name: "Trending Cases",
            data,
          },
        ],
      };
    });

    return {
      chartOptions,
    };
  },
});
</script>

<style>
/* Add any custom styling if needed */

.oten{
  z-index: 0!important;
}
</style>
