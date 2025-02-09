<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref, onMounted, watch } from 'vue';

// Reusable Components
import ShortBox from '@/Components/ShortBox.vue';
import DateCard from '@/Components/DateCard.vue';
import StaffDistributionCard from '@/Components/Staff/StaffDistributionCard.vue';
import StaffChart from '@/Components/Staff/StaffChart.vue';
import StaffDonutChart from '@/Components/Staff/StaffDonutChart.vue';

// Example logo import if needed
// import Logo from "@/Images/RHU Logo.png";

// Props from backend
const props = defineProps({
  totalUsers: Number,
  totalPatients: Number,      // if you still want to show total patients
  staffList: Array,           // Detailed staff data
  staffDistributionData: { type: Array, default: () => [] },
  onLeaveStaffData: { type: Array, default: () => [] }, // Example for staff on leave
});

// Reactive states
const totalUsers = ref(props.totalUsers || 0);
console.log('Total Users:', totalUsers.value);
const totalPatients = ref(props.totalPatients || 0);
const staffOnLeave = ref(0);
const activeUsers = ref(props.activeUsers || 0);
const distributionData = ref([]);

// Normalized distribution data
const normalizedDistributionData = computed(() => {
  if (Array.isArray(props.staffDistributionData)) {
    return props.staffDistributionData;
  }
  if (typeof props.staffDistributionData === 'object') {
    return Object.values(props.staffDistributionData);
  }
  return [];
});

// Update stats from DateCard
const updateStats = (stats) => {
  // totalUsers.value = stats.totalUsers || 0;
  staffOnLeave.value = stats.staffOnLeave || 0;
  distributionData.value = stats.distributionData || [];
};

// Example dynamic date
const currentDate = ref('');
const updateDate = () => {
  const options = { year: 'numeric', month: 'long', day: 'numeric', timeZone: 'Asia/Manila' };
  currentDate.value = new Date().toLocaleDateString('en-PH', options);
};

onMounted(() => {
  updateDate();
  setInterval(updateDate, 1000);
});

watch(
  () => props.staffDistributionData,
  (newDistData) => {
    distributionData.value = newDistData || [];
  }
);
</script>

<template>

  <Head title="Initao RHU Staff Dashboard" />

  <MainLayout>
    <!-- Main Wrapper -->
    <div class="w-full min-h-screen bg-gray-50">

      <!-- Branding Section -->
      <header
        class="flex items-center justify-between bg-gradient-to-r from-blue-500 to-green-400 px-10 py-6 text-white shadow-md">
        <div>
          <h1 class="text-2xl font-bold">Initao RHU Admin Dashboard</h1>
          <p class="text-sm">Welcome to the Rural Health Unit of Initao, focusing on staff and community health
            insights.</p>
        </div>
        <!-- Optional Logo
        <img :src="Logo" alt="Initao RHU Logo" class="h-16" />
        -->
      </header>

      <!-- Stats Section -->
      <section class="gap-6 my-10 px-10 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3">

        <!-- Total Staff -->
        <ShortBox
          class="bg-gradient-to-br from-green-100 to-green-300 text-green-800 hover:shadow-md transition-shadow">
          <div class="flex flex-col items-start gap-2">
            <div class="flex justify-between w-full">
              <h2 class="font-bold text-lg">Total Users</h2>
              <Link href="/services/patients" class="bg-green-500 text-white rounded px-3 py-1 shadow hover:opacity-90">
              View
              </Link>
            </div>
            <div class="flex items-center">
              <font-awesome-icon :icon="['fas', 'user']" class="mr-2 text-lg text-green-600" />
              <p class="text-2xl font-semibold">{{ totalUsers }}</p>
            </div>
          </div>
        </ShortBox>

        <!-- Date/Filter Card -->
    

        <!-- Active Users -->
        <ShortBox class="bg-gradient-to-br from-orange-100 to-orange-300 text-orange-800">
          <div class="flex flex-col items-start gap-2">
            <div class="flex justify-between w-full">
              <h2 class="font-bold text-lg">Active Users</h2>
              <Link href="/staff/active" class="bg-orange-500 text-white rounded px-3 py-1 shadow hover:opacity-90">
              View
              </Link>
            </div>
            <div class="flex items-center">
              <i class="fas fa-users text-orange-600 mr-2 text-lg"></i>
              <p class="text-2xl font-semibold">{{ activeUsers }}</p>
            </div>
          </div>
        </ShortBox>



        <!-- Optional: Total Patients -->
        <ShortBox class="bg-gradient-to-br from-purple-100 to-purple-300 text-purple-800">
          <div class="flex flex-col items-start gap-2">
            <div class="flex justify-between w-full">
              <h2 class="font-bold text-lg">Total Patients</h2>
              <Link href="/services/patients"
                class="bg-purple-500 text-white rounded px-3 py-1 shadow hover:opacity-90">
              View
              </Link>
            </div>
            <div class="flex items-center">
              <i class="fas fa-user text-purple-600 mr-2 text-lg"></i>
              <p class="text-2xl font-semibold">{{ totalPatients }}</p>
            </div>
          </div>
        </ShortBox>
      </section>

      <!-- Staff Distribution -->
      <section class="w-full px-10 mb-10">
        <StaffDistributionCard class="z-30" :distributionData="normalizedDistributionData" />
      </section>

      <!-- Charts Section -->
      <section class="flex flex-wrap lg:flex-nowrap gap-8 px-10 pb-10">
        <!-- Staff Chart -->
        <div class="flex-1 bg-white rounded p-6 shadow hover:shadow-lg transition-shadow border-l-4 border-green-500">
          <StaffChart :onLeaveData="onLeaveStaffData" />
        </div>

        <!-- Donut Chart -->
        <div
          class="w-full lg:w-1/3 bg-white rounded p-6 shadow hover:shadow-lg transition-shadow border-l-4 border-blue-500">
          <StaffDonutChart :distributionData="normalizedDistributionData" />
        </div>
      </section>
    </div>
  </MainLayout>
</template>

<style scoped>
/* Example for customizing box styles in one go */
.bg-gradient-to-br {
  transition: box-shadow 0.2s ease-in-out;
}

.bg-gradient-to-br:hover {
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}
</style>
