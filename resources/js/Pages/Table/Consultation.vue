<template>
    <Head title="Latest Patient Records" />
    <NewLayout>
      <ConsultationTable :patients="props.latestConsultation" />
    </NewLayout>
  </template>
  
  <script setup>
  import NewLayout from '@/Layouts/MainLayout.vue';
  import { Head } from '@inertiajs/vue3';
  import ConsultationTable from '@/Components/ConsultationTable.vue';
  
  const props = defineProps({
    latestConsultation: Array, // Declare the checkUps prop coming from the server
  });
  </script>
  