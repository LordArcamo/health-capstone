<template>
    <Head title="Patient Records" />
    <NewLayout>
      <DiseaseTable :patients="props.ITR" />
    </NewLayout>
  </template>
  
  <script setup>
  import NewLayout from '@/Layouts/NewLayout.vue';
  import { Head } from '@inertiajs/vue3';
import DiseaseTable from '@/Components/DiseaseTable.vue';
  
  const props = defineProps({
    ITR: Array, // Declare the checkUps prop coming from the server
  });
  </script>
  