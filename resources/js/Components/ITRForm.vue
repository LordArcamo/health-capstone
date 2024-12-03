<template>
  <div class="min-h-screen flex flex-col justify-center items-center">
    <div class="bg-white shadow-md rounded-lg p-8 max-w-4xl w-full">
      <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Individual Treatment Record</h2>

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
              <input type="text" v-model="form.purok" class="input" placeholder="Example: Purok 1A" required />
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
            </div>
            <div>
              <label class="block">Blood Pressure:</label>
              <input type="text" v-model="form.bloodPressure" placeholder="Example: 120/80"
                class="input border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                @input="formatBloodPressure" />
            </div>
            <div>
              <label class="block">Temperature (°C):</label>
              <input type="text" v-model="form.temperature" placeholder="Example: 37.5°C"
                class="input border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                @input="formatTemperature" />

            </div>
            <div>
              <label class="block">Height (cm):</label>
              <input type="text" v-model="form.height" placeholder="Example: 180" class="input"
                @input="validateHeight" />
            </div>
            <div>
              <label class="block">Weight (kg):</label>
              <input type="text" v-model="form.weight" placeholder="Example: 70" class="input"
                @input="validateWeight" />
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
        <div v-if="step === 3">
          <h3 class="text-lg font-semibold mb-4">Visit Information</h3>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block">Name of Healthcare Provider:</label>
              <input type="text" v-model="form.providerName" class="input" />
            </div>
            <div>
              <label class="block">Nature of Visit:</label>
              <select v-model="form.natureOfVisit" class="input" required>
                <option value="" disabled selected>Select a Nature of Visit</option>
                <option>New Consultation/Case</option>
                <option>New Admission</option>
                <option>Follow-up Visit</option>
              </select>
            </div>
            <div>
              <label class="block">Type of Consultation/Purpose of Visit:</label>
              <select v-model="form.visitType" class="input" @change="checkMentalHealth" required>
                <option value="" disabled selected>Select a Type</option>
                <option>General</option>
                <option>Dental Care</option>
                <option>Child Care</option>
                <option>Injury</option>
                <option>Adult Immunization</option>
                <option>Family Planning</option>
                <option>Postpartum</option>
                <option>Tuberculosis</option>
                <option>Child Immunization</option>
                <option>Sick Children</option>
                <option>Firecracker Injury</option>
                <option>Mental Health</option>
              </select>
            </div>

            <!-- Sessions Dropdown for Mental Health -->
            <div v-if="showSessions">
              <label class="block">Select Session:</label>
              <select v-model="form.session" class="input">
                <option value="" disabled selected>Select a Session</option>
                <option v-for="session in mentalHealthSessions" :key="session" :value="session">
                  {{ session }}
                </option>
              </select>
            </div>

            <div>
              <label class="block">Chief Complaints:</label>
              <textarea v-model="form.chiefComplaints" placeholder="Example: Low Bowel Movements etc."
                class="input"></textarea>
            </div>
            <div>
              <label class="block">Diagnosis:</label>
              <textarea v-model="form.diagnosis" placeholder="Example: Diarrhea" class="input"></textarea>
            </div>
            <div>
              <label class="block">Medication/Treatment:</label>
              <textarea v-model="form.medication" placeholder="Example: Loperamide" class="input"></textarea>
            </div>
          </div>
          <div class="mt-6 flex justify-between">
            <button @click="prevStep" class="btn">Back</button>
            <button @click="nextStep" class="btn">Next</button>
          </div>
        </div>

        <!-- Step 4: Review Submitted Data -->
        <div v-if="step === 4">
          <h3 class="text-lg font-semibold mb-4">Review Your Information</h3>
          <div class="grid grid-cols-2 gap-4">
            <!-- Loop through form fields to display data -->
            <div v-for="(value, key) in form" :key="key">
              <label class="block">{{ formatLabel(key) }}:</label>
              <p class="input">{{ value || 'Not Provided' }}</p>
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
  props: ['onSubmit'],
  data() {
    return {
      showSessions: false,
      alertMessage: '',
      showModal: false, // Tracks modal visibility
      mentalHealthSessions: [
        'Initial Assessment',
        'Counseling Session',
        'Follow-up Session',
        'Psychological Evaluation',
        'Therapeutic Intervention'
      ],
      stepTitles: ['Patient Information',
        'Consultation Details',
        'Visit Information',
        'Review Information'
      ],
      step: 1,
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
        providerName: '',
        natureOfVisit: '',
        visitType: '',
        chiefComplaints: '',
        diagnosis: '',
        medication: '',
        referredFrom: '',
        referredTo: '',
        reasonsForReferral: '',
        referredBy: '',
        session: '',
      },
      errors: {
        contact: '',
        birthdate: '',
      },
      successMessage: '',
    };
  },
  computed: {
    computedAge() {
      if (!this.form.birthdate) return '';
      const birthDate = new Date(this.form.birthdate);
      const age = new Date().getFullYear() - birthDate.getFullYear();
      return age;
    },
    isFormValid() {
      return this.form.contact.length === 10 && this.form.emergencyContact?.length === 10;
    },
  },
  methods: {
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
    checkMentalHealth() {
      this.showSessions = this.form.visitType === 'Mental Health';
      if (!this.showSessions) {
        this.form.session = '';
      }
    },
    handleSubmit(event) {
      event.preventDefault();
      this.triggerSubmit();
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

      if (!this.form.consultationDate) {
        this.errors.consultationDate = 'Consultation date is required.';
        valid = false;
      }
      if (!this.form.consultationTime) {
        this.errors.consultationTime = 'Consultation time is required.';
        valid = false;
      }
      return valid;
    },
    validateStep3() {
      this.errors = {};
      let valid = true;

      if (!this.form.providerName) {
        this.errors.providerName = 'Provider name is required.';
        valid = false;
      }
      if (!this.form.natureOfVisit) {
        this.errors.natureOfVisit = 'Nature of visit is required.';
        valid = false;
      }
      if (!this.form.visitType) {
        this.errors.visitType = 'Visit type is required.';
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
        this.step++;
      } else if (this.step === 3 && this.validateStep3()) {
        this.step = 4;
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
          (this.step === 2 && this.validateStep2()) ||
          (this.step === 3 && this.validateStep3());

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
      this.form.contact = this.form.contact.replace(/[^0-9]/g, '').slice(0, 10);
    },
    triggerSubmit() {
      if (this.validateStep1() && this.validateStep2() && this.validateStep3()) {
        this.showModal = true; // Show confirmation modal
      } else {
        this.successMessage = 'Please complete all required fields before submitting.';
      }
    },
    confirmSubmit() {
      // Prepare the form data and parse weight and height as numbers
      const formData = {
        ...this.form,
        weight: parseFloat(this.form.weight) || null, // Convert to number or null if invalid
        height: parseFloat(this.form.height) || null, // Convert to number or null if invalid
      };

      // Emit the parsed form data
      this.$emit('submitForm', formData);

      // Display success message
      this.successMessage = 'Form submitted successfully!';
      this.showModal = false; // Hide modal
      this.errors = {};
    },
    cancelSubmit() {
      this.showModal = false; // Hide modal
    },
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