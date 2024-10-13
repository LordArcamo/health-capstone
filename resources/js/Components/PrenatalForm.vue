<template>
    <div class="min-h-screen bg-gray-100 flex justify-center items-center">
      <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-2xl">
        <h2 class="text-2xl font-bold mb-4">Prenatal Check-Up Form</h2>
  
        <!-- Form Steps -->
        <div class="mb-4">
          <p class="text-gray-700">Step {{ step }} of 3</p>
          <div class="flex space-x-4 mt-4">
            <span :class="step >= 1 ? activeStepClass : inactiveStepClass">1</span>
            <span :class="step >= 2 ? activeStepClass : inactiveStepClass">2</span>
            <span :class="step >= 3 ? activeStepClass : inactiveStepClass">3</span>
          </div>
        </div>
  
        <!-- Step 1: Basic Information -->
        <div v-if="step === 1">
          <div class="mb-4">
            <label class="block text-gray-700">Name of Spouse:</label>
            <input v-model="form.spouse_name" type="text" class="w-full p-2 border rounded-lg">
          </div>
          <div class="mb-4">
            <label class="block text-gray-700">Emergency Contact Number:</label>
            <input v-model="form.emergency_contact" type="text" class="w-full p-2 border rounded-lg">
          </div>
          <div class="mb-4">
            <label class="block text-gray-700">Philhealth Member ID:</label>
            <input v-model="form.philhealth_id" type="text" class="w-full p-2 border rounded-lg">
          </div>
        </div>
  
        <!-- Step 2: Prenatal Details -->
        <div v-if="step === 2">
          <div class="mb-4">
            <label class="block text-gray-700">Gravidity:</label>
            <input v-model="form.gravidity" type="number" class="w-full p-2 border rounded-lg">
          </div>
          <div class="mb-4">
            <label class="block text-gray-700">Parity:</label>
            <input v-model="form.parity" type="number" class="w-full p-2 border rounded-lg">
          </div>
          <div class="mb-4">
            <label class="block text-gray-700">Menarche:</label>
            <input v-model="form.menarche" type="text" class="w-full p-2 border rounded-lg">
          </div>
        </div>
  
        <!-- Step 3: Check-Up Details -->
        <div v-if="step === 3">
          <div class="mb-4">
            <label class="block text-gray-700">Syphilis Result:</label>
            <select v-model="form.syphilis_result" class="w-full p-2 border rounded-lg">
              <option value="Negative">Negative</option>
              <option value="Positive">Positive</option>
            </select>
          </div>
          <div class="mb-4">
            <label class="block text-gray-700">Penicillin Usage:</label>
            <select v-model="form.penicillin_usage" class="w-full p-2 border rounded-lg">
              <option value="No">No</option>
              <option value="Yes">Yes</option>
            </select>
          </div>
          <div class="mb-4">
            <label class="block text-gray-700">Blood Pressure (BP):</label>
            <input v-model="form.bp" type="text" class="w-full p-2 border rounded-lg">
          </div>
        </div>
  
        <!-- Navigation Buttons -->
        <div class="mt-6 flex justify-between">
          <button v-if="step > 1" @click="prevStep" class="bg-gray-400 text-white py-2 px-4 rounded-lg hover:bg-gray-500">
            Previous
          </button>
          <button v-if="step < 3" @click="nextStep" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">
            Next
          </button>
          <button v-if="step === 3" @click="submitForm" class="bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600">
            Submit
          </button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  
  export default {
    data() {
      return {
        step: 1,
        form: {
          spouse_name: '',
          emergency_contact: '',
          philhealth_id: '',
          gravidity: 0,
          parity: 0,
          menarche: '',
          syphilis_result: 'Negative',
          penicillin_usage: 'No',
          bp: '',
        },
        activeStepClass: "bg-blue-500 text-white w-8 h-8 flex items-center justify-center rounded-full",
        inactiveStepClass: "bg-gray-300 text-gray-600 w-8 h-8 flex items-center justify-center rounded-full",
      };
    },
    methods: {
      nextStep() {
        if (this.step < 3) this.step++;
      },
      prevStep() {
        if (this.step > 1) this.step--;
      },
      submitForm() {
        Inertia.post('/submit-prenatal-checkup', this.form);
      }
    }
  };
  </script>
  
  <style scoped>
  /* Styling for the step indicators */
  </style>
  