<script setup>
import NewLayout from '@/Layouts/MainLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import ITRForm from '@/Components/ITRForm.vue';
import { Inertia } from '@inertiajs/inertia';
import { ref, watch, onMounted } from 'vue';

const page = usePage();
const props = defineProps({
  personalInfo: {
    type: Object,
    required: false,
    default: () => ({}), // Default to an empty object
  },
  natureOfVisit: {
    type: String,
    default: ''
  },
  doctors: {
    type: Array,
    default: () => []
  }
});

// Log props to verify data
onMounted(() => {
  console.log('IndividualTreatmentRecordCheckup mounted');
  console.log('Page props:', page.props);
  console.log('natureOfVisit prop:', props.natureOfVisit);
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
    <div>
      <ITRForm
        v-if="personalInfo !== undefined" 
        :selectedPatient="personalInfo || {}"
        :natureOfVisit="natureOfVisit"
        :doctors="doctors"
        @submitForm="submitForm"
      />
    </div>
  </NewLayout>
</template>