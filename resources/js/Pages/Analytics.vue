<script setup>
import NewLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
import TotalPatients from '@/Components/Charts/TotalPatients.vue';
import ReferedPatients from '@/Components/Charts/ReferedPatients.vue';
import Vaccinations from '@/Components/Charts/Vaccinations.vue';
import MentalHealth from '@/Components/Charts/MentalHealth.vue';
import RiskManagement from '@/Components/Charts/RiskManagement.vue';
import Cases from '@/Components/Charts/Cases.vue';
import { ref, watch, onMounted, onBeforeUnmount } from 'vue';
import { router } from '@inertiajs/vue3';
import Prenatal from '@/Components/Charts/Prenatal.vue';

const props = defineProps({
  barChart: {
    type: Array,
    default: () => []
  },
  pieChart: {
    type: Object,
    required: true,
    default: () => ({
      referred: 0,
      notReferred: 0
    })
  },
  vaccinenatedPatients: Array,
  allDates: Array,
  totalPatients: Number,
  vaccinations: Number,
  cases: Number,
  monthlyStats: Array,
  risk: Number,
  lineChart: Object,
  lineChart2: Object,
  monthly: Array,
  casesData: {
    type: Object,
    required: true,
    default: () => ({
      cases: Array(12).fill([])
    })
  },
  prenatal: Array,
  mentalHealthStats: {
    type: Object,
    required: true,
    default: () => ({
      labels: [],
      data: []
    })
  }
});
console.log('Dates', props.allDates);
console.log('Vaccine Data', props.vaccinenatedPatients);
console.log('Prenatal Data', props.prenatal);
const showFilters = ref(false);

const summaryStats = ref({
  totalPatients: props.totalPatients || 0,
  vaccinations: props.vaccinations || 0,
  cases: props.cases || 0,
  risk: props.risk || 0
});

const totalPatients = ref(props.totalPatients || 0);
const vaccinations = ref(props.vaccinations || 0);
const cases = ref(props.cases || 0);
const risk = ref(props.risk || 0);

const updateStats = (stats) => {
  summaryStats.value.totalPatients = stats.totalPatients || 0;
  summaryStats.value.vaccinations = stats.vaccinations || 0;
  summaryStats.value.cases = stats.cases || 0;
  summaryStats.value.risk = stats.risk || 0;
};

const monthlyStats = ref(props.barChart ? [...props.barChart] : Array(12).fill(0));
const monthlyVaccination = ref(props.lineChart?.data ? [...props.lineChart.data] : Array(12).fill(0));
const monthlyCases = ref(props.casesData ? [...props.casesData.cases] : Array(12).fill(0));
const referredData = ref(props.pieChart ? {...props.pieChart} : {});

const filters = ref({
  date: '',
  ageRange: '',
  gender: '',
  casesType: ''
});

const dateOptions = [
  { label: 'Last 7 Days', value: '7days' },
  { label: 'Last 30 Days', value: '30days' },
  { label: 'This Year', value: 'year' }
];

const ageRanges = [
  { label: '0-18', value: '0-18' },
  { label: '19-35', value: '19-35' },
  { label: '36-60', value: '36-60' },
  { label: '60+', value: '60+' }
];

const genderOptions = [
  { label: 'Male', value: 'male' },
  { label: 'Female', value: 'female' }
];

const casesTypes = [
  { label: 'Confirmed Cases', value: 'confirmed' },
  { label: 'Recovered Cases', value: 'recovered' },
  { label: 'Deaths', value: 'deaths' }
];

const toggleFilters = () => {
  showFilters.value = !showFilters.value;
};

let debounceTimer;
watch(filters, (newVal) => {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => {
    router.get('/system-analytics', newVal, {
      preserveState: true,
      preserveScroll: true,
      only: ['totalPatients', 'vaccinations', 'cases', 'risk', 'barChart', 'pieChart', 'lineChart', 'casesData', 'mentalHealthStats']
    });
  }, 500); // Debounce by 500ms to prevent infinite calls
}, { deep: true });

// Watch for changes in props and update the summary stats
watch(() => props.totalPatients, (newVal) => {
  summaryStats.value.totalPatients = newVal;
  totalPatients.value = newVal;
}, { immediate: true });

watch(() => props.vaccinations, (newVal) => {
  summaryStats.value.vaccinations = newVal;
  vaccinations.value = newVal;
}, { immediate: true });

