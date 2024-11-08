<script setup>
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
import Modal from '@/Components/WelcomeModal.vue';
import { ref } from 'vue';

const selectedPatient = ref(null); // Store selected patient data here

// Event handler for when a patient is selected
function handlePatientSelected(patient) {
  selectedPatient.value = patient;
}
</script>

<template>
  <NewLayout>
    <Head title="Check Up" />

    <!-- Modal to search and select patient -->
    <Modal @patientSelected="handlePatientSelected" />

    <!-- Display selected patient info -->
    <div v-if="selectedPatient" class="mt-6 p-4 bg-gray-100 rounded shadow">
      <h3 class="text-xl font-bold">Selected Patient</h3>
      <p>ID: {{ selectedPatient.personalId }}</p>
      <p>Last Name: {{ selectedPatient.lastName }}</p>
    </div>
  </NewLayout>
</template>
