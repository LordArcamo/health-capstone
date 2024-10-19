<template>
  <Head title="Patient Records" />
  <NewLayout>
    <NationalImmunizationTable :personalInformation="props.personalInformation" :patients="props.checkUps" />
  </NewLayout>
</template>

<script setup>
import NationalImmunizationTable from '@/Components/NationalImmunizationTable.vue';
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
NationalImmunizationTable

const props = defineProps({
  personalInformation: Array,
  checkUps: Array // Declare the checkUps prop coming from the server
});
</script>
