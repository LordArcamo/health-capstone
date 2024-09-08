
<template>
    <Head title="Check Up" />

    <NewLayout>

        <div class="mt-4 mx-4">
            <div class="flex justify-between">
                <h5>Check Up</h5>
                <link :href="('checkup.create')">New Check Up<link/>

            </div>
        </div>
    </NewLayout>

</template>

<script setup>
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>