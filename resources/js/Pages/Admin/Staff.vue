<template>
    <Head title="User Table" />

    <NewLayout>
        <StaffTable :staffList="props.USERS" />
    </NewLayout>
</template>

<script setup>
import NewLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
 import StaffTable from '@/Components/Staff/StaffTable.vue';

const props = defineProps({
  USERS: Array, // Declare the checkUps prop coming from the server
});
  </script>
