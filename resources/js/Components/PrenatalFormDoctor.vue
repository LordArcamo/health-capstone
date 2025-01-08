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
          <h3 class="text-2xl font-semibold mb-6 text-gray-800">Patient Information Review</h3>

          <!-- Basic Information Section -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
              <h4 class="font-semibold text-xl mb-4 text-gray-700">Basic Information</h4>
              <div>
                <p class="font-medium text-gray-600">Full Name:</p>
                <p class="text-gray-800">{{ form.firstName }} {{ form.middleName }} {{ form.lastName }}</p>
              </div>
              <div>
                <p class="font-medium text-gray-600">Age:</p>
                <p class="text-gray-800">{{ form.age || 'Not Provided' }}</p>
              </div>
              <div>
                <p class="font-medium text-gray-600">Birthdate:</p>
                <p class="text-gray-800">{{ form.birthdate || 'Not Provided' }}</p>
              </div>
              <div>
                <p class="font-medium text-gray-600">Contact:</p>
                <p class="text-gray-800">{{ form.contact || 'Not Provided' }}</p>
              </div>
            </div>

            <!-- Address Information Section -->
            <div>
              <h4 class="font-semibold text-xl mb-4 text-gray-700">Address Information</h4>
              <div>
                <p class="font-medium text-gray-600">Purok:</p>
                <p class="text-gray-800">{{ form.purok || 'Not Provided' }}</p>
              </div>
              <div>
                <p class="font-medium text-gray-600">Barangay:</p>
                <p class="text-gray-800">{{ form.barangay || 'Not Provided' }}</p>
              </div>
            </div>
          </div>

          <!-- Consultation Details Section -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
              <h4 class="font-semibold text-xl mb-4 text-gray-700">Consultation Details</h4>
              <div>
                <p class="font-medium text-gray-600">Consultation Date:</p>
                <p class="text-gray-800">{{ form.consultationDate || 'Not Provided' }}</p>
              </div>
              <div>
                <p class="font-medium text-gray-600">Consultation Time:</p>
                <p class="text-gray-800">{{ form.consultationTime || 'Not Provided' }}</p>
              </div>
              <div>
                <p class="font-medium text-gray-600">Mode of Transaction:</p>
                <p class="text-gray-800">{{ form.modeOfTransaction || 'Not Provided' }}</p>
              </div>
            </div>

            <!-- Medical Details Section -->
            <div>
              <h4 class="font-semibold text-xl mb-4 text-gray-700">Medical Details</h4>
              <div>
                <p class="font-medium text-gray-600">Blood Pressure:</p>
                <p class="text-gray-800">{{ form.bloodPressure || 'Not Provided' }}</p>
              </div>
              <div>
                <p class="font-medium text-gray-600">Temperature:</p>
                <p class="text-gray-800">{{ form.temperature || 'Not Provided' }}</p>
              </div>
              <div>
                <p class="font-medium text-gray-600">Height:</p>
                <p class="text-gray-800">{{ form.height || 'Not Provided' }}</p>
              </div>
              <div>
                <p class="font-medium text-gray-600">Weight:</p>
                <p class="text-gray-800">{{ form.weight || 'Not Provided' }}</p>
              </div>
            </div>
          </div>

          <!-- Additional Details Section -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
              <h4 class="font-semibold text-xl mb-4 text-gray-700">Emergency Details</h4>
              <div>
                <p class="font-medium text-gray-600">Provider Name:</p>
                <p class="text-gray-800">{{ form.providerName || 'Not Provided' }}</p>
              </div>
              <div>
                <p class="font-medium text-gray-600">Name of Spouse:</p>
                <p class="text-gray-800">{{ form.nameOfSpouse || 'Not Provided' }}</p>
              </div>
              <div>
                <p class="font-medium text-gray-600">Emergency Contact:</p>
                <p class="text-gray-800">{{ form.emergencyContact || 'Not Provided' }}</p>
              </div>
            </div>

            <!-- Philhealth Details Section -->
            <div>
              <h4 class="font-semibold text-xl mb-4 text-gray-700">Philhealth Information</h4>
              <div>
                <p class="font-medium text-gray-600">Philhealth Status:</p>
                <p class="text-gray-800">{{ form.philhealthStatus || 'Not Provided' }}</p>
              </div>
              <div>
                <p class="font-medium text-gray-600">Philhealth No.:</p>
                <p class="text-gray-800">{{ form.philhealthNo || 'Not Provided' }}</p>
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

        <!-- Step 3: Visit Information -->
        <div v-if="step === 2">
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

        <div v-if="step === 3">
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


        <div v-if="step === 4">
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
            <button @click="nextStep" class="btn">Next</button>
          </div>
        </div>


        <!-- Step 6: Review Submitted Data -->
        <div v-if="step === 5">
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
    prenatalConsultationDetails: {
      type: Object,
      required: false,
      default: () => ({}),
    },
    onSubmit: Function,
  },
  data() {
    return {
      alertMessage: '',
      showModal: false,
      stepTitles: [
        'Medical Record',
        'Menstrual History',
        'Prenatal',
        'Labs Done On:',
        'Review Information'
      ],
      step: 1,
      form: {
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
  watch: {
    prenatalConsultationDetails: {
      immediate: true,
      handler(newPatient) {
        if (newPatient && newPatient.prenatalConsultationDetails === null) {
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
      };
      this.errors = {};
    },
    populateForm(patient) {
      console.log("Populating form with patient:", patient);
      this.form = {
        firstName: this.prenatalConsultationDetails?.firstName || '',
        lastName: this.prenatalConsultationDetails?.lastName || '',
        middleName: this.prenatalConsultationDetails?.middleName || '',
        purok: this.prenatalConsultationDetails?.purok || '',
        barangay: this.prenatalConsultationDetails?.barangay || '',
        age: this.prenatalConsultationDetails?.age || '',
        birthdate: this.prenatalConsultationDetails?.birthdate || '',
        contact: this.prenatalConsultationDetails?.contact || '',
        modeOfTransaction: this.prenatalConsultationDetails?.modeOfTransaction || '',
        consultationDate: this.prenatalConsultationDetails?.consultationDate || '',
        consultationTime: this.prenatalConsultationDetails?.consultationTime || '',
        bloodPressure: this.prenatalConsultationDetails?.bloodPressure || '',
        temperature: this.prenatalConsultationDetails?.temperature || '',
        height: this.prenatalConsultationDetails?.height || '',
        weight: this.prenatalConsultationDetails?.weight || '',
        providerName: this.prenatalConsultationDetails?.providerName || '',
        nameOfSpouse: this.prenatalConsultationDetails?.nameOfSpouse || '',
        emergencyContact: this.prenatalConsultationDetails?.emergencyContact || '',
        fourMember: this.prenatalConsultationDetails?.fourMember || '',
        philhealthStatus: this.prenatalConsultationDetails?.philhealthStatus || '',
        philhealthNo: this.prenatalConsultationDetails?.philhealthNo || '',


      };

    },
    handleSubmit(event) {
      event.preventDefault();
      this.triggerSubmit();
    },

    validateStep2() {
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
    validateStep3() {
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
    validateStep4() {
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
      if (this.step === 1) {
        this.step++;
      } else if (this.step === 2 && this.validateStep2()) {
        this.step++;
      } else if (this.step === 3 && this.validateStep3()) {
        this.step++;
      } else if (this.step === 4 && this.validateStep4()) {
        this.step++;
      } else if (this.step === 4 && this.validateStep4()) {
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
          (this.step === 1) ||
          (this.step === 2 && this.validateStep2()) ||
          (this.step === 3 && this.validateStep3()) ||
          (this.step === 4 && this.validateStep4())
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
      if (this.validateStep2() && this.validateStep3() && this.validateStep4()) {
        this.showModal = true; // Show confirmation modal
      } else {
        this.successMessage = 'Please complete all required fields before submitting.';
      }
    },
    confirmSubmit() {
      // Prepare the form data and parse weight and height as numbers
      const payload = {
        prenatalConsultationDetailsID: this.prenatalConsultationDetails?.prenatalConsultationDetailsID || null, // Include personalId if it exists
        menarche: this.form.menarche,
        id: this.id,
        sexualOnset: this.form.sexualOnset,
        periodDuration: this.form.periodDuration,
        birthControl: this.form.birthControl,
        intervalCycle: this.form.intervalCycle,
        menopause: this.form.menopause,
        lmp: this.form.lmp,
        edc: this.form.edc,
        gravidity: this.form.gravidity,
        parity: this.form.parity,
        term: this.form.term,
        preterm: this.form.preterm,
        abortion: this.form.abortion,
        living: this.form.living,
        syphilisResult: this.form.syphilisResult,
        penicillin: this.form.penicillin,
        hemoglobin: this.form.hemoglobin,
        hematocrit: this.form.hematocrit,
        urinalysis: this.form.urinalysis,
        ttStatus: this.form.ttStatus,
        tdDate: this.form.tdDate,
      };
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
    console.log('Received personalInfo:', this.prenatalConsultationDetails);
    if (!this.prenatalConsultationDetails || Object.keys(this.prenatalConsultationDetails).length === 0) {
      console.log('Rendering empty form for new patient');
    } else {
      console.log('Rendering form for existing patient:', this.prenatalConsultationDetails);
      this.populateForm(this.prenatalConsultationDetails);
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
