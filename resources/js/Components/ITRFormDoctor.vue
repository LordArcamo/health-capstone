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

        <!-- Step 1: Review Patient Information -->
        <div v-if="step === 1">
          <h3 class="text-2xl font-semibold mb-6 text-gray-800">Patient Information Review</h3>

          <!-- Basic Information Section -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
              <h4 class="font-semibold text-xl mb-4 text-gray-700">Basic Information</h4>
              <div>
                <p class="font-medium text-gray-600">Full Name:</p>
                <p class="text-gray-800">{{ consultationDetails.firstName }} {{ consultationDetails.middleName }} {{ consultationDetails.lastName }} {{ consultationDetails.suffix
                  || 'Not Provided' }}</p>
              </div>
              <div>
                <p class="font-medium text-gray-600">Age:</p>
                <p class="text-gray-800">{{ consultationDetails.age || 'Not Provided' }}</p>
              </div>
              <div>
                <p class="font-medium text-gray-600">Birthdate:</p>
                <p class="text-gray-800">{{ consultationDetails.birthdate || 'Not Provided' }}</p>
              </div>
              <div>
                <p class="font-medium text-gray-600">Contact:</p>
                <p class="text-gray-800">{{ consultationDetails.contact || 'Not Provided' }}</p>
              </div>
              <div>
                <p class="font-medium text-gray-600">Sex:</p>
                <p class="text-gray-800">{{ consultationDetails.sex || 'Not Provided' }}</p>
              </div>
            </div>

            <!-- Address Information Section -->
            <div>
              <h4 class="font-semibold text-xl mb-4 text-gray-700">Address Information</h4>
              <div>
                <p class="font-medium text-gray-600">Purok:</p>
                <p class="text-gray-800">{{ consultationDetails.purok || 'Not Provided' }}</p>
              </div>
              <div>
                <p class="font-medium text-gray-600">Barangay:</p>
                <p class="text-gray-800">{{ consultationDetails.barangay || 'Not Provided' }}</p>
              </div>
            </div>
          </div>

          <!-- Consultation Details Section -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
              <h4 class="font-semibold text-xl mb-4 text-gray-700">Consultation Details</h4>
              <div>
                <p class="font-medium text-gray-600">Consultation Date:</p>
                <p class="text-gray-800">{{ consultationDetails.consultationDate || 'Not Provided' }}</p>
              </div>
              <div>
                <p class="font-medium text-gray-600">Consultation Time:</p>
                <p class="text-gray-800">{{ consultationDetails.consultationTime || 'Not Provided' }}</p>
              </div>
              <div>
                <p class="font-medium text-gray-600">Mode of Transaction:</p>
                <p class="text-gray-800">{{ consultationDetails.modeOfTransaction || 'Not Provided' }}</p>
              </div>
            </div>

            <!-- Medical Details Section -->
            <div>
              <h4 class="font-semibold text-xl mb-4 text-gray-700">Medical Details</h4>
              <div>
                <p class="font-medium text-gray-600">Blood Pressure:</p>
                <p class="text-gray-800">{{ consultationDetails.bloodPressure || 'Not Provided' }}</p>
              </div>
              <div>
                <p class="font-medium text-gray-600">Temperature:</p>
                <p class="text-gray-800">{{ consultationDetails.temperature || 'Not Provided' }}</p>
              </div>
              <div>
                <p class="font-medium text-gray-600">Height:</p>
                <p class="text-gray-800">{{ consultationDetails.height || 'Not Provided' }}</p>
              </div>
              <div>
                <p class="font-medium text-gray-600">Weight:</p>
                <p class="text-gray-800">{{ consultationDetails.weight || 'Not Provided' }}</p>
              </div>
            </div>
          </div>

          <!-- Referral Details Section -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
              <h4 class="font-semibold text-xl mb-4 text-gray-700">Referral Details</h4>
              <div>
                <p class="font-medium text-gray-600">Referred From:</p>
                <p class="text-gray-800">{{ consultationDetails.referredFrom || 'Not Provided' }}</p>
              </div>
              <div>
                <p class="font-medium text-gray-600">Referred To:</p>
                <p class="text-gray-800">{{ consultationDetails.referredTo || 'Not Provided' }}</p>
              </div>
              <div>
                <p class="font-medium text-gray-600">Reasons for Referral:</p>
                <p class="text-gray-800">{{ consultationDetails.reasonsForReferral || 'Not Provided' }}</p>
              </div>
              <div>
                <p class="font-medium text-gray-600">Referred By:</p>
                <p class="text-gray-800">{{ consultationDetails.referredBy || 'Not Provided' }}</p>
              </div>
            </div>

            <!-- Additional Details Section -->
            <div>
              <h4 class="font-semibold text-xl mb-4 text-gray-700">Additional Details</h4>
              <div>
                <p class="font-medium text-gray-600">Provider Name:</p>
                <p class="text-gray-800">{{ consultationDetails.providerName || 'Not Provided' }}</p>
              </div>
              <div>
                <p class="font-medium text-gray-600">Nature of Visit:</p>
                <p class="text-gray-800">{{ consultationDetails.natureOfVisit || 'Not Provided' }}</p>
              </div>
              <div>
                <p class="font-medium text-gray-600">Visit Type:</p>
                <p class="text-gray-800">{{ consultationDetails.visitType || 'Not Provided' }}</p>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="mt-6 flex justify-between">
            <button @click="prevStep"
              class="btn bg-gray-400 hover:bg-gray-500 text-white py-2 px-4 rounded-md">Back</button>
            <button @click="nextStep"
              class="btn bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-md">Next</button>
          </div>
        </div>
        <div v-if="step === 2">
          <h3 class="text-lg font-semibold mb-4">Visit Informations

          </h3>
          <div class="grid grid-cols-2 gap-4">

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
        </div>

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
    consultationDetails: {
        type: Object,
        required: false,
        default: () => ({}),
    },
    onSubmit: Function,
  },

  data() {
    return {
      alertMessage: '',
      showModal: false, // Tracks modal visibility
      stepTitles: [
        'Medical Record',
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
        referredFrom: '',
        referredTo: '',
        reasonsForReferral: '',
        referredBy: '',
        providerName: '',
        natureOfVisit: '',
        visitType: '',
        chiefComplaints: '',
        diagnosis: '',
        medication: '',
      },
      errors: {
        contact: '',
        birthdate: '',
      },
      successMessage: '',
    };
  },
  watch: {
    consultationDetails: {
        immediate: true,
      handler(newPatient) {
        if (newPatient && newPatient.consultationDetailsID === null) {
          // Clear the form for a new patient
          this.resetForm();
        } else if (newPatient && Object.keys(newPatient).length > 0) {
          // Populate the form with selected patient data
          this.populateForm(newPatient);
        }
      },
    },
  },
  methods: {
    formatLabel(key) {
        return key
            .replace(/([A-Z])/g, ' $1') // Add a space before capital letters
            .replace(/_/g, ' ') // Replace underscores with spaces
            .replace(/^\w/, (c) => c.toUpperCase()); // Capitalize the first letter
        },
    resetForm() {
      this.form = {
        chiefComplaints: '',
        diagnosis: '',
        medication: '',
      };
      this.errors = {};
    },
    populateForm(patient) {
      console.log("Populating form with patient:", patient);
      this.form = {
        firstName: this.consultationDetails?.firstName || '',
        lastName: this.consultationDetails?.lastName || '',
        middleName: this.consultationDetails?.middleName || '',
        suffix: this.consultationDetails?.suffix || '',
        purok: this.consultationDetails?.purok || '',
        barangay: this.consultationDetails?.barangay || '',
        age: this.consultationDetails?.age || '',
        birthdate: this.consultationDetails?.birthdate || '',
        contact: this.consultationDetails?.contact || '',
        sex: this.consultationDetails?.sex || '',
        consultationDate: this.consultationDetails?.consultationDate || '',
        consultationTime: this.consultationDetails?.consultationTime || '',
        modeOfTransaction: this.consultationDetails?.modeOfTransaction || '',
        bloodPressure: this.consultationDetails?.bloodPressure || '',
        temperature: this.consultationDetails?.temperature || '',
        height: this.consultationDetails?.height || '',
        weight: this.consultationDetails?.weight || '',
        natureOfVisit: this.consultationDetails?.natureOfVisit || '',
        visitType: this.consultationDetails?.visitType || '',
        referredFrom: this.consultationDetails?.referredFrom || '',
        referredTo: this.consultationDetails?.referredTo || '',
        providerName: this.consultationDetails?.providerName || '',
        reasonsForReferral: this.consultationDetails?.reasonsForReferral || '',
        referredBy: this.consultationDetails?.referredBy || '',

      };

    },

    validateStep2() {
      this.errors = {};
      let valid = true;

      if (!this.form.chiefComplaints) {
        this.errors.chiefComplaints = 'Chief Complaints is required.';
        valid = false;
      }
      if (!this.form.diagnosis) {
        this.errors.diagnosis = 'Diagnosis is required.';
        valid = false;
      }
      if (!this.form.medication) {
        this.errors.medication = 'Medication is required.';
        valid = false;
      }
      return valid;
    },
    nextStep() {
      if (this.step === 1) {
        this.step++;
      } else if (this.step === 2 && this.validateStep2()) {
        this.step++;
      } else if (this.step === 2 && this.validateStep2()) {
        this.step = 2;
      }
    },
    prevStep() {
      this.step--;
    },
    navigateToStep(targetStep) {
      if (targetStep > this.step) {
        // Validate the current step before proceeding
        const isCurrentStepValid =
          (this.step === 1)
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
    triggerSubmit() {
      if (this.validateStep2()) {
        this.showModal = true; // Show confirmation modal
      } else {
        this.successMessage = 'Please complete all required fields before submitting.';
      }
    },
    confirmSubmit() {
      const payload = {
        consultationDetailsID: this.consultationDetails?.consultationDetailsID || null,
        id: this.id,
        chiefComplaints: this.form.chiefComplaints,
        diagnosis: this.form.diagnosis,
        medication: this.form.medication,
      };

      console.log('Submitting form with payload:', payload);
      this.$emit('submitForm', payload);
      this.showModal = false; // Hide confirmation modal
    },

    cancelSubmit() {
      this.showModal = false; // Hide modal
    },
  },
  mounted() {
    console.log('Received personalInfo:', this.consultationDetails);
    if (!this.consultationDetails || Object.keys(this.consultationDetails).length === 0) {
      console.log('Rendering empty form for new patient');
    } else {
      console.log('Rendering form for existing patient:', this.consultationDetails);
      this.populateForm(this.consultationDetails);
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
