<script setup>
import { ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import Logo from "@/Images/RHU Logo.png";

// State for dropdown visibility
const showingNavigationDropdown = ref(false);
const patientsDropdownOpen = ref(false);
const profileDropdownOpen = ref(false); // State for profile dropdown

// Access current page/component from Inertia
const { component } = usePage();

// Toggles dropdowns based on input
const toggleDropdown = (dropdown) => {
  if (dropdown === "patients") {
    patientsDropdownOpen.value = !patientsDropdownOpen.value;
  } else if (dropdown === "profile") {
    profileDropdownOpen.value = !profileDropdownOpen.value;
  }
};

// Close dropdowns on route/component change
watch(() => component, () => {
  showingNavigationDropdown.value = false;
  patientsDropdownOpen.value = false;
  profileDropdownOpen.value = false; // Close profile dropdown
});
</script>

<template>
  <nav class="bg-white shadow-md border-b border-gray-200">
    <div class=" mx-auto px-6 sm:px-6 lg:px-8">
      <div class="flex justify-between h-20 items-center">
        <!-- Logo Section -->
        <div @click="$inertia.visit('/dashboard')" class="flex items-center space-x-4 cursor-pointer">
          <img :src="Logo" alt="RHU Logo" class="h-20 w-auto" />
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden sm:flex items-center space-x-6">
          <NavLink href="/dashboard" :active="component === 'Dashboard'">
            <font-awesome-icon :icon="['fas', 'home']" class="mr-2" />
            Dashboard
          </NavLink>

          <NavLink href="/checkup" :active="component === 'Checkup'">
            <font-awesome-icon :icon="['fas', 'heartbeat']" class="mr-2" />
            Checkup
          </NavLink>

          <!-- Services Dropdown -->
          <div class="relative">
            <button
              @click="toggleDropdown('patients')"
              class="flex items-center text-sm font-medium text-gray-700 hover:bg-gray-100 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-green-500"
            >
              <font-awesome-icon :icon="['fas', 'clipboard']" class="mr-2" />
              Services
              <font-awesome-icon
                :icon="['fas', 'chevron-down']"
                :class="{ 'rotate-180': patientsDropdownOpen }"
                class="ml-2 transition-transform duration-200"
              />
            </button>

            <!-- Dropdown Menu -->
            <div
              v-if="patientsDropdownOpen"
              class="absolute right-0 mt-2 bg-white shadow-lg rounded-lg border border-gray-200 w-60 z-10"
            >
              <div class="grid grid-cols-1 gap-4 p-4">
                <NavLink
                  href="/services/mental-health"
                  :active="component === 'MentalHealth'"
                  class="block text-gray-700 hover:bg-gray-100 px-2 py-1 rounded"
                >
                  Mental Health
                </NavLink>
                <NavLink
                  href="/services/patients/itrtable"
                  :active="component === 'IndividualTreatmentRecord'"
                  class="block text-gray-700 hover:bg-gray-100 px-2 py-1 rounded"
                >
                  ITR
                </NavLink>
                <NavLink
                  href="/services/patients/prenatal-postpartum"
                  :active="component === 'PrenatalPostpartum'"
                  class="block text-gray-700 hover:bg-gray-100 px-2 py-1 rounded"
                >
                  Prenatal/Postpartum
                </NavLink>
                <NavLink
                  href="/services/patients/epi-records"
                  :active="component === 'EPIRecords'"
                  class="block text-gray-700 hover:bg-gray-100 px-2 py-1 rounded"
                >
                  EPI Records
                </NavLink>
                <NavLink
                  href="/services/vaccination"
                  :active="component === 'Vaccination'"
                  class="block text-gray-700 hover:bg-gray-100 px-2 py-1 rounded"
                >
                  Vaccination
                </NavLink>
                <NavLink
                  href="/services/risk-management"
                  :active="component === 'RiskManagement'"
                  class="block text-gray-700 hover:bg-gray-100 px-2 py-1 rounded"
                >
                  Risk Management
                </NavLink>
              </div>
            </div>
          </div>
          <NavLink href="/system-analytics" :active="component === 'Analytics'">
            <font-awesome-icon :icon="['fas', 'chart-bar']" class="mr-2" />
            System Analytics
          </NavLink>

          <!-- Profile Dropdown -->
          <div class="relative">
            <button
              @click="toggleDropdown('profile')"
              class="flex items-center text-sm font-medium text-gray-700 hover:bg-gray-100 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-green-500"
            >
              <font-awesome-icon :icon="['fas', 'user']" class="mr-2" />
              Profile
              <font-awesome-icon
                :icon="['fas', 'chevron-down']"
                :class="{ 'rotate-180': profileDropdownOpen }"
                class="ml-2 transition-transform duration-200"
              />
            </button>
            <!-- Dropdown Menu -->
            <div
              v-if="profileDropdownOpen"
              class="absolute right-0 mt-2 bg-white shadow-lg rounded-lg border border-gray-200 w- z-10"
            >
              <NavLink
                href="/profile"
                :active="component === 'Profile'"
                class="block text-gray-700 hover:bg-gray-100 px-4 py-2 rounded-t"
              >
                <font-awesome-icon :icon="['fas', 'cog']" class="mr-2" />
                Account Settings
              </NavLink>
              <NavLink
                :href="route('logout')"
                method="post"
                class="block text-gray-700 hover:bg-gray-100 px-4 py-2 rounded-b"
              >
                <font-awesome-icon :icon="['fas', 'sign-out-alt']" class="mr-2" />
                Logout
              </NavLink>
            </div>
          </div>
        </div>

        <!-- Mobile Menu Toggle -->
        <div class="sm:hidden flex items-center">
          <button
            @click="showingNavigationDropdown = !showingNavigationDropdown"
            class="p-2 rounded-md text-gray-500 hover:bg-gray-100 focus:outline-none"
          >
            <font-awesome-icon
              :icon="showingNavigationDropdown ? 'times' : 'bars'"
              class="h-6 w-6"
            />
          </button>
        </div>
      </div>
    </div>
  </nav>
</template>
