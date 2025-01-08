<template>
    <Head title="Mental Health Records" />
  
    <NewLayout>
      <div >
        <StaffTable :patients="patients" />
      </div>
    </NewLayout>
  </template>
  
  <script setup>
  import NewLayout from '@/Layouts/MainLayout.vue';
  import { Head } from '@inertiajs/vue3';
 import StaffTable from '@/Components/Staff/StaffTable.vue';
  import MentalHealthSessions from '@/Components/MentalHealthSessions.vue';
  
  defineProps({
    patients: {
      type: Array,
      required: true
    },
  });
  </script>
  