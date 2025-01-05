<template>
  <Head title="Mental Health Records" />

  <NewLayout>
    <div class="flex justify-between">
      <MentalHealthSessions :initial-sessions="sessions" />
      <MentalHealthTable :patients="patients" />
    </div>
  </NewLayout>
</template>

<script setup>
import NewLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
import MentalHealthTable from '@/Components/MentalHealthTable.vue';
import MentalHealthSessions from '@/Components/MentalHealthSessions.vue';

defineProps({
  patients: {
    type: Array,
    required: true
  },
  sessions: {
    type: Array,
    required: true,
    default: () => []
  }
});
</script>
