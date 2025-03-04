<template>
    <Head title="Active User" />

    <NewLayout>
        <ActiveUserTable :activeUsers="props.activeUsers" />
    </NewLayout>
</template>

<script setup>
import NewLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
import ActiveUserTable from '@/Components/ActiveUserTable.vue';

const props = defineProps({
  activeUsers: Array, // Declare the checkUps prop coming from the server
});
  </script>
