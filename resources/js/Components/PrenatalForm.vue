<template>
  <div class="min-h-screen flex flex-col justify-center items-center bg-gray-100">
    <div class="bg-white shadow-md rounded-lg p-8 max-w-4xl w-full">
      <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Prenatal & Postnatal Checkup</h2>

      <form @submit.prevent="submitForm">
        <!-- Step 1: Patient Information -->
        <div v-if="step === 1">
          <h3 class="text-lg font-semibold mb-4">Patient Information</h3>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block">First Name:</label>
              <input type="text" v-model="form.firstName" class="input" required />
            </div>
            <div>
              <label class="block">Last Name:</label>
              <input type="text" v-model="form.lastName" class="input" required />
            </div>
            <div>
              <label class="block">Middle Name:</label>
              <input type="text" v-model="form.middleName" class="input" required />
            </div>
            <div>
              <label class="block">Purok:</label>
              <input type="text" v-model="form.purok" class="input" required />
            </div>
            <div>
              <label class="block">Barangay:</label>
              <input type="text" v-model="form.barangay" class="input" required />
            </div>
            <div>
              <label class="block">Age</label>
              <input type="number" v-model="computedAge" class="input" readonly />
            </div>
          </div>
          <div class="mt-5">
            <label class="block">Birthdate:</label>
            <input type="date" v-model="form.birthdate" class="input" required />
          </div>
          <div>
            <label class="block">Contact Number:</label>
            <input type="text" v-model="form.contact" class="input" required />
          </div>
          <div class="mt-6 flex justify-center text-right">
            <button @click="nextStep" class="btn">Next</button>
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
      if (this.step < 5) this.step++;
    },
    prevStep() {
      if (this.step > 1) this.step--;
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