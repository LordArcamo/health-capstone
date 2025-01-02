<script setup>
import NewLayout from '@/Layouts/PersonnelLayout.vue';
import { Head } from '@inertiajs/vue3';
import VaccinationTable from '@/Components/VaccinationTable.vue';

// Props from Inertia (passed from the backend)
const props = defineProps({
    patients: {
        type: Array,
        default: () => [],
    },
    VACCINATION: {
        type: Array,
        default: () => [],
    },
    vaccineCategories: Array, // Vaccine categories fetched from the backend
    filteredVaccineTypes: Array, // Optional filtered vaccine types
});

// Log VACCINATION to debug
</script>

<template>
    <Head title="Vaccination" />

    <NewLayout>
        <div>
            <div class="flex justify-between">
                <!-- Pass props to the child VaccinationTable component -->
                <VaccinationTable
                    :patients="props.patients"
                    :vaccinatedPatients="props.VACCINATION"
                    :vaccine-categories="vaccineCategories"
                    :filtered-vaccine-types="filteredVaccineTypes"
                />
            </div>
        </div>
    </NewLayout>
</template>
