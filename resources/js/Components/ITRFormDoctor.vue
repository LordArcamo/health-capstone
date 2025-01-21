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
                <p class="text-gray-800">{{ consultationDetails.firstName }} {{ consultationDetails.middleName }} {{
                  consultationDetails.lastName }} {{ consultationDetails.suffix
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
          <h3 class="text-lg font-semibold mb-4">Visit Information</h3>

          <div class="grid grid-cols-2 gap-4">

            <!-- Chief Complaints -->
            <div>
              <label class="block">Chief Complaints:</label>
              <textarea v-model="form.chiefComplaints" placeholder="Example: Low Bowel Movements etc."
                class="input"></textarea>
              <span v-if="errors.chiefComplaints" class="text-red-600 text-sm">{{ errors.chiefComplaints }}</span>
            </div>

            <!-- Require Laboratory Test (Dropdown Select) -->
            <div>
              <label class="block">Require Laboratory Test?</label>
              <select v-model="form.requireLabTest" class="input">
                <option disabled value="">-- Select Option --</option>
                <option value="yes">Yes</option>
                <option value="no">No</option>
              </select>
              <span v-if="errors.requireLabTest" class="text-red-600 text-sm">{{ errors.requireLabTest }}</span>
            </div>

            <!-- Laboratory Tests Checklist (Shows if "Yes" is Selected) -->
            <div v-if="form.requireLabTest === 'yes'" class="col-span-2">
              <label class="block font-semibold">Select Laboratory Tests:</label>
              <div class="grid grid-cols-2 gap-4">
                <div v-for="(test, index) in labTests" :key="`labTest-${index}`" class="flex items-center">
                  <input type="checkbox" :id="`labTest-${index}`" :value="test" v-model="form.selectedLabTests"
                    class="mr-2 h-4 w-4 text-green-600 focus:ring-green-500" />
                  <label :for="`labTest-${index}`" class="cursor-pointer">{{ test }}</label>
                </div>

              </div>
              <span v-if="errors.selectedLabTests" class="text-red-600 text-sm">{{ errors.selectedLabTests }}</span>
            </div>


            <!-- Auto-set Status to 'Follow-up Required' -->
            <input type="hidden" v-model="form.status" />

            <!-- Diagnosis Input with Tagging -->
            <div v-if="form.requireLabTest === 'no'">
              <label class="block text-sm font-medium text-gray-700 mb-2">Diagnosis:</label>

              <div
                class="flex flex-wrap items-center border border-gray-300 rounded-md p-2 bg-white shadow-sm focus-within:ring-2 focus-within:ring-green-500">
                <!-- Display added diagnosis tags -->
                <div v-for="(diagnosis, index) in form.diagnosisTags" :key="index"
                  class="flex items-center bg-green-100 text-green-800 px-3 py-1 mr-2 mb-2 rounded-full text-sm font-medium shadow-sm">
                  {{ diagnosis }}
                  <button type="button" @click="removeDiagnosisTag(index)"
                    class="ml-2 text-green-600 hover:text-red-500 focus:outline-none">
                    &times;
                  </button>
                </div>

                <!-- Input for new diagnosis tags -->
                <input v-model="newDiagnosis" @keydown.enter.prevent="addDiagnosisTag"
                  @keydown.space.prevent="addDiagnosisTag" placeholder="Type diagnosis and press Enter or Space"
                  class="flex-grow py-1 px-3 text-sm text-gray-700 placeholder-gray-400 bg-transparent border-none focus:ring-0 focus:outline-none" />
              </div>

              <span v-if="errors.diagnosis" class="text-red-600 text-xs mt-1">{{ errors.diagnosis }}</span>
            </div>




            <!-- Medication/Treatment (Hidden if "Yes" is Selected) -->
            <!-- <div v-if="form.requireLabTest === 'no'">
              <label class="block">Medication/Treatment:</label>
              <textarea v-model="form.medication" placeholder="Example: Loperamide" class="input"></textarea>
              <span v-if="errors.medication" class="text-red-600 text-sm">{{ errors.medication }}</span>
            </div> -->

          </div>

          <!-- Navigation Buttons -->
          <div class="mt-6 flex justify-between">
            <button @click="prevStep" class="btn">Back</button>
            <button @click="nextStep" class="btn">Next</button>
          </div>
        </div>

        <!-- Prescription Step -->
        <div v-if="step === 3">
          <h3 class="text-2xl font-semibold mb-6 text-gray-800">Prescription</h3>

          <!-- Medication Name -->
          <div class="mb-6">
            <label for="medication" class="block text-sm font-medium text-gray-700 mb-2">Medication Name</label>
            <input id="medication" type="text" v-model="form.prescription.medication" class="input"
              placeholder="Enter the medication name" />
            <span v-if="errors.prescriptionMedication" class="text-red-600 text-sm">{{ errors.prescriptionMedication
              }}</span>
          </div>

          <!-- Dosage -->
          <div class="mb-6">
            <label for="dosage" class="block text-sm font-medium text-gray-700 mb-2">Dosage</label>
            <input id="dosage" type="text" v-model="form.prescription.dosage" class="input" placeholder="e.g., 500mg" />
            <span v-if="errors.prescriptionDosage" class="text-red-600 text-sm">{{ errors.prescriptionDosage }}</span>
          </div>

          <!-- Frequency -->
          <div class="mb-6">
            <label for="frequency" class="block text-sm font-medium text-gray-700 mb-2">Frequency</label>
            <input id="frequency" type="text" v-model="form.prescription.frequency" class="input"
              placeholder="e.g., 2 times a day" />
            <span v-if="errors.prescriptionFrequency" class="text-red-600 text-sm">{{ errors.prescriptionFrequency
              }}</span>
          </div>

          <!-- Duration -->
          <div class="mb-6">
            <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Duration</label>
            <input id="duration" type="text" v-model="form.prescription.duration" class="input"
              placeholder="e.g., 7 days" />
            <span v-if="errors.prescriptionDuration" class="text-red-600 text-sm">{{ errors.prescriptionDuration
              }}</span>
          </div>

          <!-- Notes -->
          <div class="mb-6">
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
            <textarea id="notes" v-model="form.prescription.notes" class="input"
              placeholder="Enter any additional instructions for the patient"></textarea>
          </div>

          <!-- Navigation Buttons -->
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
              <p class="input">{{ key === 'age' && value === 0 ? 'Less than 1 year' : (value || 'Not Provided') }}</p>
            </div>
          </div>
          <div class="mt-6 flex justify-between">
            <button @click="prevStep" class="btn">Back</button>
            <button @click="nextStep" class="btn">Next</button>          
          </div>
        </div>

        <!-- Step 5: Prescription Generation -->
<div v-if="step === 5">
  <h3 class="text-lg font-semibold mb-4">Prescription</h3>
  
  <!-- Prescription Details -->
  <div class="bg-white shadow-md rounded-lg p-6">
    <div class="border-b pb-4 mb-4">
      <h4 class="text-2xl font-bold text-center mb-2">Prescription</h4>
      <p class="text-center text-gray-600">Issued by: {{ doctorName }}</p>
      <p class="text-center text-gray-600">{{ doctorSpecialization }}</p>
    </div>

    <!-- Patient Information -->
    <div class="mb-4">
      <h5 class="text-lg font-semibold text-gray-800 mb-2">Patient Information</h5>
      <p><strong>Name:</strong> {{ consultationDetails.firstName }} {{ consultationDetails.middleName }} {{ consultationDetails.lastName }}</p>
      <p><strong>Age:</strong> {{ consultationDetails.age || 'Not Provided' }}</p>
      <p><strong>Sex:</strong> {{ consultationDetails.sex || 'Not Provided' }}</p>
      <p><strong>Contact:</strong> {{ consultationDetails.contact || 'Not Provided' }}</p>
    </div>

    <!-- Prescription -->
    <div class="mb-4">
      <h5 class="text-lg font-semibold text-gray-800 mb-2">Medication</h5>
      <p><strong>Medication Name:</strong> {{ form.prescription.medication }}</p>
      <p><strong>Dosage:</strong> {{ form.prescription.dosage }}</p>
      <p><strong>Frequency:</strong> {{ form.prescription.frequency }}</p>
      <p><strong>Duration:</strong> {{ form.prescription.duration }}</p>
      <p><strong>Notes:</strong> {{ form.prescription.notes || 'None' }}</p>
    </div>

    <!-- Doctor's Signature -->
    <div class="flex justify-between items-center border-t pt-4 mt-4">
      <div>
        <p class="text-gray-600 text-sm">Date: {{ new Date().toLocaleDateString() }}</p>
      </div>
      <div>
        <p class="text-gray-600 text-sm">Doctor's Signature:</p>
        <div class="h-12 border-t border-dashed border-gray-500"></div>
        <p class="text-gray-800 text-center">{{ doctorName }}</p>
      </div>
    </div>
  </div>

  <!-- Action Buttons -->
  <div class="mt-6 flex justify-between">
    <button @click="prevStep" class="btn">Back</button>
    <button @click="printPrescription" class="btn">Print Prescription</button>
  </div>
</div>
      </form>
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
      newDiagnosis: '',  // ✅ Initialize input for new diagnosis
      alertMessage: '',
      showModal: false,  // Tracks modal visibility
      stepTitles: [
        'Medical Record',
        'Visit Information',
        'Prescription',
        'Review Information',
        'Print Prescription'
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
        requireLabTest: '',
        selectedLabTests: [],
        diagnosisTags: [],
        prescription: {
          medication: '',
          dosage: '',
          frequency: '',
          duration: '',
          notes: '',
        },  // ✅ Properly initialized diagnosisTags
      },
      labTests: [
        'Complete Blood Count (CBC)',
        'Urinalysis',
        'X-ray',
        'Blood Sugar Test',
        'Electrolyte Panel',
        'Stool Examination',
      ],
      errors: {},
      successMessage: '',
    };
  },

  watch: {
    'form.requireLabTest'(newValue) {
      if (newValue === 'yes') {
        this.form.status = 'Follow-up Required';
      } else if (newValue === 'no') {
        this.form.status = 'Completed';
      } else {
        this.form.status = '';
      }
    },
    consultationDetails: {
      immediate: true,
      handler(newPatient) {
        if (newPatient && newPatient.consultationDetailsID === null) {
          this.resetForm();  // Clear the form for a new patient
        } else if (newPatient && Object.keys(newPatient).length > 0) {
          this.populateForm(newPatient);  // Populate with patient data
        }
      },
    },
  },

  methods: {
    printPrescription() {
      const { prescription, firstName, lastName, middleName, age, sex, contact } = this.form;
      const { doctorName, doctorSpecialization } = this.consultationDetails;

      const documentContent = `
        <html>
          <head>
            <title>Prescription</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
            <style>
              body {
                font-family: Arial, sans-serif;
                margin: 20px;
                background: #f9fafb;
              }
              .container {
                max-width: 600px;
                margin: auto;
                background: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
              }
              h4 {
                text-align: center;
                font-size: 24px;
                font-weight: bold;
                margin-bottom: 10px;
              }
              h5 {
                font-size: 18px;
                margin-bottom: 10px;
              }
              p {
                margin: 5px 0;
                font-size: 16px;
              }
              .footer {
                display: flex;
                justify-content: space-between;
                margin-top: 20px;
                padding-top: 10px;
                border-top: 1px dashed #ccc;
              }
              .signature {
                text-align: center;
                margin-top: 40px;
              }
              .signature p {
                margin: 5px 0;
              }
            </style>
          </head>
          <body>
            <div class="container">
              <h4>Prescription</h4>
              <p class="text-center text-gray-600">Issued by: ${doctorName || 'Dr. Unknown'}</p>
              <p class="text-center text-gray-600">${doctorSpecialization || 'Specialization Not Provided'}</p>
              <div>
                <h5>Patient Information</h5>
                <p><strong>Name:</strong> ${firstName} ${middleName} ${lastName}</p>
                <p><strong>Age:</strong> ${age || 'Not Provided'}</p>
                <p><strong>Sex:</strong> ${sex || 'Not Provided'}</p>
                <p><strong>Contact:</strong> ${contact || 'Not Provided'}</p>
              </div>
              <div>
                <h5>Medication</h5>
                <p><strong>Medication Name:</strong> ${prescription.medication || 'Not Provided'}</p>
                <p><strong>Dosage:</strong> ${prescription.dosage || 'Not Provided'}</p>
                <p><strong>Frequency:</strong> ${prescription.frequency || 'Not Provided'}</p>
                <p><strong>Duration:</strong> ${prescription.duration || 'Not Provided'}</p>
                <p><strong>Notes:</strong> ${prescription.notes || 'None'}</p>
              </div>
              <div class="footer">
                <p>Date: ${new Date().toLocaleDateString()}</p>
                <div class="signature">
                  <p>________________________</p>
                  <p>Doctor's Signature</p>
                </div>
              </div>
            </div>
          </body>
        </html>
      `;

    // Open a new window
    const printWindow = window.open('', '_blank');

if (printWindow) {
  printWindow.document.open();
  printWindow.document.write(documentContent);
  printWindow.document.close();
} else {
  alert('Pop-up blocker is preventing the print window from opening.');
}
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
    // ✅ Add Diagnosis Tag without submitting form
    addDiagnosisTag(event) {
      event.preventDefault();  // 🔥 Prevent form submission
      const trimmedDiagnosis = this.newDiagnosis.trim();

      if (trimmedDiagnosis && !this.form.diagnosisTags.includes(trimmedDiagnosis)) {
        this.form.diagnosisTags.push(trimmedDiagnosis);
      }
      this.newDiagnosis = '';  // Clear input after adding
    },

    // ✅ Remove Diagnosis Tag
    removeDiagnosisTag(index) {
      this.form.diagnosisTags.splice(index, 1);
    },

    toggleLabTest(test) {
      const index = this.form.selectedLabTests.indexOf(test);
      if (index > -1) {
        this.form.selectedLabTests.splice(index, 1);  // Uncheck
      } else {
        this.form.selectedLabTests.push(test);  // Check
      }
    },

    formatLabel(key) {
      return key
        .replace(/([A-Z])/g, ' $1')  // Add space before capital letters
        .replace(/_/g, ' ')  // Replace underscores with spaces
        .replace(/^\w/, (c) => c.toUpperCase());  // Capitalize the first letter
    },

    resetForm() {
      this.form = {
        chiefComplaints: '',
        diagnosis: '',
        medication: '',
        requireLabTest: '',
        selectedLabTests: [],
        diagnosisTags: [],  // ✅ Reset diagnosisTags
      };
      this.errors = {};
      this.newDiagnosis = '';
    },

    populateForm(patient) {
      this.form = {
        ...this.form,
        firstName: patient.firstName || '',
        lastName: patient.lastName || '',
        middleName: patient.middleName || '',
        suffix: patient.suffix || '',
        purok: patient.purok || '',
        barangay: patient.barangay || '',
        age: patient.age || '',
        birthdate: patient.birthdate || '',
        contact: patient.contact || '',
        sex: patient.sex || '',
        consultationDate: patient.consultationDate || '',
        consultationTime: patient.consultationTime || '',
        modeOfTransaction: patient.modeOfTransaction || '',
        bloodPressure: patient.bloodPressure || '',
        temperature: patient.temperature || '',
        height: patient.height || '',
        weight: patient.weight || '',
        natureOfVisit: patient.natureOfVisit || '',
        visitType: patient.visitType || '',
        referredFrom: patient.referredFrom || '',
        referredTo: patient.referredTo || '',
        providerName: patient.providerName || '',
        reasonsForReferral: patient.reasonsForReferral || '',
        referredBy: patient.referredBy || '',
      };
    },

    validateStep2() {
      this.errors = {};
      let valid = true;

      if (!this.form.chiefComplaints) {
        this.errors.chiefComplaints = 'Chief Complaints is required.';
        valid = false;
      }

      if (!this.form.requireLabTest) {
        this.errors.requireLabTest = 'Please specify if a lab test is required.';
        valid = false;
      }

      if (this.form.requireLabTest === 'yes' && this.form.selectedLabTests.length === 0) {
        this.errors.selectedLabTests = 'Select at least one laboratory test.';
        valid = false;
      }

      if (this.form.requireLabTest === 'no') {
        if (this.form.diagnosisTags.length === 0) {
          this.errors.diagnosis = 'At least one diagnosis is required.';
          valid = false;
        }
        if (!this.form.medication) {
          this.errors.medication = 'Medication is required.';
          valid = false;
        }
      }

      return valid;
    },
    validateStep3() {
      this.errors = {};
      let valid = true;

      if (this.step === 4) {
        const { medication, dosage, frequency, duration } = this.form.prescription;
        if (!medication) this.errors.prescriptionMedication = 'Medication name is required.';
        if (!dosage) this.errors.prescriptionDosage = 'Dosage is required.';
        if (!frequency) this.errors.prescriptionFrequency = 'Frequency is required.';
        if (!duration) this.errors.prescriptionDuration = 'Duration is required.';
        return Object.keys(this.errors).length === 0;
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
  } else if (this.step === 4) {
    // No validation needed for Step 4 (Review Information) - Go to Print Prescription
    this.step++;
  } else {
    this.alertMessage = 'Please fill in the required fields before proceeding.';
    setTimeout(() => (this.alertMessage = ''), 2000);
  }
},

    prevStep() {
      this.step--;
    },

    closeAlert() {
      this.alertMessage = '';
    },

    triggerSubmit() {
      if (this.validateStep3()) {
        this.showModal = true;
      } else {
        this.successMessage = 'Please complete all required fields before submitting.';
      }
    },

    confirmSubmit() {
      const payload = {
        consultationDetailsID: this.consultationDetails?.consultationDetailsID || null,
        id: this.id,
        chiefComplaints: this.form.chiefComplaints,
        requireLabTest: this.form.requireLabTest,
        selectedLabTests: this.form.selectedLabTests,
        diagnosis: this.form.diagnosisTags.join(', '),  // ✅ Submit as comma-separated string
        medication: this.form.requireLabTest === 'no' ? this.form.medication : 'None',
        status: this.form.requireLabTest === 'yes' ? 'Follow-up Required' : 'Completed',
      };

      console.log('Submitting form with payload:', payload);
      this.$emit('submitForm', payload);
      this.showModal = false;
    },

    cancelSubmit() {
      this.showModal = false;
    },
  },

  mounted() {
    if (Object.keys(this.consultationDetails).length > 0) {
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
