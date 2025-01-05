<script setup> 

import EpiInfo from '@/Components/Info/EpiInfo.vue';
import NewLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
      <Head title="Initao RHU National Immunization Records" />

<NewLayout>
 <EpiInfo/>
</NewLayout>
</template>