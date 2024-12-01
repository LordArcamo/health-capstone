<template>
  <Head title="Prenatal Check Up" />
  <NewLayout>
    
    <PrenatalForm 
      v-if="personalInfo !== undefined" 
      :selectedPatient="personalInfo || {}"
      @submitForm="submitForm" 
    />
  </NewLayout>
</template>

<script setup>
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrenatalForm from '@/Components/PrenatalForm.vue';
import { Inertia } from '@inertiajs/inertia';
import { ref, watch } from 'vue';


const props = defineProps({
  personalInfo: {
      type: Object,
      required: false,
      default: () => ({}), // Default to an empty object
    },
});

watch(() => props.personalInfo, (newVal) => {
  if (!newVal || Object.keys(newVal).length === 0) {
    console.error('No valid personalInfo provided.');
  }
});

function submitForm(payload) {
  console.log('Submitting from parent:', payload);

  Inertia.post('/prenatal/store', payload, {
    onSuccess: (response) => {
      console.log('Data saved successfully!');

      // Check if the server returns a new personalId
      if (response.props.personalId) {
        payload.personalId = response.props.personalId; // Update the payload with the new personalId
        console.log('Updated payload with new personalId:', payload.personalId);
      }

      // Optionally, notify the user or reset the form
      alert('Form submitted successfully!');
    },
    onError: (errors) => {
      console.error('Form submission errors:', errors);
    },
  });
}
</script>
