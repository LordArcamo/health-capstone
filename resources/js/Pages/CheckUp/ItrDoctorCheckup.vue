<script setup>
import NewLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
import ITRFormDoctor from '@/Components/ITRFormDoctor.vue';
import { Inertia } from '@inertiajs/inertia';
import { usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';


const page = usePage();
const consultationDetails = page.props.value?.consultationDetails || null;

if (!consultationDetails) {
  console.error('No valid consultationDetails provided:', consultationDetails);
} else {
  console.log('Received consultationDetails:', consultationDetails);
}

function submitForm(payload) {
  console.log("Submitting from parent:", payload);

  if (payload.personalId) {
    // Update existing patient
    Inertia.post("/consultationDetails/store", payload, {
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
    Inertia.post("/consultationDetails/store", payload, {
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
  <Head title="Individual Treatment Record" />
  <NewLayout>
    <ITRFormDoctor
        :consultation-details="consultationDetails"
        @submitForm="submitForm"
      />
  </NewLayout>
</template>
