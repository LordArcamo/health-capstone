<template>
  <div class="min-h-screen flex flex-col justify-center items-center">
    <div class="bg-white shadow-md rounded-lg p-8 max-w-5xl w-full">
      <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Prenatal Checkup</h2>

      <!-- Step Navigation -->
      <div class="relative z-10 flex justify-between items-center pb-4 border-b">
        <div class="absolute inset-0 flex items-center justify-between px-4">
          <!-- Connecting lines -->
          <div v-for="(_, index) in stepTitles.length - 1" :key="index" class="flex-1 h-0.5 bg-gray-300">
            <div v-if="step > index + 1" class="h-0.5 bg-gradient-to-r from-[#0F8F46] to-[#FED035]"
              :style="{ width: '100%' }"></div>
          </div>
        </div>
        <div v-for="(stepTitle, index) in stepTitles" :key="index" class="relative z-10">
          <button type="button" class="px-4 py-2 rounded-sm text-sm font-semibold flex items-center justify-center"
            :class="{
              'bg-gradient-to-r from-[#0F8F46] to-[#FED035] text-white': step === index + 1,
              'bg-gray-200 text-gray-800': step !== index + 1,
            }" @click="navigateToStep(index + 1)">
            {{ stepTitle }}
          </button>
        </div>
      </div>

      <!-- Alert -->
      <div v-if="alertMessage"
        class="fixed top-16 left-1/2 transform -translate-x-1/2 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg flex items-center z-50"
        role="alert">
        <svg class="fill-current w-6 h-6 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
          <path
            d="M10 15a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm.883-9.885a.4.4 0 00-.766 0l-3.5 9.5a.4.4 0 00.766.27l3.5-9.5a.4.4 0 00-.344-.54h-.07zm-.883 2.55a.6.6 0 100 1.2.6.6 0 000-1.2z" />
        </svg>
        <span>{{ alertMessage }}</span>
        <button @click="closeAlert" class="ml-4 bg-transparent border-0 text-red-700 font-bold focus:outline-none">
          ✕
        </button>
      </div>

      <form @submit.prevent="triggerSubmit">
        <!-- Step 1: Patient Information -->
        <div v-if="step === 1">
          <h3 class="text-lg font-semibold mb-4">Patient Information</h3>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block">First Name:</label>
              <input type="text" v-model="form.firstName" class="input" @input="capitalizeName('firstName')"
                placeholder="Example: Juan" required />
              <span v-if="errors.firstName" class="text-red-600 text-sm">{{ errors.firstName }}</span>
            </div>
            <div>
              <label class="block">Last Name:</label>
              <input type="text" v-model="form.lastName" class="input" @input="capitalizeName('lastName')"
                placeholder="Example: Dela Cruz" required />
              <span v-if="errors.lastName" class="text-red-600 text-sm">{{ errors.lastName }}</span>
            </div>
            <div>
              <label class="block">Middle Name:</label>
              <input type="text" v-model="form.middleName" class="input" @input="capitalizeName('middleName')"
                placeholder="Example: Penduko" required />
              <span v-if="errors.middleName" class="text-red-600 text-sm">{{ errors.middleName }}</span>
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
              <span v-if="errors.barangay" class="text-red-600 text-sm">{{ errors.barangay }}</span>
            </div>

            <div>
              <label class="block">Purok:</label>
              <input type="text" v-model="form.purok" class="input" @input="capitalizeName('purok')"
                placeholder="Example: Purok 1A" required />
              <span v-if="errors.purok" class="text-red-600 text-sm">{{ errors.purok }}</span>
            </div>

            <div>
              <label class="block">Birthdate:</label>
              <input type="date" v-model="form.birthdate" class="input"  required :max="maxBirthdate" />
              <span v-if="errors.birthdate" class="text-red-600 text-sm">{{ errors.birthdate }}</span>
            </div>

            <div>
              <label class="block">Age:</label>
              <input type="number" v-model="computedAge" class="input" readonly />
            </div>

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

          </div>
          <div class="mt-6 flex justify-center text-right">
            <button @click="nextStep" class="btn">Next</button>
          </div>
        </div>

        <!-- Step 2: Consultation Details -->
        <div v-if="step === 2">
          <h3 class="text-lg font-semibold mb-4">For CHU/RHU Personnel Only</h3>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block">Date of Consultation:</label>
              <input type="date" v-model="form.consultationDate" class="input" required />
              <span v-if="errors.consultationDate" class="text-red-600 text-sm">{{ errors.consultationDate }}</span>
            </div>
            <div>
              <label class="block">Consultation Time:</label>
              <input type="time" v-model="form.consultationTime" class="input" required />
              <span v-if="errors.consultationTime" class="text-red-600 text-sm">{{ errors.consultationTime }}</span>
            </div>
            <div>
              <label class="block">Mode of Transaction:</label>
              <select v-model="form.modeOfTransaction" class="input" required>
                <!-- Placeholder option for selecting suffix -->
                <option value="" disabled selected>Select a Mode of Transaction</option>
                <option>Walk-in</option>
                <option>Visited</option>
                <option>Referral</option>
              </select>
              <span v-if="errors.modeOfTransaction" class="text-red-600 text-sm">{{ errors.modeOfTransaction }}</span>
            </div>
            <!-- <div>
              <label class="block">Referred From</label>
              <input type="text" v-model="form.reasonForReferral" class="input"></input>
            </div>-->
            <div>
              <label class="block">Blood Pressure:</label>
              <input type="text" v-model="form.bloodPressure" placeholder="Example: 120/80"
                class="input border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                @input="formatBloodPressure" />
              <span v-if="errors.bloodPressure" class="text-red-600 text-sm">{{ errors.bloodPressure }}</span>
            </div>
            <div>
              <label class="block">Temperature (°C):</label>
              <input type="text" v-model="form.temperature" placeholder="Example: 37.5°C"
                class="input border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                @input="formatTemperature" />
              <span v-if="errors.temperature" class="text-red-600 text-sm">{{ errors.temperature }}</span>

            </div>
            <div>
              <label class="block">Height (cm):</label>
              <input type="text" v-model="form.height" placeholder="Example: 180" class="input"
                @input="validateHeight" />
              <span v-if="errors.height" class="text-red-600 text-sm">{{ errors.height }}</span>
            </div>
            <div>
              <label class="block">Weight (kg):</label>
              <input type="text" v-model="form.weight" placeholder="Example: 70" class="input"
                @input="validateWeight" />
              <span v-if="errors.weight" class="text-red-600 text-sm">{{ errors.weight }}</span>
            </div>
          <div>
              <label class="block">Name of Attending Physician:</label>
              <select v-model="form.providerName" class="input">
                <option disabled value="">Select a physician</option>
                <option
                  v-for="doctor in doctors"
                  :key="doctor.id"
                  :value="doctor.fullName"
                >
                  {{ doctor.fullName }}
                </option>
              </select>
            </div>
            <div>
              <label class="block">Name of Spouse:</label>
              <input type="text" v-model="form.nameOfSpouse"
              placeholder="Example: Pedro Penduko"
              @input="capitalizeName('nameOfSpouse')"
              class="input">
              <span v-if="errors.nameOfSpouse" class="text-red-600 text-sm">{{ errors.nameOfSpouse }}</span>
            </div>
            <!-- Emergency Contact Number -->
            <div>
              <label class="block mb-1 font-medium text-gray-700">Emergency Contact Number:</label>
              <div class="relative">
                <input type="tel" v-model="form.emergencyContact" @input="formatEmergencyContact"
                  class="pl-14 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full"
                  placeholder="Enter 10 digits" required />
                <span
                  class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 text-sm pointer-events-none">
                  +63
                </span>
              </div>
              <span v-if="errors.emergencyContact" class="text-red-600 text-sm">{{ errors.emergencyContact }}</span>
            </div>

            <div>
              <label class="block">4ps?:</label>
              <select v-model="form.fourMember" class="input" required>
                <option value="" disabled selected>Select Yes or No</option>
                <option>Yes</option>
                <option>No</option>
              </select>
              <span v-if="errors.fourMember" class="text-red-600 text-sm">{{ errors.fourMember }}</span>
            </div>
            <div class="form-group">
              <label for="philhealthStatus" class="block font-medium text-gray-700">Philhealth Status:</label>
              <select id="philhealthStatus" v-model="form.philhealthStatus"
                class="input border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                required>
                <option value="" disabled selected>Select Philhealth Status</option>
                <option value="Member">Member</option>
                <option value="Dependent">Dependent</option>
              </select>
              <span v-if="errors.philhealthStatus" class="text-red-600 text-sm">{{ errors.philhealthStatus }}</span>

              <!-- Conditionally Render ID Input -->
              <div v-if="form.philhealthStatus === 'Member'" class="mt-4">
                <label for="philhealthNo" class="block font-medium text-gray-700">Philhealth ID Number:</label>
                <input id="philhealthNo" v-model="form.philhealthNo" type="text"
                  class="input border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  placeholder="Enter ID number" />
              </div>
            </div>

          </div>

          <div class="mt-6 flex justify-between">
            <button @click="prevStep" class="btn">Back</button>
            <button @click="nextStep" class="btn">Next</button>
          </div>
        </div>

        <!-- Step 6: Review Submitted Data -->
        <div v-if="step === 3">
          <h3 class="text-lg font-semibold mb-4">Review Your Information</h3>
          <div class="grid grid-cols-2 gap-4">
            <!-- Loop through form fields to display data -->
            <div v-for="(value, key) in form" :key="key">
              <label class="block">{{ formatLabel(key) }}:</label>
              <p class="input">{{ key === 'age' && value === 0 ? 'Less than 1 year' : (value || 'Not Provided') }}</p>
            </div>
          </div>
          <div class="mt-6 flex justify-between">
            <button @click="prevStep" class="btn">Back</button>
            <button type="submit" class="btn">Submit</button>
          </div>
        </div>

      </form>
      <!-- <div v-if="successMessage" class="mt-4 text-green-600 text-center">
        {{ successMessage }}
      </div> -->
    </div>
    <div v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
      <!-- Modal Content -->
      <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
        <!-- Modal Header -->
        <h2 class="text-xl font-bold text-gray-800 mb-4">Are you sure?</h2>
        <!-- Modal Body -->
        <p class="text-gray-600 mb-6">Do you want to submit the form?</p>
        <!-- Modal Actions -->
        <div class="flex justify-end space-x-3">
          <button @click="cancelSubmit"
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
            Cancel
          </button>
          <button @click="confirmSubmit"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
            Yes, Submit
          </button>
        </div>
      </div>
    </div>
  </div>

