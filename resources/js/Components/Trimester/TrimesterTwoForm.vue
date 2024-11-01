<template>
    <div>
      <h4 class="text-lg font-semibold">Trimester 2 Form</h4>
      <form @submit.prevent="submitForm">
        <div class="mt-4">
          <label for="field1" class="block text-gray-700">Field 1:</label>
          <input type="text" id="field1" v-model="field1" class="mt-1 border rounded-md w-full" />
        </div>
        <div class="mt-4">
          <label for="field2" class="block text-gray-700">Field 2:</label>
          <textarea id="field2" v-model="field2" class="mt-1 border rounded-md w-full" rows="4"></textarea>
        </div>
        <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Submit</button>
      </form>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        field1: '',
        field2: '',
      };
    },
    methods: {
      submitForm() {
        console.log('Trimester 2 Form Submitted:', { field1: this.field1, field2: this.field2 });
        // Handle form submission logic
      },
    },
  };
  </script>
  