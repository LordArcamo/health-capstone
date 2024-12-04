<template>
  <div v-if="showModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl p-6">
        <!-- Modal Header -->
        <div class="flex justify-between items-center border-b pb-4 mb-4">
          <h2 class="text-xl font-semibold">Risk Management Assessment</h2>
          <button @click="closeModal" class="text-gray-500 hover:text-gray-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
  
        <!-- Step 1: Search or Add Patient -->
        <div v-if="step === 1">
          <h3 class="text-lg font-semibold mb-4">Search for a Patient</h3>
          <div>
            <label for="search" class="block text-sm font-medium text-gray-700">Search by Name</label>
            <input type="text" v-model="searchQuery" @input="filterPatients" id="search" class="input"
              placeholder="Enter patient name" />
          </div>
  
          <div v-if="filteredPatients.length > 0" class="mt-4">
            <ul class="bg-white border border-gray-300 rounded-lg max-h-60 overflow-y-auto">
              <li v-for="patient in filteredPatients" :key="patient.id" @click="selectPatient(patient)" :class="{
                'bg-gray-100': selectedPatient?.id === patient.id,
                'hover:bg-gray-50': selectedPatient?.id !== patient.id,
              }" class="cursor-pointer px-4 py-2">
                {{ patient.name }} ({{ patient.age }} years)
              </li>
            </ul>
          </div>
  
          <div v-else-if="searchQuery.trim()" class="mt-4 text-sm text-gray-500">
            No patients found. You can proceed to add a new patient.
          </div>
  
          <div class="flex justify-end space-x-4 mt-6">
            <button @click="closeModal" class="bg-gray-500 text-white py-2 px-4 rounded-md">Cancel</button>
            <button :disabled="!selectedPatient && !allowAddNewPatient" @click="addOrNextStep"
              class="bg-blue-500 text-white py-2 px-4 rounded-md disabled:bg-gray-400">
              {{ selectedPatient ? "Next" : "Add New Patient" }}
            </button>
          </div>
        </div>
  
        <!-- Step 2: Add Patient Details -->
        <div v-if="step === 2">
          <h3 class="text-lg font-semibold mb-4">
            {{ selectedPatient ? "Patient Details" : "Add New Patient" }}
          </h3>
          <div v-if="selectedPatient" class="mb-4">
            <p><strong>Name:</strong> {{ selectedPatient.name }}</p>
            <p><strong>Age:</strong> {{ selectedPatient.age }}</p>
          </div>
  
          <div v-else>
                 <!-- Patient Information Form -->
                 <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block">First Name:</label>
              <input type="text" v-model="form.firstName" class="input" placeholder="Example: Juan" required />
            </div>
            <div>
              <label class="block">Last Name:</label>
              <input type="text" v-model="form.lastName" class="input" placeholder="Example: Dela Cruz" required />

            </div>

            <div>
              <label class="block">Middle Name:</label>
              <input type="text" v-model="form.middleName" class="input" placeholder="Example: Penduko" required />

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
            </div>


            <div>
              <label class="block">Purok:</label>
              <input type="text" v-model="form.purok" class="input" placeholder="Example: Purok 1A" required />
            </div>
            <div>
              <label class="block">Birthdate:</label>
              <input type="date" v-model="form.birthdate" class="input" required />
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
          </div>
          <div class="mt-6 flex justify-between">
            <button @click="prevStep" class="bg-gray-500 text-white py-2 px-4 rounded-md">Back</button>
            <button @click="nextStep" class="bg-blue-500 text-white py-2 px-4 rounded-md">Next</button>
          </div>
        </div>
  
        <!-- Step 3: Risk Factors -->
