<script setup>
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
import Modal from '@/Components/WelcomeModal.vue';
import { ref, defineProps, defineEmits } from 'vue';

// Receive patients from Inertia props
const props = defineProps({
  patients: {
    type: Array,
    required: true
  }
});

// Declare reactive variables
const selectedPatient = ref(null);

// Emit event to update patients in parent component
const emit = defineEmits(['patients-updated', 'patientSelected']);

// Handle patient selection in parent component
function handlePatientSelected(patient) {
  console.log('Selected patient:', patient); // Debugging log
  selectedPatient.value = patient; // Update the selected patient in parent
  emit('patientSelected', patient); // Emit to the parent if needed for further actions
}

// Handle patient updates coming from the child (modal)
function updatePatients(newPatients) {
  console.log('Updating patients in parent component');
  emit('patients-updated', newPatients); // Emit updated patients back to the parent
}
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
