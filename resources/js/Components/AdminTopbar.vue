<script setup>
import { ref, watch, onMounted, onBeforeUnmount } from "vue";
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
    profileDropdownOpen.value = false; // Close other dropdown
  } else if (dropdown === "profile") {
    profileDropdownOpen.value = !profileDropdownOpen.value;
    patientsDropdownOpen.value = false; // Close other dropdown
  }
};

// Close dropdowns when clicking outside
const closeDropdowns = (event) => {
  if (!event.target.closest(".dropdown-menu") && !event.target.closest(".dropdown-button")) {
    patientsDropdownOpen.value = false;
    profileDropdownOpen.value = false;
  }
};

// Close dropdowns on Escape key
const handleKeydown = (event) => {
  if (event.key === "Escape") {
    patientsDropdownOpen.value = false;
    profileDropdownOpen.value = false;
  }
};

// Close dropdowns on route/component change
watch(() => component, () => {
  showingNavigationDropdown.value = false;
  patientsDropdownOpen.value = false;
  profileDropdownOpen.value = false; // Close profile dropdown
});

// Add event listeners on mount
onMounted(() => {
  document.addEventListener("click", closeDropdowns);
  document.addEventListener("keydown", handleKeydown);
});

// Remove event listeners on unmount
onBeforeUnmount(() => {
  document.removeEventListener("click", closeDropdowns);
  document.removeEventListener("keydown", handleKeydown);
});
</script>

<template>
  <nav class="bg-white shadow-md border-b border-gray-200">
    <div class=" mx-auto px-6 sm:px-6 lg:px-8">
      <div class="flex justify-between h-20 items-center">
        <!-- Logo Section -->
        <div @click="$inertia.visit('/dashboard')" class="flex items-center space-x-4 cursor-pointer">
          <img :src="Logo" alt="RHU Logo" class="h-20 w-auto" />
          <h1 class="text-lg font-black">Initao Regional Health Unit</h1>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden sm:flex items-center space-x-6">
          <NavLink href="/dashboard" :active="component === 'Dashboard'">
            <font-awesome-icon :icon="['fas', 'home']" class="mr-2" />
            Dashboard
          </NavLink>

     <!-- Services Dropdown -->
     <div class="relative">
            <button
              @click="toggleDropdown('patients')"
              class="flex items-center text-sm font-medium text-gray-700 hover:bg-gray-100 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-green-500 dropdown-button"
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
              class="absolute right-0 mt-2 bg-white shadow-lg rounded-lg border border-gray-200 w-60 z-50 dropdown-menu"
            >
              <div class="grid grid-cols-1 gap-4 p-4">
                <NavLink
                  href="/mental-health"
                  :active="component === 'MentalHealth'"
                  class="block text-gray-700 hover:bg-gray-100 px-2 py-1 rounded"
                >
                  Mental Health
                </NavLink>
                <NavLink
                  href="/itr-services"
                  :active="component === 'IndividualTreatmentRecord'"
                  class="block text-gray-700 hover:bg-gray-100 px-2 py-1 rounded"
                >
                  Individual Treatment Record
                </NavLink>
                <NavLink
                  href="/prenatal-postpartum-services"
                  :active="component === 'PrenatalPostpartum'"
                  class="block text-gray-700 hover:bg-gray-100 px-2 py-1 rounded"
                >
                  Prenatal Records
                </NavLink>
                <NavLink
                  href="/epi-records-services"
                  :active="component === 'EPIRecords'"
                  class="block text-gray-700 hover:bg-gray-100 px-2 py-1 rounded"
                >
                  National Immunization Records
                </NavLink>
                <NavLink
                  href="/vaccination-services"
                  :active="component === 'Vaccination'"
                  class="block text-gray-700 hover:bg-gray-100 px-2 py-1 rounded"
                >
                  Vaccination
                </NavLink>
                <NavLink
                  href="/risk-management-services"
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

          <NavLink href="/system-analytics" :active="component === 'Analytics'">
            <font-awesome-icon :icon="['fas', 'chart-bar']" class="mr-2" />
            RHU Personnels
          </NavLink>

          <NavLink href="/system-analytics" :active="component === 'Analytics'">
            <font-awesome-icon :icon="['fas', 'chart-bar']" class="mr-2" />
            Register Staff
          </NavLink>

          <!-- Profile Dropdown -->
          <div class="relative">
            <button
              @click="toggleDropdown('profile')"
              class="flex items-center text-sm font-medium text-gray-700 hover:bg-gray-100 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-green-500 dropdown-button"
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
              class="absolute right-0 mt-2 bg-white shadow-lg rounded-lg border border-gray-200 w-full z-10"
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
