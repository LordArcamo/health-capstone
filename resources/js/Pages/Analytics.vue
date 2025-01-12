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

  totalPatients: Number,
  vaccinations: Number,
  cases: Number,
  risk: Number,
  lineChart: Object,
  lineChart2: Object,
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

const monthlyData = ref(props.barChart ? [...props.barChart] : Array(12).fill(0));
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

watch(filters, (newVal) => {
  router.get('/system-analytics', newVal, {
    preserveState: true,
    preserveScroll: true,
    only: ['totalPatients', 'vaccinations', 'cases', 'risk', 'barChart', 'pieChart', 'lineChart', 'casesData', 'mentalHealthStats']
  });
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
  monthlyData.value = newVal ? [...newVal] : Array(12).fill(0);
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
  monthlyData.value = null;
  monthlyVaccination.value = null;
  monthlyCases.value = null;
  referredData.value = null;
});
</script>

<template>
  <Head title="System Analytics" />

  <NewLayout>
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-purple-500 to-indigo-500 text-white py-6 px-4 lg:px-8 shadow-lg">
      <h1 class="text-4xl font-extrabold text-center">System Analytics Dashboard</h1>
      <p class="text-sm lg:text-base text-center mt-2">
        Monitor, analyze, and understand health trends with real-time insights.
      </p>
    </div>

    <!-- Filters Section -->
    <div class="bg-white shadow-lg rounded-lg p-6 mt-6">
      <div class="flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-700">Filters</h2>
        <button
          @click="toggleFilters"
          class="bg-gradient-to-r from-blue-400 to-blue-600 text-white px-6 py-3 rounded-lg shadow hover:scale-105 transition-transform"
        >
          Toggle Filters
        </button>
      </div>
      <div v-if="showFilters" class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Date Range</label>
          <select
            v-model="filters.date"
            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
          >
            <option value="" disabled>Select Date Range</option>
            <option v-for="option in dateOptions" :key="option.value" :value="option.value">
              {{ option.label }}
            </option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Age Range</label>
          <select
            v-model="filters.ageRange"
            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
          >
            <option value="" disabled>Select Age Range</option>
            <option v-for="range in ageRanges" :key="range.value" :value="range.value">
              {{ range.label }}
            </option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Gender</label>
          <select
            v-model="filters.gender"
            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
          >
            <option value="" disabled>Select Gender</option>
            <option v-for="gender in genderOptions" :key="gender.value" :value="gender.value">
              {{ gender.label }}
            </option>
          </select>
        </div>
      </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
      <div class="bg-gradient-to-br from-green-100 to-green-200 p-6 rounded-lg shadow-lg">
        <h2 class="text-lg font-bold text-gray-700">Total Patients</h2>
        <p class="text-2xl font-semibold">{{ summaryStats.totalPatients }}</p>
      </div>
      <div class="bg-gradient-to-br from-blue-100 to-blue-200 p-6 rounded-lg shadow-lg">
        <h2 class="text-lg font-bold text-gray-700">Vaccinations</h2>
        <p class="text-2xl font-semibold">{{ summaryStats.vaccinations }}</p>
      </div>
      <div class="bg-gradient-to-br from-yellow-100 to-yellow-300 p-6 rounded-lg shadow-lg">
        <h2 class="text-lg font-bold text-gray-700">Mental Health Cases</h2>
        <p class="text-2xl font-semibold">{{ summaryStats.cases }}</p>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 p-10 gap-10 mt-6">
      <div class="hover:scale-105 transition-transform">
        <TotalPatients
        :filters="filters"
        :monthly-data="monthlyData"
          />
      </div>
      <div class=" hover:scale-105 transition-transform">
        <ReferedPatients
          :filters="filters"
          :pie-chart="referredData"
        />
      </div>
      <div class="hover:scale-105 transition-transform">
        <Vaccinations
          :filters="filters"
          :vaccination-data="monthlyVaccination"
        />
      </div>
      <div class="hover:scale-105 transition-transform">
        <Cases
         :filters="filters"
         :cases-data="monthlyCases"
          />
      </div>
      <div class=" hover:scale-105 transition-transform">
        <Prenatal :prenatal="prenatal"/>
      </div>
        </div>
  </NewLayout>
</template>
