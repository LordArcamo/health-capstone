<script setup>
import NewLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';  // Import Link from Inertia
</script>

<template>
  <NewLayout>
    <Head title="Thank You General Checkup" />
    <div class="flex flex-col justify-center items-center bg-gradient-to-br from-indigo-50 via-blue-50 to-green-100 min-h-screen px-6 py-12">
      <div class="w-full max-w-4xl bg-white shadow-lg rounded-xl overflow-hidden">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-green-500 via-green-500 to-green-400 text-white py-10 px-6">
          <h1 class="text-4xl font-extrabold">🎉 Thank You!</h1>
          <p class="mt-2 text-lg">
            Your <span class="font-semibold capitalize"> General </span> Checkup form has been successfully submitted.
          </p>
        </div>

        <!-- Content Section -->
        <div class="py-8 px-6 md:px-10 text-center">
          <p class="text-gray-700 text-md mb-6">
            You can now navigate to your dashboard, submit another checkup form, or view general checkup records.
          </p>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Dashboard Link -->
            <Link href="/dashboard" class="btn-gradient">Go to Dashboard</Link>
            <!-- Check Again Link -->
            <Link href="/checkup" class="btn-gradient">CheckUp a Patient Again</Link>
            <!-- View Records Link -->
            <Link href="/services/patients/itrtable" class="btn-gradient">View General Checkup</Link>
          </div>
        </div>
      </div>
    </div>
  </NewLayout>
</template>

<style scoped>
/* Buttons with gradient */
.btn-gradient {
  padding: 12px 20px;
  background: linear-gradient(90deg, #34d399, #10b981, #059669);
  color: white;
  font-weight: bold;
  text-transform: uppercase;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  font-size: 0.9rem;
}

.btn-gradient:hover {
  transform: scale(1.05);
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2);
}

/* Gradient background for main container */
.bg-gradient-to-br {
  min-height: 100vh;
  text-align: center;
}

/* Responsive card styling */
.w-full {
  width: 100%;
}

.max-w-4xl {
  max-width: 64rem; /* Adjust to taste */
}

.rounded-xl {
  border-radius: 1rem;
}

/* Content Section */
.text-gray-700 {
  color: #4a5568; /* Slightly darker gray for better contrast */
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
