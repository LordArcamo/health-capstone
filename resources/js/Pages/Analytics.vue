<script setup>
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
import TotalPatients from '@/Components/Charts/TotalPatients.vue';
import ReferedPatients from '@/Components/Charts/ReferedPatients.vue';
import Vaccinations from '@/Components/Charts/Vaccinations.vue';
import MentalHealth from '@/Components/Charts/MentalHealth.vue';
import RiskManagement from '@/Components/Charts/RiskManagement.vue';
import Cases from '@/Components/Charts/Cases.vue';
import { reactive } from 'vue';
import { useForm } from "@inertiajs/inertia-vue3";

// Filters
const filters = reactive({
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

// Initialize the form with filters
const form = useForm({
  date: '',
  ageRange: '',
  gender: '',
  casesType: '',
});

const applyFilters = () => {
  form.date = filters.date;
  form.ageRange = filters.ageRange;
  form.gender = filters.gender;
  form.casesType = filters.casesType;

  form.get(route('system-analytics')); // Replace with your Laravel route name
};

const generateReport = () => {
  console.log('Generating Report with Filters:', filters);
};
</script>

<template>
  <Head title="System Analytics" />

  <NewLayout>
    <div class="mt-8 mx-8 space-y-8">
      <!-- Header Section -->
      <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">System Analytics Dashboard</h1>
        <button @click="generateReport"
          class="bg-blue-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-blue-700 transition">
          Generate Report
        </button>
      </div>

      <!-- Filters Section -->
      <div class="bg-white shadow-lg rounded-lg p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Date Range -->
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-2">Date Range</label>
          <select v-model="filters.date"
            class="w-full border-gray-300 rounded-lg shadow focus:ring-blue-500 focus:border-blue-500">
            <option value="" disabled>Select Date Range</option>
            <option v-for="option in dateOptions" :key="option.value" :value="option.value">
              {{ option.label }}
            </option>
          </select>
        </div>

        <!-- Age Range -->
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-2">Age Range</label>
          <select v-model="filters.ageRange"
            class="w-full border-gray-300 rounded-lg shadow focus:ring-blue-500 focus:border-blue-500">
            <option value="" disabled>Select Age Range</option>
            <option v-for="range in ageRanges" :key="range.value" :value="range.value">
              {{ range.label }}
            </option>
          </select>
        </div>

        <!-- Gender -->
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-2">Gender</label>
          <select v-model="filters.gender"
            class="w-full border-gray-300 rounded-lg shadow focus:ring-blue-500 focus:border-blue-500">
            <option value="" disabled>Select Gender</option>
            <option v-for="gender in genderOptions" :key="gender.value" :value="gender.value">
              {{ gender.label }}
            </option>
          </select>
        </div>

        <!-- Cases Type -->
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-2">Cases Type</label>
          <select v-model="filters.casesType"
            class="w-full border-gray-300 rounded-lg shadow focus:ring-blue-500 focus:border-blue-500">
            <option value="" disabled>Select Cases Type</option>
            <option v-for="type in casesTypes" :key="type.value" :value="type.value">
              {{ type.label }}
            </option>
          </select>
        </div>

        <!-- Apply Filters -->
        <div class="flex items-end">
          <button @click="applyFilters"
            class="bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-green-700 transition w-full">
            Apply Filters
          </button>
        </div>
      </div>

      <!-- Charts Section -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white shadow-lg rounded-lg p-6 col-span-2">
          <h6 class="text-xl font-semibold mb-4 text-gray-800">Total Patients</h6>
          <TotalPatients :filters="filters" />
        </div>

        <div class="bg-white shadow-lg rounded-lg p-6">
          <h6 class="text-xl font-semibold mb-4 text-gray-800">Referred Patients</h6>
          <ReferedPatients :filters="filters" />
        </div>

        <div class="bg-white shadow-lg rounded-lg p-6 col-span-3">
          <h6 class="text-xl font-semibold mb-4 text-gray-800">Vaccinations</h6>
          <Vaccinations :filters="filters" />
        </div>

        <div class="bg-white shadow-lg rounded-lg p-6 col-span-2">
          <h6 class="text-xl font-semibold mb-4 text-gray-800">Cases</h6>
          <Cases :filters="filters" />
        </div>

        <div class="bg-white shadow-lg rounded-lg p-6">
          <h6 class="text-xl font-semibold mb-4 text-gray-800">Mental Health Cases</h6>
          <MentalHealth :filters="filters" />
        </div>

        <div class="bg-white shadow-lg rounded-lg p-6 col-span-3">
          <h6 class="text-xl font-semibold mb-4 text-gray-800">Risk Management</h6>
          <RiskManagement :filters="filters" />
        </div>
      </div>
    </div>
  </NewLayout>
</template>
