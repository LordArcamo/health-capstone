<template>
  <Head title="Patient Records" />
  <NewLayout>
    <PrenatalTable :patients="props.Prenatal" />
  </NewLayout>
</template>

<script setup>
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrenatalTable from '@/Components/PrenatalTable.vue';

const props = defineProps({
  Prenatal: Array // Declare the checkUps prop coming from the server
});
</script>
