<template>
  <div class="min-h-screen flex flex-col justify-center items-center bg-gray-100">
    <div class="bg-white shadow-md rounded-lg p-8 max-w-4xl w-full">
      <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Individual Treatment Record</h2>

      <form @submit="submitForm">
        <!-- Step 1: Patient Information -->
        <div v-if="step === 1">
          <h3 class="text-lg font-semibold mb-4">Patient Information</h3>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block">First Name:</label>
              <input type="text" v-model="form.firstName" class="input" placeholder="Example: Juan" required />
              <span v-if="errors.firstName" class="text-red-600 text-sm">{{ errors.firstName }}</span>
            </div>
            <div>
              <label class="block">Last Name:</label>
              <input type="text" v-model="form.lastName" class="input" placeholder="Example: Dela Cruz" required />
              <span v-if="errors.lastName" class="text-red-600 text-sm">{{ errors.lastName }}</span>
            </div>
            <div>
              <label class="block">Middle Name:</label>
              <input type="text" v-model="form.middleName" class="input" placeholder="Example: Penduko" required />
              <span v-if="errors.middleName" class="text-red-600 text-sm">{{ errors.middleName }}</span>
            </div>
            <div>
              <label for="suffix" class="block text-sm font-medium text-gray-700">Suffix:</label>
              <select v-model="form.suffix" class="input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" >
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
              <label class="block">Purok:</label>
              <input type="text" v-model="form.purok" class="input" placeholder="Example: Purok 1A" required />
              <span v-if="errors.purok" class="text-red-600 text-sm">{{ errors.purok }}</span>
            </div>
            <div>
              <label class="block">Barangay:</label>
              <input type="text" v-model="form.barangay" class="input" placeholder="Example: Gimangpang" required />
              <span v-if="errors.barangay" class="text-red-600 text-sm">{{ errors.barangay }}</span>
            </div>
            <div>
              <label class="block">Age:</label>
              <input type="number" v-model="computedAge" class="input" readonly />
            </div>
            <div>
              <label class="block">Birthdate:</label>
              <input type="date" v-model="form.birthdate" class="input" required />
              <span v-if="errors.birthdate" class="text-red-600 text-sm">{{ errors.birthdate }}</span>
            </div>
            <!-- Contact Number -->
            <div>
              <label class="block mb-1 font-medium text-gray-700">Contact Number:</label>
              <div class="relative">
                <input
                  type="tel"
                  v-model="form.contact"
                  @input="formatContact"
                  class="pl-14 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 w-full"
                  placeholder="Enter 10 digits"
                  required
                />
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 text-sm pointer-events-none">
                  +63
                </span>
              </div>
              <span v-if="errors.contact" class="text-red-600 text-sm">{{ errors.contact }}</span>
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
            <button type="button" @click="nextStep" class="btn">Next</button>
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
              <input type="text" v-model="form.bloodPressure" placeholder="Example: 120/80" class="input" />
            </div>
            <div>
              <label class="block">Temperature (°C):</label>
              <input type="number" v-model="form.temperature" placeholder="Example: 37.5°C" step="0.1" class="input" />
            </div>
            <div>
              <label class="block">Height (cm):</label>
              <input type="number" v-model="form.height" placeholder="Example: 180" class="input" />
            </div>
            <div>
              <label class="block">Weight (kg):</label>
              <input type="number" v-model="form.weight" placeholder="Example: 81" class="input" />
            </div>
          </div>
          <div class="mt-6 flex justify-between">
            <button type="button" @click="prevStep" class="btn">Back</button>
            <button type="button" @click="nextStep" class="btn">Next</button>
          </div>
        </div>

        <!-- Step 3: Visit Information -->
        <div v-if="step === 3">
          <h3 class="text-lg font-semibold mb-4">Visit Information</h3>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block">Name of Attending Provider:</label>
              <input type="text" v-model="form.providerName" class="input" />
            </div>
            <div>
              <label class="block">Nature of Visit:</label>
              <select v-model="form.natureOfVisit" class="input" required>
                 <!-- Placeholder option for selecting suffix -->
                 <option value="" disabled selected>Select a Nature of Visit</option>
                <option>New Consultation/Case</option>
                <option>New Admission</option>
                <option>Follow-up Visit</option>
              </select>
            </div>
            <div>
              <label class="block">Type of Consultation/Purpose of Visit:</label>
              <select v-model="form.visitType" class="input" required>
                 <!-- Placeholder option for selecting suffix -->
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
              </select>
            </div>
            <div>
              <label class="block">Chief Complaints:</label>
              <textarea v-model="form.chiefComplaints" placeholder="Example: Low Bowel Movements etc." class="input"></textarea>
            </div>
            <div>
              <label class="block">Diagnosis:</label>
              <textarea v-model="form.diagnosis" placeholder="Example: Diarrhea" class="input"></textarea>
            </div>
            <div>
              <label class="block">Medication/Treatment:</label>
              <textarea v-model="form.medication" placeholder="Example: Loperamide"class="input"></textarea>
            </div>
          </div>
          <div class="mt-6 flex justify-between">
            <button type="button" @click="prevStep" class="btn">Back</button>
            <button type="submit" @click="submitForm" class="btn">Submit</button>
          </div>
        </div>
      </form>
      <div v-if="successMessage" class="mt-4 text-green-600 text-center">
        {{ successMessage }}
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
    onSubmit: Function,
  },
  data() {
    return {
      step: 1,
      form: {
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
      },
      errors: {},
      successMessage: '',
    };
  },
  computed: {
    computedAge() {
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
    'form.birthdate': function () {
      this.form.age = this.computedAge; // Automatically update age when birthdate changes
    },
  },
  methods: {
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
    formatContact() {
      // Ensure the input starts with "09", remove non-numeric characters, and limit to 10 digits
      if (!this.form.contact.startsWith('0')) {
        this.form.contact = '0' + this.form.contact.replace(/[^0-9]/g, '');
      } else {
        this.form.contact = this.form.contact.replace(/[^0-9]/g, '').slice(0, 11);
      }
    },
    nextStep() {
      if (this.step === 1 && this.validateStep1()) {
        this.step++;
      } else if (this.step === 2 && this.validateStep2()) {
        this.step++;
      } else if (this.step === 3 && this.validateStep3()) {
        this.submitForm();
      }
    },
    prevStep() {
      this.step--;
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
      if (this.form.contact.length !== 11) {
        this.errors.contact = 'Contact number must be exactly 10 digits after +63.';
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
    submitForm() {


      // Prepare the payload
      const payload = {
        personalId: this.selectedPatient?.personalId || null, // Include personalId if it exists
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
        height: this.form.height,
        weight: this.form.weight,
        providerName: this.form.providerName,
        natureOfVisit: this.form.natureOfVisit,
        visitType: this.form.visitType,
        chiefComplaints: this.form.chiefComplaints,
        diagnosis: this.form.diagnosis,
        medication: this.form.medication,
    };

      // Add personal information only if the patient is new
      if (!this.selectedPatient?.personalId) {
        Object.assign(payload, {
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
        });
      }

      console.log('Submitting form with payload:', payload);

      // Emit the form data to the parent component via the onSubmit prop
      this.$emit('submitForm', payload);

      // Optional: Display a success message or alert
      alert('Form submitted');
      this.successMessage = 'Form submitted successfully!';

      // Reset the form fields to their initial state
      this.resetForm();
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
    };
    this.errors = {};
  },
  populateForm(patient) {
    this.form = {
      ...this.form,
      ...patient,
    };
  },
},
  mounted() {
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