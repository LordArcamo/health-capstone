<script setup>
import NewLayout from '@/Layouts/PersonnelLayout.vue';
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
  console.log("Submitting from parent:", payload);

  if (payload.personalId) {
    // Update existing patient
    Inertia.post("/prenatalConstultationDetails/store", payload, {
      onSuccess: () => {
        alert("Existing patient's record updated successfully!");
      },
      onError: (errors) => {
        console.error("Error updating existing patient:", errors);
        alert("Failed to update existing patient's record.");
      },
    });
  } else {
    // Create new patient
    Inertia.post("/prenatalConstultationDetails/store", payload, {
      onSuccess: ({ props }) => {
        if (props.personalId) {
          payload.personalId = props.personalId; // Update payload with the new ID
          console.log("New personalId received:", payload.personalId);
        }
        alert("New patient record added successfully!");
      },
      onError: (errors) => {
        console.error("Error adding new patient:", errors);
        alert("Failed to add new patient record.");
      },
    });
  }
}

</script>


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
