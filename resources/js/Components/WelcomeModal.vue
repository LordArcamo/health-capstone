  <template>
    <div class="flex justify-center items-center min-h-screen">
      <div class="bg-white rounded-xl shadow-2xl py-8 px-10 text-center max-w-4xl w-full">
        <h2 class="text-3xl font-semibold mb-4 text-gray-800">Welcome! <span class="ml-2">👋</span></h2>

        <!-- Step Indicator -->
        <div class="mb-6">
          <div class="flex justify-center items-center space-x-4">
            <div 
              v-for="n in 2" 
              :key="n"
              class="relative flex items-center justify-center w-10 h-10 rounded-full border-2 transition-all duration-300"
              :class="{
                'border-green-500': step === n, 
                'border-gray-300': step !== n,
                'bg-green-500 text-white': step >= n
              }"
            >
              <span :class="{ 'font-bold': step >= n }">{{ n }}</span>
            </div>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-2 mt-4">
            <div
              class="bg-green-500 h-2 rounded-full"
              :style="{ width: step === 1 ? '50%' : '100%' }"
            ></div>
          </div>
        </div>

        <!-- Step 1: Search for a Patient -->
        <transition name="fade" mode="out-in">
          <div v-if="step === 1" key="step1" class="transition-all">
            <p class="text-gray-700 mb-4 text-lg">Enter the patient's full name to check if they exist. If not, click "Add New Patient" to register them.</p>
            <div class="flex space-x-2 mb-6">
              <input
                type="text"
                v-model="searchQuery"
                @input="debouncedSearchPatients"
                placeholder="Example: Pedro Penduko"
                class="border p-4 rounded-xl w-full focus:outline-none focus:ring-2 focus:ring-green-300 transition duration-200"
              />
            </div>

            <!-- Search Results -->
            <div class="border rounded-xl p-4 max-h-40 overflow-y-auto bg-white shadow-md mb-6">
              <ul v-if="filteredPatients.length">
                <li
                  v-for="patient in filteredPatients"
                  :key="patient.personalId"
                  @click="selectPatient(patient)"
                  class="p-4 cursor-pointer flex justify-between items-center hover:bg-gray-100 rounded-lg transition duration-300"
                >
                  <div>
                    <p class="font-semibold text-gray-800">{{ patient.firstName }} {{ patient.lastName }}</p>
                    <p class="text-sm text-gray-500">ID: {{ patient.personalId }}</p>
                  </div>
                  <span class="text-green-500 font-bold">➔</span>
                </li>
              </ul>
              <p v-else-if="searchQuery" class="text-center text-sm text-gray-500">
                No patients found for "{{ searchQuery }}".
              </p>
              <p v-else class="text-center text-sm text-gray-400">
                Start typing to search for a patient.
              </p>
            </div>

            <!-- Add New Patient -->
            <div class="flex flex-col items-center justify-center" v-if="!filteredPatients.length && searchQuery">
              <p class="text-gray-700 mb-4 text-lg">Patient not found. You can add them:</p>
              <button
                @click="addNewPatient"
                class="bg-green-600 test text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:bg-green-700 transition-all duration-300 transform hover:scale-105"
              >
                Add New Patient
              </button>
            </div>
          </div>
        </transition>

        <!-- Step 2: Choose a Check-up -->
        <transition name="fade" mode="out-in">
          <div v-if="step === 2" key="step2" class="transition-all">
            <p class="text-gray-700 mb-6 text-lg">What type of Check-Up do we have today?</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
              <Link
                v-if="selectedPatient && Object.keys(selectedPatient).length > 0"
            :href="route('itr', { patient_personalId: selectedPatient.personalId || 'new', })"
                class="bg-gradient-to-r from-green-500 to-yellow-500 text-white font-semibold py-4 px-6 rounded-xl shadow-lg hover:from-green-600 hover:to-yellow-600 transition-all duration-300 transform hover:scale-105"
              >
                Individual Treatment Record
              </Link>

              <Link
                  v-if="selectedPatient && Object.keys(selectedPatient).length > 0"
              :href="route('prenatal', { patient_personalId: selectedPatient.personalId || 'new', })"
                  class="bg-gradient-to-r from-green-500 to-yellow-500 text-white font-semibold py-4 px-6 rounded-xl shadow-lg hover:from-green-600 hover:to-yellow-600 transition-all duration-300 transform hover:scale-105"
                >
                  Prenatal Checkup
                </Link>

              <Link
                v-if="selectedPatient && Object.keys(selectedPatient).length > 0"
            :href="route('nationalimmunizationprogram', { patient_personalId: selectedPatient.personalId || 'new', })"
                class="bg-gradient-to-r from-green-500 to-yellow-500 text-white font-semibold py-4 px-6 rounded-xl shadow-lg hover:from-green-600 hover:to-yellow-600 transition-all duration-300 transform hover:scale-105"
              >
                National Immunization Program
              </Link>
            </div>
          </div>
        </transition>

        <!-- Proceed Button -->
        <div v-if="step === 1 && selectedPatient" class="mt-8">
          <button
            @click="step = 2"
            class="bg-green-500 text-white font-semibold py-4 px-6 rounded-xl shadow-lg hover:bg-green-600 transition-all duration-300 transform hover:scale-105"
          >
            Proceed to Check-Up
          </button>
        </div>

        <!-- Modal -->
        <!-- <transition name="modal" mode="out-in">
          <div v-if="showModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex justify-center items-center">
            <div class="bg-white rounded-xl shadow-lg py-8 px-6 text-center max-w-md w-full relative transition-transform transform scale-95 hover:scale-100">
              <button
                @click="showModal = false"
                class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl"
              >
                &times;
              </button>
              <h2 class="text-2xl font-semibold mb-6">Select Checkup Type</h2>
              <div class="flex flex-col space-y-4">
                <Link
                  :href="route('prenatal', { patient_id: selectedPatient.personalId })"
                  class="bg-gradient-to-r from-green-500 to-yellow-500 text-white font-semibold py-4 px-6 rounded-xl shadow-lg hover:from-green-600 hover:to-yellow-600 transition-all duration-300 transform hover:scale-105"
                >
                  Prenatal Checkup
                </Link>
                <Link
                  :href="route('postpartum', { patient_id: selectedPatient.personalId })"
                  class="bg-gradient-to-r from-green-500 to-yellow-500 text-white font-semibold py-4 px-6 rounded-xl shadow-lg hover:from-green-600 hover:to-yellow-600 transition-all duration-300 transform hover:scale-105"
                >
                  Postpartum Checkup
                </Link>
              </div>
            </div>
          </div>
        </transition> -->
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
        // Only set the filteredPatients if there's a search query
        if (this.searchQuery.trim() === '') {
          this.filteredPatients = []; // Clear patients if search query is empty
        } else {
          this.filteredPatients = [...newPatients]; // Otherwise show all patients
        }
      },
    },
  },
  methods: {
    debouncedSearchPatients: debounce(function () {
      this.searchPatients();
    }, 100),
    searchPatients() {
      const query = this.searchQuery.trim().toLowerCase();

      // If search query is empty, clear the filtered list
      if (query === '') {
        this.filteredPatients = [];
      } else {
        // Filter patients only when there's a search query
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
      this.selectedPatient = patient;
      this.$emit('patientSelected', patient); // Emit selected patient
      this.step = 2;
    },
    addNewPatient() {
      console.log("Add New Patient triggered");
      // Create a blank selected patient for a new patient flow
      this.selectedPatient = {
          personalId: null, // Indicating a new patient
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
      this.$emit('patientSelected', this.selectedPatient); // Emit the new patient object
      this.step = 2; // Proceed to check-up type selection
  }
  },
};
</script>

  <style scoped>
  .fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s;
  }
  .fade-enter, .fade-leave-to {
    opacity: 0;
  }
  .modal-enter-active, .modal-leave-active {
    transition: transform 0.3s, opacity 0.3s;
  }
  .modal-enter, .modal-leave-to {
    transform: scale(0.95);
    opacity: 0;
  }
  </style>
