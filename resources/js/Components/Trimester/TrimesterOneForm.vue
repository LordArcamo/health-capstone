<!-- resources/js/Components/Trimester/TrimesterOneForm.vue -->
<template>
    <div>
      <h3 class="text-lg font-semibold">Trimester One Form</h3>
      <form @submit.prevent="submitForm">
        <!-- Add form fields specific to Trimester 1 -->
        <div class="mb-4">
          <label class="block text-gray-700">Field 1</label>
          <input type="text" v-model="fieldOne" class="border rounded-md w-full" />
        </div>
        <!-- Other fields as needed -->
      </form>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        fieldOne: '',
      };
    },
    methods: {
      submitForm() {
        console.log('Submitting Trimester One form');
        // Form submission logic here
      },
    },
  };
  </script>
  