<script setup>
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
import ITR from '@/Components/ITRForm.vue';
import { Inertia } from '@inertiajs/inertia';

function submitForm(form) {
  console.log('Submitting from parent:', form); // Log the form data for debugging

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
    <!-- Pass the submitForm function as a prop to the child component -->
    <ITR @submitForm="submitForm" /> 
  </NewLayout>
</template>
