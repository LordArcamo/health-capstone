<template>
    <Head title="Patient Records" />
    <NewLayout>
      <ReferredPatient :patients="props.referredPatients" />
    </NewLayout>
  </template>
  
  <script setup>
  import NewLayout from '@/Layouts/MainLayout.vue';
  import { Head } from '@inertiajs/vue3';
 import ReferredPatient from '@/Components/ReferredPatientTable.vue';
  
  const props = defineProps({
    referredPatients: Array, // Declare the checkUps prop coming from the server
  });
  </script>
  