<template>
  <Head title="Mental Health Records" />

  <NewLayout>
    <div class="flex justify-between">
      <!-- Pass sessions data to the MentalHealthSessions component -->
      <MentalHealthSessions />
      <MentalHealthTable :patients="props.MENTALHEALTH" />
      <!-- Pass sessions data to the MentalHealthTable component if needed -->
    </div>
  </NewLayout>
</template>


<script setup>
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
import MentalHealthTable from '@/Components/MentalHealthTable.vue';
import MentalHealthSessions from '@/Components/MentalHealthSessions.vue';

// Sessions are passed directly via Inertia's props
// const sessions = ref($page.props.sessions || []);

const props = defineProps({
  MENTALHEALTH: Array, // This will hold the Mental Health patients

  
});


</script>

