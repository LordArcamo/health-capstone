<script setup>
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
import ITR from '../Components/ITRForm.vue';
import { Inertia } from '@inertiajs/inertia';

function submitForm(form) {
  console.log('Submitting from parent:', form); // Debugging
  Inertia.post('/itr/store', form)
    .then(response => {
      console.log('Response:', response); // Check the response
    })
    .catch(error => {
      console.error('Error during submission:', error); // Log any errors
    });
}

</script>
<template>
  <Head title="Individual Treatment Record Up" />

<NewLayout>
    <ITR :onSubmit="submitForm" /> 
  </NewLayout>
</template>