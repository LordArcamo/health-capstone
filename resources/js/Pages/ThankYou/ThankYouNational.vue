<script setup>
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <NewLayout>
    <Head title="Thank You National Immunization Checkup" />
    <div class="flex flex-col justify-center items-center bg-gradient-to-br from-teal-50 via-teal-100 to-blue-100 min-h-screen px-6 py-12">
      <div class="w-full max-w-4xl bg-white shadow-lg rounded-xl overflow-hidden">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-teal-400 via-teal-500 to-blue-400 text-white py-10 px-6">
          <h1 class="text-4xl font-extrabold">💉 Thank You!</h1>
          <p class="mt-2 text-lg">
            Your <span class="font-semibold capitalize"> National Immunization </span>Checkup form has been successfully submitted.
          </p>
        </div>

        <!-- Content Section -->
        <div class="py-8 px-6 md:px-10 text-center">
          <p class="text-gray-700 text-md mb-6">
            You can now navigate to your dashboard, submit another checkup form, or view national immunization records.
          </p>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Dashboard Link -->
            <Link href="/dashboard" class="btn-teal-gradient flex items-center justify-center">Go to Dashboard</Link>
            <!-- Check Again Link -->
            <Link href="/checkup" class="btn-teal-gradient flex items-center justify-center">Checkup Another Patient</Link>
            <!-- View Records Link -->
            <Link href="/services/patients/epi-records" class="btn-teal-gradient flex items-center justify-center">View National Immunization Records</Link>
          </div>
        </div>
      </div>
    </div>
  </NewLayout>
</template>

<style scoped>
/* Buttons with teal gradient */
.btn-teal-gradient {
  padding: 12px 20px;
  background: linear-gradient(90deg, #14b8a6, #0d9488, #0f766e);
  color: white;
  font-weight: bold;
  text-transform: uppercase;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  font-size: 0.9rem;
}

.btn-teal-gradient:hover {
  transform: scale(1.05);
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2);
}

/* Gradient background for main container */
.bg-gradient-to-br {
  min-height: 100vh;
  text-align:center;
}

/* Responsive card styling */
.w-full {
  width: 100%;
}

.max-w-4xl {
  max-width: 64rem;
}

.rounded-xl {
  border-radius: 1rem;
}

/* Content Section */
.text-gray-700 {
  color: #4a5568;
}

.grid {
  display: grid;
  gap: 1rem;
}

@media (min-width: 768px) {
  .grid {
    grid-template-columns: repeat(3, 1fr);
  }
}
</style>
