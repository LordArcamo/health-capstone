<script setup> 
import VaccinationInfo from '@/Components/Info/VaccinationInfo.vue';
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
            <Head title="Initao RHU Vaccination Program" />
      <NewLayout>
            <VaccinationInfo/>
      </NewLayout>
 </template>