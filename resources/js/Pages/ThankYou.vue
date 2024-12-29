<template>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gray-100">
      <div class="bg-white shadow-lg rounded-lg p-8 max-w-lg w-full text-center">
        <h1 class="text-2xl font-bold text-green-500">Thank You!</h1>
        <p class="mt-4 text-gray-700">Your form has been successfully submitted.</p>
        <div class="mt-6 flex justify-center space-x-4">
          <button @click="goToDashboard" class="btn">Go to Dashboard</button>
          <button @click="checkAgain" class="btn">Submit Another Form</button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    methods: {
      goToDashboard() {
        this.$inertia.visit('/dashboard'); // Adjust the route as needed
      },
      checkAgain() {
        this.$inertia.visit('/form'); // Adjust to your form's route
      },
    },
  };
  </script>
  
  <style scoped>
  .btn {
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
  .btn:hover {
    background-color: #45a049;
  }
  </style>
  