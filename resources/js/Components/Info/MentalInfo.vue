<template>
  <div class="min-h-screen bg-gray-50 text-gray-700">
    <!-- Header Section -->
    <header class="bg-gradient-to-r from-blue-400 to-indigo-500 p-10 text-white shadow-lg">
      <div class="container mx-auto text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-2">Mental Health Program</h1>
        <p class="text-lg md:text-xl">
          Promoting mental well-being through accessible resources and support services.
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
            Take the First Step to Better Mental Health
          </h2>
          <p class="text-gray-600 text-sm md:text-base">
            Access mental health resources and support for individuals and families.
          </p>
        </div>
        <!-- CTA Button -->
        <div>
          <Link href="/services/mental-health">
            <button
              class="inline-flex items-center justify-center bg-blue-500 text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:bg-blue-600 hover:shadow-lg transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2"
            >
              Explore Mental Health Resources
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
            The <strong>Mental Health Program</strong> provides essential tools, resources, and support to
            help individuals and families improve their mental well-being. We focus on building
            awareness, offering counseling, and ensuring access to necessary services.
          </p>
          <ul class="space-y-4">
            <li class="flex items-center">
              <span class="inline-block bg-blue-500 text-white p-2 rounded-full mr-3"> ✔ </span>
              <span class="text-gray-600">Access mental health education and resources.</span>
            </li>
            <li class="flex items-center">
              <span class="inline-block bg-blue-500 text-white p-2 rounded-full mr-3"> ✔ </span>
              <span class="text-gray-600">Find counseling services for individuals and families.</span>
            </li>
            <li class="flex items-center">
              <span class="inline-block bg-blue-500 text-white p-2 rounded-full mr-3"> ✔ </span>
              <span class="text-gray-600">Participate in mental health awareness campaigns.</span>
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
        <h2 class="text-2xl font-semibold mb-8 text-gray-800">Key Features</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <!-- Feature Cards -->
          <div
            class="p-6 bg-gray-100 rounded-lg text-center shadow transition duration-300 ease-in-out hover:bg-blue-500 hover:text-white"
          >
            <h3 class="text-lg font-bold mb-2">Mental Health Education</h3>
            <p class="text-sm">
              Access informative resources to understand and improve mental well-being.
            </p>
          </div>

          <div
            class="p-6 bg-gray-100 rounded-lg text-center shadow transition duration-300 ease-in-out hover:bg-blue-500 hover:text-white"
          >
            <h3 class="text-lg font-bold mb-2">Counseling Services</h3>
            <p class="text-sm">
              Find professional counseling services for individuals and families.
            </p>
          </div>

          <div
            class="p-6 bg-gray-100 rounded-lg text-center shadow transition duration-300 ease-in-out hover:bg-blue-500 hover:text-white"
          >
            <h3 class="text-lg font-bold mb-2">Awareness Campaigns</h3>
            <p class="text-sm">
              Participate in campaigns to build awareness and reduce stigma around mental health.
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
