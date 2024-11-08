<script setup>
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
import FormComponent from '@/Components/WelcomeModal.vue';

// Define props for patients
const props = defineProps({
  patients: Array,
});
</script>

<template>
  <NewLayout>
    <Head title="Check Up" />
    <!-- Ensure we pass patients to FormComponent as initialPatients -->
    <FormComponent :initialPatients="patients" />
  </NewLayout>
</template>
