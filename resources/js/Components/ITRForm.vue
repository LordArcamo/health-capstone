<template>
  <div class="min-h-screen flex flex-col justify-center items-center">
    <div class="bg-white shadow-md rounded-lg p-8 max-w-4xl w-full">
      <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Patient Information Record</h2>
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
              <input type="text" v-model="form.firstName" @input="capitalizeName('firstName')" class="input"
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

              <!-- Error message if no suffix is selected -->
              <span v-if="errors.suffix" class="text-red-600 text-sm">{{ errors.suffix }}</span>
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
              <input type="date" v-model="form.birthdate" class="input" required @input="restrictBirthdateInput"
                @change="validateBirthdate" />
              <span v-if="errors.birthdate" class="text-red-600 text-sm">{{ errors.birthdate }}</span>
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
          <div class="mt-6 flex justify-center text-right">
            <button @click="nextStep" class="btn">Next</button>
          </div>
        </div>

        <!-- Step 2: Consultation Details -->
        <div v-if="step === 2">
          <h3 class="text-lg font-semibold mb-4">Consultation Details</h3>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block">Consultation Date:</label>
              <input type="date" v-model="form.consultationDate" class="input" required />
              <span v-if="errors.consultationDate" class="text-red-600 text-sm">{{ errors.consultationDate }}</span>
            </div>

            <div>
              <label class="block">Consultation Time:</label>
              <input type="time" v-model="form.consultationTime" class="input" required @input="handleTimeSelection" />
              <span v-if="errors.consultationTime" class="text-red-600 text-sm">{{ errors.consultationTime }}</span>
            </div>

            <div>
              <label class="block">Mode of Transaction:</label>
              <select v-model="form.modeOfTransaction" class="input" required>
                <!-- Placeholder option for selecting suffix -->
                <option value="" disabled selected>Select a Mode of Transaction</option>
                <option value="Walk-in">Consultation</option>
                <option value="Visited">Emergency</option>
                <option value="Referral">Referral</option>
              </select>
              <span v-if="errors.modeOfTransaction" class="text-red-600 text-sm">{{ errors.modeOfTransaction }}</span>
            </div>
            <div>
              <label class="block">Nature of Visit:</label>
              <select v-model="form.natureOfVisit" class="input" required>
                <option value="" disabled>Select a Nature of Visit</option>
                <option value="New Consultation/Case">New Consultation/Case</option>
                <option value="New Admission">New Admission</option>
                <option value="Follow-Up Visit">Follow-Up Visit</option>
              </select>
              <span v-if="errors.natureOfVisit" class="text-red-600 text-sm">{{ errors.natureOfVisit }}</span>
            </div>
            <div>
              <label class="block">Type of Consultation/Purpose of Visit:</label>
              <select v-model="form.visitType" class="input" @change="checkMentalHealth" required>
                <option value="" disabled selected>Select a Type</option>
                <option value="General">General</option>
                <option value="Dental Care">Dental Care</option>
                <option value="Child Care">Child Care</option>
                <option value="Injury">Injury</option>
                <option value="Adult Immunization">Adult Immunization</option>
                <option value="Postpartum">Postpartum</option>
                <option value="Tuberculosis">Tuberculosis</option>
                <option value="Child Immunization">Child Immunization</option>
                <option value="Sick Children">Sick Children</option>
                <option value="Firecracker Injury">Firecracker Injury</option>
              </select>
              <span v-if="errors.visitType" class="text-red-600 text-sm">{{ errors.visitType }}</span>
            </div>
            <div>
              <label class="block">Blood Pressure:</label>
              <input type="text" v-model="form.bloodPressure" placeholder="Example: 120/80"
                class="input border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                @input="formatBloodPressure" required />
              <span v-if="errors.bloodPressure" class="text-red-600 text-sm">{{ errors.bloodPressure }}</span>
            </div>
            <div>
              <label class="block">Temperature (°C):</label>
              <input type="text" v-model="form.temperature" placeholder="Example: 37.5°C"
                class="input border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                @input="formatTemperature" required />
              <span v-if="errors.temperature" class="text-red-600 text-sm">{{ errors.temperature }}</span>
            </div>
            <div>
              <label class="block">Height (cm):</label>
              <input type="text" v-model="form.height" placeholder="Example: 180" class="input" @input="validateHeight"
                required />
              <span v-if="errors.height" class="text-red-600 text-sm">{{ errors.height }}</span>
            </div>
            <div>
              <label class="block">Weight (kg):</label>
              <input type="text" v-model="form.weight" placeholder="Example: 70" class="input" @input="validateWeight"
                required />
              <span v-if="errors.weight" class="text-red-600 text-sm">{{ errors.weight }}</span>
            </div>
            <div>
              <label class="block">Name of Attending Physician:</label>
              <input
     type="text"
  v-model="form.providerName"
  @input="autoDoctor"
  placeholder="Example: Dr. Jose Legazpi"
  class="input"
  />
              <span v-if="errors.providerName" class="text-red-600 text-sm">{{ errors.providerName }}</span>
            </div>

          </div>
          <div v-if="form.modeOfTransaction === 'Referral'" class="mt-4">
            <h3 class="text-lg font-semibold mb-4">Referred Transaction Details</h3>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block">Referred From:</label>
                <input type="text" v-model="form.referredFrom" class="input" placeholder="Referred From" />
              </div>
              <div>
                <label class="block">Referred To:</label>
                <input type="text" v-model="form.referredTo" class="input" placeholder="Referred To" />
              </div>
              <div>
                <label class="block">Reasons for Referral:</label>
                <textarea v-model="form.reasonsForReferral" class="input"
                  placeholder="Describe reasons for referral"></textarea>
              </div>
              <div>
                <label class="block">Referred By:</label>
                <input type="text" v-model="form.referredBy" class="input" placeholder="Referred By" />
              </div>
            </div>
          </div>
          <div class="mt-6 flex justify-between">
            <button @click="prevStep" class="btn">Back</button>
            <button @click="nextStep" class="btn">Next</button>
          </div>
        </div>

        <!-- Step 3: Visit Information -->
        <!-- <div v-if="step === 3">
          <h3 class="text-lg font-semibold mb-4">Visit Information</h3>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block">Name of Attending Physician:</label>
              <input type="text" v-model="form.providerName" @input="capitalizeName('providerName')" placeholder="Example: Dr. Jose Legazpi" class="input" />
              <span v-if="errors.providerName" class="text-red-600 text-sm">{{ errors.providerName }}</span>
            </div>

            <div>
              <label class="block">Chief Complaints:</label>
              <textarea v-model="form.chiefComplaints" placeholder="Example: Low Bowel Movements etc."
                class="input"></textarea>
                <span v-if="errors.chiefComplaints" class="text-red-600 text-sm">{{ errors.chiefComplaints }}</span>
            </div>
            <div>
              <label class="block">Diagnosis:</label>
              <textarea v-model="form.diagnosis" placeholder="Example: Diarrhea" class="input"></textarea>
              <span v-if="errors.diagnosis" class="text-red-600 text-sm">{{ errors.diagnosis }}</span>
            </div>
            <div>
              <label class="block">Medication/Treatment:</label>
              <textarea v-model="form.medication" placeholder="Example: Loperamide" class="input"></textarea>
              <span v-if="errors.medication" class="text-red-600 text-sm">{{ errors.medication }}</span>
            </div>
          </div>
          <div class="mt-6 flex justify-between">
            <button @click="prevStep" class="btn">Back</button>
            <button @click="nextStep" class="btn">Next</button>
          </div>
        </div> -->

        <!-- Step 4: Review Submitted Data -->
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

    <!-- Confirmation Modal -->
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
            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-500 transition">
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
      default() {
        return {};
      }
    },
    onSubmit: Function,
    natureOfVisit: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      step: 1,
      stepTitles: ['Patient Information', 'Consultation Details', 'Review Information'],
      showModal: false,
      alertMessage: '',
      errors: {},
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
        consultationDate: '',
        consultationTime: '',
        modeOfTransaction: '',
        bloodPressure: '',
        temperature: '',
        height: '',
        weight: '',
        natureOfVisit: this.natureOfVisit || '', 
        visitType: '',
        providerName: '',
        referredFrom: '',
        referredTo: '',
        reasonsForReferral: '',
        referredBy: '',
      },
    };
  },
  computed: {
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
      return this.form.contact.length === 10;
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
    natureOfVisit: {
      immediate: true,
      handler(newValue) {
        if (newValue) {
          this.form.natureOfVisit = newValue;
        }
      }
    },
    // Watch for changes in birthdate and update the age field
    computedAge(newAge) {
      this.form.age = newAge;
    },
    'form.birthdate': function () {
      this.form.age = this.computedAge; // Update age when birthdate changes
    },
    'form.modeOfTransaction': function (newVal) {
      if (newVal === 'Referral') {
        // Reset the fields to empty when Referral is selected
        this.form.referredFrom = '';
        this.form.referredTo = '';
        this.form.reasonsForReferral = '';
        this.form.referredBy = '';
      } else {
        // Set the fields to "None" when not Referral
        this.form.referredFrom = 'None';
        this.form.referredTo = 'None';
        this.form.reasonsForReferral = 'None';
        this.form.referredBy = 'None';
      }
    },
  },
  methods: {
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
        consultationDate: '',
        consultationTime: '',
        modeOfTransaction: '',
        bloodPressure: '',
        temperature: '',
        height: '',
        weight: '',
        natureOfVisit: '',
        visitType: '',
        providerName: '',
        referredFrom: '',
        referredTo: '',
        reasonsForReferral: '',
        referredBy: '',
      };
      this.errors = {};
    },
    populateForm(patient) {
      console.log("Populating form with patient:", patient);
      this.form = {
        firstName: this.selectedPatient?.firstName || '',
        lastName: this.selectedPatient?.lastName || '',
        middleName: this.selectedPatient?.middleName || '',
        suffix: this.selectedPatient?.suffix || '',
        purok: this.selectedPatient?.purok || '',
        barangay: this.selectedPatient?.barangay || '',
        age: '',
        birthdate: this.selectedPatient?.birthdate || '',
        contact: this.selectedPatient?.contact || '',
        sex: this.selectedPatient?.sex || '',

      };
      this.form.age = this.computedAge;

    },
    formatLabel(key) {
      return key.replace(/([A-Z])/g, ' $1').replace(/^./, str => str.toUpperCase());
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
    
    validateConsultationDate() {
      const today = new Date(new Date().toLocaleString("en-US", { timeZone: "Asia/Manila" }));
      const inputDate = new Date(this.form.consultationDate);

      console.log(today);  // Always prints the current date and time in the Philippines

      if (!this.form.consultationDate) {
        this.errors.consultationDate = "Consultation date is required.";
        return false;
      }

      if (isNaN(inputDate.getTime())) {
        this.errors.consultationDate = "Please enter a valid date.";
        return false;
      }

      if (inputDate > today) {
        this.errors.consultationDate = "Consultation date cannot be in the future.";
        return false;
      }

      // Clear error if validation passes
      this.errors.consultationDate = "";
      return true;
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
    handleTimeSelection(event) {
      // Automatically retract the time picker
      const input = this.$refs.timeInput;

      // Force blur after a change (immediately hide dropdown)
      if (input) {
        setTimeout(() => {
          input.blur();
        }, 100); // Adding a slight delay for smoothness
      }

      // Optional Validation
      if (!this.form.consultationTime) {
        this.errors.consultationTime = "Consultation time is required.";
      } else {
        this.errors.consultationTime = "";
      }
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
    restrictBirthdateInput(event) {
      const input = event.target.value;

      // Restrict year part to a maximum of 4 digits
      const parts = input.split('-');
      if (parts[0] && parts[0].length > 4) {
        parts[0] = parts[0].slice(0, 4); // Limit the year to 4 digits
      }

      // Reassemble the date and update the form value
      this.form.birthdate = parts.join('-');
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
        this.errors.birthdate = 'Please enter a birthdate that is valid.';
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
    validateStep1() {
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

      const today = new Date();
      const consultationDate = new Date(this.form.consultationDate);

      if (!this.form.consultationDate) {
        this.errors.consultationDate = "Consultation date is required.";
        valid = false;
      } else if (isNaN(consultationDate.getTime())) {
        this.errors.consultationDate = "Please enter a valid date.";
        valid = false;
      } else if (consultationDate > today) {
        this.errors.consultationDate = "Consultation date cannot be in the future.";
        valid = false;
      }

      if (!this.form.consultationTime) {
        this.errors.consultationTime = "Consultation time is required.";
        valid = false;
      }
      if (!this.form.modeOfTransaction) {
        this.errors.modeOfTransaction = "Mode of Transaction is required.";
        valid = false;
      }
      if (!this.form.bloodPressure) {
        this.errors.bloodPressure = "Blood Pressure is required.";
        valid = false;
      }
      if (!this.form.temperature) {
        this.errors.temperature = "Temperature is required.";
        valid = false;
      }
      if (!this.form.height) {
        this.errors.height = "Height is required.";
        valid = false;
      }
      if (!this.form.weight) {
        this.errors.weight = "Weight is required.";
        valid = false;
      }

      return valid;
    },

    // validateStep3() {
    //   this.errors = {};
    //   let valid = true;

    //   if (!this.form.providerName) {
    //     this.errors.providerName = 'Provider name is required.';
    //     valid = false;
    //   }
    //   if (!this.form.natureOfVisit) {
    //     this.errors.natureOfVisit = 'Nature of visit is required.';
    //     valid = false;
    //   }
    //   if (!this.form.visitType) {
    //     this.errors.visitType = 'Visit type is required.';
    //     valid = false;
    //   }
    //   if (!this.form.chiefComplaints) {
    //     this.errors.chiefComplaints = 'Chief Complaints is required.';
    //     valid = false;
    //   }
    //   if (!this.form.diagnosis) {
    //     this.errors.diagnosis = 'Diagnosis is required.';
    //     valid = false;
    //   }
    //   if (!this.form.medication) {
    //     this.errors.medication = 'Medication is required.';
    //     valid = false;
    //   }
    //   return valid;
    // },
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
    formatContact() {
      // Remove all non-numeric characters first
      this.form.contact = this.form.contact.replace(/[^0-9]/g, '');

      // Ensure the number starts with "9" and limit to 11 digits
      if (!this.form.contact.startsWith('9')) {
        this.form.contact = '9' + this.form.contact.slice(0, 10); // Add "9" and limit to 10 more digits
      } else {
        this.form.contact = this.form.contact.slice(0, 10); // Limit to 11 digits if it already starts with "9"
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
      const payload = {
        personalId: this.selectedPatient?.personalId || null,
        id: this.id,
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
        consultationDate: this.form.consultationDate,
        consultationTime: this.form.consultationTime,
        modeOfTransaction: this.form.modeOfTransaction,
        bloodPressure: this.form.bloodPressure,
        temperature: this.form.temperature,
        height: parseFloat(this.form.height),
        weight: parseFloat(this.form.weight),
        referredFrom: this.form.referredFrom || 'None',
        referredTo: this.form.referredTo || 'None',
        reasonsForReferral: this.form.reasonsForReferral || 'None',
        referredBy: this.form.referredBy || 'None',
        natureOfVisit: this.form.natureOfVisit,
        visitType: this.form.visitType,
        providerName: this.form.providerName,
      };

      console.log('Submitting form with payload:', payload);

      // Emit the form data for processing
      this.$emit('submitForm', payload);

      this.showModal = false; // Hide confirmation modal
    },

    cancelSubmit() {
      this.showModal = false; // Hide modal
    },
  },
  mounted() {
    console.log('Component mounted with natureOfVisit:', this.natureOfVisit);
    if (this.natureOfVisit) {
      this.$nextTick(() => {
        this.form.natureOfVisit = this.natureOfVisit;
        console.log('Set natureOfVisit to:', this.form.natureOfVisit);
      });
    }
    this.setAutoDateTime();
    setInterval(this.setAutoDateTime, 0); // Updates every 60 seconds
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
/* Styling for the default input[type="time"] */
.time-input {
  width: 100%;
  padding: 8px;
  margin-top: 4px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
  color: #333;
  box-sizing: border-box;
  outline: none;
}

/* Focus styling */
.time-input:focus {
  border-color: #28a745;
  box-shadow: 0 0 4px rgba(40, 167, 69, 0.5);
}

/* Custom placeholder for time input (may not show in all browsers) */
.time-input::-webkit-datetime-edit-fields-wrapper {
  background-color: #f9f9f9;
  border-radius: 4px;
}

.time-input::-webkit-datetime-edit {
  padding: 0 4px;
  color: #555;
}

.time-input::-webkit-calendar-picker-indicator {
  cursor: pointer;
  background: none;
  margin-right: 4px;
  opacity: 0.6;
}

.time-input::-webkit-calendar-picker-indicator:hover {
  opacity: 1;
}

.input {
  width: 100%;
  padding: 8px;
  margin-top: 4px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
}

.btn {
  padding: 8px 16px;
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
