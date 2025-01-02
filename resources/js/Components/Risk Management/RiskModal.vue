<template>
  <div v-if="showModal"
    class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl p-6 mx-auto mt-10 mb-10"
      style="max-height: 90vh; overflow-y: auto;">
      <!-- Modal Header -->
      <div class="flex justify-between items-center border-b pb-4 mb-4">
        <h2 class="text-xl font-semibold">Risk Management Assessment</h2>
        <button @click="closeModal" class="text-gray-500 hover:text-gray-800">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Step 1: Search or Add Patient -->
      <div v-if="step === 1">
        <h3 class="text-lg font-semibold mb-4">Search for a Patient</h3>
        <div>
          <label for="search" class="block text-sm font-medium text-gray-700">Search by Name</label>
          <input type="text" v-model="searchQuery" @input="filterPatients" id="search" class="input"
            placeholder="Enter patient name or ID" />
        </div>

        <!-- Loading Indicator -->
        <div v-if="loading" class="loading-indicator mt-4 flex items-center">
          <svg class="animate-spin h-5 w-5 mr-3" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none">
            </circle>
            <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
          </svg>
          Searching...
        </div>

        <!-- Patient List -->
        <div v-if="searchQuery.trim() && !loading && filteredPatients.length > 0"
          class="patient-list mt-4 bg-white border border-gray-300 rounded-lg max-h-60 overflow-y-auto">
          <div v-for="patient in filteredPatients" :key="patient.personalId" @click="selectPatient(patient)" :class="[
            'patient-item cursor-pointer px-4 py-2',
            { 'bg-gray-100': selectedPatient?.personalId === patient.personalId, 'hover:bg-gray-50': true }
          ]">
            <div class="flex justify-between items-center">
              <div>
                <span class="font-medium">
                  {{ patient.firstName }} {{ patient.middleName }} {{ patient.lastName }}
                  {{ patient.suffix !== 'None' ? patient.suffix : '' }}
                </span>
              </div>
              <div class="text-sm text-gray-500">Age: {{ patient.age }}</div>
            </div>
          </div>
        </div>

        <!-- No Results Message -->
        <div v-else-if="searchQuery.trim() && !loading" class="mt-4 text-sm text-gray-500 px-4 py-2">
          No patients found. You can proceed to add a new patient.
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-4 mt-6">
          <button @click="closeModal"
            class="bg-orange-400 text-white py-2 px-4 rounded-md hover:bg-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-300 shadow-md transition-all">
            Cancel
          </button>
          <button :disabled="!searchQuery.trim() || (!selectedPatient && !allowAddNewPatient)" @click="addOrNextStep"
            class="py-2 px-4 rounded-md shadow-md text-white transition-all 
         disabled:opacity-50 disabled:cursor-not-allowed
         bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-300">
            {{ selectedPatient ? "Next" : "Add New Patient" }}
          </button>

        </div>

      </div>


      <!-- Step 2: Add Patient Details -->
      <div v-if="step === 2">
        <h3 class="text-lg font-semibold mb-4">
          {{ selectedPatient ? "Patient Details" : "Add New Patient" }}
        </h3>
        <div v-if="selectedPatient" class="mb-4 p-4 bg-gray-50 rounded-lg">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <p class="text-sm text-gray-500">Full Name</p>
              <p class="font-medium">
                {{ selectedPatient.firstName }} {{ selectedPatient.middleName }} {{ selectedPatient.lastName }}
                {{ selectedPatient.suffix !== 'None' ? selectedPatient.suffix : '' }}
              </p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Patient ID</p>
              <p class="font-medium">{{ selectedPatient.personalId }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Gender</p>
              <p class="font-medium">{{ selectedPatient.sex }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Birthdate</p>
              <p class="font-medium">{{ selectedPatient.birthdate }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Age</p>
              <p class="font-medium">{{ selectedPatient.age }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Address</p>
              <p class="font-medium">{{ selectedPatient.purok }} {{ selectedPatient.barangay }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Contact</p>
              <p class="font-medium">{{ selectedPatient.contact }}</p>
            </div>
          </div>
        </div>

        <div v-else>
          <!-- Patient Information Form -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block">First Name:</label>
              <input type="text" v-model="form.firstName" class="input" placeholder="Example: Juan" required />
            </div>
            <div>
              <label class="block">Last Name:</label>
              <input type="text" v-model="form.lastName" class="input" placeholder="Example: Dela Cruz" required />

            </div>

            <div>
              <label class="block">Middle Name:</label>
              <input type="text" v-model="form.middleName" class="input" placeholder="Example: Penduko" required />

            </div>
            <div>
              <label for="suffix" class="block text-sm font-medium text-gray-700">Suffix:</label>
              <select v-model="form.suffix"
                class="input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-blue-500">
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
              <select id="barangay" v-model="form.barangay" class="input" required>
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
            </div>


            <div>
              <label class="block">Purok:</label>
              <input type="text" v-model="form.purok" class="input" placeholder="Example: Purok 1A" required />
            </div>
            <div>
              <label class="block">Birthdate:</label>
              <input type="date" v-model="form.birthdate" class="input" required />
            </div>
            <div>
              <label class="block">Age:</label>
              <input type="number" v-model="computedAge" class="input" readonly />
            </div>
            <!-- Contact Number -->
            <div>
              <label class="block mb-1 font-medium text-gray-700">Contact Number:</label>
              <div class="relative">
                <input type="tel" v-model="form.contact" @input="formatContact"
                  class="pl-14 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-200 focus:border-blue-500 w-full"
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
        <div class="mt-6 flex justify-between">
          <button @click="prevStep"
            class="bg-orange-400 text-white py-2 px-4 rounded-md hover:bg-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-300 shadow-md transition-all">
            Back
          </button>
          <button @click="nextStep"
            class="py-2 px-4 rounded-md shadow-md text-white transition-all
         bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-300">
            Next
          </button>

        </div>
      </div>

      <!-- Step 3: Risk Factors -->
      <div v-if="step === 3">
        <div class="grid grid-cols-1 gap-4">
          <!-- High Fat/High Salt Food Intake -->
          <div>
            <h3 class="block text-lg font-bold text-gray-700">High Fat/High Salt Food Intake </h3>
            <label>
              Eats processed/fastfoods (e.g., instant noodles, hamburgers,fries,fried chicken skin, etc.,) and ihaw-ihaw
              (e.g., isaw,etc.,)
              weekly.
            </label>
            <div class="flex gap-4 mt-2">
              <label><input type="radio" v-model="form.foodIntake" value="Yes" /> Yes</label>
              <label><input type="radio" v-model="form.foodIntake" value="No" /> No</label>
            </div>
          </div>

          <!-- Dietary Fiber Intake -->
          <div class="mb-5 mt-5">
            <h3 class="block text-lg font-bold text-gray-700">Dietary Fiber Intake</h3>

            <!-- 3 Servings of Vegetables -->
            <div class="mt-2">
              <label class="block text-sm text-gray-700">3 servings of vegetables daily</label>
              <div class="mt-2 flex gap-4">
                <label class="flex items-center gap-2">
                  <input type="radio" v-model="form.vegetableIntake" value="Yes" class="radio" />
                  Yes
                </label>
                <label class="flex items-center gap-2">
                  <input type="radio" v-model="form.vegetableIntake" value="No" class="radio" />
                  No
                </label>
              </div>
            </div>

            <!-- 2-3 Servings of Fruits -->
            <div class="mt-4">
              <label class="block text-sm text-gray-700">2-3 servings of fruits daily</label>
              <div class="mt-2 flex gap-4">
                <label class="flex items-center gap-2">
                  <input type="radio" v-model="form.fruitIntake" value="Yes" class="radio" />
                  Yes
                </label>
                <label class="flex items-center gap-2">
                  <input type="radio" v-model="form.fruitIntake" value="No" class="radio" />
                  No
                </label>
              </div>
            </div>
          </div>

          <!-- Physical Activity -->
          <div class="mb-5">
            <h3 class="block text-lg font-bold text-gray-700">Physical Activities <br /> </h3>
            <label>
              Does at least 2.5 hours a week of moderate-intensity physical activity.
            </label>
            <div class="mt-2 flex gap-4">
              <label><input type="radio" v-model="form.physicalActivity" value="Yes" /> Yes</label>
              <label><input type="radio" v-model="form.physicalActivity" value="No" /> No</label>
            </div>
          </div>

          <div>
            <!-- Question 1 -->
            <div class="mb-2">
              <h3 class="block text-lg font-bold text-gray-700">Presence or absence of Diabetes</h3>
              <label class="block text-sm font-medium text-gray-700">
                1. Was the patient diagnosed as having diabetes?
              </label>
              <div class="mt-2 flex gap-4">
                <label class="flex items-center gap-2">
                  <input type="radio" v-model="form.diabetesDiagnosis" value="Yes" class="radio" />
                  Yes
                </label>
                <label class="flex items-center gap-2">
                  <input type="radio" v-model="form.diabetesDiagnosis" value="No" class="radio" />
                  No
                </label>
              </div>
            </div>

            <!-- Follow-up for "Yes" -->
            <div v-if="form.diabetesDiagnosis === 'Yes'" class="mb-4">
              <label class="block text-sm font-medium text-gray-700">
                If yes, is the patient:
              </label>
              <div class="mt-2 flex gap-4">
                <label class="flex items-center gap-2">
                  <input type="radio" v-model="form.diabetesMedication" value="With Medication" class="radio" />
                  With Medication
                </label>
                <label class="flex items-center gap-2">
                  <input type="radio" v-model="form.diabetesMedication" value="Without Medication" class="radio" />
                  Without Medication
                </label>
              </div>
              <p class="mt-2 font-bold text-sm text-red-900">
                AND PERFORM URINE TEST FOR KETONES.
              </p>

              <!-- Urine Ketones Input -->
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Presence of Urine Ketones</label>
                <div class="mt-2 flex gap-4">
                  <label class="flex items-center gap-2">
                    <input type="radio" v-model="form.urineKetones" value="Yes" class="radio" />
                    Yes
                  </label>
                  <label class="flex items-center gap-2">
                    <input type="radio" v-model="form.urineKetones" value="No" class="radio" />
                    No
                  </label>
                </div>
                <div v-if="form.urineKetones === 'Yes'" class="mt-4">
                  <label for="urineKetoneLevel" class="block text-sm font-medium text-gray-700">Urine Ketone
                    Level</label>
                  <input type="text" id="urineKetoneLevel" v-model="form.urineKetoneLevel" class="input"
                    placeholder="Enter value" />
                  <label for="ketonesDate" class="block text-sm font-medium text-gray-700 mt-4">Date Taken</label>
                  <input type="date" id="ketonesDate" v-model="form.ketonesDate" class="input" />
                </div>
              </div>
            </div>

            <!-- Question 2 -->
            <!-- Question 2 -->
            <div v-if="form.diabetesDiagnosis === 'No'" class="mb-2">
              <label class="block text-sm font-medium text-gray-700">
                2. Does the patient have the following symptoms?
              </label>
              <div class="mt-2">
                <label class="flex items-center gap-2">
                  <input type="checkbox" v-model="form.symptoms" value="Polyphagia" class="checkbox" />
                  Polyphagia
                </label>
                <label class="flex items-center gap-2">
                  <input type="checkbox" v-model="form.symptoms" value="Polyuria" class="checkbox" />
                  Polyuria
                </label>
              </div>
              <p class="mt-2 text-sm text-gray-500">
                If two or more of the above symptoms are present, perform a blood glucose test.
              </p>

              <!-- Raised Blood Glucose Input -->
              <div v-if="form.symptoms.length >= 2" class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Raised Blood Glucose</label>
                <div class="mt-2 flex gap-4">
                  <label class="flex items-center gap-2">
                    <input type="radio" v-model="form.raisedBloodGlucose" value="Yes" class="radio" />
                    Yes
                  </label>
                  <label class="flex items-center gap-2">
                    <input type="radio" v-model="form.raisedBloodGlucose" value="No" class="radio" />
                    No
                  </label>
                </div>
                <div v-if="form.raisedBloodGlucose === 'Yes'" class="mt-4">
                  <label for="fbsRbs" class="block text-sm font-medium text-gray-700">FBS/RBS</label>
                  <input type="text" id="fbsRbs" v-model="form.fbsRbsMg" class="input"
                    placeholder="Enter value (e.g., 100 mg/dL)" />
                  <input type="text" id="fbsRbsMmol" v-model="form.fbsRbsMmol" class="input mt-2"
                    placeholder="Enter value (e.g., 5.6 mmol/L)" />
                  <label for="glucoseDate" class="block text-sm font-medium text-gray-700 mt-4">Date Taken</label>
                  <input type="date" id="glucoseDate" v-model="form.glucoseDate" class="input" />
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-6 flex justify-between">
          <button @click="prevStep"
            class="bg-orange-400 text-white py-2 px-4 rounded-md hover:bg-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-300 shadow-md transition-all">
            Back
          </button>
          <button @click="nextStep"
            class="bg-gradient-to-r from-orange-500 to-orange-600 text-white py-2 px-4 rounded-md hover:from-orange-600 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-300 shadow-md transition-all">
            Next
          </button>

        </div>
      </div>

      <div v-if="step === 4">
        <h3 class="text-lg font-semibold mb-4">Review Your Information</h3>
        <div class="grid grid-cols-2 gap-4">
          <!-- Loop through form fields to display data -->
          <div v-for="(value, key) in reviewFields" :key="key" class="border-b pb-2">
            <label class="block font-medium">{{ formatLabel(key) }}:</label>
            <p class="text-gray-600">{{ value || 'Not Provided' }}</p>
          </div>
        </div>
        <div class="flex justify-between mt-6">
          <button @click="prevStep" class="bg-orange-400 text-white py-2 px-4 rounded-md hover:bg-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-300 shadow-md transition-all">Back</button>
          <button @click="showConfirmationModal = true"
          class="bg-gradient-to-r from-orange-500 to-orange-600 text-white py-2 px-4 rounded-md hover:from-orange-600 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-300 shadow-md transition-all">
            Save
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
             class="bg-orange-400 text-white py-2 px-4 rounded-md hover:bg-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-300 shadow-md transition-all" >Cancel</button>
            <button @click="saveVaccination"  class="bg-gradient-to-r from-orange-500 to-orange-600 text-white py-2 px-4 rounded-md hover:from-orange-600 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-300 shadow-md transition-all">Yes, Submit</button>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { router } from '@inertiajs/vue3';

export default {
  props: {
    showModal: {
      type: Boolean,
      required: true
    },
    patients: {
      type: Array,
      required: true,
      default: () => [],
    },
  },
  data() {
    return {
      step: 1,
      searchQuery: '',
      selectedPatient: null,
      filteredPatients: this.patients,
      allowAddNewPatient: true,
      showConfirmationModal: false,
      loading: false,
      form: {
        firstName: '',
        lastName: '',
        middleName: '',
        suffix: '',
        barangay: '',
        purok: '',
        birthdate: '',
        contact: '',
        sex: '',
        foodIntake: '',
        fruitIntake: '',
        vegetableIntake: '',
        diabetesDiagnosis: '',
        diabetesMedication: '',
        symptoms: [],
        physicalActivity: '',
        raisedBloodGlucose: '',
        fbsRbsMg: '',
        fbsRbsMmol: '',
        raisedBloodGlucoseDate: '',
        raisedBloodLipids: '',
        totalCholesterol: '',
        raisedBloodLipidsDate: '',
        urineKetones: '',
        urineKetoneLevel: '',
        urineKetonesDate: '',
        urineProtein: '',
        urineProteinLevel: '',
        urineProteinDate: '',
      }
    };

  },
  methods: {
    capitalizeName(field) {
      if (this.form[field]) {
        this.form[field] = this.form[field]
          .split(" ") // Split the input by spaces
          .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()) // Capitalize the first letter
          .join(" "); // Join the words back together
      }
    },
    formatLabel(key) {
      // Convert camelCase or snake_case keys to readable labels
      return key
        .replace(/([A-Z])/g, " $1") // Add space before uppercase letters
        .replace(/_/g, " ") // Replace underscores with spaces
        .replace(/\b\w/g, (char) => char.toUpperCase()); // Capitalize first letters
    },
    openConfirmationModal() {
      if (!this.validateStep3()) {
        this.alertMessage = "Please complete all required fields before submission.";
        this.showAlert = true;
        return;
      }
      this.showConfirmationModal = true; // Open the confirmation modal if validation passes
    },
    confirmSave() {
      this.saveRiskData(); // Call the save function if the user confirms
      this.showConfirmationModal = false; // Close the confirmation modal
    },
    cancelConfirmation() {
      this.showConfirmationModal = false; // Close the confirmation modal without saving
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

      // Validate Food Intake
      if (!this.form.foodIntake) {
        this.errors.foodIntake = 'Food intake is required.';
        valid = false;
      }

      // Validate Fruit Intake
      if (!this.form.fruitIntake) {
        this.errors.fruitIntake = 'Fruit intake is required.';
        valid = false;
      }

      // Validate Vegetable Intake
      if (!this.form.vegetableIntake) {
        this.errors.vegetableIntake = 'Vegetable intake is required.';
        valid = false;
      }

      // Validate Diabetes Diagnosis
      if (!this.form.diabetesDiagnosis) {
        this.errors.diabetesDiagnosis = 'Diabetes diagnosis is required.';
        valid = false;
      }

      // Validate Diabetes Medication (if diagnosed)
      if (
        this.form.diabetesDiagnosis === 'Yes' &&
        !this.form.diabetesMedication
      ) {
        this.errors.diabetesMedication = 'Diabetes medication status is required.';
        valid = false;
      }

      // Validate Symptoms (if no diagnosis)
      if (
        this.form.diabetesDiagnosis === 'No' &&
        this.form.symptoms.length < 2
      ) {
        this.errors.symptoms = 'At least two symptoms are required for further tests.';
        valid = false;
      }

      // Validate Physical Activity
      if (!this.form.physicalActivity) {
        this.errors.physicalActivity = 'Physical activity is required.';
        valid = false;
      }

      // Validate Raised Blood Glucose (if applicable)
      if (
        this.form.symptoms.length >= 2 &&
        !this.form.raisedBloodGlucose
      ) {
        this.errors.raisedBloodGlucose = 'Raised blood glucose status is required.';
        valid = false;
      }

      if (
        this.form.raisedBloodGlucose === 'Yes' &&
        (!this.form.fbsRbsMg || !this.form.fbsRbsMmol || !this.form.raisedBloodGlucoseDate)
      ) {
        this.errors.raisedBloodGlucoseDetails = 'FBS/RBS details and date are required.';
        valid = false;
      }

      // Validate Raised Blood Lipids (if applicable)
      if (
        this.form.raisedBloodLipids === 'Yes' &&
        (!this.form.totalCholesterol || !this.form.raisedBloodLipidsDate)
      ) {
        this.errors.raisedBloodLipidsDetails = 'Total cholesterol and date are required.';
        valid = false;
      }

      // Validate Urine Ketones (if applicable)
      if (
        this.form.urineKetones === 'Yes' &&
        (!this.form.urineKetoneLevel || !this.form.urineKetonesDate)
      ) {
        this.errors.urineKetonesDetails = 'Urine ketone level and date are required.';
        valid = false;
      }

      // Validate Urine Protein (if applicable)
      if (
        this.form.urineProtein === 'Yes' &&
        (!this.form.urineProteinLevel || !this.form.urineProteinDate)
      ) {
        this.errors.urineProteinDetails = 'Urine protein level and date are required.';
        valid = false;
      }

      return valid;
    },
    searchPatients() {
      this.loading = true; // Show loading indicator
      try {
        const response = Inertia.get('/path-to-your-endpoint', {
          query: this.searchQuery // Pass the search query
        });

        // Assuming the response contains the patients data
        this.filteredPatients = response.data.patients; // Update with the fetched patients
      } catch (error) {
        console.error("Error fetching patients:", error);
      } finally {
        this.loading = false; // Hide loading indicator
      }
    },
    filterPatients() {
      if (this.searchQuery) {
        this.filteredPatients = this.patients.filter(patient => {
          const fullName = `${patient.firstName} ${patient.lastName}`.toLowerCase();
          return (
            fullName.includes(this.searchQuery.toLowerCase()) ||
            patient.firstName.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
            patient.lastName.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
            (patient.personalId && patient.personalId.toString().includes(this.searchQuery))
          );
        });

        // Reset selectedPatient if no patients are found
        if (this.filteredPatients.length === 0) {
          this.selectedPatient = null; // Ensure this is set to null when no matches
        }
      } else {
        this.filteredPatients = this.patients; // Reset to all patients if search query is empty
      }
    },
    selectPatient(patient) {
      this.selectedPatient = patient;
      // Pre-fill the form with selected patient's data
      this.form = {
        ...this.form,
        firstName: patient.firstName,
        lastName: patient.lastName,
        middleName: patient.middleName || '',
        suffix: patient.suffix || '',
        barangay: patient.barangay,
        purok: patient.purok,
        birthdate: patient.birthdate,
        contact: patient.contact,
        sex: patient.sex
      };
    },
    closeModal() {
      this.$emit('close');
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
      } else if (this.step === 4) {
        this.saveRiskData();
      }
    },
    prevStep() {
      if (this.step > 1) {
        this.step--;
      }
    },
    addOrNextStep() {
      if (this.selectedPatient) {
        this.nextStep();
      } else {
        // Clear form for new patient
        Object.keys(this.form).forEach(key => {
          if (!['foodIntake',
            'physicalActivity',
            'bloodGlucose',
            'fbsRbs',
            'bloodGlucoseDate',
            'bloodLipids',
            'totalCholesterol',
            'bloodLipidsDate',
            'urineKetones',
            'urineKetoneLevel',
            'urineKetonesDate',
            'urineProtein',
            'urineProteinLevel',
            'urineProteinDate'].includes(key)) {
            this.form[key] = '';
          }
        });
        this.nextStep();
      }
    },
    saveRiskData() {
      if (!this.validateStep3()) {
        this.alertMessage = "Please complete all required fields";
        this.showAlert = true;
        return;
      }
      console.log("Risk Management data saved:", this.form); // Verify the form object
      this.showConfirmationModal = false;
      this.closeModal();
      this.form.age = this.computedAge;

      const riskData = {
        riskDetails: {
          foodIntake: this.form.foodIntake,
          physicalActivity: this.form.physicalActivity,
          bloodGlucose: this.form.bloodGlucose,
          fbsRbs: this.form.fbsRbs,
          bloodGlucoseDate: this.form.bloodGlucoseDate,
          bloodLipids: this.form.bloodLipids,
          totalCholesterol: this.form.totalCholesterol,
          bloodLipidsDate: this.form.bloodLipidsDate,
          urineKetones: this.form.urineKetones,
          urineKetoneLevel: this.form.urineKetoneLevel,
          urineKetonesDate: this.form.urineKetonesDate,
          urineProtein: this.form.urineProtein,
          urineProteinLevel: this.form.urineProteinLevel,
          urineProteinDate: this.form.urineProteinDate,
        },
      };

      // For existing patients, only send the personalId
      if (this.selectedPatient?.personalId) {
        riskData.patient = {
          personalId: this.selectedPatient.personalId,
          isExisting: true // Add a flag to indicate this is an existing patient
        };
      } else {
        // For new patients, include all details
        riskData.patient = {
          firstName: this.form.firstName,
          lastName: this.form.lastName,
          middleName: this.form.middleName,
          suffix: this.form.suffix,
          purok: this.form.purok,
          barangay: this.form.barangay,
          age: this.form.age,
          birthdate: this.form.birthdate,
          contact: this.form.contact,
          sex: this.form.sex,
          isExisting: false
        };
      }
      Inertia.post('/risk-management/store', riskData, {
        onSuccess: () => {
          console.log("Risk Management Data Successfully Submitted:", riskData);
          this.resetForm();
          this.closeModal();
        },
        onError: (errors) => {
          console.error("Submission failed:", errors);
          this.errors = errors;
          console.log("Risk Management Data Failed to Submit:", riskData);
        },
      });
    }
  },
  computed: {
    reviewFields() {
    return {
      'Food Intake': this.form.foodIntake || 'Not Provided',
      'Fruit Intake': this.form.fruitIntake || 'Not Provided',
      'Vegetable Intake': this.form.vegetableIntake || 'Not Provided',
      'Diabetes Diagnosis': this.form.diabetesDiagnosis || 'Not Provided',
      'Diabetes Medication': this.form.diabetesMedication || 'Not Provided',
      'Symptoms': this.form.symptoms.length
        ? this.form.symptoms.join(', ')
        : 'Not Provided',
      'Physical Activity': this.form.physicalActivity || 'Not Provided',
      'Raised Blood Glucose': this.form.raisedBloodGlucose || 'Not Provided',
      'FBS/RBS (mg/dL)': this.form.fbsRbsMg || 'Not Provided',
      'FBS/RBS (mmol/L)': this.form.fbsRbsMmol || 'Not Provided',
      'Raised Blood Glucose Date': this.form.raisedBloodGlucoseDate || 'Not Provided',
      'Raised Blood Lipids': this.form.raisedBloodLipids || 'Not Provided',
      'Total Cholesterol': this.form.totalCholesterol || 'Not Provided',
      'Raised Blood Lipids Date': this.form.raisedBloodLipidsDate || 'Not Provided',
      'Urine Ketones': this.form.urineKetones || 'Not Provided',
      'Urine Ketone Level': this.form.urineKetoneLevel || 'Not Provided',
      'Urine Ketones Date': this.form.urineKetonesDate || 'Not Provided',
      'Urine Protein': this.form.urineProtein || 'Not Provided',
      'Urine Protein Level': this.form.urineProteinLevel || 'Not Provided',
      'Urine Protein Date': this.form.urineProteinDate || 'Not Provided',
    };
  },
    computedAge() {
      if (!this.form.birthdate) return '';
      const birthDate = new Date(this.form.birthdate);
      const today = new Date();
      let age = today.getFullYear() - birthDate.getFullYear();
      const monthDiff = today.getMonth() - birthDate.getMonth();
      if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
      }
      return age;
    }
  },
  watch: {
    searchQuery: {
      handler(val) {
        if (val.length >= 2) {
          this.filterPatients();
        } else {
          this.filteredPatients = [];
        }
      },
      debounce: 300
    }
  }
};
</script>

<style scoped>
.radio {
  accent-color: #4a90e2;
  /* Tailwind accent color */
}

.checkbox {
  accent-color: #4a90e2;
}

.input {
  @apply mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500;
}

.patient-list {
  @apply mt-4 bg-white border border-gray-300 rounded-lg max-h-60 overflow-y-auto divide-y divide-gray-200;
}

.patient-item {
  @apply px-4 py-3 hover:bg-gray-50 cursor-pointer transition duration-150;
}

.patient-item.selected {
  @apply bg-blue-50;
}

.loading-indicator {
  @apply flex items-center justify-center py-4 text-gray-500;
}

[type='checkbox']:checked,
[type='radio']:checked {
  border-color: transparent;
  background-color: hsla(11, 80%, 45%, 1);
  background-size: 100% 100%;
  background-position: center;
  background-repeat: no-repeat;
}
</style>