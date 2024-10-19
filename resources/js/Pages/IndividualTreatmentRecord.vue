<<<<<<< HEAD
<script setup>
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
import ITR from '@/Components/ITRForm.vue';
import { Inertia } from '@inertiajs/inertia';

function submitForm(form) {
  console.log('Submitting from parent:', form); // Debugging
  Inertia.post('/itr/store', form)
    .then(response => {
      console.log('Response:', response); // Check the response
    })
    .catch(error => {
      console.error('Error during submission:', error); // Log any errors
    });
}

</script>
<template>
  <Head title="Individual Treatment Record" />

<NewLayout>
    <ITR :onSubmit="submitForm" /> 
  </NewLayout>
</template>
=======
<template>
    <Head title="Patient Records" />
    <NewLayout>
      <PatientTable :personalInformation="props.personalInformation" :patients="props.checkUps" />
    </NewLayout>
  </template>
  
  <script setup>
  import NewLayout from '@/Layouts/NewLayout.vue';
  import { Head } from '@inertiajs/vue3';
  import PatientTable from '../Components/PatientTable.vue';
  
  const props = defineProps({
    personalInformation: Array,
    checkUps: Array // Declare the checkUps prop coming from the server
  });
  </script>
  
>>>>>>> d157683049fd015b37059832b49ca82f03b28dcf
