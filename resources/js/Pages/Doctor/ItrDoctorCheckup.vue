<script setup>
import NewLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
import ITRFormDoctor from '@/Components/ITRFormDoctor.vue';
import { Inertia } from '@inertiajs/inertia';
import { ref, watch } from 'vue';

const props = defineProps({
  consultationDetails: {
    type: Object,
    required: false,
    default: () => ({}),
  },
  loggedInUser: { // Define the loggedInUser prop
    type: Object,
    required: true,
  },
});


watch(() => props.consultationDetails, (newVal) => {
  if (!newVal || Object.keys(newVal).length === 0) {
    console.error('No valid personalInfo provided.');
  }
});

function submitForm(payload) {
  console.log("Submitting from parent:", payload);

  Inertia.post("/store/itr", payload, {
    onSuccess: () => {
      alert("Record saved successfully!");
    },
    onError: (errors) => {
      console.error("Error saving record:", errors);
      alert("Failed to save record.");
    },
  });
}
console.log('Parent Component - LoggedInUser:', props.loggedInUser);
</script>

<template>
  <Head title="Individual Treatment Record" />
  <NewLayout>
    <ITRFormDoctor
      v-if="consultationDetails !== undefined"
      :consultationDetails="consultationDetails || {}"
      :loggedInUser="loggedInUser "
      @submitForm="submitForm"
    />
  </NewLayout>
</template>
