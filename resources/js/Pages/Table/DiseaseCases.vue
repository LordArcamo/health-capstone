<template>
  <Head title="Patient Records" />
  <NewLayout>
    <!-- Pass the patients (cases) prop dynamically from the backend -->
    <DiseaseTable :patients="props.cases" />
  </NewLayout>
</template>

<script setup>
import NewLayout from '@/Layouts/PersonnelLayout.vue';
import { Head } from '@inertiajs/vue3';
import DiseaseTable from '@/Components/DiseaseTable.vue';

// Define props to accept data passed from the backend
const props = defineProps({
  cases: {
    type: Array,
    required: true, // Ensure the data is mandatory for the component to function
  },
});
</script>
