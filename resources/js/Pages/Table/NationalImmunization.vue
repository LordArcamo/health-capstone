<template>
  <Head title="National Immunization" />

  <NewLayout>
    <NationalImmunizationTable :personalInfo="props.personal_information" :patients = "props.Immunization" />
  </NewLayout>
</template>

<script setup>
import NationalImmunizationTable from '@/Components/NationalImmunizationTable.vue';
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
  personal_information: Array,
  Immunization: Array // Declare the checkUps prop coming from the server
  });
</script>
