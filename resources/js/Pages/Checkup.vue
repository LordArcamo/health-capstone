<script setup>
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
import Form from '../Components/CheckupForm.vue';

</script>

<template>

  <Head title="Check Up" />

  <NewLayout>
    <Form />
  </NewLayout>

</template>