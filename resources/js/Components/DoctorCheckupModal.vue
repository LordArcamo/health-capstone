<template>
  <div class="relative min-h-screen flex justify-center items-center bg-gradient-to-br from-green-400 to-blue-300 overflow-hidden">
    <!-- Decorative Background -->
    <div class="absolute inset-0 opacity-10">
      <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 1440 320">
        <path fill="#ffffff"
          d="M0,160L40,165.3C80,171,160,181,240,181.3C320,181,400,171,480,160C560,149,640,139,720,122.7C800,107,880,85,960,101.3C1040,117,1120,171,1200,181.3C1280,192,1360,160,1400,144L1440,128L1440,0L1400,0C1360,0,1280,0,1200,0C1120,0,1040,0,960,0C880,0,800,0,720,0C640,0,560,0,480,0C400,0,320,0,240,0C160,0,80,0,40,0L0,0Z" />
      </svg>
    </div>

    <!-- Main Content -->
    <div class="relative z-10 bg-white rounded-2xl shadow-2xl py-14 px-12 max-w-7xl w-full">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

        <!-- Patients in Queue -->
        <div class="space-y-6">
          <h2 class="text-4xl font-extrabold text-green-600">🩺 Patients in Queue</h2>

          <!-- Search Bar -->
          <div class="relative">
            <input type="text" v-model="searchQuery" placeholder="🔍 Search patient..."
              class="w-full px-5 py-4 border border-green-400 rounded-xl focus:ring-2 focus:ring-green-500 placeholder-gray-400 shadow-sm transition" />
          </div>

          <!-- Patient Cards -->
          <div v-if="paginatedPatients.length" class="space-y-4">
            <div v-for="(patient, index) in paginatedPatients" :key="index"
              class="flex items-center justify-between p-5 bg-white rounded-xl shadow-md hover:bg-green-50 transition transform hover:scale-105 cursor-pointer">

              <!-- Left Section: Patient Details -->
              <div class="flex items-center gap-4">
                <!-- Queue Number -->
                <div
                  class="w-10 h-10 flex items-center justify-center bg-green-100 text-green-800 font-bold text-lg rounded-full">
                  {{ ((currentPage - 1) * patientsPerPage) + index + 1 }}
                </div>

                <!-- Patient Info -->
                <div>
                  <h3 class="text-lg font-semibold text-green-700">{{ patient.firstName }} {{ patient.lastName }}</h3>
                  <p class="text-sm text-gray-500">Age: {{ patient.age }} | Reason: {{ patient.visitType }}</p>
                  <p class="text-xs text-gray-400">⏰ Queued In: {{ formatTime(patient.consultationTime) }}</p>
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="flex space-x-3">
                <button @click="startCheckup(patient)" :disabled="patient.status === 'Cancelled'" :class="[
                    'px-5 py-2 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transition',
                    patient.status === 'Cancelled' ? 'bg-gray-400 cursor-not-allowed' :
                      patient.visitType === 'Prenatal' ? 'bg-pink-500 hover:bg-pink-600' : 'bg-green-500 hover:bg-green-600'
                  ]">
                    {{ patient.visitType === 'Prenatal' ? 'Start Prenatal' : 'Start Checkup' }}
                  </button>
                  <button @click="markAsCancelled(patient)" :disabled="patient.status === 'Cancelled'"
                    class="px-4 py-2 bg-red-500 text-white text-sm font-semibold rounded-lg shadow-md hover:bg-red-600 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                    Cancel
                  </button>
              </div>
            </div>
          </div>

          <p v-else class="text-center text-gray-500">No patients found in the queue.</p>

          <!-- Pagination -->
          <div class="flex justify-center space-x-5 mt-6">
            <button @click="previousPage" :disabled="currentPage === 1"
              class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 disabled:opacity-50">
              Previous
            </button>
            <button @click="nextPage" :disabled="currentPage === totalPages"
              class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 disabled:opacity-50">
              Next
            </button>
          </div>
        </div>

        <!-- Patient Details -->
        <div class="bg-white rounded-xl shadow-lg p-8 space-y-6">
          <h2 class="text-4xl font-extrabold text-green-600">📝 Patient Details</h2>

          <div v-if="selectedPatient">
            <h3 class="text-2xl font-semibold text-green-700">{{ selectedPatient.firstName }} {{ selectedPatient.lastName }}</h3>
            <p class="text-lg text-gray-600">Age: {{ selectedPatient.age }}</p>
            <p class="text-lg text-gray-600">Gender: {{ selectedPatient.sex }}</p>
            <p class="text-lg text-gray-600">Visit Type: {{ selectedPatient.visitType }}</p>

            <!-- Checkup Buttons -->
            <div class="flex space-x-4 mt-8">
              <button @click="startGeneralCheckup"
                class="bg-blue-500 text-white py-3 px-6 rounded-lg hover:bg-blue-600 transition">
                🏥 Start General Checkup
              </button>

              <button v-if="selectedPatient.visitType === 'Prenatal'" @click="startPrenatalCheckup"
                class="bg-pink-500 text-white py-3 px-6 rounded-lg hover:bg-pink-600 transition">
                🤰 Start Prenatal Checkup
              </button>
            </div>
          </div>
          <p v-else class="text-center text-gray-500">Select a patient to see details.</p>
        </div>
      </div>
    </div>
  </div>
