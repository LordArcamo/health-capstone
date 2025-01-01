<script setup>
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
import TotalPatients from '@/Components/Charts/TotalPatients.vue';
import ReferedPatients from '@/Components/Charts/ReferedPatients.vue';
import Vaccinations from '@/Components/Charts/Vaccinations.vue';
import MentalHealth from '@/Components/Charts/MentalHealth.vue';
import RiskManagement from '@/Components/Charts/RiskManagement.vue';
import Cases from '@/Components/Charts/Cases.vue';
import { ref, onMounted } from 'vue';

const showFilters = ref(false);

const props = defineProps({
  barChart: Array,
  pieChart: Object,
  totalPatients: Number,
  vaccinations: Number,
  cases: Number,
  risk: Number,
  lineChart: Object,
  lineChart2: Object,
  casesData: Array,
});

const totalPatients = ref(props.totalPatients || 0);
const vaccinations = ref(props.vaccinations || 0);
const cases = ref(props.cases || 0);
const risk = ref(props.risk || 0);

const updateStats = (stats) => {
  totalPatients.value = stats.totalPatients || 0;
  vaccinations = ref(props.vaccinations || 0);
  cases = ref(props.cases || 0);
  risk = ref(props.risk || 0);
};

const monthlyData = ref(Array(12).fill(0)); // Initialize with 12 months
if (props.barChart && props.barChart.length === 12) {
  monthlyData.value = props.barChart; // Assign data directly
}

const monthlyVaccination = ref(Array(12).fill(0)); // Default to zero for 12 months
if (props.lineChart && props.lineChart.data) {
  monthlyVaccination.value = props.lineChart.data;
}

const monthlyCases = ref(Array(12).fill(0)); // Initialize with 12 months
if (props.casesData && props.casesData.length === 12) {
  monthlyCases.value = props.casesData;
}

const filters = ref({
  date: '',
  ageRange: '',
  gender: '',
  casesType: '',
});

const dateOptions = [
  { label: 'Last 7 Days', value: '7days' },
  { label: 'Last 30 Days', value: '30days' },
  { label: 'This Year', value: 'year' },
];

const ageRanges = [
  { label: '0-18', value: '0-18' },
  { label: '19-35', value: '19-35' },
  { label: '36-60', value: '36-60' },
  { label: '60+', value: '60+' },
];

const genderOptions = [
  { label: 'Male', value: 'male' },
  { label: 'Female', value: 'female' },
];

const casesTypes = [
  { label: 'Confirmed Cases', value: 'confirmed' },
  { label: 'Recovered Cases', value: 'recovered' },
  { label: 'Deaths', value: 'deaths' },
];

const toggleFilters = () => {
  showFilters.value = !showFilters.value;
};

const applyFilters = () => {
  console.log('Filters applied:', filters.value);
};
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
      <div v-if="showFilters" class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
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
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Cases Type</label>
          <select
            v-model="filters.casesType"
            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
          >
            <option value="" disabled>Select Cases Type</option>
            <option v-for="type in casesTypes" :key="type.value" :value="type.value">
              {{ type.label }}
            </option>
          </select>
        </div>
      </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
      <div class="bg-gradient-to-br from-green-100 to-green-200 p-6 rounded-lg shadow-lg">
        <h2 class="text-lg font-bold text-gray-700">Total Patients</h2>
        <p class="text-2xl font-semibold">{{ totalPatients }}</p>
      </div>
      <div class="bg-gradient-to-br from-blue-100 to-blue-200 p-6 rounded-lg shadow-lg">
        <h2 class="text-lg font-bold text-gray-700">Vaccinations</h2>
        <p class="text-2xl font-semibold">{{ vaccinations }}</p>
      </div>
      <div class="bg-gradient-to-br from-yellow-100 to-yellow-300 p-6 rounded-lg shadow-lg">
        <h2 class="text-lg font-bold text-gray-700">Mental Health Cases</h2>
        <p class="text-2xl font-semibold">{{ cases }}</p>
      </div>
      <div class="bg-gradient-to-br from-red-100 to-red-300 p-6 rounded-lg shadow-lg">
        <h2 class="text-lg font-bold text-gray-700">High-Risk Cases</h2>
        <p class="text-2xl font-semibold">{{ risk }}</p>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
      <div class="bg-white shadow-lg rounded-lg p-6 hover:scale-105 transition-transform">
        <h6 class="text-lg font-bold text-gray-800 mb-4">Total Patients</h6>
        <TotalPatients 
        :filters="filters"
        :monthly-data="monthlyData"
          />
      </div>
      <div class="bg-white shadow-lg rounded-lg p-6 hover:scale-105 transition-transform">
        <h6 class="text-lg font-bold text-gray-800 mb-4">Referred Patients</h6>
        <ReferedPatients 
          :filters="filters"
          :pieChart="props.pieChart"
        />
      </div>
      <div class="bg-white shadow-lg rounded-lg p-6 hover:scale-105 transition-transform">
        <h6 class="text-lg font-bold text-gray-800 mb-4">Vaccination Overview</h6>
        <Vaccinations 
          :filters="filters"
          :vaccination-data="monthlyVaccination"
        />
      </div>
      <div class="bg-white shadow-lg rounded-lg p-6 hover:scale-105 transition-transform">
        <h6 class="text-lg font-bold text-gray-800 mb-4">Cases Overview</h6>
        <Cases
         :filters="filters"
         :cases-data="monthlyCases"
          />
      </div>
      <div class="bg-white shadow-lg rounded-lg p-6 hover:scale-105 transition-transform">
        <h6 class="text-lg font-bold text-gray-800 mb-4">Mental Health</h6>
        <MentalHealth :filters="filters" />
      </div>
      <div class="bg-white shadow-lg rounded-lg p-6 hover:scale-105 transition-transform">
        <h6 class="text-lg font-bold text-gray-800 mb-4">Risk Management</h6>
        <RiskManagement :filters="filters" />
      </div>
    </div>
  </NewLayout>
</template>
