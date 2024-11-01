<!-- TrimesterModal.vue -->
<template>
    <div v-if="show" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50" @click.self="close">
      <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl"> <!-- Increased size to max-w-2xl -->
        
        <!-- Step Indicator -->
        <div class="flex justify-between items-center px-6 py-3 bg-green-100 rounded-t-lg">
          <div class="flex items-center space-x-2 text-green-700 font-semibold">
            <div class="w-5 h-5 rounded-full" :class="step === 1 ? 'bg-green-600' : 'bg-green-300'"></div>
            <span>Step 1: Select Trimester</span>
            <div v-if="step === 2" class="ml-2 flex items-center space-x-2">
              <div class="w-5 h-5 rounded-full" :class="step === 2 ? 'bg-green-600' : 'bg-green-300'"></div>
              <span>Step 2: Fill Out Form</span>
            </div>
          </div>
        </div>
  
        <!-- Header with Back Button -->
        <header class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-800">
            <span v-if="step === 1">Select Trimester</span>
            <span v-else>Trimester {{ selectedTrimester }} Form</span>
          </h3>
          <button v-if="step === 2" @click="goBack" class="flex items-center text-green-600 hover:text-green-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back
          </button>
        </header>
  
        <!-- Modal Body -->
        <main class="px-6 py-6 text-gray-700">
          <!-- Step 1: Select Trimester -->
          <div v-if="step === 1">
            <p>Select the appropriate trimester options here:</p>
            <select v-model="selectedTrimester" @change="onTrimesterSelect" class="mt-4 border rounded-md w-full p-2">
              <option disabled value="">-- Please choose a trimester --</option>
              <option v-for="(trimester, index) in trimesters" :key="index" :value="(index + 1).toString()">
                {{ trimester }}
              </option>
            </select>
          </div>
  
          <!-- Step 2: Show Selected Trimester Form -->
          <div v-else-if="step === 2">
            <component :is="formComponent" />
          </div>
        </main>
  
        <!-- Footer Buttons -->
        <footer class="flex justify-end px-6 py-4 border-t border-gray-200">
          <button v-if="step === 1" @click="confirmSelection" :disabled="!selectedTrimester" class="bg-green-600 text-white px-4 py-2 rounded-md mr-2 hover:bg-green-700">
            Next
          </button>
          <button @click="close" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">Close</button>
        </footer>
      </div>
    </div>
  </template>
  
  <script>
  import TrimesterOneForm from '@/Components/Trimester/TrimesterOneForm.vue';
  import TrimesterTwoForm from '@/Components/Trimester/TrimesterTwoForm.vue';
  import TrimesterThreeForm from '@/Components/Trimester/TrimesterThreeForm.vue';
  import TrimesterFourForm from '@/Components/Trimester/TrimesterFourForm.vue';
  import TrimesterFiveForm from '@/Components/Trimester/TrimesterFiveForm.vue';
  
  export default {
    props: {
      show: {
        type: Boolean,
        default: false,
      },
    },
    data() {
      return {
        trimesters: [
          'Trimester 1',
          'Trimester 2',
          'Trimester 3',
          'Trimester 4',
          'Trimester 5',
        ],
        selectedTrimester: '',
        step: 1, // Start at Step 1
      };
    },
    computed: {
      formComponent() {
        switch (this.selectedTrimester) {
          case '1': return TrimesterOneForm;
          case '2': return TrimesterTwoForm;
          case '3': return TrimesterThreeForm;
          case '4': return TrimesterFourForm;
          case '5': return TrimesterFiveForm;
          default: return null;
        }
      },
    },
    methods: {
      close() {
        this.selectedTrimester = ''; // Reset selection on close
        this.step = 1; // Reset to step 1 on close
        this.$emit("close");
      },
      onTrimesterSelect() {
        console.log(`Selected Trimester: ${this.selectedTrimester}`);
      },
      confirmSelection() {
        if (this.selectedTrimester) {
          this.step = 2; // Move to Step 2 to show form
        }
      },
      goBack() {
        this.step = 1; // Return to Step 1
      },
    },
  };
  </script>
  