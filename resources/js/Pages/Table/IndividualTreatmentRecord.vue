<template>
  <Head title="Patient Records" />
  <NewLayout>
    <ItrTable :patients="props.ITR" />
  </NewLayout>
</template>

<script setup>
import NewLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
import ItrTable from '@/Components/ItrTable.vue';

const props = defineProps({
  ITR: Array, // Declare the checkUps prop coming from the server
});
</script>
