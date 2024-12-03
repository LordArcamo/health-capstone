<template>
  <div class="min-h-screen flex flex-col justify-center items-center">
    <div class="bg-white shadow-md rounded-lg p-8 max-w-5xl w-full">
      <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Prenatal & Postnatal Checkup</h2>

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

      <form @submit.prevent="submitForm">
        <!-- Step 1: Patient Information -->
        <div v-if="step === 1">
          <h3 class="text-lg font-semibold mb-4">Patient Information</h3>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block">First Name:</label>
              <input type="text" v-model="form.firstName" class="input"  @input="capitalizeName('firstName')" placeholder="Example: Juan" required />
              <span v-if="errors.firstName" class="text-red-600 text-sm">{{ errors.firstName }}</span>
            </div>
            <div>
              <label class="block">Last Name:</label>
              <input type="text" v-model="form.lastName" class="input"  @input="capitalizeName('lastName')" placeholder="Example: Dela Cruz" required />
              <span v-if="errors.lastName" class="text-red-600 text-sm">{{ errors.lastName }}</span>
            </div>
            <div>
              <label class="block">Middle Name:</label>
              <input type="text" v-model="form.middleName" class="input"  @input="capitalizeName('middleName')" placeholder="Example: Penduko" required />
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
              <input type="date" v-model="form.birthdate" class="input" required />
              <span v-if="errors.birthdate" class="text-red-600 text-sm">{{ errors.birthdate }}</span>
            </div>

            <div>
              <label class="block">Age:</label>
              <input type="number" v-model="computedAge" class="input" readonly />
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
            <!-- <div>
              <label class="block">Referred From</label>
              <input type="text" v-model="form.reasonForReferral" class="input"></input>
            </div>-->
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
            <div>
              <label class="block">Name of Attending Doctor/Provider:</label>
              <input type="text" v-model="form.providerName" placeholder="Example: Dr.Aileen Uy"
                class="input"></input>
            </div>
            <div>
              <label class="block">Name of Spouse:</label>
              <input type="text" v-model="form.nameOfSpouse" placeholder="Example: Pedro Penduko" class="input"></input>
              <span v-if="errors.nameOfSpouse" class="text-red-600 text-sm">{{ errors.nameofSpouse }}</span>
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

              <!-- Conditionally Render ID Input -->
              <div v-if="form.philhealthStatus === 'Member'" class="mt-4">
                <label for="philhealthId" class="block font-medium text-gray-700">Philhealth ID Number:</label>
                <input id="philhealthId" v-model="form.philhealthId" type="text"
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

        <!-- Step 3: Visit Information -->
        <div v-if="step === 3">
          <!-- Menstrual History Section -->
          <h2 class="font-bold">Menstrual History</h2>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block">Menarche:</label>
              <input type="text" v-model="form.menarche" class="input" placeholder="Enter age at menarche" required />
              <span v-if="errors.menarche" class="text-red-600 text-sm">{{ errors.menarche }}</span>
            </div>

            <div>
              <label class="block">Onset of Sexual Intercourse:</label>
              <input type="text" v-model="form.sexualOnset" class="input" placeholder="Enter age" />
              <span v-if="errors.sexualOnset" class="text-red-600 text-sm">{{ errors.sexualOnset }}</span>
            </div>

            <div>
              <label class="block">Period/Duration:</label>
              <input type="text" v-model="form.periodDuration" class="input" placeholder="e.g., 5-7 days" required />
              <span v-if="errors.periodDuration" class="text-red-600 text-sm">{{ errors.periodDuration }}</span>
            </div>

            <div>
              <label class="block">Birth Control Method:</label>
              <input type="text" v-model="form.birthControl" class="input" placeholder="Enter method" />
              <span v-if="errors.birthControl" class="text-red-600 text-sm">{{ errors.birthControl }}</span>
            </div>

            <div>
              <label class="block">Interval/Cycle:</label>
              <input type="text" v-model="form.intervalCycle" class="input" placeholder="e.g., 28 days" required />
              <span v-if="errors.intervalCycle" class="text-red-600 text-sm">{{ errors.intervalCycle }}</span>
            </div>

            <div>
              <label class="block">Menopause? (Yes/No):</label>
              <select v-model="form.menopause" class="input" required>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
              <span v-if="errors.menopause" class="text-red-600 text-sm">{{ errors.menopause }}</span>
            </div>

            <div>
              <label class="block">LMP (Last Menstrual Period):</label>
              <input type="date" v-model="form.lmp" class="input" required />
              <span v-if="errors.lmp" class="text-red-600 text-sm">{{ errors.lmp }}</span>
            </div>

            <div>
              <label class="block">EDC (Estimated Date of Confinement):</label>
              <input type="date" v-model="form.edc" class="input" required />
              <span v-if="errors.edc" class="text-red-600 text-sm">{{ errors.edc }}</span>
            </div>
          </div>


          <div class="mt-6 flex justify-between">
            <button @click="prevStep" class="btn">Back</button>
            <button @click="nextStep" class="btn">Next</button>
          </div>
        </div>

        <div v-if="step === 4">
          <!-- Prenatal Section -->
          <h2 class="font-bold mt-4">Prenatal</h2>
          <div class="grid grid-cols-3 gap-4">
            <div>
              <label class="block">Gravidity:</label>
              <input type="text" v-model="form.gravidity" class="input" placeholder="Enter number of pregnancies"
                required />
              <span v-if="errors.gravidity" class="text-red-600 text-sm">{{ errors.gravidity }}</span>
            </div>

            <div>
              <label class="block">Parity:</label>
              <input type="text" v-model="form.parity" class="input" placeholder="Enter number of live births"
                required />
              <span v-if="errors.parity" class="text-red-600 text-sm">{{ errors.parity }}</span>
            </div>

            <div>
              <label class="block">Term:</label>
              <input type="text" v-model="form.term" class="input" placeholder="Enter term pregnancies" />
              <span v-if="errors.term" class="text-red-600 text-sm">{{ errors.term }}</span>
            </div>

            <div>
              <label class="block">Preterm:</label>
              <input type="text" v-model="form.preterm" class="input" placeholder="Enter preterm pregnancies" />
              <span v-if="errors.preterm" class="text-red-600 text-sm">{{ errors.preterm }}</span>
            </div>

            <div>
              <label class="block">Abortion:</label>
              <input type="text" v-model="form.abortion" class="input" placeholder="Enter abortions" />
            </div>

            <div>
              <label class="block">Living:</label>
              <input type="text" v-model="form.living" class="input" placeholder="Enter number of living children" />
              <span v-if="errors.living" class="text-red-600 text-sm">{{ errors.living }}</span>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4 mt-4">
            <div>
              <label class="block">Syphilis Test Result:</label>
              <select v-model="form.syphilisResult" class="input" required>
                <option value="" disabled selected>Select a result</option>
                <option value="Negative">Negative</option>
                <option value="Positive">Positive</option>
              </select>
              <span v-if="errors.syphilisResult" class="text-red-600 text-sm">{{ errors.syphilisResult }}</span>
            </div>

            <div>
              <label class="block">Penicillin Given:</label>
              <select v-model="form.penicillin" class="input" required>
                <option value="" disabled selected>Has penicillin been given?</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
              <span v-if="errors.penicillin" class="text-red-600 text-sm">{{ errors.penicillin }}</span>
            </div>
          </div>

          <div class="mt-6 flex justify-between">
            <button @click="prevStep" class="btn">Back</button>
            <button @click="nextStep" class="btn">Next</button>
          </div>
        </div>


        <div v-if="step === 5">
          <!-- Labs Section -->
          <h2 class="font-bold mt-4">Labs Done On:</h2>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block">Hemoglobin (gm/100ml):</label>
              <input type="number" v-model="form.hemoglobin" step="0.1" class="input"
                placeholder="Enter hemoglobin level" required />
              <span v-if="errors.hemoglobin" class="text-red-600 text-sm">{{ errors.hemoglobin }}</span>
            </div>

            <div>
              <label class="block">Hematocrit (vol %):</label>
              <input type="number" v-model="form.hematocrit" step="0.1" class="input"
                placeholder="Enter hematocrit level" required />
              <span v-if="errors.hematocrit" class="text-red-600 text-sm">{{ errors.hematocrit }}</span>
            </div>

            <div>
              <label class="block">Urinalysis:</label>
              <input type="text" v-model="form.urinalysis" class="input" placeholder="Enter urinalysis result" />
              <span v-if="errors.urinalysis" class="text-red-600 text-sm">{{ errors.urinalysis }}</span>
            </div>
            <div>
              <label class="block">TT Status:</label>
              <input type="text" v-model="form.ttStatus" class="input" placeholder="Enter TT status" />
              <span v-if="errors.ttStatus" class="text-red-600 text-sm">{{ errors.ttStatus }}</span>
            </div>
          </div>

          <div class="mt-5">
            <label class="block">TD (Date Given):</label>
            <input type="date" v-model="form.tdDate" class="input" placeholder="Select date given" />
            <span v-if="errors.tdDate" class="text-red-600 text-sm">{{ errors.tdDate }}</span>
          </div>

          <div class="mt-6 flex justify-between">
            <button @click="prevStep" class="btn">Back</button>
            <button @click="handleSubmit" class="btn">Submit</button>
          </div>
        </div>


        <!-- Step 6: Review Submitted Data -->
        <div v-if="step === 6">
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
  </div>

