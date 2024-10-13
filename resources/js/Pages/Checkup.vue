<script setup>
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
import Modal from '../Components/WelcomeModal.vue';
// import FormComponent from '@/Components/CheckupForm.vue';

</script>

<template>
  <NewLayout>
    <Head title="Check Up" />
  <Modal/>
<!-- </NewLayout>
    <FormComponent :onSubmit="submitForm" /> -->
  </NewLayout>
</template>