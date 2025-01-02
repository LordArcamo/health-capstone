<template>
    <Head title="Patient Records" />
    <NewLayout>
      <PatientTable :patients="props.totalPatients" />
    </NewLayout>
  </template>
  
  <script setup>
  import NewLayout from '@/Layouts/PersonnelLayout.vue';
  import { Head } from '@inertiajs/vue3';
  import PatientTable from '@/Components/PatientTable.vue';
  
  const props = defineProps({
    totalPatients: Array, // Declare the checkUps prop coming from the server
  });
  </script>
  