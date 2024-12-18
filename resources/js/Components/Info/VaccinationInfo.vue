<template>
  <div class="min-h-screen bg-gray-50 text-gray-700">
    <!-- Header Section -->
    <header class="bg-gradient-to-r from-green-500 to-blue-500 p-10 text-white shadow-lg">
      <div class="container mx-auto text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-2">Vaccination Program</h1>
        <p class="text-lg md:text-xl">
          Empowering communities through safe and timely vaccination services.
        </p>
      </div>
    </header>

    <!-- Standalone Call-to-Action Section -->
    <section class="container mx-auto px-6 lg:px-12 py-8 md:py-12">
      <div
        class="flex flex-col md:flex-row justify-between items-center bg-white shadow-md rounded-lg p-6 md:p-8"
      >
        <!-- Text Section -->
        <div class="mb-6 md:mb-0 text-center md:text-left">
          <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-2">
            Protect Your Health with Vaccinations
          </h2>
          <p class="text-gray-600 text-sm md:text-base">
            View vaccination records and learn about the latest vaccine schedules today.
          </p>
        </div>
        <!-- CTA Button -->
        <div>
          <Link href="/services/vaccination">
            <button
              class="inline-flex items-center justify-center bg-green-500 text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:bg-green-600 hover:shadow-lg transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2"
            >
              View Vaccination Records
            </button>
          </Link>
        </div>
      </div>
    </section>

    <!-- Service Information Section -->
    <section class="container mx-auto my-12 px-6 lg:px-12">
      <div class="grid md:grid-cols-2 gap-12 items-center">
        <!-- Text Content -->
        <div>
          <h2 class="text-3xl font-semibold mb-6 text-gray-800">About the Program</h2>
          <p class="text-gray-600 leading-relaxed mb-6">
            The <strong>Vaccination Program</strong> ensures individuals and communities receive
            timely vaccinations to protect against preventable diseases. We offer access to vaccine
            schedules, records, and essential immunization information.
          </p>
          <ul class="space-y-4">
            <li class="flex items-center">
              <span class="inline-block bg-green-500 text-white p-2 rounded-full mr-3"> ✔ </span>
              <span class="text-gray-600">Access up-to-date vaccination schedules.</span>
            </li>
            <li class="flex items-center">
              <span class="inline-block bg-green-500 text-white p-2 rounded-full mr-3"> ✔ </span>
              <span class="text-gray-600">Track and view individual vaccination records securely.</span>
            </li>
            <li class="flex items-center">
              <span class="inline-block bg-green-500 text-white p-2 rounded-full mr-3"> ✔ </span>
              <span class="text-gray-600">Stay informed about new vaccines and immunization drives.</span>
            </li>
          </ul>
        </div>

        <!-- Image Section -->
        <div class="flex justify-center">
          <img src="/images/RHU%20Logo.png" alt="Initao RHU Logo" class="w-full max-w-sm rounded-lg shadow-lg" />
        </div>
      </div>
    </section>

    <!-- Key Features Section -->
    <section class="bg-white py-12">
      <div class="container mx-auto px-6 lg:px-12 text-center">
        <h2 class="text-2xl font-semibold text-center mb-8">Key Features</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <!-- Feature Cards -->
          <div
            class="p-6 bg-gray-100 rounded-lg text-center shadow transition duration-300 ease-in-out hover:bg-green-500 hover:text-white"
          >
            <h3 class="text-lg font-bold mb-2">Vaccine Schedules</h3>
            <p class="text-sm">Stay updated with the recommended vaccine schedules for all ages.</p>
          </div>

          <div
            class="p-6 bg-gray-100 rounded-lg text-center shadow transition duration-300 ease-in-out hover:bg-green-500 hover:text-white"
          >
            <h3 class="text-lg font-bold mb-2">Record Management</h3>
            <p class="text-sm">Access, manage, and view individual vaccination records securely.</p>
          </div>

          <div
            class="p-6 bg-gray-100 rounded-lg text-center shadow transition duration-300 ease-in-out hover:bg-green-500 hover:text-white"
          >
            <h3 class="text-lg font-bold mb-2">Public Awareness</h3>
            <p class="text-sm">
              Learn about new vaccines, immunization campaigns, and public health initiatives.
            </p>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import Link from "@/Components/NavLink.vue";
</script>

<style scoped></style>
