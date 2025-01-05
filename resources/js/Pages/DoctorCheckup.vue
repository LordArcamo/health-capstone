<script setup>
import NewLayout from '@/Layouts/MainLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import DoctorCheckupModal from '@/Components/DoctorCheckupModal.vue';
import { ref } from 'vue';

// Receive patients from Inertia props
const props = defineProps({
  patients: {
    type: Array,
    required: true,
  },
});

// Declare reactive variables
const selectedPatient = ref(null);

// Emit custom events
const emit = defineEmits(['patientsUpdated', 'patientSelected']);

// Function to handle patient selection from Modal
function handlePatientSelected(patient) {
  if (patient && patient.id) {
    selectedPatient.value = patient; // Update the selected patient
    emit('patientSelected', patient); // Emit the selected patient to the parent
    navigateToCheckup(patient.id); // Navigate to the checkup page
  } else {
    console.warn('Invalid patient data received.');
  }
}

// Function to navigate to the checkup page
function navigateToCheckup(patientId) {
  router.visit(`/checkup/${patientId}`, {
    method: 'get',
    onSuccess: () => console.log(`Navigated to checkup page for patient ID: ${patientId}`),
  });
}

// Function to handle patient updates in the parent
function updatePatients(newPatients) {
  console.log('Updating patients in parent component:', newPatients);
  emit('patientsUpdated', newPatients); // Emit updated patients back to the parent
}
</script>

<template>
  <NewLayout>
    <Head title="Check Up" />

    <!-- Modal for searching and selecting patients -->
    <DoctorCheckupModal
      :patients="props.patients"
      @patientSelected="handlePatientSelected"
      @patients-updated="updatePatients"
    />
  </NewLayout>
</template>
