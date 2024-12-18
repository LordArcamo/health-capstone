<template>
  <div class="min-h-screen bg-gray-50 text-gray-700">
    <!-- Header Section -->
    <header class="bg-gradient-to-r from-yellow-500 to-orange-500 p-10 text-white shadow-lg">
      <div class="container mx-auto text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-2">Risk Information Program</h1>
        <p class="text-lg md:text-xl">
          Mitigating risks through data-driven insights and proactive management.
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
            Stay Informed and Manage Risks Effectively
          </h2>
          <p class="text-gray-600 text-sm md:text-base">
            Access critical risk information to identify, assess, and respond to potential challenges.
          </p>
        </div>
        <!-- CTA Button -->
        <div>
          <Link href="/services/risk-management">
            <button
              class="inline-flex items-center justify-center bg-yellow-500 text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:bg-yellow-600 hover:shadow-lg transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2"
            >
              View Risk Information
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
            The <strong>Risk Information Program</strong> provides essential tools to help you identify,
            monitor, and manage risks effectively. By leveraging data-driven insights and proactive
            measures, you can reduce vulnerabilities and improve decision-making.
          </p>
          <ul class="space-y-4">
            <li class="flex items-center">
              <span class="inline-block bg-yellow-500 text-white p-2 rounded-full mr-3"> ✔ </span>
              <span class="text-gray-600">Identify potential risks through data analysis.</span>
            </li>
            <li class="flex items-center">
              <span class="inline-block bg-yellow-500 text-white p-2 rounded-full mr-3"> ✔ </span>
              <span class="text-gray-600">Access real-time risk reports and insights.</span>
            </li>
            <li class="flex items-center">
              <span class="inline-block bg-yellow-500 text-white p-2 rounded-full mr-3"> ✔ </span>
              <span class="text-gray-600">Develop strategies for effective risk mitigation.</span>
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
        <h2 class="text-2xl font-semibold text-center mb-8 text-gray-800">Key Features</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <!-- Feature Cards -->
          <div
            class="p-6 bg-gray-100 rounded-lg text-center shadow transition duration-300 ease-in-out hover:bg-yellow-500 hover:text-white"
          >
            <h3 class="text-lg font-bold mb-2">Risk Identification</h3>
            <p class="text-sm">
              Identify potential risks using comprehensive data and analysis.
            </p>
          </div>

          <div
            class="p-6 bg-gray-100 rounded-lg text-center shadow transition duration-300 ease-in-out hover:bg-yellow-500 hover:text-white"
          >
            <h3 class="text-lg font-bold mb-2">Real-Time Monitoring</h3>
            <p class="text-sm">
              Monitor risks in real-time with instant access to critical reports.
            </p>
          </div>

          <div
            class="p-6 bg-gray-100 rounded-lg text-center shadow transition duration-300 ease-in-out hover:bg-yellow-500 hover:text-white"
          >
            <h3 class="text-lg font-bold mb-2">Mitigation Strategies</h3>
            <p class="text-sm">
              Develop effective strategies to manage and reduce risks proactively.
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