</template>

<script>
export default {
  props: ['onSubmit'],
  data() {
    return {
      step: 1,
      alertMessage: '',
      stepTitles: [
        'Patient Information',
        'For CHU/RHU Personnel Only',
        'Menstrual History',
        'Prenatal',
        'Labs Done On:',
        'Review Information'
      ],
      form: {
        firstName: '',
        lastName: '',
        middleName: '',
        suffix: '',
        purok: '',
        barangay: '',
        age: '',
        birthdate: '',
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
        philhealthId: '',
        menarche: '',
        sexualOnset: '',
        periodDuration: '',
        birthControl: '',
        intervalCycle: '',
        menopause: '',
        lmp: '',
        edc: '',
        gravidity: '',
        parity: '',
        term: '',
        preterm: '',
        abortion: '',
        living: '',
        syphilisResult: '',
        penicillin: '',
        hemoglobin: '',
        hematocrit: '',
        urinalysis: '',
        ttStatus: '',
        tdDate: '',
      },
      errors: {
        emergencyContact: '',
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
  },
  methods: {
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
    handleSubmit(event) {
      // Prevent the default form submission (which reloads the page)
      event.preventDefault();

      // Now call submitForm logic without reloading the page
      this.submitForm();
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
      if (!this.form.consultationTime) {
        this.errors.consultationTime = 'Consultation time is required.';
        valid = false;
      }
      if (!this.form.nameOfSpouse) {
        this.errors.nameOfSpouse = 'Name of Spouse is required.';
        valid = false;
      }
      if (!this.form.emergencyContact) {
        this.errors.emergencyContact = 'Emergency Contact is required.';
        valid = false;
      }
      return valid;
    },
    validateStep3() {
      this.errors = {};
      let valid = true;

      if (!this.form.menarche) {
        this.errors.menarche = 'Menarche is required.';
        valid = false;
      }
      if (!this.form.sexualOnset) {
        this.errors.sexualOnset = 'Sexual Onset of visit is required.';
        valid = false;
      }
      if (!this.form.periodDuration) {
        this.errors.periodDuration = 'Period Duration is required.';
        valid = false;
      }
      if (!this.form.birthControl) {
        this.errors.birthControl = 'Birth Control is required.';
        valid = false;
      }
      if (!this.form.intervalCycle) {
        this.errors.intervalCycle = 'Interval Cycle is required.';
        valid = false;
      }
      if (!this.form.menopause) {
        this.errors.menopause = 'Menopause is required.';
        valid = false;
      }
      if (!this.form.lmp) {
        this.errors.lmp = 'LMP is required.';
        valid = false;
      }
      if (!this.form.edc) {
        this.errors.edc = 'EDC is required.';
        valid = false;
      }
      return valid;
    },
    validateStep4() {
      this.errors = {};
      let valid = true;

      if (!this.form.gravidity) {
        this.errors.gravidity = 'Gravidity is required.';
        valid = false;
      }
      if (!this.form.parity) {
        this.errors.parity = 'Parity is required.';
        valid = false;
      }
      if (!this.form.term) {
        this.errors.term = 'Term is required.';
        valid = false;
      }
      if (!this.form.preterm) {
        this.errors.preterm = 'Pre-Term is required.';
        valid = false;
      }
      if (!this.form.living) {
        this.errors.living = 'Living is required.';
        valid = false;
      }
      if (!this.form.syphilisResult) {
        this.errors.syphilisResult = 'Syphilis Result is required.';
        valid = false;
      }
      if (!this.form.penicillin) {
        this.errors.penicillin = 'Penicillin is required.';
        valid = false;
      }
      return valid;
    },
    validateStep5() {
      this.errors = {};
      let valid = true;

      if (!this.form.hemoglobin) {
        this.errors.hemoglobin = 'Hemoglobin is required.';
        valid = false;
      }
      if (!this.form.hematocrit) {
        this.errors.hematocrit = 'Hematocrit is required.';
        valid = false;
      }
      if (!this.form.urinalysis) {
        this.errors.urinalysis = 'Urinalysis is required.';
        valid = false;
      }
      if (!this.form.ttStatus) {
        this.errors.ttStatus = 'TT Status is required.';
        valid = false;
      }
      if (!this.form.tdDate) {
        this.errors.tdDate = 'TD Date is required.';
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
      } else if (this.step === 4 && this.validateStep4()) {
        this.step++;
      } else if (this.step === 5 && this.validateStep5()) {
        this.step++;
      } else if (this.step === 5 && this.validateStep5()) {
        this.submitForm();
      }

    },
    prevStep() {
      this.step--;
    },
    formatEmergencyContact() {
      // Keep only numeric characters, limit to 10 digits
      this.form.emergencyContact = this.form.emergencyContact.replace(/[^0-9]/g, '').slice(0, 10);
    },
    submitForm() {
      // Check if all steps are valid
      if (this.validateStep1() && this.validateStep2() && this.validateStep3() && this.validateStep4() && this.validateStep5()) {
        // Prepare the form data for submission
        const formData = { ...this.form };

        // Emit the form data to the parent component via the onSubmit prop
        this.$emit('submitForm', formData);

        // Display a success message (optional, as the parent may handle success messages)
        this.successMessage = 'Form submitted successfully!';

        // Don't reset the form, keeping the entered values
        // Optional: Reset errors if needed
        this.errors = {};
      } else {
        // Handle the error case if validation fails (e.g., show a general error message)
        this.successMessage = 'Please complete all required fields before submitting.';
      }
    },
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
