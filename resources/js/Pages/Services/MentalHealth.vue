<script setup>
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
import MentalHealthTable from '@/Components/MentalHealthTable.vue';
import MentalHealthSessions from '@/Components/MentalHealthSessions.vue';

// Sessions are passed directly via Inertia's props
// const sessions = ref($page.props.sessions || []);
</script>

<template>
  <Head title="Mental Health Records" />

  <NewLayout>
    <div class="flex justify-between">
      <!-- Pass sessions data to the MentalHealthSessions component -->
      <MentalHealthSessions />

      <!-- Pass sessions data to the MentalHealthTable component if needed -->
      <MentalHealthTable />
    </div>
  </NewLayout>
</template>
