<template>
  <Head title="National Immunization" />

  <NewLayout>
    <NationalImmunizationTable :patients = "props.IMMUNIZATION" />
  </NewLayout>
</template>

<script setup>
import NationalImmunizationTable from '@/Components/NationalImmunizationTable.vue';
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
  IMMUNIZATION: Array,

  });
</script>
