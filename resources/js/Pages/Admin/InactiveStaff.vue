<template>
    <Head title="Inactive User Table" />

    <NewLayout>
        <InactiveStaffTable :getInactiveUsers="props.USERS" />
    </NewLayout>
</template>

<script setup>
import NewLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
 import InactiveStaffTable from '@/Components/Staff/InactiveStaffTable.vue';

const props = defineProps({
  USERS: Array, // Declare the checkUps prop coming from the server
});
  </script>
