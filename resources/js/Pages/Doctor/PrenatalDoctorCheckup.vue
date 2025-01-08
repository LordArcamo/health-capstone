<script setup>
import NewLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrenatalFormDoctor from '@/Components/PrenatalFormDoctor.vue';
import { Inertia } from '@inertiajs/inertia';
import { ref, watch } from 'vue';


const props = defineProps({
    prenatalConsultationDetails: {
      type: Object,
      required: false,
      default: () => ({}), // Default to an empty object
    },
});

watch(() => props.prenatalConsultationDetails, (newVal) => {
  if (!newVal || Object.keys(newVal).length === 0) {
    console.error('No valid personalInfo provided.');
  }
});

function submitForm(payload) {
  console.log("Submitting from parent:", payload);

  Inertia.post("/store/prenatal", payload, {
    onSuccess: () => {
      alert("Record saved successfully!");
    },
    onError: (errors) => {
      console.error("Error saving record:", errors);
      alert("Failed to save record.");
    },
  });
}

</script>

<template>
  <Head title="Prenatal Record" />
  <NewLayout>
    <PrenatalFormDoctor
        v-if="prenatalConsultationDetails !== undefined"
        :prenatalConsultationDetails="prenatalConsultationDetails || {}"
        @submitForm="submitForm"
      />
  </NewLayout>
</template>
