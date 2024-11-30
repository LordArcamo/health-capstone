<template>
  <div>
    <h2 class="text-2xl font-bold text-center mb-4">Trending Disease Cases</h2>
    
    <!-- Filters for Year and Month -->
    <div class="flex justify-between items-center mb-4">
        <!-- Year Filter -->
        <div class="flex items-center space-x-2">
            <label for="year" class="font-semibold text-gray-700">Year:</label>
            <select v-model="selectedYear" @change="filterData" class="border rounded px-6 py-1">
                <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
            </select>
        </div>
        
        <!-- Month Filter -->
        <div class="flex items-center space-x-2">
            <label for="month" class="font-semibold text-gray-700">Month:</label>
            <select v-model="selectedMonth" @change="filterData" class="border rounded px-2 py-1">
                <option value="">All</option>
                <option v-for="(month, index) in months" :key="index" :value="month">{{ month }}</option>
            </select>
        </div>

        <div class="flex gap-1">
          <button @click="downloadChartImage" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
            Download PNG
        </button>
        <button @click="downloadChartData" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700">
            Download CSV
        </button>
        </div>
    </div>

    <div class="h-80 mb-4"> <!-- Fixed height for chart container -->
        <Bar ref="barChart" :data="filteredChartData" :options="chartOptions" />
    </div>
  </div>
</template>
<script>
import { Bar } from 'vue-chartjs';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
} from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

export default {
    name: 'BarChart',
    components: { Bar },
    data() {
        return {
            years: [2022, 2023, 2024],
            months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            selectedYear: 2023,
            selectedMonth: '',
            chartData: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [
    {
        label: 'Bronchitis (Male, 0-18)',
        data: [50, 70, 80, 100, 120, 140, 160, 180, 200, 220, 240, 260],
        backgroundColor: '#FF5733',  // Red
        borderRadius: 10,
    },
    {
        label: 'Dengue X (Female, 0-18)',
        data: [40, 60, 70, 90, 110, 130, 150, 170, 190, 210, 230, 250],
        backgroundColor: '#FF8D1A',  // Orange
        borderRadius: 10,
    },
    {
        label: 'Flu (Male, 19-40)',
        data: [100, 130, 160, 200, 240, 280, 320, 350, 380, 410, 440, 470],
        backgroundColor: '#FFB74D',  // Light Orange
        borderRadius: 10,
    },
    {
        label: 'Pneumonia (Female, 41-60)',
        data: [120, 160, 200, 240, 280, 320, 360, 400, 440, 480, 520, 560],
        backgroundColor: '#F57C00',  // Deep Orange
        borderRadius: 10,
    },
    {
        label: 'Asthma (Male, 60+)',
        data: [80, 100, 120, 150, 180, 210, 240, 270, 300, 330, 360, 400],
        backgroundColor: '#2196F3',  // Blue
        borderRadius: 10,
    },
]

            },
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                },
                scales: {
                    x: {
                        display: false,
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Cases',
                        },
                        beginAtZero: true,
                    },
                },
            },
        };
    },
    computed: {
        filteredChartData() {
            if (this.selectedMonth) {
                const monthIndex = this.months.indexOf(this.selectedMonth);
                return {
                    labels: [this.selectedMonth],
                    datasets: this.chartData.datasets.map(dataset => ({
                        ...dataset,
                        data: [dataset.data[monthIndex]],
                    })),
                };
            }
            return this.chartData;
        },
    },
    methods: {
        filterData() {
            // Triggers data update for the chart based on selected filters
        },
        
        // Download the chart as PNG
        downloadChartImage() {
            const chart = this.$refs.barChart.chart;  // Access the chart instance through .chart
            const imageUrl = chart.toBase64Image();  // Get the PNG base64 image
            const link = document.createElement('a');
            link.href = imageUrl;
            link.download = 'chart.png';  // Download the image as 'chart.png'
            link.click();
        },

        // Download the chart data as CSV
        downloadChartData() {
            const header = ['Month', ...this.chartData.datasets.map(dataset => dataset.label)];
            const rows = this.chartData.labels.map((label, index) => {
                return [label, ...this.chartData.datasets.map(dataset => dataset.data[index])];
            });

            let csvContent = 'data:text/csv;charset=utf-8,' + header.join(',') + '\n';
            rows.forEach(row => {
                csvContent += row.join(',') + '\n';
            });

            const link = document.createElement('a');
            link.href = encodeURI(csvContent);
            link.download = 'chart_data.csv';
            link.click();
        },
    },
};
</script>
