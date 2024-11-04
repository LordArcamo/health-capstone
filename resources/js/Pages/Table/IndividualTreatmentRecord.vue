<template>
  <Head title="Patient Records" />
  <NewLayout>
    <PatientTable :personalInfo="props.personal_information" :patients="props.checkUps" />
  </NewLayout>
</template>

<script setup>
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
import PatientTable from '@/Components/PatientTable.vue';

const props = defineProps({
  personal_information: Array,
  checkUps: Array, // Declare the checkUps prop coming from the server
});
</script>
