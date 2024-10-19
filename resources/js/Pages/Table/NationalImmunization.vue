<template>
  <Head title="National Immunization" />

  <NewLayout>
    <NationalImmunizationTable />
  </NewLayout>
</template>

<script setup>
import NationalImmunizationTable from '@/Components/NationalImmunizationTable.vue';
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
  personalInformation: Array,
  checkUps: Array // Declare the checkUps prop coming from the server
});
</script>
