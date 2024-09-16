<script setup>
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
import Patient from '../Components/PatientTable.vue';


</script>

<template>
    <Head title="Patient Records" />
    <NewLayout>
      <Patient />
    </NewLayout>

</template>