</template>

<script>
export default {
  props: {
    selectedPatient: {
      type: Object,
      required: false,
      default: () => ({}),
    },
    doctors: {
      type: Array,
      default: () => []
    },
    onSubmit: Function,
  },
  data() {
    return {
      isNewPatient: true,
      alertMessage: '',
      showModal: false,
      stepTitles: [
        'Patient Information',
        'For CHU/RHU Personnel Only',
        'Review Information'
      ],
      step: 1,
      form: {
        firstName: this.selectedPatient?.firstName || '',
        lastName: this.selectedPatient?.lastName || '',
        middleName: this.selectedPatient?.middleName || '',
        purok: this.selectedPatient?.purok || '',
        barangay: this.selectedPatient?.barangay || '',
        age: '',
        birthdate: this.selectedPatient?.birthdate || '',
        contact: this.selectedPatient?.contact || '',
        modeOfTransaction: '',
        consultationDate: '',
        consultationTime: '',
        bloodPressure: '',
        temperature: '',
        height: '',
        weight: '',
        providerName: '',
        nameOfSpouse: '',
        emergencyContact: '',
        fourMember: '',
        philhealthStatus: '',
        philhealthNo: ''
      },
      errors: {
        emergencyContact: '',
      },
      successMessage: '',
    };
  },
  computed: {
    maxBirthdate() {
      const today = new Date();
      return today.toISOString().split('T')[0]; // format: YYYY-MM-DD
    },
    computedAge() {
      if (!this.form.birthdate) return '';
      const birthDate = new Date(this.form.birthdate);
      const today = new Date();

      let age = today.getFullYear() - birthDate.getFullYear();
      const hasBirthdayOccurred =
        today.getMonth() > birthDate.getMonth() ||
        (today.getMonth() === birthDate.getMonth() && today.getDate() >= birthDate.getDate());

      if (!hasBirthdayOccurred) {
        age--;
      }

      // Return 0 if the calculated age is less than 1
      return age < 1 ? 0 : age;
    },
    isFormValid() {
      return this.form.contact.length === 10 && this.form.emergencyContact?.length === 10;
    },
  },
  watch: {
    selectedPatient: {
      immediate: true,
      handler(newPatient) {
        if (newPatient && newPatient.personalId === null) {
          // Clear the form for a new patient
          this.resetForm();
        } else if (newPatient && Object.keys(newPatient).length > 0) {
          // Populate the form with selected patient data
          this.populateForm(newPatient);
        }
      },
    },
    // Watch for changes in birthdate and update the age field
    computedAge(newAge) {
      this.form.age = newAge;
    },
    'form.birthdate': function () {
      this.form.age = this.computedAge; // Update age when birthdate changes
    },
  },
  'form.philhealthStatus': function (newVal) {
    if (newVal === 'Member') {
      // Reset the fields to empty when Referral is selected
      this.form.philhealthNo = '';
    } else {
      // Set the fields to "None" when not Referral
      this.form.philhealthNo = 'None';

    }
  },
  methods: {
    resetForm() {
      this.form = {
        firstName: '',
        lastName: '',
        middleName: '',
        purok: '',
        barangay: '',
        age: '',
        birthdate: '',
        contact: '',
        modeOfTransaction: '',
        consultationDate: '',
        consultationTime: '',
        bloodPressure: '',
        temperature: '',
        height: '',
        weight: '',
        providerName: '',
        nameOfSpouse: '',
        emergencyContact: '',
        fourMember: '',
        philhealthStatus: '',
        philhealthNo: '',
      };
      this.errors = {};
    },
    populateForm(patient) {
      console.log("Populating form with patient:", patient);
      this.form = {
        firstName: this.selectedPatient?.firstName || '',
        lastName: this.selectedPatient?.lastName || '',
        middleName: this.selectedPatient?.middleName || '',
        purok: this.selectedPatient?.purok || '',
        barangay: this.selectedPatient?.barangay || '',
        age: '',
        birthdate: this.selectedPatient?.birthdate || '',
        contact: this.selectedPatient?.contact || '',

      };
      this.form.age = this.computedAge;

    },
    setAutoDateTime() {
      const now = new Date();

      // Format the date to YYYY-MM-DD for the date input
      const year = now.getFullYear();
      const month = String(now.getMonth() + 1).padStart(2, '0'); // Month is zero-based
      const day = String(now.getDate()).padStart(2, '0');
      this.form.consultationDate = `${year}-${month}-${day}`;

      // Format the time to HH:MM for the time input
      const hours = String(now.getHours()).padStart(2, '0');
      const minutes = String(now.getMinutes()).padStart(2, '0');
      this.form.consultationTime = `${hours}:${minutes}`;
    },
    formatLabel(key) {
      // Convert camelCase keys to readable labels
      return key
        .replace(/([A-Z])/g, " $1") // Add spaces before uppercase letters
        .replace(/^./, (str) => str.toUpperCase()); // Capitalize the first letter
    },
    capitalizeName(field) {
      if (this.form[field]) {
        this.form[field] = this.form[field]
          .toLowerCase() // Convert entire string to lowercase first
          .replace(/\b\w/g, char => char.toUpperCase()); // Capitalize first letter of each word
      }
    },
    autoDoctor() {
        let value = this.form.providerName.trim();

        if (value && !value.toLowerCase().startsWith('dr.')) {
            this.form.providerName = `Dr. ${value.replace(/^Dr\.?\s*/i, '')}`;
        }
    },
    formatBloodPressure(event) {
      let value = event.target.value.replace(/[^0-9]/g, ''); // Remove all non-numeric characters
      if (value.length > 3) {
        value = `${value.slice(0, 3)}/${value.slice(3, 5)}`; // Insert slash after the third character
      }
      this.form.bloodPressure = value;
    },
    formatTemperature(event) {
      let value = event.target.value.replace(/[^0-9]/g, ''); // Remove all non-numeric characters

      if (value.length > 2) {
        value = `${value.slice(0, 2)}.${value.slice(2, 3)}`; // Automatically add the decimal after the second digit
      }

      this.form.temperature = value; // Update the input value
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
    validateBirthdate() {
      this.errors.birthdate = ''; // Reset error message

      if (!this.form.birthdate) {
        this.errors.birthdate = 'Birthdate is required.';
        return;
      }

      const birthDate = new Date(this.form.birthdate);
      const today = new Date();
      const maxDate = new Date(today.getFullYear() - 200, today.getMonth(), today.getDate());

      if (birthDate < maxDate) {
        this.errors.birthdate = 'Please enter a valid birthdate.';
        return;
      }

      if (birthDate > today) {
        this.errors.birthdate = 'Birthdate cannot be in the future.';
        this.form.birthdate = ''; // Optional: reset value
        return;
      }
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
    handleSubmit(event) {
      event.preventDefault();
      this.triggerSubmit();
    },
    validateStep1() {
      this.errors = {};
      let valid = true;

      // Validate each field
      if (!this.form.firstName) {
        this.errors.firstName = 'First name is required.';
        valid = false;
      }
      if (!this.form.lastName) {
        this.errors.lastName = 'Last name is required.';
        valid = false;
      }
      if (!this.form.middleName) {
        this.errors.middleName = 'Middle name is required.';
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
    validateStep2() {
      this.errors = {};
      let valid = true;

      if (!this.form.modeOfTransaction) {
        this.errors.modeOfTransaction = 'Mode of Transaction is required.';
        valid = false;
      }
      if (!this.form.consultationDate) {
        this.errors.consultationDate = 'Consultation date is required.';
        valid = false;
      }
      if (!this.form.consultationTime) {
        this.errors.consultationTime = 'Consultation time is required.';
        valid = false;
      }
      if (!this.form.height || isNaN(this.form.height) || parseFloat(this.form.height) <= 0) {
        this.errors.height = 'Valid height is required.';
        valid = false;
      }
      if (!this.form.weight || isNaN(this.form.weight) || parseFloat(this.form.weight) <= 0) {
        this.errors.weight = 'Valid weight is required.';
        valid = false;
      }
      if (!this.form.temperature || isNaN(this.form.temperature) || parseFloat(this.form.temperature) <= 0) {
        this.errors.temperature = 'Valid temperature is required.';
        valid = false;
      }
      if (!this.form.bloodPressure) {
        this.errors.bloodPressure = 'Blood Pressure is required.';
        valid = false;
      }
      if (!this.form.providerName) {
        this.errors.providerName = 'Provider Name is required.';
        valid = false;
      }
      if (!this.form.emergencyContact) {
        this.errors.emergencyContact = 'Emergency Contact is required.';
        valid = false;
      }
      if (!this.form.nameOfSpouse) {
        this.errors.nameOfSpouse = 'Name of Spouse is required.';
        valid = false;
      }
      if (!this.form.fourMember) {
        this.errors.fourMember = 'Name of Spouse is required.';
        valid = false;
      }
      if (!this.form.philhealthStatus) {
        this.errors.philhealthStatus = 'Philhealth Status is required.';
        valid = false;
      }
      return valid;
    },
    nextStep() {
      if (this.step === 1 && this.validateStep1()) {
        this.step++;
      } else if (this.step === 2 && this.validateStep2()) {
        this.step++;
      } else if (this.step === 3 && this.validateStep3()) {
        this.step = 3;;
      }

    },
    prevStep() {
      this.step--;
    },
    navigateToStep(targetStep) {
      if (targetStep > this.step) {
        // Validate the current step before proceeding
        const isCurrentStepValid =
          (this.step === 1 && this.validateStep1()) ||
          (this.step === 2 && this.validateStep2());

        if (!isCurrentStepValid) {
          this.alertMessage = 'Please Fill In the Needed Details Before Proceeding to the Next Step.';

          // Automatically close the alert after 2 seconds
          setTimeout(() => {
            this.alertMessage = '';
          }, 2000);

          return; // Prevent navigation
        }
      }
      // If validation passes or moving backward, update the step
      this.step = targetStep;
    },

    closeAlert() {
      this.alertMessage = ''; // Close the alert
    },
    formatEmergencyContact() {
      this.form.emergencyContact = this.form.emergencyContact.replace(/[^0-9]/g, ''); // Only allow numbers
      if (!this.form.emergencyContact.startsWith('0')) {
        this.form.emergencyContact = `0${this.form.emergencyContact}`; // Ensure it starts with "0"
      }
      this.form.emergencyContact = this.form.emergencyContact.slice(0, 11); // Limit to 11 digits
    },
    formatContact() {
      // Ensure the input starts with "09", remove non-numeric characters, and limit to 10 digits
      if (!this.form.contact.startsWith('0')) {
        this.form.contact = '0' + this.form.contact.replace(/[^0-9]/g, '');
      } else {
        this.form.contact = this.form.contact.replace(/[^0-9]/g, '').slice(0, 11);
      }
    },
    triggerSubmit() {
      if (this.validateStep1() && this.validateStep2()) {
        this.showModal = true; // Show confirmation modal
      } else {
        this.successMessage = 'Please complete all required fields before submitting.';
      }
    },
    confirmSubmit() {
      // Prepare the form data and parse weight and height as numbers
      const payload = {
        personalId: this.selectedPatient?.personalId || null,
        id: this.id,
        firstName: this.form.firstName,
        lastName: this.form.lastName,
        middleName: this.form.middleName,
        purok: this.form.purok,
        barangay: this.form.barangay,
        age: this.form.age,
        birthdate: this.form.birthdate,
        contact: this.form.contact,
        modeOfTransaction: this.form.modeOfTransaction,
        consultationDate: this.form.consultationDate,
        consultationTime: this.form.consultationTime,
        bloodPressure: this.form.bloodPressure,
        temperature: this.form.temperature,
        height: this.form.height,
        weight: this.form.weight,
        providerName: this.form.providerName,
        nameOfSpouse: this.form.nameOfSpouse,
        emergencyContact: this.form.emergencyContact,
        fourMember: this.form.fourMember,
        philhealthStatus: this.form.philhealthStatus,
        philhealthNo: this.form.philhealthNo || 'None',
      };
      if (this.selectedPatient?.personalId) {
        // Use existing values for suffix and sex
        payload.suffix = this.selectedPatient.suffix || 'None';
        payload.sex = this.selectedPatient.sex || 'Female';
      } else {
        // Assign default values for new patients
        payload.suffix = 'None';
        payload.sex = 'Female';
      }


      if (!this.selectedPatient?.personalId) {
        Object.assign(payload, {
          firstName: this.form.firstName,
          lastName: this.form.lastName,
          middleName: this.form.middleName,
          purok: this.form.purok,
          barangay: this.form.barangay,
          age: this.form.age,
          birthdate: this.form.birthdate,
          contact: this.form.contact,
        });
      }
      console.log('Submitting form with payload:', payload);
      // Emit the parsed form data
      this.$emit('submitForm', payload);

      // Display success message
      this.successMessage = 'Form submitted successfully!';
      this.showModal = false; // Hide modal
      this.errors = {};
      this.resetForm();
    },
    cancelSubmit() {
      this.showModal = false; // Hide modal
    },
  },
  mounted() {
    this.setAutoDateTime();
    setInterval(this.setAutoDateTime, 0);
    console.log('Received personalInfo:', this.personalInfo);
    if (!this.selectedPatient || Object.keys(this.selectedPatient).length === 0) {
      console.log('Rendering empty form for new patient');
    } else {
      console.log('Rendering form for existing patient:', this.selectedPatient);
      this.populateForm(this.selectedPatient);
    }
  },
};
</script>

<style scoped>
.input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  margin-top: 0.5rem;
}

.btn {
  background-color: #007523;
  color: white;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.btn:hover {
  background-color: #155a8a;
}
</style>
