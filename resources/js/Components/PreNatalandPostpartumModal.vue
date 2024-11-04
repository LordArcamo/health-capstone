<script>
import { Link } from '@inertiajs/vue3';

export default {
  components: { Link },
  props: {
    patientId: {
      type: [String, Number],
      required: true,
    },
  },
  methods: {
    closeModal() {
      this.$emit('close');
    },
  },
};
</script>

<style scoped>
/* Optional: Custom modal styles */
</style>

<template>
  <div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="bg-white rounded-lg shadow-lg py-8 px-6 text-center max-w-md w-full relative">

      <h2 class="text-3xl font-bold mb-6">Select Checkup Type</h2>
      <p class="text-gray-700 mb-6 text-lg">Please select the type of checkup for this patient:</p>

      <div class="flex flex-col space-y-4">
        <Link 
          :href="route('prenatal', { patient_id: patientId })"
          class="bg-gradient-to-r from-green-500 to-yellow-500 text-white font-semibold py-3 px-6 rounded shadow hover:from-green-600 hover:to-yellow-600 transition-colors duration-300"
        >
          Prenatal Checkup
        </Link>
        
        <Link 
          :href="route('postpartum', { patient_id: patientId })"
          class="bg-gradient-to-r from-green-500 to-yellow-500 text-white font-semibold py-3 px-6 rounded shadow hover:from-green-600 hover:to-yellow-600 transition-colors duration-300"
        >
          Postpartum Checkup
        </Link>
      </div>
    </div>
  </div>
</template>
