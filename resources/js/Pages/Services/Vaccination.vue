<script setup>
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
import VaccinationTable from '@/Components/VaccinationTable.vue';

// Props from Inertia (passed from the backend)
defineProps({
    patients: Array, // Array of patients fetched from the backend
    vaccineCategories: Array, // Vaccine categories fetched from the backend
    filteredVaccineTypes: Array, // Optional filtered vaccine types
});
</script>

<template>
    <Head title="Vaccination" />

    <NewLayout>
        <div class="mt-4 mx-4">
            <div class="flex justify-between">
                <!-- Pass props to the child VaccinationTable component -->
                <VaccinationTable
                    :patients="patients"
                    :vaccine-categories="vaccineCategories"
                    :filtered-vaccine-types="filteredVaccineTypes"
                />
            </div>
        </div>
    </NewLayout>
</template>
