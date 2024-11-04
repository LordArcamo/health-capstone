<script>
import { Link } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';

export default {
  components: { Link },
  data() {
    return {
      step: 1,
      searchQuery: '',
      patients: [],
      selectedPatient: null,
    };
  },
  methods: {
    searchPatients() {
      if (this.searchQuery.trim() === '') {
        this.patients = [];
        return;
      }

      Inertia.get(route('patients.search'), { query: this.searchQuery }, {
        preserveState: true,
        onSuccess: (page) => {
          this.patients = page.props.patients;
        },
      });
    },
    selectPatient(patient) {
      this.selectedPatient = patient;
      this.step = 2; // Move to the next step (check-up selection)
    },
    addNewPatient() {
      // Set a new patient as selected
      this.selectedPatient = { personalId: 'new', lastName: this.searchQuery }; // Simulate a new patient
      this.step = 2; // Move to the next step (check-up selection)
    },
    goBackToCheckup() {
      // Navigate back to the Checkup page
      Inertia.visit(route('checkup')); // Make sure the route name matches your Checkup route
    },
  },
};
</script>

<style scoped>
/* Optional: You can add some custom styles here */
</style>


<template>
  <div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="bg-white rounded-lg shadow-lg py-12 px-10 text-center max-w-3xl w-full">
      <h2 class="text-5xl font-bold mb-8">Welcome! <span class="ml-2">👋</span></h2>

      <!-- Step Indicator -->
      <div class="mb-6">
        <span class="text-green-600 font-semibold text-lg">Step {{ step }} of 2</span>
      </div>

      <!-- Step 1: Search for a Patient -->
      <div v-if="step === 1">
        <p class="text-gray-700 mb-4 text-lg">Search for a patient to begin:</p>
        <input
          type="text"
          v-model="searchQuery"
          @input="searchPatients"
          placeholder="Enter patient Lastname or ID"
          class="border p-3 rounded w-full mb-4 focus:outline-none focus:ring-2 focus:ring-green-300 transition duration-200"
        />
        <!-- Search Results -->
        <div class="border rounded p-4 max-h-40 overflow-y-auto mb-4">
          <ul v-if="patients.length">
            <li 
              v-for="patient in patients" 
              :key="patient.personalId"
              @click="selectPatient(patient)"
              class="p-3 cursor-pointer hover:bg-gray-200 rounded transition duration-200"
            >
              {{ patient.personalId }} ({{ patient.lastName }})
            </li>
          </ul>
          <p v-else-if="searchQuery" class="text-sm text-gray-500">No patients found.</p>
        </div>

        <!-- Button to Add New Patient -->
        <div class="flex flex-col center" v-if="!patients.length && searchQuery">
          <p class="text-gray-700 mb-4 text-lg">Patient not found. You can add them:</p>
          <div class="flex items-center">
            <button 
            @click="addNewPatient" 
            class="bg-green-600 text-white font-semibold py-3 px-6 rounded shadow hover:bg-blue-700 transition-colors duration-300"
          >
            Add New Patient
          </button>
          </div>
        </div>
      </div>

      <!-- Step 2: Choose a Check-up -->
      <div v-if="step === 2">
        <p class="text-gray-700 mb-6 text-lg">What type of Check-Up do we have today?</p>
        <div class="flex justify-center space-x-4 mb-4">
          <Link 
            :href="route('itr', { patient_personalId: selectedPatient.personalId })"
            class="bg-gradient-to-r from-green-500 to-yellow-500 text-white font-semibold py-3 px-6 rounded shadow hover:from-green-600 hover:to-yellow-600 transition-colors duration-300"
          >
            Individual Treatment Record
          </Link>
          <Link 
            :href="route('prenatal', { patient_personalId: selectedPatient.personalId })"
            class="bg-gradient-to-r from-green-500 to-yellow-500 text-white font-semibold py-3 px-6 rounded shadow hover:from-green-600 hover:to-yellow-600 transition-colors duration-300"
          >
            Prenatal & Postpartum Checkup
          </Link>
          <Link 
            :href="route('nationalimmunizationprogram', { patient_personalId: selectedPatient.personalId })"
            class="bg-gradient-to-r from-green-500 to-yellow-500 text-white font-semibold py-3 px-6 rounded shadow hover:from-green-600 hover:to-yellow-600 transition-colors duration-300"
          >
            National Immunization Program
          </Link>
        </div>
      </div>

      <!-- Proceed Button for Step 1 -->
      <div v-if="step === 1 && selectedPatient" class="mt-6">
        <button 
          @click="step = 2" 
          class="bg-green-500 text-white font-semibold py-3 px-6 rounded shadow hover:bg-green-600 transition-colors duration-300"
        >
          Proceed to Check-Up
        </button>
      </div>

      <!-- Back to Checkup Page -->
      <div class="mt-6">
        <button 
          @click="goBackToCheckup"
          class="text-gray-600 hover:underline text-lg"
        >
          Back to Checkup Page
        </button>
      </div>
    </div>
  </div>
</template>
