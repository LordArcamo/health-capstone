<template>
  <Head title="Patient Records" />
  <NewLayout>
    <PrenatalTable :personalInfo="props.personal_information" :patients="props.Prenatal" />
  </NewLayout>
</template>

<script setup>
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrenatalTable from '@/Components/PrenatalTable.vue';

const props = defineProps({
  personal_information: Array,
  Prenatal: Array // Declare the checkUps prop coming from the server
});
</script>
