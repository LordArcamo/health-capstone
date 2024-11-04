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
              <input type="text" v-model="form.lastName " class="input"required/>
            </div>
            <div>
              <label class="block">Middle Name:</label>
              <input type="text" v-model="form.middleName " class="input" required/>
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
            <div>
              <label class="block">Birthdate:</label>
              <input type="date" v-model="form.birthdate" class="input" required />
            </div>
            <div>
              <label class="block">Contact Number:</label>
              <input type="text" v-model="form.contact" class="input" required />
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
                <option>Walk-in</option>
                <option>Visited</option>
                <option>Referral</option>
              </select>
            </div>
            <div>
              <label class="block">Date of Consultation:</label>
              <input type="date" v-model="form.consultationDate" class="input" required />
            </div>
            <div>
              <label class="block">Consultation Time:</label>
              <input type="time" v-model="form.consultationTime" class="input" required />
            </div>
            <!-- <div>
              <label class="block">Referred From</label>
              <input type="text" v-model="form.reasonForReferral" class="input"></input>
            </div>-->
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
            <div>
              <label class="block">Name of Attending Provider:</label>
              <input type="text" v-model="form.providerName" class="input"></input>
            </div> 
            <div>
              <label class="block">Name of Spouse:</label>
              <input type="text" v-model="form.nameOfSpouse" class="input"></input>
            </div> 
            <div>
              <label class="block">Emergency Contact Number:</label>
              <input type="text" v-model="form.emergencyContact" class="input"></input>
            </div> 
            <div>
              <label class="block">4ps?:</label>
              <select v-model="form.fourMember" class="input" required>
                <option>Yes</option>
                <option>No</option>
              </select>
            </div>

          </div>
          <div class="form-group">
            <label for="philhealthStatus" class="block font-medium text-gray-700">Philhealth Status:</label>
            <select 
              id="philhealthStatus" 
              v-model="form.philhealthStatus" 
              class="input border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
              required
            >
              <option value="member">Member</option>
              <option value="dependent">Dependent</option>
            </select>

            <!-- Conditionally Render ID Input -->
            <div v-if="form.philhealthStatus === 'member'" class="mt-4">
              <label for="philhealthId" class="block font-medium text-gray-700">Philhealth ID Number:</label>
              <input 
                id="philhealthId" 
                v-model="form.philhealthId" 
                type="text" 
                class="input border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                placeholder="Enter ID number"
              />
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
      </div>

      <div>
        <label class="block">Onset of Sexual Intercourse:</label>
        <input type="text" v-model="form.sexualOnset" class="input" placeholder="Enter age" />
      </div>

      <div>
        <label class="block">Period/Duration:</label>
        <input type="text" v-model="form.periodDuration" class="input" placeholder="e.g., 5-7 days" required />
      </div>

      <div>
        <label class="block">Birth Control Method:</label>
        <input type="text" v-model="form.birthControl" class="input" placeholder="Enter method" />
      </div>

      <div>
        <label class="block">Interval/Cycle:</label>
        <input type="text" v-model="form.intervalCycle" class="input" placeholder="e.g., 28 days" required />
      </div>

      <div>
        <label class="block">Menopause? (Yes/No):</label>
        <select v-model="form.menopause" class="input" required>
          <option value="yes">Yes</option>
          <option value="no">No</option>
        </select>
      </div>

      <div>
        <label class="block">LMP (Last Menstrual Period):</label>
        <input type="date" v-model="form.lmp" class="input" required />
      </div>

      <div>
        <label class="block">EDC (Estimated Date of Confinement):</label>
        <input type="date"  v-model="form.edc"class="input" required />
      </div>
    </div>


    <!-- Labs Section -->
    <!-- <h2 class="font-bold mt-4">Labs Done On:</h2>
    <div class="grid grid-cols-2 gap-4">
      <div>
        <label class="block">Hemoglobin (gm/100ml):</label>
        <input v-model.number="form.hemoglobin" type="number" step="0.1" class="input" required />
      </div>

      <div>
        <label class="block">Hematocrit (vol %):</label>
        <input v-model.number="form.hematocrit" type="number" step="0.1" class="input" required />
      </div>

      <div>
        <label class="block">Urinalysis:</label>
        <input v-model="form.urinalysis" type="text" class="input" />
      </div>
    </div>

    <div class="grid grid-cols-2 gap-4 mt-4">
      <div>
        <label class="block">TT Status:</label>
        <input v-model="form.ttStatus" type="text" class="input" />
      </div>

      <div>
        <label class="block">TD (Date Given):</label>
        <input v-model="form.tdDate" type="date" class="input" />
      </div>
    </div> -->
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
            <input type="text" v-model="form.gravidity" class="input" required />
          </div>

          <div>
            <label class="block">Parity:</label>
            <input type="text" v-model="form.parity" class="input" required />
          </div>

          <div>
            <label class="block">Term:</label>
            <input type="text" v-model="form.term" class="input" />
          </div>

          <div>
            <label class="block">Preterm:</label>
            <input type="text" v-model="form.preterm" class="input" />
          </div>

          <div>
            <label class="block">Abortion:</label>
            <input type="text" v-model="form.abortion" class="input" />
          </div>

          <div>
            <label class="block">Living:</label>
            <input type="text" v-model="form.living" class="input" />
          </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-4">
          <div>
            <label class="block">Syphilis Test Result:</label>
            <select v-model="form.syphilisResult" class="input" required>
              <option value="negative">Negative</option>
              <option value="positive">Positive</option>
            </select>
          </div>

          <div>
            <label class="block">Penicillin Given:</label>
            <select v-model="form.penicillin" class="input" required>
              <option value="yes">Yes</option>
              <option value="no">No</option>
            </select>
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
        <input type="number" v-model="form.hemoglobin" step="0.1" class="input" required />
      </div>

      <div>
        <label class="block">Hematocrit (vol %):</label>
        <input type="number" v-model="form.hematocrit" step="0.1" class="input" required />
      </div>

      <div>
        <label class="block">Urinalysis:</label>
        <input type="text" v-model="form.urinalysis" class="input" />
      </div>
      <div>
        <label class="block">TT Status:</label>
        <input type="text" v-model="form.ttStatus" class="input" />
      </div>
    </div>

   

      <div>
        <label class="block">TD (Date Given):</label>
        <input type="date"v-model="form.tdDate" class="input" />
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