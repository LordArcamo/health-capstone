<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

// Use the Vue 3 Composition API with 'ref'
const sidebarOpen = ref(false);
const patientsDropdownOpen = ref(false); // Tracks if Patients dropdown is open

// Function to toggle the sidebar
const toggleSidebar = () => {
  sidebarOpen.value = !sidebarOpen.value;
};

// Function to toggle the Patients dropdown
const togglePatientsDropdown = () => {
  patientsDropdownOpen.value = !patientsDropdownOpen.value;
};
</script>

<template>
  <div class="relative flex">
    <!-- Hamburger Menu (visible on mobile) -->
    <button @click="toggleSidebar" class="p-4 block lg:hidden fixed top-0 left-0 z-50">
      <!-- Icon for the menu -->
      <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
      </svg>
    </button>

    <!-- Sidebar (hidden on mobile by default) -->
    <div
      class="fixed top-0 left-0 h-screen w-48 bg-white text-gray-800 p-5 transform transition-transform lg:translate-x-0 lg:block"
      :class="{ '-translate-x-full': !sidebarOpen }">
      <h2 class="text-xl font-bold mb-10">Admin Panel</h2>
      <nav class="flex flex-col space-y-2">
        <Link href="/dashboard" class="block py-2.5 px-4 rounded hover:bg-gray-100 transition duration-200"
          :class="{ 'bg-gray-100': $page.component === 'Dashboard' }">
          Dashboard
        </Link>

        <!-- Patients Dropdown -->
        <div>
          <button @click="togglePatientsDropdown" class="w-full flex justify-between py-2.5 px-4 rounded hover:bg-green-100 hover:text-black transition duration-200">
            <span :class="{ 'font-semibold': $page.component.startsWith('Patients') }">Patients</span>
            <svg :class="{ 'rotate-90': patientsDropdownOpen }" class="w-4 h-4 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>

          <!-- Nested patient records (collapsible) -->
          <div v-if="patientsDropdownOpen" class="pl-6 space-y-2">
            <Link href="/patients/itrtable" class="block py-2.5 px-4 rounded hover:bg-green-100 transition duration-200"
              :class="{ 'bg-gray-100': $page.component === 'IndividualTreatmentRecord' }">
              Individual Treatment Record
            </Link>
            <Link href="/patients/prenatal-postpartum" class="block py-2.5 px-4 rounded hover:bg-green-100 transition duration-200"
              :class="{ 'bg-gray-100': $page.component === 'PrenatalPostpartum' }">
              Prenatal/Postpartum
            </Link>
            <Link href="/patients/epi-records" class="block py-2.5 px-4 rounded hover:bg-green-100 transition duration-200"
              :class="{ 'bg-gray-100': $page.component === 'EPIRecords' }">
              National Immunization Program / EPI Records
            </Link>
          </div>
        </div>

        <Link href="/checkup" class="block py-2.5 px-4 rounded hover:bg-gray-100 transition duration-200"
          :class="{ 'bg-gray-100': $page.component === 'Checkup' }">
          Checkup
        </Link>
        <Link href="/mortality" class="block py-2.5 px-4 rounded hover:bg-gray-100 transition duration-200"
          :class="{ 'bg-gray-100': $page.component === 'Mortality' }">
          Mortality
        </Link>
      </nav>
    </div>

    <!-- Overlay for closing sidebar when it's open -->
    <div v-if="sidebarOpen" @click="toggleSidebar" class="fixed inset-0 bg-black opacity-50 lg:hidden"></div>

    <!-- Main content -->
    <div class="flex-1 p-5 lg:ml-48">
      <slot />
    </div>
  </div>
</template>

<style scoped>
/* Optional sidebar styles */
</style>
