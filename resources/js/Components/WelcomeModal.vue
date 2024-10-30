<template>
  <div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="bg-white rounded-lg shadow-lg py-12 px-10 text-center">
      <h2 class="text-4xl font-bold mb-6">Welcome! <span class="ml-2">👋</span></h2>

      <!-- Step 1: Search for a Patient -->
      <div v-if="step === 1">
        <p class="text-gray-700 mb-4">Search for a patient to begin:</p>
        <input
          type="text"
          v-model="searchQuery"
          @input="searchPatients"
          placeholder="Enter patient Lastname or ID"
          class="border p-2 rounded w-full mb-4"
        />

        <!-- Display search results -->
        <ul v-if="patients.length" class="max-h-40 overflow-y-auto">
          <li 
            v-for="patient in patients" 
            :key="patient.id" 
            @click="selectPatient(patient)"
            class="p-2 cursor-pointer hover:bg-gray-200 rounded"
          >
            {{ patient.id }} ({{ patient.lastName }})
          </li>
        </ul>

        <p v-else class="text-sm text-gray-500">No patients found.</p>
      </div>

      <!-- Step 2: Choose a Check-up -->
      <div v-if="step === 2">
        <p class="text-gray-700 mb-6">What type of Check-Up do we have today?</p>
        <div class="flex justify-center space-x-4">
          <Link 
            :href="route('itr', { patient_id: selectedPatient.id })"
            class="bg-gradient-to-r from-[#0F8F46] to-[#FED035] text-white font-semibold py-2 px-4 rounded shadow hover:from-green-700 hover:to-yellow-500 transition-colors duration-300"
          >
            Individual Treatment Record
          </Link>
          <Link 
            :href="route('prenatal', { patient_id: selectedPatient.id })"
            class="bg-gradient-to-r from-[#0F8F46] to-[#FED035] text-white font-semibold py-2 px-4 rounded shadow hover:from-green-700 hover:to-yellow-500 transition-colors duration-300"
          >
            Prenatal & Postpartum Checkup
          </Link>
          <Link 
            :href="route('nationalimmunizationprogram', { patient_id: selectedPatient.id })"
            class="bg-gradient-to-r from-[#0F8F46] to-[#FED035] text-white font-semibold py-2 px-4 rounded shadow hover:from-green-700 hover:to-yellow-500 transition-colors duration-300"
          >
            National Immunization Program
          </Link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';

export default {
  components: { Link },
  data() {
    return {
      step: 1, // Track the current step
      searchQuery: '',
      patients: [], // Store search results
      selectedPatient: null, // Store the selected patient
    };
  },
  methods: {
    searchPatients() {
      if (this.searchQuery.trim() === '') {
        this.patients = [];
        return;
      }

      // Make Inertia request to fetch patients based on search query
      Inertia.get(route('patients.search'), { query: this.searchQuery }, {
        preserveState: true, // Keep the modal state intact
        onSuccess: (page) => {
          // Access the results from Inertia response and update patients list
          this.patients = page.props.patients;
        },
      });
    },
    selectPatient(patient) {
      this.selectedPatient = patient;
      this.step = 2; // Move to the next step (check-up selection)
    },
  },
};
</script>
