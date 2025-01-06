<template>
  <div class="relative min-h-screen max-h-screen flex justify-center items-center bg-gradient-to-br from-green-400 to-green-200 overflow-hidden">
    <!-- Background Decorative Overlay -->
    <div class="absolute inset-0 z-0 opacity-10">
      <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 1440 320">
        <path fill="#ffffff" d="M0,160L40,165.3C80,171,160,181,240,181.3C320,181,400,171,480,160C560,149,640,139,720,122.7C800,107,880,85,960,101.3C1040,117,1120,171,1200,181.3C1280,192,1360,160,1400,144L1440,128L1440,0L1400,0C1360,0,1280,0,1200,0C1120,0,1040,0,960,0C880,0,800,0,720,0C640,0,560,0,480,0C400,0,320,0,240,0C160,0,80,0,40,0L0,0Z"></path>
      </svg>
    </div>

    <!-- Main Content -->
    <div class="relative z-10 bg-white rounded-md shadow-lg py-16 px-10 text-center max-w-7xl w-full space-y-16">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
        <!-- Left Column: Patients in Queue -->
        <div class="space-y-8 px-4 max-h-[80vh] overflow-y-scroll scrollbar-thin scrollbar-thumb-green-600 scrollbar-track-transparent">
          <h2 class="text-4xl font-extrabold text-green-600">Patients in Queue</h2>
          <input
            type="text"
            v-model="searchQuery"
            placeholder="Search for a patient..."
            class="w-full border border-green-500 rounded-md px-6 py-4 text-lg text-gray-800 focus:outline-none focus:ring-4 focus:ring-green-400 transition"
          />

          <!-- Patient Cards -->
          <div v-if="paginatedPatients.length" class="space-y-6">
            <div
              v-for="(patient, index) in paginatedPatients"
              :key="index"
              class="p-6 bg-white rounded-md shadow-xl hover:bg-green-100 transition cursor-pointer transform hover:scale-105"
              @click="selectPatient(patient)"
            >
              <div class="flex items-center justify-between">
                <div class="space-y-2">
                  <h3 class="text-xl font-semibold text-green-600">{{ patient.firstName }} {{ patient.lastName }}</h3>
                  <p class="text-sm text-gray-500">Age: {{ patient.age }}</p>
                  <p class="text-sm text-gray-500">Reason: {{ patient.visitType }}</p>
                </div>
                <button class="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                  View
                </button>
              </div>
            </div>
          </div>

          <p v-else class="text-center text-gray-500">No patients found in the queue.</p>

          <!-- Pagination Controls -->
          <div class="flex justify-center space-x-8 mt-8">
            <button
              @click="previousPage"
              :disabled="currentPage === 1"
              class="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 transition disabled:opacity-50"
            >
              Previous
            </button>
            <button
              @click="nextPage"
              :disabled="currentPage === totalPages"
              class="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 transition disabled:opacity-50"
            >
              Next
            </button>
          </div>
        </div>

        <!-- Right Column: Patient Details & Checkup -->
        <div class="bg-white rounded-md shadow-xl p-8 space-y-6 max-h-[80vh] overflow-y-scroll scrollbar-thin scrollbar-thumb-green-600 scrollbar-track-transparent">
          <h2 class="text-4xl font-extrabold text-green-600">Patient Details</h2>

          <div v-if="selectedPatient">
            <h3 class="text-2xl font-semibold text-green-600">{{ selectedPatient.firstName }} {{ selectedPatient.lastName }}</h3>
            <p class="text-lg text-gray-500">Age: {{ selectedPatient.age }}</p>
            <p class="text-lg text-gray-500">Gender: {{ selectedPatient.sex }}</p>
            <p class="text-lg text-gray-500">Checkup Type: <span class="font-bold text-green-600">{{ selectedPatient.checkupType }}</span></p>

            <div v-if="selectedPatient.medicalRecord" class="mt-6">
              <h4 class="font-semibold text-green-600">Medical Record</h4>
              <p class="text-lg text-gray-500">{{ selectedPatient.medicalRecord }}</p>
            </div>

            <!-- Checkup Buttons -->
            <div class="mt-8 space-x-6">
              <button
                v-if="selectedPatient.checkupType === 'General Checkup'"
                @click="startGeneralCheckup"
                class="bg-green-600 text-white py-4 px-8 rounded-md hover:bg-green-700 transition"
              >
                Start General Checkup
              </button>
              <button
                v-if="selectedPatient.checkupType === 'Prenatal Checkup' && selectedPatient.sex === 'Female'"
                @click="startPrenatalCheckup"
                class="bg-yellow-600 text-white py-4 px-8 rounded-md hover:bg-yellow-700 transition"
              >
                Start Prenatal Checkup
              </button>
            </div>
          </div>
          <p v-else class="text-center text-gray-500">No patient selected.</p>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
