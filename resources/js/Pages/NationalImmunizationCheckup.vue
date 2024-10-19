<script setup>
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
import NationalImmunizationForm from '../Components/NationalImmunizationProgramForm.vue';

</script>

<template>

  <Head title="Check Up" />

  <NewLayout>
    <NationalImmunizationForm/>
  </NewLayout>

</template>