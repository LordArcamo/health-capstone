<template>
  <Head title="Patient Records" />
  <NewLayout>
    <PrenatalTable :patients="props.PRENATAL" />
  </NewLayout>
</template>

<script setup>
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrenatalTable from '@/Components/PrenatalTable.vue';

const props = defineProps({
  PRENATAL: Array,
});
</script>
