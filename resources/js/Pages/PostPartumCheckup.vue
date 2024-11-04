<template>
    <Head title="PostPartum Check Up" />
    <NewLayout>
      <PostpartumForm :onSubmit="submitForm" />
    </NewLayout>
  </template>
  
  <script setup>
  import NewLayout from '@/Layouts/NewLayout.vue';
  import { Head } from '@inertiajs/vue3';
  import PostpartumForm from '@/Components/PostpartumForm.vue';
  import { Inertia } from '@inertiajs/inertia';
  
  function submitForm(form) {
    console.log('Submitting from parent:', form); // Log the form data for debugging
    
    // Post the form data to the backend using Inertia
    return Inertia.post('/prenatal/store', form, {
      onSuccess: () => {
        // Handle success (e.g., show a success message or reset form)
        console.log('Data saved successfully!');
      },
      onError: (errors) => {
        // Handle errors (e.g., show error messages)
        console.error('Form submission errors:', errors);
      },
    });
  }
  </script>
  