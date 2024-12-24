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
            <label for="search" class="block text-sm font-medium text-gray-700">Search by Name or ID</label>
            <input type="text" v-model="searchQuery" @input="filterPatients" id="search" class="input"
              placeholder="Enter patient name or ID" />
          </div>
  
          <div v-if="loading" class="loading-indicator">
            <svg class="animate-spin h-5 w-5 mr-3" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Searching...
          </div>
  
          <div v-else-if="filteredPatients.length > 0" class="patient-list">
            <div v-for="patient in filteredPatients" :key="patient.personalId" 
              @click="selectPatient(patient)"
              :class="[
                'patient-item',
                { 'selected': selectedPatient?.personalId === patient.personalId }
              ]">
              <div class="flex justify-between items-center">
                <div>
                  <span class="font-medium">
                    {{ patient.firstName }} {{ patient.middleName }} {{ patient.lastName }}
                    {{ patient.suffix !== 'None' ? patient.suffix : '' }}
                  </span>
                  <p class="text-sm text-gray-500">ID: {{ patient.personalId }}</p>
                </div>
                <div class="text-sm text-gray-500">
                  Age: {{ patient.age }}
                </div>
              </div>
            </div>
          </div>
  
          <div v-else-if="searchQuery.trim()" class="mt-4 text-sm text-gray-500">
            No patients found. You can proceed to add a new patient.
          </div>
  
          <div class="flex justify-end space-x-4 mt-6">
            <button @click="closeModal" class="bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600">
              Cancel
            </button>
            <button @click="addOrNextStep"
              class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 disabled:bg-gray-400">
              {{ selectedPatient ? "Next" : "Add New Patient" }}
            </button>
          </div>
        </div>
  
        <!-- Step 2: Add Patient Details -->
        <div v-if="step === 2">
          <h3 class="text-lg font-semibold mb-4">
            {{ selectedPatient ? "Patient Details" : "Add New Patient" }}
          </h3>
          <div v-if="selectedPatient" class="mb-4 p-4 bg-gray-50 rounded-lg">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-gray-500">Full Name</p>
                <p class="font-medium">
                  {{ selectedPatient.firstName }} {{ selectedPatient.middleName }} {{ selectedPatient.lastName }}
                  {{ selectedPatient.suffix !== 'None' ? selectedPatient.suffix : '' }}
                </p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Patient ID</p>
                <p class="font-medium">{{ selectedPatient.personalId }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Gender</p>
                <p class="font-medium">{{ selectedPatient.sex }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Birthdate</p>
                <p class="font-medium">{{ selectedPatient.birthdate }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Age</p>
                <p class="font-medium">{{ selectedPatient.age }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Address</p>
                <p class="font-medium">{{ selectedPatient.purok }} {{ selectedPatient.barangay }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Contact</p>
                <p class="font-medium">{{ selectedPatient.contact }}</p>
              </div>
            </div>
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
  import { ref, computed } from 'vue';
  import { Inertia } from '@inertiajs/inertia';
  import { router } from '@inertiajs/vue3';

  export default {
    props: {
      showModal: {
        type: Boolean,
        required: true
      },
      patients: {
        type: Array,
        required: true,
        default: () => [],
      },
    },
    data() {
      return {
        step: 1,
        searchQuery: '',
        selectedPatient: null,
        filteredPatients: this.patients,
        allowAddNewPatient: true,
        loading: false,
        form: {
          firstName: '',
          lastName: '',
          middleName: '',
          suffix: '',
          barangay: '',
          purok: '',
          birthdate: '',
          contact: '',
          sex: '',
          foodIntake: '',
          physicalActivity: '',
          bloodGlucose: '',
          fbsRbs: '',
          bloodGlucoseDate: '',
          bloodPressure: '',
          systolic: '',
          diastolic: '',
          bpDate: '',
          smoking: '',
          alcoholDrinking: '',
          familyHistory: '',
          familyHistoryDetails: '',
        }
      };
    },
    methods: {
      searchPatients() {
          this.loading = true; // Show loading indicator
          try {
              const response = Inertia.get('/path-to-your-endpoint', {
                  query: this.searchQuery // Pass the search query
              });

              // Assuming the response contains the patients data
              this.filteredPatients = response.data.patients; // Update with the fetched patients
          } catch (error) {
              console.error("Error fetching patients:", error);
          } finally {
              this.loading = false; // Hide loading indicator
          }
      },
      filterPatients() {
          if (this.searchQuery) {
              this.filteredPatients = this.patients.filter(patient => {
                const fullName = `${patient.firstName} ${patient.lastName}`.toLowerCase();
                return (
                  fullName.includes(this.searchQuery.toLowerCase()) ||
                  patient.firstName.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                  patient.lastName.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                  (patient.personalId && patient.personalId.toString().includes(this.searchQuery))
                );
              });

              // Reset selectedPatient if no patients are found
              if (this.filteredPatients.length === 0) {
                  this.selectedPatient = null; // Ensure this is set to null when no matches
              }
          } else {
              this.filteredPatients = this.patients; // Reset to all patients if search query is empty
          }
      },
      selectPatient(patient) {
        this.selectedPatient = patient;
        // Pre-fill the form with selected patient's data
        this.form = {
          ...this.form,
          firstName: patient.firstName,
          lastName: patient.lastName,
          middleName: patient.middleName || '',
          suffix: patient.suffix || '',
          barangay: patient.barangay,
          purok: patient.purok,
          birthdate: patient.birthdate,
          contact: patient.contact,
          sex: patient.sex
        };
      },
      closeModal() {
        this.$emit('close');
      },
      nextStep() {
        this.step++;
      },
      prevStep() {
        this.step--;
      },
      addOrNextStep() {
        if (this.selectedPatient) {
          this.nextStep();
        } else {
          // Clear form for new patient
          Object.keys(this.form).forEach(key => {
            if (!['foodIntake', 'physicalActivity', 'bloodGlucose', 'bloodPressure', 'smoking', 'alcoholDrinking', 'familyHistory'].includes(key)) {
              this.form[key] = '';
            }
          });
          this.nextStep();
        }
      },
      saveRiskData() {
        Inertia.post('/risk-management/store', this.form, {
          onSuccess: () => {
            console.log("Risk Management Data Successfully Submitted:", vaccinationData);
            this.resetForm();
            this.closeModal();
          },
          onError: (errors) => {
            console.error("Submission failed:", errors);
            this.errors = errors;
            console.log("Risk Management Data Failed to Submit:", vaccinationData);
          },
        });
      }
    },
    computed: {
      computedAge() {
        if (!this.form.birthdate) return '';
        const birthDate = new Date(this.form.birthdate);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
          age--;
        }
        return age;
      }
    },
    watch: {
      searchQuery: {
        handler(val) {
          if (val.length >= 2) {
            this.filterPatients();
          } else {
            this.filteredPatients = [];
          }
        },
        debounce: 300
      }
    }
  };
  </script>
  
  <style scoped>
  .input {
    @apply mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500;
  }

  .patient-list {
    @apply mt-4 bg-white border border-gray-300 rounded-lg max-h-60 overflow-y-auto divide-y divide-gray-200;
  }

  .patient-item {
    @apply px-4 py-3 hover:bg-gray-50 cursor-pointer transition duration-150;
  }

  .patient-item.selected {
    @apply bg-blue-50;
  }

  .loading-indicator {
    @apply flex items-center justify-center py-4 text-gray-500;
  }
  </style>