<template>
  <div class="relative min-h-screen flex justify-center items-center bg-gradient-to-br from-green-100 to-blue-100">
    <!-- Background Decorative Overlay -->
    <div class="absolute inset-0 z-0 opacity-20">
      <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 1440 320">
        <path fill="#FFFFFF" fill-opacity="1"
          d="M0,160L40,165.3C80,171,160,181,240,181.3C320,181,400,171,480,160C560,149,640,139,720,122.7C800,107,880,85,960,101.3C1040,117,1120,171,1200,181.3C1280,192,1360,160,1400,144L1440,128L1440,0L1400,0C1360,0,1280,0,1200,0C1120,0,1040,0,960,0C880,0,800,0,720,0C640,0,560,0,480,0C400,0,320,0,240,0C160,0,80,0,40,0L0,0Z">
        </path>
      </svg>
    </div>

    <!-- Main Content -->
    <div class="relative z-10 bg-white rounded-xl shadow-2xl py-8 px-10 text-center max-w-4xl w-full space-y-8">
      <!-- Welcome Section -->
      <div>
        <h2 class="text-4xl font-semibold mb-4 text-gray-800">
          Welcome to Initao RHU! <span class="ml-2">🌿</span>
        </h2>
        <p class="text-md text-gray-500 mt-2">
          The Initao Regional Health Unit is dedicated to providing top-notch healthcare services to the community.
          From preventive care to specialized checkups, we are here to ensure the well-being of every individual.
        </p>
      </div>

      <!-- Step Indicator -->
      <div>
        <div class="flex justify-center items-center space-x-4">
          <div v-for="n in 2" :key="n"
            class="relative flex items-center justify-center w-12 h-12 rounded-full border-2 transition-all duration-300"
            :class="{
              'border-green-500 bg-green-500 text-white': step >= n,
              'border-gray-300 text-gray-600': step < n,
            }">
            <span class="font-semibold">{{ n }}</span>
          </div>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2 mt-4">
          <div class="bg-green-500 h-2 rounded-full" :style="{ width: step === 1 ? '50%' : '100%' }"></div>
        </div>
      </div>

      <!-- Step 1: Search for a Patient -->
      <transition name="fade" mode="out-in">
        <div v-if="step === 1" key="step1" class="space-y-6">
          <p class="text-gray-700 text-lg">
            Enter Patient Details to Check whether they exist or not.
          </p>
          <div class="relative">
            <input type="text" v-model="searchQuery" @input="handleInput" placeholder="Example: Pedro Penduko"
              class="border border-gray-300 p-4 rounded-xl w-full focus:outline-none focus:ring-2 focus:ring-green-300 transition duration-200" />
          </div>

          <!-- Search Results -->
          <div v-if="searchQuery.trim()" class="border rounded-xl p-4 max-h-40 overflow-y-auto bg-white shadow-md mt-6">
            <ul v-if="filteredPatients.length">
              <li v-for="patient in filteredPatients" :key="patient.personalId" @click="selectPatient(patient)"
                class="p-4 cursor-pointer flex justify-between items-center hover:bg-gray-100 rounded-lg transition duration-300">
                <div>
                  <p class="font-semibold text-gray-800">
                    {{ patient.firstName }} {{ patient.lastName }}
                  </p>
                  <p class="text-sm text-gray-500">
                    Gender: {{ patient.sex }}, Age: {{ patient.age }}
                  </p>
                </div>
                <span class="text-green-500 font-bold">➔</span>
              </li>
            </ul>
            <p v-else class="text-center text-sm text-gray-500">
              No patients found for "{{ searchQuery }}".
            </p>
          </div>

          <!-- Add New Patient -->
          <div v-if="!filteredPatients.length && searchQuery.trim()"
            class="flex flex-col items-center justify-center mt-6">
            <p class="text-gray-700 mb-4 text-lg">
              Patient not found. Would you like to add them?
            </p>
            <button @click="addNewPatient"
              class="bg-green-600 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:bg-green-700 transition-all duration-300 transform hover:scale-105">
              Add New Patient
            </button>
          </div>
        </div>
      </transition>

      <!-- Step 2: Choose a Check-up -->
      <transition name="fade" mode="out-in">
        <div v-if="step === 2" key="step2" class="space-y-6">
          <p class="text-gray-700 text-lg">
            What type of Check-Up are we conducting today?
          </p>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <Link
              v-if="!selectedPatient.personalId || selectedPatient.sex === 'Male' || selectedPatient.sex === 'Female'"
              :href="route('itr', { patient_personalId: selectedPatient.personalId || 'new' })"
              class="bg-gradient-to-r from-green-500 to-yellow-500 text-white font-semibold py-4 px-6 rounded-xl shadow-lg hover:from-green-600 hover:to-yellow-600 transition-all duration-300 transform hover:scale-105">
            General Checkup
            </Link>
            <Link v-if="!selectedPatient.personalId || (selectedPatient.sex === 'Female' && selectedPatient.age >= 8)"
              :href="route('prenatal', { patient_personalId: selectedPatient.personalId || 'new' })"
              class="bg-gradient-to-r from-green-500 to-yellow-500 text-white font-semibold py-4 px-6 rounded-xl shadow-lg hover:from-green-600 hover:to-yellow-600 transition-all duration-300 transform hover:scale-105">
            Prenatal Checkup
            </Link>

            <Link v-if="!selectedPatient.personalId || selectedPatient.age <= 10"
              :href="route('nationalimmunizationprogram', { patient_personalId: selectedPatient.personalId || 'new' })"
              class="bg-gradient-to-r from-green-500 to-yellow-500 text-white font-semibold py-4 px-6 rounded-xl shadow-lg hover:from-green-600 hover:to-yellow-600 transition-all duration-300 transform hover:scale-105">
            National Immunization Program
            </Link>
          </div>
        </div>
      </transition>

      <!-- Proceed Button -->
      <div v-if="step === 1 && selectedPatient" class="mt-8">
        <button @click="step = 2"
          class="bg-green-500 text-white font-semibold py-4 px-6 rounded-xl shadow-lg hover:bg-green-600 transition-all duration-300 transform hover:scale-105">
          Proceed to Check-Up
        </button>
      </div>
    </div>
  </div>
