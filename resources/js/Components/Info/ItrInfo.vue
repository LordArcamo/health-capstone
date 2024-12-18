<template>
  <div class="min-h-screen bg-gray-50 text-gray-700">
    <!-- Header Section -->
    <header class="bg-gradient-to-r from-green-500 to-blue-500 p-10 text-white shadow-lg">
      <div class="container mx-auto text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-2">Individual Treatment Records</h1>
        <p class="text-lg md:text-xl">
          Efficiently manage patient records and generate insightful reports.
        </p>
      </div>
    </header>

    <!-- Standalone Call-to-Action Section -->
    <section class="container mx-auto px-6 lg:px-12 py-8 md:py-12">
      <div class="flex flex-col md:flex-row justify-between items-center bg-white shadow-md rounded-lg p-6 md:p-8">
        <!-- Text Section -->
        <div class="mb-6 md:mb-0 text-center md:text-left">
          <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-2">
            Start Managing Your Records Now
          </h2>
          <p class="text-gray-600 text-sm md:text-base">
            Access the full list of patient records and streamline your operations today.
          </p>
        </div>
        <!-- CTA Button -->
        <div>
          <Link href="/services/patients/itrtable">
          <button
            class="inline-flex items-center justify-center bg-green-500 text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:bg-green-600 hover:shadow-lg transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2">
            View Individual Treatment Records
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
          <h2 class="text-3xl font-semibold mb-6 text-gray-800">About the Service</h2>
          <p class="text-gray-600 leading-relaxed mb-6">
            The <strong>Individual Treatment Record</strong> system simplifies patient data
            management. From quick filtering to generating reports and importing data, our service
            enhances productivity for healthcare providers.
          </p>
          <ul class="space-y-4">
            <li class="flex items-center">
              <span class="inline-block bg-green-500 text-white p-2 rounded-full mr-3"> ✔ </span>
              <span class="text-gray-600">Search and filter patient records quickly.</span>
            </li>
            <li class="flex items-center">
              <span class="inline-block bg-green-500 text-white p-2 rounded-full mr-3"> ✔ </span>
              <span class="text-gray-600">Import CSV files for batch record management.</span>
            </li>
            <li class="flex items-center">
              <span class="inline-block bg-green-500 text-white p-2 rounded-full mr-3"> ✔ </span>
              <span class="text-gray-600">Generate detailed reports for analysis.</span>
            </li>
          </ul>
        </div>

        <!-- Image Section -->
        <div class="flex justify-center">
          <img src="/images/RHU%20Logo.png" alt="Initao RHU Logo" class="w-full max-w-sm rounded-lg shadow-lg" />
        </div>
      </div>
    </section>

    <!-- Feature Section -->
    <section class="bg-white py-12">
      <div class="container mx-auto px-6 lg:px-12">
        <h2 class="text-2xl font-semibold text-center mb-6">Key Features</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <!-- Feature Cards -->
          <div
            class="p-6 bg-white-100 rounded-lg text-center shadow transition duration-300 ease-in-out hover:bg-green-400 hover:text-white">
            <h3 class="text-lg font-bold mb-2">Patient Records</h3>
            <p class="text-sm">
              View, search, and manage individual treatment records seamlessly.
            </p>
          </div>

          <div
            class="p-6 bg-white-100 rounded-lg text-center shadow transition duration-300 ease-in-out hover:bg-green-400 hover:text-white">
            <h3 class="text-lg font-bold mb-2">Report Generation</h3>
            <p class="text-sm">
              Generate customized reports for specific patient visits and diagnosis.
            </p>
          </div>

          <div
            class="p-6 bg-white-100 rounded-lg text-center shadow transition duration-300 ease-in-out hover:bg-green-400 hover:text-white">
            <h3 class="text-lg font-bold mb-2">CSV Import</h3>
            <p class="text-sm">
              Bulk upload patient data for easy record management.
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