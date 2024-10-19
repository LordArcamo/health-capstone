<template>
<<<<<<< HEAD
  <Head title="National Immunization" />

  <NewLayout>
    <NationalImmunizationForm :onSubmit="submitForm" />
=======
  <Head title="Patient Records" />
  <NewLayout>
    <NationalImmunizationTable :personalInformation="props.personalInformation" :patients="props.checkUps" />
>>>>>>> d157683049fd015b37059832b49ca82f03b28dcf
  </NewLayout>
</template>

<script setup>
import NationalImmunizationTable from '@/Components/NationalImmunizationTable.vue';
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
<<<<<<< HEAD
import NationalImmunizationForm from '@/Components/NationalImmunizationProgramForm.vue';
import { Inertia } from '@inertiajs/inertia';

function submitForm(form) {
  console.log('Submitting from parent:', form); // Log the form data for debugging
  
  // Post the form data to the backend using Inertia
  return Inertia.post('/nationalimmunizationprogram/store', form, {
    onSuccess: () => {
      // Handle success (e.g., show a success message or reset form)
      console.log('Data saved successfully!');
    },
    onError: (errors) => {
      // Handle errors (e.g., show error messages)
      console.error('Form submission errors:', errors);
    },
  });
}
=======
NationalImmunizationTable

const props = defineProps({
  personalInformation: Array,
  checkUps: Array // Declare the checkUps prop coming from the server
});
>>>>>>> d157683049fd015b37059832b49ca82f03b28dcf
</script>
