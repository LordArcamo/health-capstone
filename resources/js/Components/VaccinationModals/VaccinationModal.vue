<template>
<div v-if="showModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto">
    <AlertMessage v-if="showAlert" :message="alertMessage" @close="showAlert = false" />
    <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl p-6 overflow-y-auto max-h-[90vh]">
        <!-- Modal Content -->
      <div class="flex justify-between items-center border-b pb-4 mb-4">
        <h2 class="text-xl font-semibold">Vaccination Details</h2>
        <button @click="closeModal" class="text-gray-500 hover:text-gray-800">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Step 1: Search for Patient -->
      <div v-if="step === 1">
        <h3 class="text-lg font-semibold mb-4">Search for a Patient</h3>
        <div>
          <label for="search" class="block text-sm font-medium text-gray-700">Search by Name</label>
          <input 
            type="text" 
            v-model="searchQuery" 
            @input="debouncedSearchPatients" 
            id="search" 
            class="input" 
            placeholder="Example: Pedro Penduko" 
          />
        </div>

        <div class="bg-white border border-gray-300 rounded-lg max-h-60 overflow-y-auto">
          <ul v-if="filteredPatients.length">
            <li 
            v-for="patient in filteredPatients" 
            :key="patient.id" 
            @click="selectPatient(patient)" 
            :class="{
              'bg-gray-100': selectedPatient?.id === patient.id,
              'hover:bg-gray-50': selectedPatient?.id !== patient.id,
            }" class="cursor-pointer px-4 py-2">
              {{ patient.firstName }} {{ patient.lastName }} ({{ patient.age }} years)
            </li>
          </ul>
          <div v-else-if="searchQuery.trim()" class="mt-4 text-sm text-gray-500">
          No patients found. You can proceed to add a new patient.
        </div>
        <div v-else class="mt-4 text-sm text-gray-500 px-4 py-2">
            Start typing to search for a patient.
          </div>
        </div>


        <div class="flex justify-end space-x-4 mt-6">
          <button @click="closeModal" class="bg-red-500 text-white py-2 px-4 rounded-md">Cancel</button>
          <button
            :disabled="!selectedPatient && !allowAddNewPatient"
            @click="addOrNextStep"
            class="bg-blue-500 text-white py-2 px-4 rounded-md disabled:bg-gray-400"
          >
            {{ selectedPatient ? "Next" : "Add New Patient" }}
          </button>
        </div>
      </div>

      <!-- Step 2: Patient Details -->
      <div v-if="step === 2">
        <h3 class="text-lg font-semibold mb-4">
          {{ selectedPatient ? "Patient Details" : "Add New Patient" }}
        </h3>
        <div v-if="selectedPatient" class="mb-4">
          <p><strong>Name:</strong> {{ selectedPatient.name }}</p>
          <p><strong>Age:</strong> {{ selectedPatient.age }}</p>
          <p><strong>Contact:</strong> {{ selectedPatient.contact }}</p>
        </div>

        <div v-else>
          <!-- Patient Information Form -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block">First Name:</label>
              <input type="text" v-model="form.firstName" @input="capitalizeName('firstName')" class="input"
                placeholder="Example: Juan" required />
              <span v-if="errors.firstName" class="text-red-600 text-sm">{{ errors.firstName }}</span>
            </div>
            <div>
              <label class="block">Last Name:</label>
              <input type="text" v-model="form.lastName" @input="capitalizeName('lastName')" class="input"
                placeholder="Example: Dela Cruz" required />
              <span v-if="errors.lastName" class="text-red-600 text-sm">{{ errors.lastName }}</span>
            </div>

            <div>
              <label class="block">Middle Name:</label>
              <input type="text" v-model="form.middleName" @input="capitalizeName('middleName')" class="input"
                placeholder="Example: Penduko" required />
              <span v-if="errors.middleName" class="text-red-600 text-sm">{{ errors.middleName }}</span>
            </div>
            <div>
              <label for="suffix" class="block text-sm font-medium text-gray-700">Suffix:</label>
              <select v-model="form.suffix"
                class="input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <!-- Placeholder option for selecting suffix -->
                <option value="" disabled selected>Select a Suffix</option>
                <option value="None">None</option>
                <option value="Jr.">Jr.</option>
                <option value="Sr.">Sr.</option>
                <option value="I">I</option>
                <option value="II">II</option>
                <option value="III">III</option>
                <option value="IV">IV</option>
                <option value="V">V</option>
              </select>


            </div>
            <div>
              <label for="barangay" class="block mb-1">Barangay:</label>
              <select id="barangay" v-model="form.barangay" class="input">
                <option disabled value="">Select a Barangay</option>
                <option value="Apas">Apas</option>
                <option value="Aluna">Aluna</option>
                <option value="Andales">Andales</option>
                <option value="Calacapan">Calacapan</option>
                <option value="Gimangpang">Gimangpang</option>
                <option value="Jampason">Jampason</option>
                <option value="Kamelon">Kamelon</option>
                <option value="Kanitoan">Kanitoan</option>
                <option value="Oguis">Oguis</option>
                <option value="Pagahan">Pagahan</option>
                <option value="Poblacion">Poblacion</option>
                <option value="Pontacon">Pontacon</option>
                <option value="San Pedro">San Pedro</option>
                <option value="Sinalac">Sinalac</option>
                <option value="Tawantawan">Tawantawan</option>
                <option value="Tubigan">Tubigan</option>
              </select>
              <span v-if="errors.barangay" class="text-red-600 text-sm">{{ errors.barangay }}</span>
            </div>


            <div>
              <label class="block">Purok:</label>
              <input type="text" v-model="form.purok" class="input" placeholder="Example: Purok 1A" required />
              <span v-if="errors.purok" class="text-red-600 text-sm">{{ errors.purok }}</span>
            </div>
            <div>
              <label class="block">Birthdate:</label>
              <input type="date" v-model="form.birthdate" @input="restrictBirthdateInput" @change="validateBirthdate"
                class="input" required />
              <span v-if="errors.birthdate" class="text-red-600 text-sm">{{ errors.birthdate }}</span>
            </div>
            <div>
              <label class="block">Age:</label>
              <input type="number" v-model="patientAgeInYears" class="input" readonly />
            </div>
            <!-- Contact Number -->
            <div>
              <label class="block mb-1 font-medium text-gray-700">Contact Number:</label>
              <div class="relative">
                <input type="tel" v-model="form.contact" @input="formatContact"
                  class="pl-14 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 w-full"
                  placeholder="Enter 10 digits" required />
                <span
                  class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 text-sm pointer-events-none">
                  +63
                </span>
              </div>
            </div>

            <div>
              <label class="block">Gender</label>
              <select v-model="form.sex" class="input" required>
                <!-- Placeholder option for selecting suffix -->
                <option value="" disabled selected>Select a Gender</option>
                <option>Male</option>
                <option>Female</option>
              </select>
            </div>
          </div>
        </div>

        <div class="flex justify-between mt-6">
          <button @click="prevStep" class="bg-gray-500 text-white py-2 px-4 rounded-md">Back</button>
          <button @click="nextStep" class="bg-blue-500 text-white py-2 px-4 rounded-md">Next</button>
        </div>
      </div>


      <!-- Step 3: Vaccine Selection -->
      <div v-if="step === 3">
        <h3 class="text-lg font-semibold mb-4">Vaccine Details</h3>
        <div class="mb-4">
          <label for="vaccineCategory" class="block text-sm font-medium text-gray-700">
            Vaccine Category
          </label>
          <select v-model="form.vaccineCategory" id="vaccineCategory" class="input">
            <option disabled value="">Select Category</option>
            <option v-for="category in vaccineCategories" :key="category" :value="category">
              {{ category }}
            </option>
          </select>
          <span v-if="errors.vaccineCategory" class="text-red-600 text-sm">{{ errors.vaccineCategory }}</span>
        </div>

        <div class="mb-4" v-if="form.vaccineCategory">
          <label for="vaccineType" class="block text-sm font-medium text-gray-700">
            Vaccine Type
          </label>
          <select v-model="form.vaccineType" id="vaccineType" class="input">
            <option disabled value="">Select Vaccine Type</option>
            <option v-for="type in vaccineTypesForCategory" :key="type" :value="type">
              {{ type }}
            </option>
            <span v-if="errors.vaccineType" class="text-red-600 text-sm">{{ errors.vaccineType }}</span>
          </select>
        </div>

        <div class="flex justify-between mt-6">
          <button @click="prevStep" class="bg-gray-500 text-white py-2 px-4 rounded-md">Back</button>
          <button @click="nextStep" class="bg-blue-500 text-white py-2 px-4 rounded-md">Next</button>
        </div>
      </div>

      <!-- Step 4: Vaccination Record Form -->
      <div v-if="step === 4">
        <h3 class="text-lg font-semibold mb-6">Vaccination Record</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Date of Visit -->
          <div>
            <label for="dateOfVisit" class="block text-sm font-medium text-gray-700">
              Date of Visit
            </label>
            <input type="date" v-model="form.dateOfVisit" id="dateOfVisit" class="input" required />
            <span v-if="errors.dateOfVisit" class="text-red-600 text-sm">{{ errors.dateOfVisit }}</span>
          </div>

          <!-- Conditional Age Input -->
          <div>
            <label for="age" class="block text-sm font-medium text-gray-700">
              {{ isUnderOneYear ? "Age in Months" : "Age in Years" }}
            </label>
            <input
              type="number"
              id="age"
              v-model="computedVaccinationAge"
              class="input"
              :placeholder="isUnderOneYear ? 'Enter age in months' : 'Enter age in years'"
              :value="computedVaccinationAge"
              readonly
            />
          </div>

          <!-- Weight -->
          <div>
            <label for="weight" class="block text-sm font-medium text-gray-700">
              Weight
            </label>
            <input type="text" v-model="form.weight" id="weight" class="input" @input="validateWeight"
              placeholder="Enter weight (e.g., 10 kg)" required />
            <span v-if="errors.weight" class="text-red-600 text-sm">{{ errors.weight }}</span>
          </div>

          <!-- Height -->
          <div>
            <label for="height" class="block text-sm font-medium text-gray-700">
              Height
            </label>
            <input type="text" v-model="form.height" id="height" class="input" @input="validateHeight"
              placeholder="Enter height (e.g., 75 cm)" required />
            <span v-if="errors.height" class="text-red-600 text-sm">{{ errors.height }}</span>
          </div>

          <!-- Temperature -->
          <div>
            <label for="temperature" class="block text-sm font-medium text-gray-700">
              Temperature
            </label>
            <input type="text" v-model="form.temperature" id="temperature" @input="formatTemperature" class="input"
              placeholder="Enter temperature (e.g., 36.5°C)" required />
            <span v-if="errors.temperature" class="text-red-600 text-sm">{{ errors.temperature }}</span>
          </div>

          <!-- Antigen Given -->
          <div>
            <label for="antigenGiven" class="block text-sm font-medium text-gray-700">
              Antigen Given
            </label>
            <input type="text" v-model="form.antigenGiven" id="antigenGiven" class="input"
              placeholder="Enter antigen details" required />
            <span v-if="errors.antigenGiven" class="text-red-600 text-sm">{{ errors.antigenGiven }}</span>
          </div>

          <!-- Injected By -->
          <div>
            <label for="injectedBy" class="block text-sm font-medium text-gray-700">
              Injected By
            </label>
            <input type="text" v-model="form.injectedBy" id="injectedBy" class="input"
              placeholder="Enter name of injector" required />
            <span v-if="errors.injectedBy" class="text-red-600 text-sm">{{ errors.injectedBy }}</span>
          </div>

          <!-- Exclusively Breastfed (only for Under 1 Year) -->
          <div v-if="isUnderOneYear">
            <label for="exclusivelyBreastfed" class="block text-sm font-medium text-gray-700">
              Exclusively Breastfed
            </label>
            <select v-model="form.exclusivelyBreastfed" id="exclusivelyBreastfed" class="input">
              <option disabled value="">Select</option>
              <option value="Yes">Yes</option>
              <option value="No">No</option>
            </select>
          </div>

          <!-- Next Appointment -->
          <div>
            <label for="nextAppointment" class="block text-sm font-medium text-gray-700">
              Next Appointment
            </label>
            <input type="date" v-model="form.nextAppointment" id="nextAppointment" class="input" required />
            <span v-if="errors.nextAppointment" class="text-red-600 text-sm">{{ errors.nextAppointment }}</span>
          </div>
        </div>
        <div class="flex justify-between mt-6">
          <button @click="prevStep" class="bg-gray-500 text-white py-2 px-4 rounded-md">Back</button>
          <button @click="nextStep" class="bg-blue-500 text-white py-2 px-4 rounded-md">Next</button>

        </div>
      </div>

      <!-- Step 5: Review Data -->
      <div v-if="step === 5">
        <h3 class="text-lg font-semibold mb-4">Review Your Information</h3>
        <div class="grid grid-cols-2 gap-4">
          <!-- Loop through form fields to display data -->
          <div v-for="(value, key) in reviewFields" :key="key" class="border-b pb-2">
            <label class="block font-medium">{{ formatLabel(key) }}:</label>
            <p class="text-gray-600">{{ value || 'Not Provided' }}</p>
          </div>
        </div>
        <div class="flex justify-between mt-6">
          <button @click="prevStep" class="bg-gray-500 text-white py-2 px-4 rounded-md">Back</button>
          <button @click="showConfirmationModal = true" :disabled="!isFormValid"
            class="bg-green-500 text-white py-2 px-4 rounded-md disabled:bg-gray-400">
            Submit
          </button>
        </div>
      </div>

      <!-- Confirmation Modal -->
      <div v-if="showConfirmationModal"
        class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-60">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
          <h3 class="text-lg font-semibold mb-4">Confirm Submission</h3>
          <p class="mb-6">Are you sure you want to submit this vaccination record?</p>
          <div class="flex justify-end space-x-4">
            <button @click="showConfirmationModal = false"
              class="bg-gray-500 text-white py-2 px-4 rounded-md">Cancel</button>
            <button @click="saveVaccination" class="bg-green-500 text-white py-2 px-4 rounded-md">Yes, Submit</button>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import AlertMessage from '../AlertMessage.vue';

