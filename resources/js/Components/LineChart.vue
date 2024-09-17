<template>
    <div class="w-full h-96">
        <Line :data="chartData" :options="chartOptions" />
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
            chartData: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                datasets: [
                    {
                        label: 'Disease A (Male)',
                        data: [30, 45, 50, 70, 40, 60, 80],
                        fill: false,
                        borderColor: '#36A2EB',
                        tension: 0.1,
                        backgroundColor: '#36A2EB',
                        pointBackgroundColor: '#36A2EB',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#36A2EB',
                    },
                    {
                        label: 'Disease A (Female)',
                        data: [50, 35, 55, 65, 70, 85, 95],
                        fill: false,
                        borderColor: '#FF6384',
                        tension: 0.1,
                        backgroundColor: '#FF6384',
                        pointBackgroundColor: '#FF6384',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#FF6384',
                    },
                    {
                        label: 'Disease B (Male)',
                        data: [20, 30, 25, 50, 60, 75, 65],
                        fill: false,
                        borderColor: '#FFCE56',
                        tension: 0.1,
                        backgroundColor: '#FFCE56',
                        pointBackgroundColor: '#FFCE56',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#FFCE56',
                    },
                    {
                        label: 'Disease B (Female)',
                        data: [40, 55, 35, 45, 80, 65, 85],
                        fill: false,
                        borderColor: '#4BC0C0',
                        tension: 0.1,
                        backgroundColor: '#4BC0C0',
                        pointBackgroundColor: '#4BC0C0',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#4BC0C0',
                    },
                ],
            },
            chartOptions: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += `${context.parsed.y}`;
                                }
                                return label;
                            },
                        },
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
};
</script>

<style scoped>
/* Custom styles if needed */
</style>