</template>




<script>
import { router } from '@inertiajs/vue3';

export default {
  props: {
    patientsInQueue: Array,
  },
  data() {
    return {
      searchQuery: '',
      selectedPatient: null,
      currentPage: 1,
      patientsPerPage: 5,
    };
  },
  computed: {
    filteredPatients() {
      return this.patientsInQueue.filter((patient) =>
        `${patient.firstName} ${patient.lastName}`.toLowerCase().includes(this.searchQuery.toLowerCase())
      );
    },
    totalPages() {
      return Math.ceil(this.filteredPatients.length / this.patientsPerPage);
    },
    paginatedPatients() {
      const start = (this.currentPage - 1) * this.patientsPerPage;
      return this.filteredPatients.slice(start, start + this.patientsPerPage);
    },
  },
  methods: {
    refreshData() {
        this.$inertia.reload({ only: ['patientsInQueue'] });
    },
    formatTime(time) {
      return new Date(time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    },
    selectPatient(patient) {
      this.selectedPatient = patient;
    },
    startCheckup(patient) {
      console.log('Patient Data:', patient); // Debugging: Inspect patient data

      // Check if visitType is Prenatal and prenatalConsultationDetailsID exists
      if (patient?.visitType === 'Prenatal' && patient?.prenatalConsultationDetailsID) {
        console.log('Navigating to prenatal route with ID:', patient.prenatalConsultationDetailsID); // Debug
        this.$inertia.visit('/doctor-checkup/prenatal', {
          method: 'get',
          data: { prenatalConsultationDetailsID: patient.prenatalConsultationDetailsID },
        });
        return; // Exit after handling Prenatal case
      }

      // Check if consultationDetailsID exists (for general case)
      if (patient?.consultationDetailsID) {
        console.log('Navigating to ITR route with ID:', patient.consultationDetailsID); // Debug
        this.$inertia.visit('/doctor-checkup/itr', {
          method: 'get',
          data: { consultationDetailsID: patient.consultationDetailsID },
        });
        return; // Exit after handling ITR case
      }

      // If no valid data is found
      console.warn('Invalid patient data:', patient);
    },
    markAsCancelled(patient) {
        const data = patient.visitType === 'Prenatal' 
        ? { prenatalConsultationDetailsID: patient.prenatalConsultationDetailsID }
        : { consultationDetailsID: patient.consultationDetailsID };

        this.$inertia.post('/doctor/mark-as-cancelled', data, {
        onSuccess: () => {
            // Refresh the data after successful cancellation
            this.refreshData();
        }
        });
    },
    previousPage() {
      if (this.currentPage > 1) this.currentPage--;
    },
    nextPage() {
      if (this.currentPage < this.totalPages) this.currentPage++;
    },
  }
};

</script>


<style scoped>
  /* Add your custom styles if necessary */
  .hover\:bg-gray-100:hover {
    background-color: #f7fafc;
  }
  .bg-gradient-to-br {
    background: linear-gradient(to bottom right, #4caf50, #2196f3);
  }
</style>
