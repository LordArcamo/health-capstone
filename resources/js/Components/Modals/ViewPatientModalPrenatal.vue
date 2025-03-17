<template>
  <div v-if="showModal && selectedPatient"
    class="fixed inset-0 bg-pink-100 bg-opacity-80 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl h-[90vh] p-8 relative overflow-hidden">
      <!-- Close Button -->
      <button @click="$emit('close')"
        class="absolute top-4 right-4 bg-pink-600 text-white rounded-full w-10 h-10 flex items-center justify-center hover:bg-pink-700 transition">
        &times;
      </button>

      <!-- Scrollable Content -->
      <div class="overflow-y-auto h-full pr-4">
        <!-- Header Section -->
        <div class="border-b pb-4 mb-6 flex items-center gap-4">
          <div class="bg-pink-200 text-pink-600 rounded-full p-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
          </div>
          <div>
            <h2 class="text-2xl font-bold text-pink-600">Prenatal Checkup Details</h2>
            <p class="text-gray-600">Details for {{ selectedPatient.fullName }}</p>
          </div>
        </div>

        <!-- Patient Details Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <!-- Column 1 -->
          <div>
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Basic Information</h3>
            <ul class="space-y-2">
              <li><strong>Full Name:</strong> {{ selectedPatient.firstName }} {{ selectedPatient.middleName || '' }} {{
                selectedPatient.lastName }}</li>
              <li><strong>Address:</strong> {{ selectedPatient.purok }}, {{ selectedPatient.barangay }}</li>
              <li><strong>Age:</strong> {{ selectedPatient.age }}</li>
              <li><strong>Birthday:</strong> {{ selectedPatient.birthdate }}</li>
              <li><strong>Mode of Transaction:</strong> {{ selectedPatient.modeOfTransaction }}</li>
              <li><strong>Date of Consultation:</strong> {{ selectedPatient.consultationDate }}</li>
              <li><strong>Time of Consultation:</strong> {{ selectedPatient.consultationTime }}</li>
            </ul>
          </div>

          <!-- Column 2 -->
          <div>
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Health Details</h3>
            <ul class="space-y-2">
              <li><strong>Blood Pressure:</strong> {{ selectedPatient.bloodPressure }}</li>
              <li><strong>Temperature:</strong> {{ selectedPatient.temperature }}</li>
              <li><strong>Height:</strong> {{ selectedPatient.height }}</li>
              <li><strong>Weight:</strong> {{ selectedPatient.weight }}</li>
              <li><strong>Name of Attending Provider:</strong> {{ selectedPatient.providerName }}</li>
              <li><strong>Name of Spouse:</strong> {{ selectedPatient.nameOfSpouse }}</li>
              <li><strong>Emergency Contact Number:</strong> {{ selectedPatient.emergencyContact }}</li>
            </ul>
          </div>
        </div>

        <!-- Additional Details -->
        <div class="mt-8">
          <h3 class="text-lg font-semibold text-gray-700 mb-4">Reproductive History</h3>
          <ul class="grid grid-cols-1 md:grid-cols-2 gap-2">
            <li><strong>4Ps Member:</strong> {{ selectedPatient.fourMember }}</li>
            <li><strong>Philhealth Status:</strong> {{ selectedPatient.philhealthStatus }}</li>
            <li><strong>Philhealth ID Number:</strong> {{ selectedPatient.philhealthNo }}</li>
            <li><strong>Menarche:</strong> {{ selectedPatient.menarche }}</li>
            <li><strong>Onset of Sexual Intercourse:</strong> {{ selectedPatient.sexualOnset }}</li>
            <li><strong>Period/Duration:</strong> {{ selectedPatient.periodDuration }}</li>
            <li><strong>Birth Control Method:</strong> {{ selectedPatient.birthControl }}</li>
            <li><strong>Interval/Cycle:</strong> {{ selectedPatient.intervalCycle }}</li>
            <li><strong>Menopause:</strong> {{ selectedPatient.menopause }}</li>
            <li><strong>LMP (Last Menstrual Period):</strong> {{ selectedPatient.lmp }}</li>
            <li><strong>EDC (Estimated Date of Confinement):</strong> {{ selectedPatient.edc }}</li>
            <li><strong>Gravidity:</strong> {{ selectedPatient.gravidity }}</li>
            <li><strong>Parity:</strong> {{ selectedPatient.parity }}</li>
            <li><strong>Term:</strong> {{ selectedPatient.term }}</li>
            <li><strong>Preterm:</strong> {{ selectedPatient.preterm }}</li>
            <li><strong>Abortion:</strong> {{ selectedPatient.abortion }}</li>
            <li><strong>Living:</strong> {{ selectedPatient.living }}</li>
          </ul>
        </div>

        <!-- Footer Actions -->
        <div class="mt-6 flex justify-end gap-4">
          <button @click="$emit('close')"
            class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
            Close
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    showModal: {
      type: Boolean,
      required: true,
    },
    selectedPatient: {
      type: Object,
      required: true,
    },
  },
};
</script>

<style scoped>
.modal {
  max-width: 100%;
  margin: auto;
}

button {
  transition: all 0.3s ease;
}

button:hover {
  transform: scale(1.05);
}
</style>
