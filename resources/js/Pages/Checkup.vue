<script setup>
import NewLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
import Modal from '@/Components/WelcomeModal.vue';
import { ref } from 'vue';

// Receive patients from Inertia props
const props = defineProps({
  patients: {
    type: Array,
    required: true
  }
});

// Declare reactive variables


const selectedPatient = ref({});


const emit = defineEmits(['patients-updated', 'patientSelected']);

// Function to handle patient selection from Modal
function handlePatientSelected(patient) {
  selectedPatient.value = patient; // Update the selected patient
}

// Function to handle patient updates in the parent
function updatePatients(newPatients) {
  console.log('Updating patients in parent component');
  emit('patients-updated', newPatients); // Emit updated patients back to the parent
}

// Function to handle form submission from ITRForm
</script>

<template>
  <NewLayout>
    <Head title="Check Up" />

    <!-- Modal for searching and selecting patients -->
    <Modal
      :patients="props.patients"
      @patientSelected="handlePatientSelected"
      @patients-updated="updatePatients"
    />
  </NewLayout>
</template>
