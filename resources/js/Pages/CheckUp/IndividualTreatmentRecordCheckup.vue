<script setup>
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
import ITRForm from '@/Components/ITRForm.vue';
import { Inertia } from '@inertiajs/inertia';

// Function to handle form submission
function submitForm(form) {
  console.log('Submitting from parent:', form);

  // Post the form data to the backend using Inertia
  return Inertia.post('/itr/store', form, {
    onSuccess: () => {
      console.log('Data saved successfully!');
    },
    onError: (errors) => {
      console.error('Form submission errors:', errors);
    },
  });
}
</script>

<template>
  <Head title="Individual Treatment Record Up" />
  <NewLayout>
    <!-- Listen to the submitForm event from the child component -->
    <ITRForm @submitForm="submitForm" />
  </NewLayout>
</template>
