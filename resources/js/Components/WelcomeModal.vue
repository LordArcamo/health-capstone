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
        <div class="flex space-x-2 mb-4">
          <input
            type="text"
            v-model="searchQuery"
            @input="debouncedSearchPatients"
            placeholder="Enter patient Firstname, Lastname, or ID"
            class="border p-3 rounded w-full focus:outline-none focus:ring-2 focus:ring-green-300 transition duration-200"
          />
        </div>

        <!-- Search Results -->
        <div class="border rounded p-4 max-h-40 overflow-y-auto mb-4 bg-white shadow-md">
          <ul v-if="filteredPatients.length">
            <li
              v-for="patient in filteredPatients"
              :key="patient.personalId"
              @click="selectPatient(patient)"
              class="p-3 cursor-pointer flex justify-between items-center hover:bg-gray-100 rounded transition duration-200"
            >
              <div>
                <p class="font-medium text-gray-800">
                  {{ patient.firstName }} {{ patient.lastName }}
                </p>
                <p class="text-sm text-gray-500">ID: {{ patient.personalId }}</p>
                <p class="text-sm text-gray-500" v-if="patient.email">Email: {{ patient.email }}</p>
              </div>
              <span class="text-green-500 font-bold">➔</span>
            </li>
          </ul>
          <p v-else-if="searchQuery" class="text-center text-sm text-gray-500">
            No patients found for "{{ searchQuery }}".
          </p>
          <p v-else class="text-center text-sm text-gray-400">
            Start typing to search for a patient.
          </p>
        </div>

        <!-- Add New Patient -->
        <div v-if="!filteredPatients.length && searchQuery">
          <p class="text-gray-700 mb-4 text-lg">Patient not found. You can add them:</p>
          <button
            @click="addNewPatient"
            class="bg-green-600 text-white font-semibold py-3 px-6 rounded shadow hover:bg-green-700 transition-colors duration-300"
          >
            Add New Patient
          </button>
        </div>
      </div>

      <!-- Step 2: Choose a Check-up -->
      <div v-if="step === 2">
        <p class="text-gray-700 mb-6 text-lg">What type of Check-Up do we have today?</p>
        <div class="flex justify-center space-x-4 mb-4">
          <Link
            v-if="selectedPatient && Object.keys(selectedPatient).length > 0"
            :href="route('itr', { patient_personalId: selectedPatient.personalId || 'new', })"
            class="bg-gradient-to-r from-green-500 to-yellow-500 text-white font-semibold py-3 px-6 rounded shadow hover:from-green-600 hover:to-yellow-600 transition-colors duration-300"
          >
            Individual Treatment Record
          </Link>

          <button
            @click="showModal = true"
            class="bg-gradient-to-r from-green-500 to-yellow-500 text-white font-semibold py-3 px-6 rounded shadow hover:from-green-600 hover:to-yellow-600 transition-colors duration-300"
          >
            Prenatal & Postpartum Checkup
          </button>

          <Link
            v-if="selectedPatient && Object.keys(selectedPatient).length > 0"
            :href="route('nationalimmunizationprogram', { patient_personalId: selectedPatient.personalId || 'new', })"
            class="bg-gradient-to-r from-green-500 to-yellow-500 text-white font-semibold py-3 px-6 rounded shadow hover:from-green-600 hover:to-yellow-600 transition-colors duration-300"
          >
            National Immunization Program
          </Link>
        </div>
      </div>

      <!-- Proceed Button -->
      <div v-if="step === 1 && selectedPatient" class="mt-6">
        <button
          @click="step = 2"
          class="bg-green-500 text-white font-semibold py-3 px-6 rounded shadow hover:bg-green-600 transition-colors duration-300"
        >
          Proceed to Check-Up
        </button>
      </div>

      <!-- Modal -->
      <div v-if="showModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex justify-center items-center">
        <div class="bg-white rounded-lg shadow-lg py-8 px-6 text-center max-w-md w-full relative">
          <button
            @click="showModal = false"
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-lg"
          >
            &times;
          </button>
          <h2 class="text-3xl font-bold mb-6">Select Checkup Type</h2>
          <div class="flex flex-col space-y-4">
            <Link
              v-if="selectedPatient && Object.keys(selectedPatient).length > 0"
              :href="route('prenatal', { patient_personalId: selectedPatient.personalId || 'new', })"
              class="bg-gradient-to-r from-green-500 to-yellow-500 text-white font-semibold py-3 px-6 rounded shadow hover:from-green-600 hover:to-yellow-600 transition-colors duration-300"
            >
              Prenatal Checkup
            </Link>
            <Link
              :href="route('postpartum', { patient_id: selectedPatient.personalId })"
              class="bg-gradient-to-r from-green-500 to-yellow-500 text-white font-semibold py-3 px-6 rounded shadow hover:from-green-600 hover:to-yellow-600 transition-colors duration-300"
            >
              Postpartum Checkup
            </Link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';
import { debounce } from 'lodash';
//import { usePatientStore } from '@/Stores/patientStore';

export default {
  components: { Link },
  props: {
    patients: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      step: 1,
      searchQuery: '',
      showModal: false,
      filteredPatients: [],
      selectedPatient: null,
    };
  },
    watch: {
    patients: {
      immediate: true,
      handler(newPatients) {
        console.log('Received patients:', newPatients);
        // Only set the filteredPatients if there's a search query
        if (this.searchQuery.trim() === '') {
          this.filteredPatients = []; // Clear patients if search query is empty
        } else {
          this.filteredPatients = [...newPatients]; // Otherwise show all patients
        }
      },
    },
  },
  methods: {
    debouncedSearchPatients: debounce(function () {
      this.searchPatients();
    }, 100),
    searchPatients() {
      const query = this.searchQuery.trim().toLowerCase();
      console.log('Search Query:', query);

      // If search query is empty, clear the filtered list
      if (query === '') {
        this.filteredPatients = [];
      } else {
        // Filter patients only when there's a search query
        this.filteredPatients = this.patients.filter(patient => {
          const fullName = `${patient.firstName} ${patient.lastName}`.toLowerCase();
          return (
            fullName.includes(query) ||
            patient.firstName.toLowerCase().includes(query) ||
            patient.lastName.toLowerCase().includes(query) ||
            patient.personalId.toString().includes(query)
          );
        });
      }

    console.log('Filtered Patients:', this.filteredPatients);
  },
  selectPatient(patient) {
      console.log('Patient selected:', patient);
      this.selectedPatient = patient;
      this.$emit('patientSelected', patient); // Emit selected patient
      this.step = 2;
    },
    addNewPatient() {
      console.log("Add New Patient triggered");
      // Create a blank selected patient for a new patient flow
      this.selectedPatient = {
          personalId: null, // Indicating a new patient
          firstName: '',
          lastName: '',
          middleName: '',
          suffix: '',
          purok: '',
          barangay: '',
          birthdate: '',
          contact: '',
          sex: '',
      };
      this.$emit('patientSelected', this.selectedPatient); // Emit the new patient object
      this.step = 2; // Proceed to check-up type selection
  }
  },
};
</script>

