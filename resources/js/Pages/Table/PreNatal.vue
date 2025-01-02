<template>
  <Head title="Patient Records" />
  <NewLayout>
    <PrenatalTable 
    :patients="props.PRENATAL" 
    :prefilledData="props.prefilledData" 
    :prenatalId="props.prenatalId" 
    :trimester="props.trimester"
    />
  </NewLayout>
</template>

<script setup>
import NewLayout from '@/Layouts/PersonnelLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrenatalTable from '@/Components/PrenatalTable.vue';

const props = defineProps({
  PRENATAL: Array,
  prefilledData: Object,
  prenatalId: Number,
  trimester: String,
});
</script>
