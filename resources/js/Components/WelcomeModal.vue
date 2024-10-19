<template>
    <div class=" flex justify-center items-center min-h-screen bg-gray-100">
      <div class="bg-white rounded-lg shadow-lg py-40 px-20 text-center">
        <h2 class="text-4xl font-bold mb-6">Welcome! <span class="ml-2">👋</span></h2>
        <p class="text-gray-700 mb-6 text-center">What type of Check-Up do we have today?</p>
        <div class="flex justify-center space-x-4">
          <!-- Using Inertia Link component for navigation -->
          <Link :href="route('itr')" class="bg-gradient-to-r from-[#0F8F46] to-[#FED035] text-white font-semibold py-2 px-4 rounded shadow hover:from-green-700 hover:to-yellow-500 transition-colors duration-300">
            Individual Treatment Record
          </Link>
          <Link :href="route('prenatal')" class="bg-gradient-to-r from-[#0F8F46] to-[#FED035] text-white font-semibold py-2 px-4 rounded shadow hover:from-green-700 hover:to-yellow-500 transition-colors duration-300">
            Prenatal & Postpartum Checkup
          </Link>
          <Link :href="route('nationalimmunizationprogram')"  class="bg-gradient-to-r from-[#0F8F46] to-[#FED035] text-white font-semibold py-2 px-4 rounded shadow hover:from-green-700 hover:to-yellow-500 transition-colors duration-300">
            National Immunization Program
          </Link>
        </div>
      </div>
    </div>
  </template>
  
  <script>
import { Link } from '@inertiajs/vue3';
  export default {
    components: {
      Link,
    },
  };
  </script>
  