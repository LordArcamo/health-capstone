<template>
    <div class="">
        <h2 class="text-2xl font-bold text-center mb-4">Disease Cases by Gender and Month</h2>
        
        <!-- Filters for Year and Month -->
        <div class="flex justify-between items-center mb-4">
            <!-- Year Filter -->
            <div class="flex items-center space-x-2">
                <label for="year" class="font-semibold text-gray-700">Year:</label>
                <select v-model="selectedYear" @change="filterData" class="border rounded px-2 py-1">
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
        </div>

        <div class="h-80"> <!-- Fixed height for chart container -->
            <Line :data="filteredChartData" :options="chartOptions" />
        </div>
    </div>
</template>

<script>
import { Line } from 'vue-chartjs';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    LineElement,
    CategoryScale,
    LinearScale,
    PointElement,
} from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement);

export default {
    name: 'LineChart',
    components: { Line },
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
                        label: 'Disease A (Male)',
                        data: [30, 45, 50, 70, 40, 60, 80, 90, 9, 10, 11, 12],
                        fill: true,
                        borderColor: '#36A2EB',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        tension: 0.4,
                    },
                    {
                        label: 'Disease A (Female)',
                        data: [50, 35, 55, 65, 70, 85, 95, 30, 50, 60, 70, 80],
                        fill: true,
                        borderColor: '#FF6384',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        tension: 0.4,
                    },
                    {
                        label: 'Disease B (Female)',
                        data: [50, 35, 55, 65, 70, 85, 95, 30, 50, 60, 70, 1000],
                        fill: true,
                        borderColor: '#FF6389',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        tension: 0.4,
                    },
                ],
                
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
                        title: {
                            display: true,
                            text: 'Months',
                        },
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
    },
};
</script>

<style scoped>
/* Keep chart height consistent without stretching */
.h-80 {
    max-height: 320px;
}
</style>