<!-- Step 3: Risk Factors -->
<div v-if="step === 3">
  <h3 class="text-lg font-semibold mb-4">Risk Factors</h3>
  <div class="grid grid-cols-1 gap-4">
    <!-- High Fat/High Salt Food Intake -->
    <div>
      <label class="block text-sm font-medium text-gray-700">High Fat/High Salt Food Intake</label>
      <div class="flex gap-4 mt-2">
        <label><input type="radio" v-model="form.foodIntake" value="Yes" /> Yes</label>
        <label><input type="radio" v-model="form.foodIntake" value="No" /> No</label>
      </div>
    </div>

    <!-- Physical Activity -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Physical Activities</label>
      <div class="mt-2 flex gap-4">
        <label><input type="radio" v-model="form.physicalActivity" value="Yes" /> Yes</label>
        <label><input type="radio" v-model="form.physicalActivity" value="No" /> No</label>
      </div>
    </div>

    <!-- Raised Blood Glucose -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Raised Blood Glucose</label>
      <div class="mt-2 flex gap-4">
        <label><input type="radio" v-model="form.bloodGlucose" value="Yes" /> Yes</label>
        <label><input type="radio" v-model="form.bloodGlucose" value="No" /> No</label>
      </div>
      <div v-if="form.bloodGlucose === 'Yes'" class="mt-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">FBS/RBS (mg/dL)</label>
            <input type="number" v-model="form.fbsRbs" class="input" placeholder="Enter mg/dL" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Date Taken</label>
            <input type="date" v-model="form.bloodGlucoseDate" class="input" />
          </div>
        </div>
      </div>
    </div>

    <!-- Raised Blood Lipids -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Raised Blood Lipids</label>
      <div class="mt-2 flex gap-4">
        <label><input type="radio" v-model="form.bloodLipids" value="Yes" /> Yes</label>
        <label><input type="radio" v-model="form.bloodLipids" value="No" /> No</label>
      </div>
      <div v-if="form.bloodLipids === 'Yes'" class="mt-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Total Cholesterol (mmol/L)</label>
            <input type="number" v-model="form.totalCholesterol" class="input" placeholder="Enter mmol/L" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Date Taken</label>
            <input type="date" v-model="form.bloodLipidsDate" class="input" />
          </div>
        </div>
      </div>
    </div>

    <!-- Presence of Urine Ketones -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Presence of Urine Ketones</label>
      <div class="mt-2 flex gap-4">
        <label><input type="radio" v-model="form.urineKetones" value="Yes" /> Yes</label>
        <label><input type="radio" v-model="form.urineKetones" value="No" /> No</label>
      </div>
      <div v-if="form.urineKetones === 'Yes'" class="mt-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Urine Ketone (mg/dL)</label>
          <input type="number" v-model="form.urineKetoneLevel" class="input" placeholder="Enter mg/dL" />
        </div>
        <div class="mt-2">
          <label class="block text-sm font-medium text-gray-700">Date Taken</label>
          <input type="date" v-model="form.urineKetonesDate" class="input" />
        </div>
      </div>
    </div>

    <!-- Presence of Urine Protein -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Presence of Urine Protein</label>
      <div class="mt-2 flex gap-4">
        <label><input type="radio" v-model="form.urineProtein" value="Yes" /> Yes</label>
        <label><input type="radio" v-model="form.urineProtein" value="No" /> No</label>
      </div>
      <div v-if="form.urineProtein === 'Yes'" class="mt-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Urine Protein Level (mg/dL)</label>
          <input type="number" v-model="form.urineProteinLevel" class="input" placeholder="Enter mg/dL" />
        </div>
        <div class="mt-2">
          <label class="block text-sm font-medium text-gray-700">Date Taken</label>
          <input type="date" v-model="form.urineProteinDate" class="input" />
        </div>
      </div>
    </div>
  </div>

  <div class="mt-6 flex justify-between">
    <button @click="prevStep" class="bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600">Back</button>
    <button @click="saveRiskData" class="bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600">Save</button>
  </div>
</div>

      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      showModal: Boolean,
    },
    data() {
      return {
        step: 1,
        searchQuery: "",
        patients: [
          { id: 1, name: "John Doe", age: 35 },
          { id: 2, name: "Jane Smith", age: 29 },
        ],
        filteredPatients: [],
        selectedPatient: null,
        allowAddNewPatient: false,
        form: {
          name: "",
          age: "",
          foodIntake: "",
          physicalActivity: "",
        },
      };
    },
    methods: {
      closeModal() {
        this.$emit("close");
      },
      nextStep() {
        if (this.step < 3) this.step++;
      },
      prevStep() {
        if (this.step > 1) this.step--;
      },
      saveRiskData() {
        this.$emit("save", this.form);
        this.closeModal();
      },
      filterPatients() {
        const query = this.searchQuery.trim().toLowerCase();
        this.filteredPatients = this.patients.filter((patient) =>
          patient.name.toLowerCase().includes(query)
        );
        this.allowAddNewPatient = this.filteredPatients.length === 0;
      },
      selectPatient(patient) {
        this.selectedPatient = patient;
        this.allowAddNewPatient = false;
      },
      addOrNextStep() {
        if (this.allowAddNewPatient) {
          this.step = 2;
        } else if (this.selectedPatient) {
          this.nextStep();
        }
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
  </style>
  