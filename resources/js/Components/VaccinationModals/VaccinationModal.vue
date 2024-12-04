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
          <button @click="closeModal" class="bg-red-500 text-white py-2 px-4 rounded-md">Cancel</button>
          <button :disabled="!selectedPatient && !allowAddNewPatient" @click="addOrNextStep"
            class="bg-blue-500 text-white py-2 px-4 rounded-md disabled:bg-gray-400">
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
          <label for="vaccineCategory" class="block text-sm font-medium text-gray-700">
            Vaccine Category
          </label>
          <select v-model="form.vaccineCategory" id="vaccineCategory" class="input">
            <option disabled value="">Select Category</option>
            <option v-for="category in vaccineCategories" :key="category" :value="category">
              {{ category }}
            </option>
          </select>
        </div>

        <div class="mb-4" v-if="form.vaccineCategory">
          <label for="vaccineType" class="block text-sm font-medium text-gray-700">
            Vaccine Type
          </label>
          <select v-model="form.vaccineType" id="vaccineType" class="input">
            <option disabled value="">Select Vaccine Type</option>
            <option v-for="type in vaccineTypesForCategory" :key="type" :value="type">
              {{ type }}
            </option>
          </select>
        </div>

        <div class="flex justify-between mt-6">
          <button @click="prevStep" class="bg-gray-500 text-white py-2 px-4 rounded-md">Back</button>
          <button @click="nextStep" class="bg-blue-500 text-white py-2 px-4 rounded-md">Next</button>
        </div>
      </div>

      <!-- Step 4: Vaccination Record Form -->
      <div v-if="step === 4">
        <h3 class="text-lg font-semibold mb-6">Vaccination Record</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Date of Visit -->
          <div>
            <label for="dateOfVisit" class="block text-sm font-medium text-gray-700">
              Date of Visit
            </label>
            <input type="date" v-model="form.dateOfVisit" id="dateOfVisit" class="input" required />
          </div>

          <!-- Conditional Age Input -->
          <div>
            <label for="age" class="block text-sm font-medium text-gray-700">
              {{ isUnderOneYear ? 'Age in Months' : 'Age in Years' }}
            </label>
            <input type="number" id="age" v-model="age" class="input"
              :placeholder="isUnderOneYear ? 'Enter age in months' : 'Enter age in years'" required />
          </div>

          <!-- Weight -->
          <div>
            <label for="weight" class="block text-sm font-medium text-gray-700">
              Weight
            </label>
            <input type="text" v-model="form.weight" id="weight" class="input" placeholder="Enter weight (e.g., 10 kg)"
              required />
          </div>

          <!-- Height -->
          <div>
            <label for="height" class="block text-sm font-medium text-gray-700">
              Height
            </label>
            <input type="text" v-model="form.height" id="height" class="input" placeholder="Enter height (e.g., 75 cm)"
              required />
          </div>

          <!-- Temperature -->
          <div>
            <label for="temperature" class="block text-sm font-medium text-gray-700">
              Temperature
            </label>
            <input type="text" v-model="form.temperature" id="temperature" class="input"
              placeholder="Enter temperature (e.g., 36.5°C)" required />
          </div>

          <!-- Antigen Given -->
          <div>
            <label for="antigenGiven" class="block text-sm font-medium text-gray-700">
              Antigen Given
            </label>
            <input type="text" v-model="form.antigenGiven" id="antigenGiven" class="input"
              placeholder="Enter antigen details" required />
          </div>

          <!-- Injected By -->
          <div>
            <label for="injectedBy" class="block text-sm font-medium text-gray-700">
              Injected By
            </label>
            <input type="text" v-model="form.injectedBy" id="injectedBy" class="input"
              placeholder="Enter name of injector" required />
          </div>

          <!-- Exclusively Breastfed (only for Under 1 Year) -->
          <div v-if="isUnderOneYear">
            <label for="exclusivelyBreastfed" class="block text-sm font-medium text-gray-700">
              Exclusively Breastfed
            </label>
            <select v-model="form.exclusivelyBreastfed" id="exclusivelyBreastfed" class="input">
              <option disabled value="">Select</option>
              <option value="Yes">Yes</option>
              <option value="No">No</option>
            </select>
          </div>

          <!-- Next Appointment -->
          <div>
            <label for="nextAppointment" class="block text-sm font-medium text-gray-700">
              Next Appointment
            </label>
            <input type="date" v-model="form.nextAppointment" id="nextAppointment" class="input" required />
          </div>
        </div>
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
        birthdate: "",
        contact: "",
        vaccineCategory: "",
        vaccineType: "",
        dateOfVisit: "",
        ageInMonths: "",
        ageInYears: "",
        weight: "",
        height: "",
        temperature: "",
        antigenGiven: "",
        injectedBy: "",
        exclusivelyBreastfed: "",
        nextAppointment: "",
      },
      vaccineCategories: [
        "Pregnant",
        "Under 1 Year",
        "School-aged Immunization",
        "Senior Citizen",
      ],
      vaccineTypes: {
        Pregnant: ["Tetanus Toxoid", "Tetanus Diphtheria"],
        "Under 1 Year": [
          "BCG",
          "Hepatitis B",
          "Pentavalent 1, 2, 3",
          "Bivalent Oral Polio Vaccine 1, 2, 3",
          "Inactivated Polio Vaccine 1, 2, 3",
          "Pneumococcal Conjugate Vaccine 1, 2, 3",
          "Measles, Mumps, Rubella 1, 2",
        ],
        "School-aged Immunization": [
          "Grade 1: Measles & Rubella",
          "Grade 4: Tetanus Diphtheria",
          "HPV",
        ],
        "Senior Citizen": ["PPV23", "Flu Vaccine"],
      },
      vaccineTypesForCategory: [],
    };
  },
  watch: {
    "form.vaccineCategory": function () {
      this.updateVaccineTypes();
    },
  },
  computed: {
    // Determine if the selected category is "Under 1 Year"
    isUnderOneYear() {
      return this.form.vaccineCategory === "Under 1 Year";
    },
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
  
  methods: {
    handleAgeInput(event) {
      // Clear irrelevant field based on the selected vaccine category
      if (this.isUnderOneYear) {
        this.form.ageInYears = "";
      } else {
        this.form.ageInMonths = "";
      }
    },
    updateVaccineTypes() {
      this.vaccineTypesForCategory =
        this.vaccineTypes[this.form.vaccineCategory] || [];
    },
    closeModal() {
      this.showModal = false;
    },
    nextStep() {
      if (this.step === 4) {
        this.saveVaccination(); // Save vaccination details on Step 4
      } else {
        this.step++;
      }
    },
    prevStep() {
      if (this.step > 1) {
        this.step--;
      }
    },
    addOrNextStep() {
      if (this.allowAddNewPatient) {
        this.step = 2;
      } else if (this.selectedPatient) {
        this.nextStep();
      }
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
    validateStepTwo() {
      const requiredFields = ["firstName", "lastName", "birthdate"];
      for (const field of requiredFields) {
        if (!this.form[field]) {
          alert(`Please complete the field: ${field}`);
          return;
        }
      }
      this.nextStep();
    },
    saveVaccination() {
      console.log("Vaccination saved:", this.form);
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
