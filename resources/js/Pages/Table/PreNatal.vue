<template>
  <Head title="Patient Records" />
  <NewLayout>
    <PrenatalTable
    :patients="props.PRENATAL"
    :prefilledData="props.prefilledData"
    :prenatalConsultationDetailsID="props.prenatalConsultationDetailsID"
    :trimester="props.trimester"
    />
  </NewLayout>
</template>

<script setup>
import NewLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrenatalTable from '@/Components/PrenatalTable.vue';

const props = defineProps({
  PRENATAL: Array,
  prefilledData: Object,
  prenatalConsultationDetailsID: Number,
  trimester: String,
});
</script>
