<script setup>
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
import PreNatalForm from '@/Components/PrenatalForm.vue';

</script>

<template>

  <Head title="Check Up" />

  <NewLayout>
    <PreNatalForm/>
  </NewLayout>

</template>