</template>



<script>
import { Link } from '@inertiajs/vue3';
import { debounce } from 'lodash';

export default {
  components: { Link },
  props: {
    patients: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      step: 1,
      searchQuery: '',
      selectedPatient: null,
      showModal: false,
      filteredPatients: [],
    };
  },
  watch: {
    patients: {
      immediate: true,
      handler(newPatients) {
        console.log('Received patients:', newPatients);
        if (this.searchQuery.trim() === '') {
          this.filteredPatients = [];
        } else {
          this.filteredPatients = [...newPatients];
        }
      },
    },
  },
  methods: {
    handleInput() {
      this.capitalizeInput();
      this.debouncedSearchPatients();
    },
    capitalizeInput() {
      this.searchQuery = this.searchQuery
        .split(' ')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
    },
    debouncedSearchPatients: debounce(function () {
      this.searchPatients();
    }, 100),
    searchPatients() {
      const query = this.searchQuery.trim().toLowerCase();
      console.log('Search Query:', query);

      if (query === '') {
        this.filteredPatients = [];
      } else {
        this.filteredPatients = this.patients.filter(patient => {
          const fullName = `${patient.firstName} ${patient.lastName}`.toLowerCase();
          return (
            fullName.includes(query) ||
            patient.firstName.toLowerCase().includes(query) ||
            patient.lastName.toLowerCase().includes(query) ||
            patient.personalId.toString().includes(query)
          );
        });
      }

    },
    selectPatient(patient) {
      console.log('Patient selected:', patient);
      this.selectedPatient = {
        ...patient,
        age: this.calculateAge(patient.birthdate),
      };
      this.$emit('patientSelected', patient);
      this.step = 2;
    },
    calculateAge(birthdate) {
      const birthDate = new Date(birthdate);
      const today = new Date();
      let age = today.getFullYear() - birthDate.getFullYear();
      const monthDiff = today.getMonth() - birthDate.getMonth();

      if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
      }

      return age;
    },
    addNewPatient() {
      console.log('Add New Patient triggered');
      this.selectedPatient = {
        personalId: null,
        firstName: '',
        lastName: '',
        middleName: '',
        suffix: '',
        purok: '',
        barangay: '',
        birthdate: '',
        contact: '',
        sex: '',
      };
      this.$emit('patientSelected', this.selectedPatient);
      this.step = 2;
    },
  },
};
</script>


<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s;
}

.fade-enter,
.fade-leave-to {
  opacity: 0;
}

.modal-enter-active,
.modal-leave-active {
  transition: transform 0.3s, opacity 0.3s;
}

.modal-enter,
.modal-leave-to {
  transform: scale(0.95);
  opacity: 0;
}
</style>
