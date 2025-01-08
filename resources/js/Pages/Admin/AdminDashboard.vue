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
  totalStaff: Number,
  totalPatients: Number,      // if you still want to show total patients
  staffList: Array,           // Detailed staff data
  staffDistributionData: { type: Array, default: () => [] },
  onLeaveStaffData: { type: Array, default: () => [] }, // Example for staff on leave
});

// Reactive states
const totalStaff = ref(props.totalStaff || 0);
const totalPatients = ref(props.totalPatients || 0);
const staffOnLeave = ref(0);
const staffList = ref(props.staffList || []);
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
  totalStaff.value = stats.totalStaff || 0;
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
      <header class="flex items-center justify-between bg-gradient-to-r from-blue-500 to-green-400 px-10 py-6 text-white shadow-md">
        <div>
          <h1 class="text-2xl font-bold">Initao RHU Admin Dashboard</h1>
          <p class="text-sm">Welcome to the Rural Health Unit of Initao, focusing on staff and community health insights.</p>
        </div>
        <!-- Optional Logo
        <img :src="Logo" alt="Initao RHU Logo" class="h-16" />
        -->
      </header>

      <!-- Stats Section -->
      <section class="gap-6 my-10 px-10 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4">

        <!-- Total Staff -->
        <ShortBox class="bg-gradient-to-br from-green-100 to-green-300 text-green-800">
          <div class="flex flex-col items-start gap-2">
            <div class="flex justify-between w-full">
              <h2 class="font-bold text-lg">Total Users</h2>
              <Link href="/staff/all" class="bg-green-500 text-white rounded px-3 py-1 shadow hover:opacity-90">
                View
              </Link>
            </div>
            <div class="flex items-center">
              <i class="fas fa-users text-green-600 mr-2 text-lg"></i>
              <p class="text-2xl font-semibold">{{ totalStaff }}</p>
            </div>
          </div>
        </ShortBox>

        <!-- Date/Filter Card -->
        <DateCard
          class="z-30"
          :staffList="staffList"
          @updateStats="updateStats"
        />

        <!-- Staff On Leave -->
        <ShortBox class="bg-gradient-to-br from-blue-100 to-blue-300 text-blue-800">
          <div class="flex flex-col items-start gap-2">
            <div class="flex justify-between w-full">
              <h2 class="font-bold text-lg">Users On Leave</h2>
              <Link href="/staff/on-leave" class="bg-blue-500 text-white rounded px-3 py-1 shadow hover:opacity-90">
                View
              </Link>
            </div>
            <div class="flex items-center">
              <i class="fas fa-user-clock text-blue-600 mr-2 text-lg"></i>
              <p class="text-2xl font-semibold">{{ staffOnLeave }}</p>
            </div>
          </div>
        </ShortBox>

        <!-- Optional: Total Patients -->
        <ShortBox class="bg-gradient-to-br from-purple-100 to-purple-300 text-purple-800">
          <div class="flex flex-col items-start gap-2">
            <div class="flex justify-between w-full">
              <h2 class="font-bold text-lg">Total Patients</h2>
              <Link href="/services/patients" class="bg-purple-500 text-white rounded px-3 py-1 shadow hover:opacity-90">
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
        <StaffDistributionCard
          class="z-30"
          :distributionData="normalizedDistributionData"
        />
      </section>

      <!-- Charts Section -->
      <section class="flex flex-wrap lg:flex-nowrap gap-8 px-10 pb-10">
        <!-- Staff Chart -->
        <div class="flex-1 bg-white rounded p-6 shadow hover:shadow-lg transition-shadow border-l-4 border-green-500">
          <StaffChart :onLeaveData="onLeaveStaffData" />
        </div>

        <!-- Donut Chart -->
        <div class="w-full lg:w-1/3 bg-white rounded p-6 shadow hover:shadow-lg transition-shadow border-l-4 border-blue-500">
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
