<template>
  <div class="min-h-screen bg-gray-50 text-gray-700">
    <!-- Header Section -->
    <header class="bg-gradient-to-r from-pink-500 to-purple-500 p-10 text-white shadow-lg">
      <div class="container mx-auto text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-2">Prenatal Care Program</h1>
        <p class="text-lg md:text-xl">
          Supporting mothers and ensuring healthy pregnancies through comprehensive care.
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
            Take Care of Your Pregnancy Today
          </h2>
          <p class="text-gray-600 text-sm md:text-base">
            Access prenatal records, resources, and schedules to ensure the best care for you and
            your baby.
          </p>
        </div>
        <!-- CTA Button -->
        <div>
          <Link href="/services/patients/prenatal-postpartum">
            <button
              class="inline-flex items-center justify-center bg-pink-500 text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:bg-pink-600 hover:shadow-lg transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-pink-400 focus:ring-offset-2"
            >
              View Prenatal Records
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
            The <strong>Prenatal Care Program</strong> focuses on providing expectant mothers with the
            necessary care, resources, and support to ensure a healthy pregnancy. From regular check-ups
            to nutritional guidance, we’re here to support every step of the way.
          </p>
          <ul class="space-y-4">
            <li class="flex items-center">
              <span class="inline-block bg-pink-500 text-white p-2 rounded-full mr-3"> ✔ </span>
              <span class="text-gray-600">Track prenatal visits and health progress.</span>
            </li>
            <li class="flex items-center">
              <span class="inline-block bg-pink-500 text-white p-2 rounded-full mr-3"> ✔ </span>
              <span class="text-gray-600">Access essential resources for maternal health.</span>
            </li>
            <li class="flex items-center">
              <span class="inline-block bg-pink-500 text-white p-2 rounded-full mr-3"> ✔ </span>
              <span class="text-gray-600">Monitor baby’s development and milestones.</span>
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
            class="p-6 bg-gray-100 rounded-lg text-center shadow transition duration-300 ease-in-out hover:bg-pink-500 hover:text-white"
          >
            <h3 class="text-lg font-bold mb-2">Prenatal Check-Ups</h3>
            <p class="text-sm">Schedule and track regular check-ups for a healthy pregnancy.</p>
          </div>

          <div
            class="p-6 bg-gray-100 rounded-lg text-center shadow transition duration-300 ease-in-out hover:bg-pink-500 hover:text-white"
          >
            <h3 class="text-lg font-bold mb-2">Maternal Health Education</h3>
            <p class="text-sm">
              Access resources on nutrition, exercise, and self-care during pregnancy.
            </p>
          </div>

          <div
            class="p-6 bg-gray-100 rounded-lg text-center shadow transition duration-300 ease-in-out hover:bg-pink-500 hover:text-white"
          >
            <h3 class="text-lg font-bold mb-2">Development Tracking</h3>
            <p class="text-sm">
              Monitor your baby’s development milestones throughout pregnancy.
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
