<script setup> 
import PrenatalInfo from '@/Components/Info/PrenatalInfo.vue';
import NewLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
            <Head title="Initao RHU Prenatal and Postpartum Records" />

      <NewLayout>
            <PrenatalInfo/>
      </NewLayout>
 </template>