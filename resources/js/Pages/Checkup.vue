<template>
  <NewLayout>
    <Head title="Check Up" />
    <FormComponent :onSubmit="submitForm" />
  </NewLayout>
</template>

<script setup>
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
import FormComponent from '@/Components/CheckupForm.vue';
import { Inertia } from '@inertiajs/inertia';

function submitForm(form) {
  console.log('Submitting from parent:', form); // Debugging
  Inertia.post('/checkup/store', form)
    .then(response => {
      console.log('Response:', response); // Check the response
    })
    .catch(error => {
      console.error('Error during submission:', error); // Log any errors
    });
}
</script>