<script setup> 
import MentalInfo from '@/Components/Info/MentalInfo.vue';
import NewLayout from '@/Layouts/PersonnelLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
      <Head title="Initao RHU Mental Health Program" />

      <NewLayout>
            <MentalInfo/>
      </NewLayout>
 </template>