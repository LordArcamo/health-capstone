<template>
  <div class="min-h-screen flex flex-col justify-center items-center bg-gray-100">
    <div class="bg-white shadow-md rounded-lg p-8 max-w-4xl w-full">
      <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Individual Treatment Record</h2>

      <form @submit.prevent="submitForm">
        <!-- Step 1: Patient Information -->
        <div v-if="step === 1">
          <h3 class="text-lg font-semibold mb-4">Patient Information</h3>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block">First Name:</label>
              <input type="text" v-model="form.firstName" class="input" :class="{'border-red-500': errors.firstName}" required />
              <p v-if="errors.firstName" class="text-red-500 text-sm animate-pulse">First Name is required.</p>
            </div>
            <div>
              <label class="block">Last Name:</label>
              <input type="text" v-model="form.lastName" class="input" :class="{'border-red-500': errors.lastName}" required />
              <p v-if="errors.lastName" class="text-red-500 text-sm animate-pulse">Last Name is required.</p>
            </div>
            <div>
              <label class="block">Middle Name:</label>
              <input type="text" v-model="form.middleName" class="input" :class="{'border-red-500': errors.middleName}" required />
              <p v-if="errors.middleName" class="text-red-500 text-sm animate-pulse">Middle Name is required.</p>
            </div>
            <div>
              <label class="block">Suffix:</label>
              <select v-model="form.suffix" class="input" :class="{'border-red-500': errors.suffix}" required>
                <option value="">Select Suffix</option>
                <option value="Jr.">Jr.</option>
                <option value="Sr.">Sr.</option>
                <option value="I">I</option>
                <option value="II">II</option>
                <option value="III">III</option>
                <option value="IV">IV</option>
                <option value="V">V</option>
                <option value="None">None</option>
              </select>
              <p v-if="errors.suffix" class="text-red-500 text-sm animate-pulse">Suffix is required.</p>
            </div>
            <div>
              <label class="block">Purok:</label>
              <input type="text" v-model="form.purok" class="input" :class="{'border-red-500': errors.purok}" required />
              <p v-if="errors.purok" class="text-red-500 text-sm animate-pulse">Purok is required.</p>
            </div>
            <div>
              <label class="block">Barangay:</label>
              <input type="text" v-model="form.barangay" class="input" :class="{'border-red-500': errors.barangay}" required />
              <p v-if="errors.barangay" class="text-red-500 text-sm animate-pulse">Barangay is required.</p>
            </div>
            <div>
              <label class="block">Age:</label>
              <input type="number" v-model="computedAge" class="input" readonly />
            </div>
            <div>
              <label class="block">Birthdate:</label>
              <input type="date" v-model="form.birthdate" class="input" :class="{'border-red-500': errors.birthdate}" required />
              <p v-if="errors.birthdate" class="text-red-500 text-sm animate-pulse">Birthdate is required.</p>
            </div>
            <div>
              <label class="block">Contact Number:</label>
              <input type="text" v-model="form.contact" class="input" :class="{'border-red-500': errors.contact}" required />
              <p v-if="errors.contact" class="text-red-500 text-sm animate-pulse">Contact Number is required.</p>
            </div>
            <div>
              <label class="block">Gender</label>
              <select v-model="form.sex" class="input" :class="{'border-red-500': errors.sex}" required>
                <option>Male</option>
                <option>Female</option>
              </select>
              <p v-if="errors.sex" class="text-red-500 text-sm animate-pulse">Gender is required.</p>
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
              <input type="date" v-model="form.consultationDate" class="input" :class="{'border-red-500': errors.consultationDate}" required />
              <p v-if="errors.consultationDate" class="text-red-500 text-sm animate-pulse">Consultation Date is required.</p>
            </div>
            <div>
              <label class="block">Consultation Time:</label>
              <input type="time" v-model="form.consultationTime" class="input" :class="{'border-red-500': errors.consultationTime}" required />
              <p v-if="errors.consultationTime" class="text-red-500 text-sm animate-pulse">Consultation Time is required.</p>
            </div>
            <div>
              <label class="block">Mode of Transaction:</label>
              <select v-model="form.modeOfTransaction" class="input" :class="{'border-red-500': errors.modeOfTransaction}" required>
                <option>Walk-in</option>
                <option>Visited</option>
                <option>Referral</option>
              </select>
              <p v-if="errors.modeOfTransaction" class="text-red-500 text-sm animate-pulse">Mode of Transaction is required.</p>
            </div>
            <div>
              <label class="block">Blood Pressure:</label>
              <input type="text" v-model="form.bloodPressure" class="input" />
            </div>
            <div>
              <label class="block">Temperature (°C):</label>
              <input type="number" v-model="form.temperature" step="0.1" class="input" />
            </div>
            <div>
              <label class="block">Height (cm):</label>
              <input type="number" v-model="form.height" class="input" />
            </div>
            <div>
              <label class="block">Weight (kg):</label>
              <input type="number" v-model="form.weight" class="input" />
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
              <label class="block">Name of Attending Provider:</label>
              <input type="text" v-model="form.providerName" class="input" :class="{'border-red-500': errors.providerName}" />
              <p v-if="errors.providerName" class="text-red-500 text-sm animate-pulse">Provider Name is required.</p>
            </div>
            <div>
              <label class="block">Nature of Visit:</label>
              <select v-model="form.natureOfVisit" class="input" :class="{'border-red-500': errors.natureOfVisit}" required>
                <option>New Consultation/Case</option>
                <option>New Admission</option>
                <option>Follow-up Visit</option>
              </select>
              <p v-if="errors.natureOfVisit" class="text-red-500 text-sm animate-pulse">Nature of Visit is required.</p>
            </div>
            <div>
              <label class="block">Type of Consultation/Purpose of Visit:</label>
              <select v-model="form.visitType" class="input" :class="{'border-red-500': errors.visitType}" required>
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
              <p v-if="errors.visitType" class="text-red-500 text-sm animate-pulse">Visit Type is required.</p>
            </div>
            <div>
              <label class="block">Chief Complaints:</label>
              <textarea v-model="form.chiefComplaints" class="input" :class="{'border-red-500': errors.visitType}" required></textarea>
              <p v-if="errors.visitType" class="text-red-500 text-sm animate-pulse">Chief Complaints: is required.</p>
            </div>
            <div>
              <label class="block">Diagnosis:</label>
              <textarea v-model="form.diagnosis" class="input" :class="{'border-red-500': errors.visitType}" required></textarea>
              <p v-if="errors.visitType" class="text-red-500 text-sm animate-pulse">Diagnosis is required.</p>
            </div>
            <div>
              <label class="block">Medication/Treatment:</label>
              <textarea v-model="form.medication" class="input" :class="{'border-red-500': errors.visitType}" required></textarea>
              <p v-if="errors.visitType" class="text-red-500 text-sm animate-pulse">Medication/Treatment is required.</p>
            </div>
          </div>
          <div class="mt-6 flex justify-between">
            <button @click="prevStep" class="btn">Back</button>
            <button @click="submitForm" class="btn">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { faLessThan } from '@fortawesome/free-solid-svg-icons';