import { debounce } from 'lodash';
export default {
  props: {
    patients: {
        type: Array,
        default: () => [],
      },
  },
  components: {
    AlertMessage,
  },
  data() {
    return {
      showModal: true,
      showAlert: false,
      alertMessage: "",
      showConfirmationModal: false, // To toggle confirmation modal
      step: 1,
      searchQuery: "",
      filteredPatients: this.patients || [],
      selectedPatient: null,
      allowAddNewPatient: false,
      form: {
        firstName: '',
        lastName: '',
        middleName: '',
        suffix: '',
        purok: '',
        barangay: '',
        age: '',
        birthdate: '',
        contact: '',
        sex: '',
        vaccineCategory: "",
        vaccineType: "",
        dateOfVisit: "",
        ageInMonths: "",
        ageInYears: "",
        weight: "",
        height: "",
        temperature: "",
        antigenGiven: "",
        injectedBy: "",
        exclusivelyBreastfed: "None",
        nextAppointment: "",
      },
      vaccineCategories: [
        "Pregnant",
        "Under 1 Year",
        "School-aged Immunization",
        "Senior Citizen",
      ],
      vaccineTypes: {
        Pregnant: ["Tetanus Toxoid", "Tetanus Diphtheria"],
        "Under 1 Year": [
          "BCG",
          "Hepatitis B",
          "Pentavalent 1, 2, 3",
          "Bivalent Oral Polio Vaccine 1, 2, 3",
          "Inactivated Polio Vaccine 1, 2, 3",
          "Pneumococcal Conjugate Vaccine 1, 2, 3",
          "Measles, Mumps, Rubella 1, 2",
        ],
        "School-aged Immunization": [
          "Grade 1: Measles & Rubella",
          "Grade 4: Tetanus Diphtheria",
          "HPV",
        ],
        "Senior Citizen": ["PPV23", "Flu Vaccine"],
      },
      vaccineTypesForCategory: [],
      errors: {},
    };
  },
  watch: {
    searchQuery: {
      immediate: true,
      handler() {
        this.debouncedSearchPatients();
      },
    },
    patients(newPatients) {
      this.filteredPatients = newPatients;
    },
    patients: {
      immediate: true,
      handler(newPatients) {
        console.log('Patients updated in modal:', newPatients);
        if (Array.isArray(newPatients) && newPatients.length > 0) {
          this.filteredPatients = [...newPatients]; // Update filtered patients
        } else {
          console.warn('Patients array is empty or undefined.');
          this.filteredPatients = [];
        }
      },
    },
    'form.birthdate': function () {
      this.form.age = this.patientAgeInYears; // Automatically update age when birthdate changes
    },
    "form.vaccineCategory": function () {
      this.updateVaccineTypes();
    },
    "form.vaccineCategory": function () {
      this.updateVaccineTypes();
      if (!this.isUnderOneYear) {
        this.form.exclusivelyBreastfed = null; // Reset this field if the category is not "Under 1 Year"
      }
    },
    'form.vaccineCategory': function (newVal) {
        if (newVal === 'Under 1 Year') {
            // Reset the fields to empty when Referral is selected
            this.form.exclusivelyBreastfed = '';
            
        } else {
            // Set the fields to "None" when not Referral
            this.form.exclusivelyBreastfed = 'None';
        }
      },
    },
  computed: {
    patientAgeInYears() {
      if (!this.form.birthdate) return '';
      const birthDate = new Date(this.form.birthdate);
      const today = new Date();

      let age = today.getFullYear() - birthDate.getFullYear();

      // Check if the birthday has occurred this year
      const hasBirthdayOccurred = 
        today.getMonth() > birthDate.getMonth() || 
        (today.getMonth() === birthDate.getMonth() && today.getDate() >= birthDate.getDate());

      if (!hasBirthdayOccurred) {
        age--;
      }

      return age;
    },

    // Age for Vaccination Record Form (dynamic: months or years)
    computedVaccinationAge() {
      if (!this.form.birthdate) return "";
      const birthDate = new Date(this.form.birthdate);
      const today = new Date();

      // Calculate months
      const yearsDifference = today.getFullYear() - birthDate.getFullYear();
      const monthsDifference = today.getMonth() - birthDate.getMonth();
      let totalMonths = yearsDifference * 12 + monthsDifference;

      // Subtract one month if today’s day is earlier than the birthday day
      if (today.getDate() < birthDate.getDate()) {
        totalMonths--;
      }

      // Return months or years depending on the selected category
      return this.isUnderOneYear ? totalMonths : Math.floor(totalMonths / 12);
    },

    // Determine if the selected vaccine category is "Under 1 Year"
    isUnderOneYear() {
      return this.form.vaccineCategory === "Under 1 Year";
    },
  },
  
  methods: {
    formatLabel(key) {
      // Convert camelCase or snake_case keys to readable labels
      return key
        .replace(/([A-Z])/g, " $1") // Add space before uppercase letters
        .replace(/_/g, " ") // Replace underscores with spaces
        .replace(/\b\w/g, (char) => char.toUpperCase()); // Capitalize first letters
    },
    openConfirmationModal() {
      if (!this.validateStep4()) {
        this.alertMessage = "Please complete all required fields before submission.";
        this.showAlert = true;
        return;
      }
      this.showConfirmationModal = true; // Open the confirmation modal if validation passes
    },
    confirmSave() {
      this.saveVaccination(); // Call the save function if the user confirms
      this.showConfirmationModal = false; // Close the confirmation modal
    },
    cancelConfirmation() {
      this.showConfirmationModal = false; // Close the confirmation modal without saving
    },
    searchPatients() {
      const query = this.searchQuery.trim().toLowerCase();
      console.log('Search Query:', query);

      if (!Array.isArray(this.patients) || this.patients.length === 0) {
        console.warn('Patients array is empty or undefined.');
        this.filteredPatients = [];
        return;
      }

      if (query === '') {
        console.log('Clearing filtered patients because query is empty.');
        this.filteredPatients = [...this.patients];
        return;
      }

      this.filteredPatients = this.patients.filter((patient) => {
        const fullName = `${patient.firstName} ${patient.lastName}`.toLowerCase();
        return (
          fullName.includes(query) ||
          patient.firstName.toLowerCase().includes(query) ||
          patient.lastName.toLowerCase().includes(query) ||
          (patient.personalId && patient.personalId.toString().includes(query))
        );
      });

      console.log('Filtered Patients:', this.filteredPatients);
      this.allowAddNewPatient = this.filteredPatients.length === 0;
    },

    // Debounced search for better performance
    debouncedSearchPatients: debounce(function () {
      console.log('Debounced search triggered:', this.searchQuery);
      this.searchPatients();
    }, 300),
    
    updateAgeFields() {
      const age = this.computedVaccinationAge;
      if (this.isUnderOneYear) {
        this.form.ageInMonths = age; // Set months for "Under 1 Year"
        this.form.ageInYears = ""; // Clear years
      } else {
        this.form.ageInYears = age; // Set years
        this.form.ageInMonths = ""; // Clear months
      }
    },
    handleAgeInput(event) {
      if (this.isUnderOneYear) {
        this.form.ageInMonths = "";
      } else {
        this.form.ageInYears = "";
      }
    },
    formatContact() {
      this.form.contact = this.form.contact.replace(/[^0-9]/g, '').slice(0, 10);
    },
    updateVaccineTypes() {
      this.vaccineTypesForCategory = this.vaccineTypes[this.form.vaccineCategory] || [];
    },
    validateHeight(event) {
      let value = event.target.value.replace(/[^0-9.]/g, ''); // Allow only numbers and a single decimal point

      // Handle multiple decimal points
      const parts = value.split('.');
      if (parts.length > 2) {
        value = `${parts[0]}.${parts[1]}`; // Keep only the first decimal point
      }

      // Auto-format when the input reaches 4 digits without a decimal
      if (value.length === 4 && !value.includes('.')) {
        value = `${value.slice(0, 3)}.${value.slice(3)}`; // Add decimal before the last digit
      }

      // Limit to 3 digits before the decimal and 1 digit after
      if (value.includes('.')) {
        const [integer, decimal] = value.split('.');
        value = `${integer.slice(0, 3)}.${decimal.slice(0, 1)}`;
      }

      this.form.height = value; // Update the form value
    },
    formatTemperature(event) {
      let value = event.target.value.replace(/[^0-9]/g, ''); // Remove all non-numeric characters

      if (value.length > 2) {
        value = `${value.slice(0, 2)}.${value.slice(2, 3)}`; // Automatically add the decimal after the second digit
      }

      this.form.temperature = value; // Update the input value
    },
    validateWeight(event) {
      // Retrieve and sanitize the input value
      let value = event.target.value.replace(/[^0-9.]/g, ''); // Allow only numeric characters and a single decimal point

      // Ensure there is only one decimal point
      const parts = value.split('.');
      if (parts.length > 2) {
        value = `${parts[0]}.${parts[1]}`; // Combine the first two parts, discarding extra decimals
      }

      // Auto-format when the input reaches 4 digits without a decimal
      if (value.length === 4 && !value.includes('.')) {
        value = `${value.slice(0, 3)}.${value.slice(3)}`; // Add a decimal before the last digit
      }

      // If a decimal exists, enforce limits on the integer and decimal parts
      if (value.includes('.')) {
        const [integer, decimal] = value.split('.');
        value = `${integer.slice(0, 3)}.${decimal.slice(0, 1)}`; // Limit to 3 digits before and 1 digit after the decimal
      } else {
        // If no decimal, limit the integer part to 3 digits
        value = value.slice(0, 3);
      }

      // Update the form weight with the validated value
      this.form.weight = value;
    },
    closeModal() {
      this.showModal = false;
    },
    nextStep() {
      if (this.step === 1 && this.selectedPatient) {
        this.step = 3; // Skip to vaccine selection if patient exists
      } else if (this.step === 1 && this.validateStep1()) {
        this.step++;
      } else if (this.step === 2 && this.validateStep2()) {
        this.step++;
      } else if (this.step === 3 && this.validateStep3()) {
        this.step++;
      } else if (this.step === 4 && this.validateStep4()) {
        this.step++; // Move to step 5 for review
      } else if (this.step === 5) {
        this.saveVaccination(); // Save the details after review
      }
    },
    prevStep() {
      if (this.step > 1) {
        this.step--;
      }
    },
    addOrNextStep() {
      if (this.selectedPatient) {
        // If a patient is selected, go directly to Step 3
        this.step = 3;
      } else if (this.allowAddNewPatient) {
        this.step = 2;
      } else if (this.selectedPatient) {
        this.step++;
      }
    },
    filterPatients() {
      const query = this.searchQuery.trim().toLowerCase();
      this.filteredPatients = this.patients.filter(patient =>
        patient.name.toLowerCase().includes(query)
      );
      this.allowAddNewPatient = this.filteredPatients.length === 0;
    },
    selectPatient(patient) {
      this.selectedPatient = patient;
      this.allowAddNewPatient = false;
    },
    validateStep1() {
      this.errors = {};
      if (!this.selectedPatient && !this.allowAddNewPatient) {
        this.errors.step1 = 'Please select a patient or proceed to add a new one.';
        return false;
      }
      return true;
    },
    validateStep2() {
      this.errors = {};
      let valid = true;

      if (!this.form.firstName) {
        this.errors.firstName = 'First name is required.';
        valid = false;
      }
      if (!this.form.lastName) {
        this.errors.lastName = 'Last name is required.';
        valid = false;
      }
      if (!this.form.purok) {
        this.errors.purok = 'Purok is required.';
        valid = false;
      }
      if (!this.form.barangay) {
        this.errors.barangay = 'Barangay is required.';
        valid = false;
      }
      if (!this.form.birthdate) {
        this.errors.birthdate = 'Birthdate is required.';
        valid = false;
      }

      return valid;
    },
    validateStep3() {
      this.errors = {};
      let valid = true;

      if (!this.form.vaccineCategory) {
        this.errors.vaccineCategory = 'Vaccine Category is required.';
        valid = false;
      }
      if (!this.form.vaccineType) {
        this.errors.vaccineType = 'Vaccine Type is required.';
        valid = false;
      }
      return valid;
    },
    validateStep4() {
      this.errors = {};
      let valid = true;

      if (!this.form.dateOfVisit) {
        this.errors.dateOfVisit = 'Date of Visit is required.';
        valid = false;
      }
      if (!this.form.weight) {
        this.errors.weight = 'Weight is required.';
        valid = false;
      }
      if (!this.form.height) {
        this.errors.height = 'Height is required.';
        valid = false;
      }
      if (!this.form.temperature) {
        this.errors.temperature = 'Temperature is required.';
        valid = false;
      }
      if (!this.form.antigenGiven) {
        this.errors.antigenGiven = 'Antigen Given is required.';
        valid = false;
      }
      if (!this.form.injectedBy) {
        this.errors.injectedBy = 'Injected By field is required.';
        valid = false;
      }
      if (this.isUnderOneYear && !this.form.exclusivelyBreastfed) {
        this.errors.exclusivelyBreastfed = 'This field is required for children under 1 year.';
        valid = false;
      }
      if (!this.form.nextAppointment) {
        this.errors.nextAppointment = 'Next Appointment is required.';
        valid = false;
      }

      return valid;
    },
    saveVaccination() {
      if (this.isUnderOneYear) {
        this.form.ageInMonths = this.computedVaccinationAge;
        this.form.ageInYears = "";
      } else {
        this.form.ageInYears = this.computedVaccinationAge;
        this.form.ageInMonths = "";
      }

      // Check if all validations are passed before saving
      if (!this.validateStep4()) {
        this.alertMessage = "Please complete all required fields";
        this.showAlert = true;
        return;
      }
      console.log("Vaccination data saved:", this.form); // Verify the form object
      this.showConfirmationModal = false;
      this.closeModal();

      // Consolidate all data from the form
      const vaccinationData = {
        patient: this.selectedPatient
          ? { ...this.selectedPatient }
          : {
            firstName: this.form.firstName,
            lastName: this.form.lastName,
            middleName: this.form.middleName,
            suffix: this.form.suffix,
            purok: this.form.purok,
            barangay: this.form.barangay,
            age: this.computedAge,
            birthdate: this.form.birthdate,
            contact: this.form.contact,
            sex: this.form.sex,
          },
        vaccinationDetails: {
          vaccineCategory: this.form.vaccineCategory,
          vaccineType: this.form.vaccineType,
          dateOfVisit: this.form.dateOfVisit,
          age: this.isUnderOneYear ? this.form.ageInMonths : this.form.ageInYears,
          weight: this.form.weight,
          height: this.form.height,
          temperature: this.form.temperature,
          antigenGiven: this.form.antigenGiven,
          injectedBy: this.form.injectedBy,
          exclusivelyBreastfed: this.isUnderOneYear
            ? this.form.exclusivelyBreastfed
            : null,
          nextAppointment: this.form.nextAppointment,
        },
      };

      // Log the consolidated data
      console.log("Vaccination saved:", vaccinationData);

      this.alertMessage = "Submission successful!";
      this.showAlert = true;

      // Reset form and close modal
      this.resetForm();
      this.closeModal();
    },
    resetForm() {
      this.form = {
        firstName: '',
        lastName: '',
        middleName: '',
        suffix: '',
        purok: '',
        barangay: '',
        age: '',
        birthdate: '',
        contact: '',
        sex: '',
        vaccineCategory: '',
        vaccineType: '',
        dateOfVisit: '',
        ageInMonths: '',
        ageInYears: '',
        weight: '',
        height: '',
        temperature: '',
        antigenGiven: '',
        injectedBy: '',
        exclusivelyBreastfed: '',
        nextAppointment: '',
      };
      this.selectedPatient = null;
      this.errors = {};
      this.step = 1; // Reset to the first step
    }

  },
  mounted() {
    if (this.patients && Array.isArray(this.patients)) {
      this.filteredPatients = this.patients;
      console.log('Initialized filteredPatients:', this.filteredPatients);
    }
  },
};
</script>

<style scoped>
.input {
  width: 100%;
  padding: 8px;
  margin-top: 4px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
}
</style>
