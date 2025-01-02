<script setup>
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed, ref, onMounted, watch } from 'vue';
import { Link } from '@inertiajs/vue3';

import ShortBox from '@/Components/ShortBox.vue';
import DateCard from '@/Components/DateCard.vue';
import DiseaseCard from '@/Components/DiseaseCard.vue';
import DonutChart from '@/Components/DonutChart.vue';
import PatientChart from '@/Components/PatientChart.vue';
import Logo from "@/Images/RHU Logo.png";


// Props from backend
const props = defineProps({
  auth: Object,
  flash: Object,
  totalPatients: Number,
  patients: Array,
  casesData: [Array, Object],
  nonReferredData: { type: Array, default: () => [] },
});

// Reactive states
const totalPatients = ref(props.totalPatients || 0);
const referredPatients = ref(0);
const patients = ref(props.patients || []);
const casesData = ref([]);

// Normalized cases data
const normalizedCasesData = computed(() => {
  if (Array.isArray(props.casesData)) return props.casesData;
  if (typeof props.casesData === 'object') return Object.values(props.casesData);
  return [];
});

// Update stats from DateCard
const updateStats = (stats) => {
  totalPatients.value = stats.totalPatients || 0;
  referredPatients.value = stats.referredPatients || 0;
  casesData.value = stats.diseaseCounts || [];
};

// Dynamic date
const currentDate = ref('');
const updateDate = () => {
  const options = { year: 'numeric', month: 'long', day: 'numeric', timeZone: 'Asia/Manila' };
  currentDate.value = new Date().toLocaleDateString('en-PH', options);
};

onMounted(() => {
  updateDate();
  setInterval(updateDate, 1000);
});

watch(() => props.casesData, (newCasesData) => {
  casesData.value = newCasesData || [];
});
</script>

<template>
  <Head title="Initao RHU Dashboard" />

  <NewLayout>
    <div>
    <h1>Welcome, {{ auth.user.name }}</h1>
    <p>Your role is: {{ auth.user.role }}</p>
    <div v-if="auth.user.role === 'admin'">
      <p>Admin doctor dashboard content goes here.</p>
    </div>
    <div v-else-if="auth.user.role === 'staff'">
      <p>Staff dashboard content goes here.</p>
    </div>
    <div v-if="flash.success" class="alert alert-success">
      {{ flash.success }}
    </div>
    <div v-if="flash.error" class="alert alert-danger">
      {{ flash.error }}
    </div>
  </div>
    <div class="overflow-y-auto w-full min-h-screen bg-gray-50">
      <!-- Branding Section -->
      <div class="flex items-center justify-between bg-gradient-to-r from-blue-500 to-green-400 px-10 py-6 text-white shadow-md">
        <div>
          <h1 class="text-2xl font-bold">Initao RHU Dashboard</h1>
          <p class="text-sm">Welcome to the Rural Health Unit of Initao, empowering community health with data-driven insights.</p>
        </div>
        <!-- <img :src="Logo" alt="Initao RHU Logo" class="h-16"> -->
      </div>

      <!-- Stats Section -->
   <!-- Stats Section -->
<div class="w-full gap-6 my-10 px-10 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4">
  <!-- Total Patients -->
  <ShortBox class="bg-gradient-to-br from-green-100 to-green-300 text-green-800 hover:shadow-md transition-shadow">
    <div class="flex flex-col items-start gap-2">
      <div class="flex justify-between w-full">
        <h2 class="font-bold text-lg">Total Patients</h2>
        <Link href="/services/patients" class="bg-green-500 text-white rounded px-3 py-1 shadow hover:opacity-90">
          View
        </Link>
      </div>
      <div class="flex items-center">
        <font-awesome-icon :icon="['fas', 'user']" class="mr-2 text-lg text-green-600" />
        <p class="text-2xl font-semibold">{{ totalPatients }}</p>
      </div>
    </div>
  </ShortBox>

  <!-- Date Card -->
  <DateCard class="z-30" :patients="patients" @updateStats="updateStats" />

  <!-- Referred Patients -->
  <ShortBox class="bg-gradient-to-br from-blue-100 to-blue-300 text-blue-800 hover:shadow-md transition-shadow">
    <div class="flex flex-col items-start gap-2">
      <div class="flex justify-between w-full">
        <h2 class="font-bold text-lg">Referred Patients</h2>
        <Link href="/services/patients/referred" class="bg-blue-500 text-white rounded px-3 py-1 shadow hover:opacity-90">
          View
        </Link>
      </div>
      <div class="flex items-center">
        <font-awesome-icon :icon="['fas', 'user']" class="mr-2 text-lg text-blue-600" />
        <p class="text-2xl font-semibold">{{ referredPatients }}</p>
      </div>
    </div>
  </ShortBox>

  <!-- Disease Distribution -->
  <DiseaseCard  class="z-30" :casesData="normalizedCasesData" />
</div>


      <!-- Charts Section -->
      <div class="flex flex-wrap lg:flex-nowrap gap-8 px-10">
         <!-- Patient Chart -->
         <div class="flex-1 z-0 bg-white rounded p-6 shadow hover:shadow-lg transition-shadow border-l-4 border-green-500">
          <PatientChart :nonReferredData="nonReferredData" />
        </div>

        <!-- Donut Chart -->
        <div class="w-full lg:w-1/3 bg-white rounded p-6 shadow hover:shadow-lg transition-shadow border-l-4 border-blue-500">
          <DonutChart :nonReferredData="nonReferredData" />
        </div>
      </div>
    </div>
  </NewLayout>
</template>

<style scoped>
.avatar-icon {
  font-size: 24px;
}

.bg-gradient-to-r {
  background: linear-gradient(to right, var(--tw-gradient-stops));
}
</style>