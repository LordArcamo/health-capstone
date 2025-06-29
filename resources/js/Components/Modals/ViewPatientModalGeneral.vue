<template>
<div
  v-if="showModal && selectedPatient"
  class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 p-4 overflow-auto"
>
  <div
    class="bg-white rounded-lg shadow-lg w-full max-w-4xl max-h-[90vh] p-8 relative overflow-y-auto"
  >
    <!-- Close Button -->
    <button @click="$emit('close')"
      class="absolute top-4 right-4 bg-red-600 text-white rounded-full w-10 h-10 flex items-center justify-center hover:bg-red-700 transition">
      &times;
    </button>

    <!-- Header Section -->
    <div class="border-b pb-4 mb-6 flex items-center gap-4">
      <div class="bg-green-100 text-green-700 rounded-full p-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
      </div>
      <div>
        <h2 class="text-2xl font-bold text-gray-800">Individual Treatment Records</h2>
        <p class="text-gray-600">Details for {{ selectedPatient.firstName }} {{ selectedPatient.middleName || '' }} {{ selectedPatient.lastName }}</p>
      </div>
    </div>

    <!-- Patient Details Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <!-- Column 1: Basic Information -->
      <div>
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Basic Information</h3>
        <ul class="space-y-2">
          <li><strong>Full Name:</strong> {{ selectedPatient.firstName }} {{ selectedPatient.middleName || '' }} {{ selectedPatient.lastName }}</li>
          <li><strong>Suffix:</strong> {{ selectedPatient.suffix || 'N/A' }}</li>
          <li><strong>Address:</strong> {{ selectedPatient.purok }}, {{ selectedPatient.barangay }}</li>
          <li><strong>Age:</strong> {{ selectedPatient.age }}</li>
          <li><strong>Birthday:</strong> {{ selectedPatient.birthdate }}</li>
          <li><strong>Contact:</strong> {{ selectedPatient.contact }}</li>
          <li><strong>Gender:</strong> {{ selectedPatient.sex }}</li>
        </ul>
      </div>

      <!-- Column 2: Consultation Details -->
      <div>
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Consultation Details</h3>
        <ul class="space-y-2">
          <li><strong>Time of Consultation:</strong> {{ selectedPatient.consultationTime }}</li>
          <li><strong>Date of Consultation:</strong> {{ selectedPatient.consultationDate }}</li>
          <li><strong>Mode of Transaction:</strong> {{ selectedPatient.modeOfTransaction }}</li>
          <li><strong>Blood Pressure:</strong> {{ selectedPatient.bloodPressure }}</li>
          <li><strong>Temperature:</strong> {{ selectedPatient.temperature }}</li>
          <li><strong>Height:</strong> {{ selectedPatient.height }}</li>
          <li><strong>Weight:</strong> {{ selectedPatient.weight }}</li>
        </ul>
      </div>

      <!-- Prescription Details -->
      <div class="mt-8">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Prescription Details</h3>
        <div class="bg-gray-50 p-4 rounded-lg">
          <div v-if="selectedPatient.medication" class="space-y-4">
            <div class="p-3 bg-white rounded-md shadow-sm">
              <ul class="space-y-2">
                <li><strong>Medication:</strong> {{ selectedPatient.medication || 'N/A' }}</li>
                <li><strong>Dosage:</strong> {{ selectedPatient.dosage || 'N/A' }}</li>
                <li><strong>Frequency:</strong> {{ selectedPatient.frequency || 'N/A' }}</li>
                <li><strong>Duration:</strong> {{ selectedPatient.duration || 'N/A' }}</li>
                <li><strong>Notes:</strong> {{ selectedPatient.notes || 'N/A' }}</li>
              </ul>
            </div>
          </div>
          <div v-else class="text-gray-500 italic">No prescriptions available</div>
        </div>
      </div>

      <!-- Additional Details -->
      <div class="mt-8">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Additional Details</h3>
        <ul class="space-y-2">
          <li><strong>Name of Attending Provider:</strong> {{ selectedPatient.providerName }}</li>
          <li><strong>Nature of Visit:</strong> {{ selectedPatient.natureOfVisit }}</li>
          <li><strong>Type of Consultation/Purpose of Visit:</strong> {{ selectedPatient.visitType }}</li>
          <li><strong>Chief Complaints:</strong> {{ selectedPatient.chiefComplaints }}</li>
          <li><strong>Diagnosis:</strong> {{ selectedPatient.diagnosis }}</li>
        </ul>
      </div>
    </div>

    <!-- Footer Actions -->
    <div class="mt-6 flex justify-end gap-4">
      <button @click="$emit('close')" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
        Close
      </button>
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
