<template>
  <div v-if="showModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl p-6">
      <!-- Modal Header -->
      <div class="flex justify-between items-center border-b pb-4 mb-4">
        <h2 class="text-xl font-semibold">Vaccination Details</h2>
        <button @click="closeModal" class="text-gray-500 hover:text-gray-800">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Step 1: Search for Patient -->
      <div v-if="step === 1">
        <h3 class="text-lg font-semibold mb-4">Search for a Patient</h3>
        <div>
          <label for="search" class="block text-sm font-medium text-gray-700">Search by Name</label>
          <input
            type="text"
            v-model="searchQuery"
            @input="filterPatients"
            id="search"
            class="input"
            placeholder="Enter patient name"
          />
        </div>

        <div v-if="filteredPatients.length > 0" class="mt-4">
          <ul class="bg-white border border-gray-300 rounded-lg max-h-60 overflow-y-auto">
            <li
              v-for="patient in filteredPatients"
              :key="patient.id"
              @click="selectPatient(patient)"
              :class="{
                'bg-gray-100': selectedPatient?.id === patient.id,
                'hover:bg-gray-50': selectedPatient?.id !== patient.id,
              }"
              class="cursor-pointer px-4 py-2"
            >
              {{ patient.name }} ({{ patient.age }} years)
            </li>
          </ul>
        </div>

        <div v-else-if="searchQuery.trim()" class="mt-4 text-sm text-gray-500">
          No patients found. You can proceed to add a new patient.
        </div>

        <div class="flex justify-end space-x-4 mt-6">
          <button @click="closeModal" class="bg-red-500 text-white py-2 px-4 rounded-md">Cancel</button>
          <button
            :disabled="!selectedPatient && !allowAddNewPatient"
            @click="addOrNextStep"
            class="bg-blue-500 text-white py-2 px-4 rounded-md disabled:bg-gray-400"
          >
            {{ selectedPatient ? "Next" : "Add New Patient" }}
          </button>
        </div>
      </div>

      <!-- Step 2: Patient Details -->
      <div v-if="step === 2">
        <h3 class="text-lg font-semibold mb-4">
          {{ selectedPatient ? "Patient Details" : "Add New Patient" }}
        </h3>
        <div v-if="selectedPatient" class="mb-4">
          <p><strong>Name:</strong> {{ selectedPatient.name }}</p>
          <p><strong>Age:</strong> {{ selectedPatient.age }}</p>
          <p><strong>Contact:</strong> {{ selectedPatient.contact }}</p>
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

        <div class="flex justify-between mt-6">
          <button @click="prevStep" class="bg-gray-500 text-white py-2 px-4 rounded-md">Back</button>
          <button @click="nextStep" class="bg-blue-500 text-white py-2 px-4 rounded-md">Next</button>
        </div>
      </div>

      <!-- Step 3: Vaccine Selection -->
      <div v-if="step === 3">
        <h3 class="text-lg font-semibold mb-4">Vaccine Details</h3>
        <div class="mb-4">
          <label for="vaccineCategory" class="block text-sm font-medium text-gray-700">Vaccine Category</label>
          <select
            v-model="form.vaccineCategory"
            id="vaccineCategory"
            class="input"
          >
            <option disabled value="">Select Category</option>
            <option v-for="category in vaccineCategories" :key="category" :value="category">{{ category }}</option>
          </select>
        </div>
        <div class="mb-4">
          <label for="vaccineType" class="block text-sm font-medium text-gray-700">Vaccine Type</label>
          <select
            v-model="form.vaccineType"
            id="vaccineType"
            class="input"
          >
            <option disabled value="">Select Vaccine Type</option>
            <option v-for="type in vaccineTypes" :key="type" :value="type">{{ type }}</option>
          </select>
        </div>

        <div class="flex justify-between mt-6">
          <button @click="prevStep" class="bg-gray-500 text-white py-2 px-4 rounded-md">Back</button>
          <button @click="nextStep" class="bg-blue-500 text-white py-2 px-4 rounded-md">Next</button>
        </div>
      </div>

      <!-- Step 4: Confirmation -->
      <div v-if="step === 4">
        <h3 class="text-lg font-semibold mb-4">Confirmation</h3>
        <p><strong>Patient:</strong> {{ selectedPatient?.name || form.firstName + " " + form.lastName }}</p>
        <p><strong>Vaccine Category:</strong> {{ form.vaccineCategory }}</p>
        <p><strong>Vaccine Type:</strong> {{ form.vaccineType }}</p>

        <div class="flex justify-between mt-6">
          <button @click="prevStep" class="bg-gray-500 text-white py-2 px-4 rounded-md">Back</button>
          <button @click="saveVaccination" class="bg-green-500 text-white py-2 px-4 rounded-md">Save</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      showModal: true,
      step: 1,
      searchQuery: "",
      patients: [
        { id: 1, name: "John Doe", age: 35, contact: "+63 912 345 6789" },
        { id: 2, name: "Jane Smith", age: 29, contact: "+63 987 654 3210" },
      ],
      filteredPatients: [],
      selectedPatient: null,
      allowAddNewPatient: false,
      form: {
        firstName: "",
        lastName: "",
        vaccineCategory: "",
        vaccineType: "",
      },
      vaccineCategories: ["Category A", "Category B"],
      vaccineTypes: ["Type 1", "Type 2"],
    };
  },
  methods: {
    closeModal() {
      this.showModal = false;
    },
    nextStep() {
      this.step++;
    },
    prevStep() {
      this.step--;
    },
    addOrNextStep() {
      if (this.allowAddNewPatient) {
        this.selectedPatient = null; // Clear any selected patient when adding a new one
        this.step = 2; // Move to add patient form
      } else if (this.selectedPatient) {
        this.nextStep(); // Proceed to the next step if a patient is selected
      }
    },
    filterPatients() {
      const query = this.searchQuery.trim().toLowerCase();
      if (query) {
        this.filteredPatients = this.patients.filter((patient) =>
          patient.name.toLowerCase().includes(query)
        );
        this.allowAddNewPatient = this.filteredPatients.length === 0;
      } else {
        this.filteredPatients = [];
        this.allowAddNewPatient = false;
      }
    },
    selectPatient(patient) {
      this.selectedPatient = patient; // Set the selected patient
      this.allowAddNewPatient = false; // Disable add new patient when a patient is selected
    },
    saveVaccination() {
      const newPatient = {
        id: this.patients.length + 1,
        name: `${this.form.firstName} ${this.form.lastName}`,
        age: "N/A",
        contact: this.form.contact || "N/A",
      };
      if (this.allowAddNewPatient) {
        this.patients.push(newPatient); // Add new patient to the list
        this.selectedPatient = newPatient; // Set the new patient as selected
      }
      console.log("Vaccination saved:", {
        patient: this.selectedPatient,
        vaccineCategory: this.form.vaccineCategory,
        vaccineType: this.form.vaccineType,
      });
      this.closeModal();
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
