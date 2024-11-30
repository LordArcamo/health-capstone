<template>
  <div>
    <NewLayout>
      <PatientTabs/>
    </NewLayout>
  </div>
</template>

<script setup>
import PatientTabs from '@/Components/PatientTabs.vue';
import { Inertia } from '@inertiajs/inertia';
import NewLayout from '@/Layouts/NewLayout.vue';

</script>