watch(() => props.cases, (newVal) => {
  summaryStats.value.cases = newVal;
  cases.value = newVal;
}, { immediate: true });

watch(() => props.risk, (newVal) => {
  summaryStats.value.risk = newVal;
  risk.value = newVal;
}, { immediate: true });

watch(() => props.barChart, (newVal) => {
  monthlyStats.value = newVal ? [...newVal] : Array(12).fill(0);
}, { immediate: false, deep: false });

watch(() => props.pieChart, (newVal) => {
  referredData.value = newVal ? {...newVal} : {};
}, { immediate: false, deep: false });

watch(() => props.lineChart?.data, (newVal) => {
  monthlyVaccination.value = newVal ? [...newVal] : Array(12).fill(0);
}, { immediate: false, deep: false });

watch(() => props.casesData, (newVal) => {
  monthlyCases.value = newVal ? [...newVal.cases] : Array(12).fill(0);
}, { immediate: false, deep: false });

onBeforeUnmount(() => {
  monthlyStats.value = null;
  monthlyVaccination.value = null;
  monthlyCases.value = null;
  referredData.value = null;
});
</script>

<template>
  <Head title="System Analytics" />

  <NewLayout>
    <!-- Header Section -->
    <header class="bg-gradient-to-r from-indigo-600 via-purple-500 to-pink-500 text-white py-10 px-6 shadow-xl rounded-b-lg">
      <h1 class="text-5xl font-extrabold text-center tracking-wide drop-shadow-md">
        Medical System Analytics Dashboard
      </h1>
      <p class="text-lg lg:text-xl text-center mt-4 opacity-90">
        Real-time insights into patient health trends, empowering better decisions.
      </p>
    </header>

    <!-- Summary Cards -->
    <section class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 px-6">
      <div class="bg-gradient-to-br from-green-500 to-green-300 text-white p-8 rounded-lg shadow-xl hover:scale-105 transition-transform">
        <h2 class="text-xl font-semibold">Total Patients</h2>
        <p class="text-4xl font-bold mt-3">{{ summaryStats.totalPatients }}</p>
      </div>
      <div class="bg-gradient-to-br from-blue-500 to-blue-300 text-white p-8 rounded-lg shadow-xl hover:scale-105 transition-transform">
        <h2 class="text-xl font-semibold">Vaccinations</h2>
        <p class="text-4xl font-bold mt-3">{{ summaryStats.vaccinations }}</p>
      </div>
      <div class="bg-gradient-to-br from-yellow-500 to-yellow-300 text-white p-8 rounded-lg shadow-xl hover:scale-105 transition-transform">
        <h2 class="text-xl font-semibold">Mental Health Cases</h2>
        <p class="text-4xl font-bold mt-3">{{ summaryStats.cases }}</p>
      </div>
    </section>

    <!-- Charts Section -->
    <section class="mt-16 px-6">
      <h2 class="text-3xl font-bold text-gray-800 mb-8">Analytics Overview</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-10">
        <div class="hover:-translate-y-1 transition">
          <TotalPatients :filters="filters" :monthly-stats="monthlyStats" />
        </div>
        <div class="hover:-translate-y-1 transition">
          <ReferedPatients :filters="filters" :pie-chart="referredData" />
        </div>
        <div class="bg-white p-8 rounded-lg shadow-lg border-t-4 border-yellow-500 hover:shadow-xl hover:-translate-y-1 transition">
          <h3 class="text-lg font-bold text-gray-700">Vaccinations</h3>
          <Vaccinations :filters="filters" :vaccination-data="monthlyVaccination" :vaccinenatedPatients="vaccinenatedPatients" />
        </div>
        <div class="bg-white p-8 rounded-lg shadow-lg border-t-4 border-red-500 hover:shadow-xl hover:-translate-y-1 transition">
          <h3 class="text-lg font-bold text-gray-700">Cases</h3>
          <Cases :filters="filters" :monthly="monthly" />
        </div>
        <div class="bg-white p-8 rounded-lg shadow-lg border-t-4 border-purple-500 hover:shadow-xl hover:-translate-y-1 transition">
          <h3 class="text-lg font-bold text-gray-700">Prenatal Cases</h3>
          <Prenatal :prenatal="prenatal" />
        </div>
      </div>
    </section>
  </NewLayout>
</template>
