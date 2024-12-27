<script setup> 
import RiskInfo from '@/Components/Info/RiskInfo.vue';
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
            <Head title="Initao RHU Risk Management Records" />
      <NewLayout>
            <RiskInfo/>
      </NewLayout>
 </template>