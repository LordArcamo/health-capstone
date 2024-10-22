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
              <input type="text" v-model="form.firstName " class="input"required/>
            </div>
            <div>
              <label class="block">Last Name:</label>
              <input type="text" v-model="form.lastName " class="input" required />
            </div>
            <div>
              <label class="block">Middle Name:</label>
              <input type="text" v-model="form.middleName " class="input" required/>
            </div>
            <div>
              <label class="block">Suffix:</label>
              <select v-model="form.suffix" class="input">
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
            </div>
            <div>
              <label class="block">Residential Address:</label>
              <input type="text" v-model="form.address" class="input" required />
            </div>
            <div>
              <label class="block">Age:</label>
              <input type="number" v-model="computedAge" class="input" readonly />
            </div>
            <div>
              <label class="block">Birthdate:</label>
              <input type="date" v-model="form.birthdate" class="input" required />
            </div>
            <div>
              <label class="block">Contact Number:</label>
              <input type="text" v-model="form.contact" class="input" required />
            </div>
            <div>
              <label class="block">Gender</label>
              <select v-model="form.sex" class="input" required>
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
            </div>
            <div>
              <label class="block">Consultation Time:</label>
              <input type="time" v-model="form.consultationTime" class="input" required />
            </div>
            <div>
              <label class="block">Mode of Transaction:</label>
              <select v-model="form.modeOfTransaction" class="input" required>
                <option>Walk-in</option>
                <option>Visited</option>
                <option>Referral</option>
              </select>
            </div>
            <!-- <div>
              <label class="block">Referred From</label>
              <input type="text" v-model="form.reasonForReferral" class="input"></input>
            </div>
            <div>
              <label class="block">Reason(s) for Referral:</label>
              <textarea v-model="form.reasonForReferral" class="input"></textarea>
            </div> -->
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
              <input type="text" v-model="form.providerName" class="input" />
            </div>
            <div>
              <label class="block">Nature of Visit:</label>
              <select v-model="form.natureOfVisit" class="input" required>
                <option>New Consultation/Case</option>
                <option>New Admission</option>
                <option>Follow-up Visit</option>
              </select>
            </div>
            <div>
              <label class="block">Type of Consultation/Purpose of Visit:</label>
              <select v-model="form.visitType" class="input" required>
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
              <textarea v-model="form.chiefComplaints" class="input"></textarea>
            </div>
            <div>
              <label class="block">Diagnosis:</label>
              <textarea v-model="form.diagnosis" class="input"></textarea>
            </div>
            <div>
              <label class="block">Medication/Treatment:</label>
              <textarea v-model="form.medication" class="input"></textarea>
            </div>
          </div>
          <div class="mt-6 flex justify-between">
            <button @click="prevStep" class="btn">Back</button>
            <button type="submit" class="btn">Submit</button>
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
  props: ['onSubmit'],
  data() {
    return {
      step: 1,
      form: {
        firstName: '',
        lastName: '',
        middleName: '',
        suffix: '',
        address: '',
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
        medication: ''
      },
      successMessage: '',
    };
  },
  computed: {
    computedAge() {
      return this.calculateAge();
    },
  },
  methods: {
    calculateAge() {
      if (!this.form.birthdate) return '';
      const birthdate = new Date(this.form.birthdate);
      const today = new Date();
      let age = today.getFullYear() - birthdate.getFullYear();
      const monthDiff = today.getMonth() - birthdate.getMonth();
      if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdate.getDate())) {
        age--;
      }
      this.form.age = age;
      return age;
    },
    submitForm() {
      console.log('Submitting form with data:', this.form);
      alert('Form submitted');
      this.onSubmit(this.form); // Pass form data to parent
    },
    nextStep() {
      if (this.step < 3) {
        this.step++;
      }
    },
    prevStep() {
      if (this.step > 1) {
        this.step--;
      }
    },
  }
};
</script>

<style>
.input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  margin-top: 0.5rem;
}

.btn {
  background-color: #1d72b8;
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