export default {
  props: {
    patientsInQueue: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      // Use the passed prop `patientsInQueue` in the parent, but here is the test data for local testing
    //   patientsInQueue: [
    //     {
    //       firstName: 'John',
    //       lastName: 'Doe',
    //       age: 34,
    //       sex: 'Male',
    //       reason: 'Fever',
    //       checkupType: 'General Checkup',
    //       medicalRecord: 'No chronic diseases.',
    //       queueTime: '2025-01-05 10:00:00',
    //     },
    //     {
    //       firstName: 'Jane',
    //       lastName: 'Smith',
    //       age: 28,
    //       sex: 'Female',
    //       reason: 'Pregnancy check-up',
    //       checkupType: 'Prenatal Checkup',
    //       medicalRecord: 'Healthy, first pregnancy.',
    //       queueTime: '2025-01-05 10:15:00',
    //     },
    //     {
    //       firstName: 'Alice',
    //       lastName: 'Johnson',
    //       age: 45,
    //       sex: 'Female',
    //       reason: 'Routine check-up',
    //       checkupType: 'General Checkup',
    //       medicalRecord: 'High blood pressure.',
    //       queueTime: '2025-01-05 10:30:00',
    //     },
    //     {
    //       firstName: 'Robert',
    //       lastName: 'Brown',
    //       age: 51,
    //       sex: 'Male',
    //       reason: 'Chest pain',
    //       checkupType: 'General Checkup',
    //       medicalRecord: 'History of heart disease.',
    //       queueTime: '2025-01-05 10:45:00',
    //     },
    //   ],
      searchQuery: '',
      selectedPatient: null,
      currentPage: 1,
      patientsPerPage: 3,
    };
  },
  computed: {
    filteredPatients() {
    console.log('Search Query:', this.searchQuery);
        const filtered = this.patientsInQueue.filter((patient) => {
            return (
            patient.firstName.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
            patient.lastName.toLowerCase().includes(this.searchQuery.toLowerCase())
            );
        });
        console.log('Filtered Patients:', filtered);
        return filtered;
    },
    totalPages() {
      return Math.ceil(this.filteredPatients.length / this.patientsPerPage);
    },
    paginatedPatients() {
      const start = (this.currentPage - 1) * this.patientsPerPage;
      const end = start + this.patientsPerPage;
      return this.filteredPatients.slice(start, end);
    },
  },
  methods: {
    selectPatient(patient) {
      console.log('Patient selected:', patient);
      this.selectedPatient = { ...patient }; // Copy patient details
    },
    startGeneralCheckup() {
      console.log('Starting General Checkup for', this.selectedPatient.firstName);
      // Call API or other logic here
    },
    startPrenatalCheckup() {
      console.log('Starting Prenatal Checkup for', this.selectedPatient.firstName);
      // Call API or other logic here
    },
    previousPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
  },
  mounted() {
    console.log('Received patientsInQueue:', this.patientsInQueue);
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