export default {
  data() {
    return {
      step: 1,
      form: {
        firstName: '',
        lastName: '',
        middleName: '',
        suffix: '',
        purok: '',
        barangay: '',
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
      },
      errors: {
        firstName: false,
        lastName: false,
        middleName: false,
        suffix: false,
        purok: false,
        barangay: false,
        birthdate: false,
        contact: false,
        sex: false,
        consultationDate: false,
        consultationTime: false,
        modeOfTransaction: false,
        providerName: false,
        natureOfVisit: false,
        visitType: false,
        chiefComplaints: false,
        diagnosis: false,
        medication: false,
      },
    };
  },
  computed: {
    computedAge() {
      if (!this.form.birthdate) return '';
      const birthDate = new Date(this.form.birthdate);
      const today = new Date();
      let age = today.getFullYear() - birthDate.getFullYear();
      const m = today.getMonth() - birthDate.getMonth();
      if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
      }
      return age;
    },
  },
  methods: {
    nextStep() {
      if (this.validateForm()) {
        this.step++;
      }
    },
    prevStep() {
      this.step--;
    },
    validateForm() {
      let isValid = true;
      // Validate only the fields in the current step
      if (this.step === 1) {
        isValid = this.validateStep1();
      } else if (this.step === 2) {
        isValid = this.validateStep2();
      } else if (this.step === 3) {
        isValid = this.validateStep3();
      }
      return isValid;
    },
    validateStep1() {
      let isValid = true;
      const requiredFields = [
        'firstName', 'lastName', 'middleName', 'suffix', 'purok', 'barangay', 'birthdate', 'contact', 'sex'
      ];
      requiredFields.forEach((field) => {
        if (!this.form[field]) {
          this.errors[field] = true;
          isValid = false;
        } else {
          this.errors[field] = false;
        }
      });
      return isValid;
    },
    validateStep2() {
      let isValid = true;
      const requiredFields = [
        'consultationDate', 'consultationTime', 'modeOfTransaction'
      ];
      requiredFields.forEach((field) => {
        if (!this.form[field]) {
          this.errors[field] = true;
          isValid = false;
        } else {
          this.errors[field] = false;
        }
      });
      return isValid;
    },
    validateStep3() {
      let isValid = true;
      const requiredFields = [
        'providerName', 'natureOfVisit', 'visitType', 'chiefComplaints' , 'diagnosis' , 'medication'
      ];
      requiredFields.forEach((field) => {
        if (!this.form[field]) {
          this.errors[field] = true;
          isValid = false;
        } else {
          this.errors[field] = false;
        }
      });
      return isValid;
    },
    submitForm() {
      if (this.validateForm()) {
        alert('Form submitted successfully!');
        this.resetForm();
      }
    },
    resetForm() {
      this.step = 1;
      this.form = {
        firstName: '',
        lastName: '',
        middleName: '',
        suffix: '',
        purok: '',
        barangay: '',
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
      this.errors = {
        firstName: false,
        lastName: false,
        middleName: false,
        suffix: false,
        purok: false,
        barangay: false,
        birthdate: false,
        contact: false,
        sex: false,
        consultationDate: false,
        consultationTime: false,
        modeOfTransaction: false,
        providerName: false,
        natureOfVisit: false,
        visitType: false,
        chiefComplaints: false,
        diagnosis: false,
        medication: false,
      };
    }
  },
};
</script>

<style scoped>
.input {
  width: 100%;
  padding: 0.5rem;
  border-radius: 0.375rem;
  border: 1px solid #e2e8f0;
  background-color: #f9fafb;
  transition: all 0.3s;
}

.input:focus {
  border-color: #3182ce;
  outline: none;
}

.btn {
  padding: 0.5rem 1rem;
  background-color: #3182ce;
  color: white;
  border-radius: 0.375rem;
  transition: background-color 0.3s;
}

.btn:hover {
  background-color: #2b6cb0;
}

.animate-pulse {
  animation: pulse 1s infinite;
}

@keyframes pulse {
  0% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
  100% {
    opacity: 1;
  }
}
</style>
