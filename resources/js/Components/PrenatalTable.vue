<template>
  <div class="container mx-auto py-8 px-4">
   
    <!-- Search and Filter Section -->
    <div class="mb-6 flex flex-col md:flex-row gap-4">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search by name, diagnosis, or visit type"
        class="border border-gray-300 p-3 rounded w-full md:w-2/3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
      />
      <div class="flex gap-4 w-full md:w-1/3">
        <select
          v-model="filterPrk"
          class="border border-gray-300 p-3 rounded w-1/2 shadow-sm focus:outline-none focus:ring-2 focus:ring-green-400"
        >
          <option value="">All Purok</option>
          <option v-for="purok in purokOptions" :key="purok" :value="purok">
            {{ purok }}
          </option>
        </select>
        <select
          v-model="filterBarangay"
          class="border border-gray-300 p-3 rounded w-1/2 shadow-sm focus:outline-none focus:ring-2 focus:ring-green-400"
        >
          <option value="">All Barangay</option>
          <option v-for="barangay in barangayOptions" :key="barangay" :value="barangay">
            {{ barangay }}
          </option>
        </select>
      </div>
    </div>

    <!-- Generate Report Button -->
    <div class="mb-6">
      <button
        @click="generateReport"
        class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-400 hover:text-black transition-colors"
      >
        Generate Report
      </button>
    </div>

    <!-- Responsive Table -->
    <div class="overflow-x-auto bg-gray-100 rounded-lg">
      <table class="min-w-full table-auto bg-white shadow-sm rounded-lg">
        <thead>
          <tr class="bg-gradient-to-r from-green-500 to-yellow-500 text-white uppercase text-sm font-bold">
            <th class="py-4 px-6 text-left border-b border-indigo-200">First Name</th>
            <th class="py-4 px-6 text-left border-b border-indigo-200">Last Name</th>
            <th class="py-4 px-6 text-left border-b border-indigo-200">Purok</th>
            <th class="py-4 px-6 text-left border-b border-indigo-200">Barangay</th>
            <th class="py-4 px-6 text-left border-b border-indigo-200">Age</th>
            <th class="py-4 px-6 text-left border-b border-indigo-200">Birthday</th>
            <th class="py-4 px-6 text-left border-b border-indigo-200">Emergency Contact Number</th>
            <th class="py-4 px-6 text-left border-b border-indigo-200">Actions</th>
          </tr>
        </thead>

        <tbody class="text-gray-600 text-sm">
          <tr
            v-for="patient in filteredPatients"
            :key="patient.personalId"
            class="border-b border-gray-200 hover:bg-gray-50 transition-colors"
          >
            <td class="py-3 px-6">{{ patient.firstName }}</td>
            <td class="py-3 px-6">{{ patient.lastName }}</td>
            <td class="py-3 px-6">{{ patient.purok }}</td>
            <td class="py-3 px-6">{{ patient.barangay }}</td>
            <td class="py-3 px-6">{{ patient.age }}</td>
            <td class="py-3 px-6">{{ patient.birthdate }}</td>
            <td class="py-3 px-6">{{ patient.emergencyContact }}</td>
            <td class="py-3 px-6">
              <div class="flex gap-1">
                <button
                @click="openModal(patient)"
                class="bg-green-500 text-white px-3 py-1 rounded hover:bg-yellow-300 hover:text-black"
              >
                View More
              </button>
              <button @click="openTrimesterModal" class="bg-blue-500 text-white px-3 py-1  rounded-md hover:bg-blue-600">
                Trimester
              </button>
              
              <!-- Trimester Modal Component -->
              <TrimesterModal :show="showTrimesterModal" @close="closeTrimesterModal" @confirm="confirmTrimesterSelection" />
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    
    <!-- Pagination controls -->
    <div class="mt-6 flex justify-center items-center space-x-4">
      <button
        @click="prevPage"
        :disabled="currentPage === 1"
        class="bg-red-500 text-white font-semibold py-2 px-4 rounded shadow hover:bg-red-600 disabled:opacity-50"
      >
        Previous
      </button>
      <span class="text-gray-700">Page {{ currentPage }} of {{ totalPages }}</span>
      <button
        @click="nextPage"
        :disabled="currentPage === totalPages"
        class="bg-green-500 text-white font-semibold py-2 px-4 rounded shadow hover:bg-green-600 disabled:opacity-50"
      >
        Next
      </button>
    </div>

    <!-- Modal -->
    <div
      v-if="isModalOpen"
      class="fixed overflow-y-auto inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50 p-4"
    >
      <div class="bg-white rounded-lg shadow-lg w-full max-w-lg sm:max-w-2xl p-6 relative">
        <button
          @click="closeModal"
          class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-700"
        >
          &times;
        </button>

        <h2 class="text-xl sm:text-2xl font-bold mb-4">
          Details for {{ selectedPatient.firstName }} {{ selectedPatient.lastName }}
        </h2>
        <ul class="space-y-2 flex gap-10">
          <div>
            <li>
            <strong>Full Name:</strong>
            {{ selectedPatient.firstName }} {{ selectedPatient.middleName }} {{ selectedPatient.lastName }}
          </li>
            <li><strong>Address:</strong> {{ selectedPatient.address }}</li>
          <li><strong>Age:</strong> {{ selectedPatient.age }}</li>
          <li><strong>Birthday:</strong> {{ selectedPatient.birthdate }}</li>
          <li><strong>Mode of Transaction:</strong> {{ selectedPatient.modeOfTransaction }}</li>
          <li><strong>Date of Consultation:</strong> {{ selectedPatient.consultationDate }}</li>
          <li><strong>Time of Consultation:</strong> {{ selectedPatient.consultationTime }}</li>
          <li><strong>Blood Pressure:</strong> {{ selectedPatient.bloodPressure }}</li>
          <li><strong>Temperature:</strong> {{ selectedPatient.temperature }}</li>
          <li><strong>Height:</strong> {{ selectedPatient.height }}</li>
          <li><strong>Weight:</strong> {{ selectedPatient.weight }}</li>
          <li><strong>Name of Attending Provider:</strong> {{ selectedPatient.providerName }}</li>
          <li><strong>Name of Spouse:</strong> {{ selectedPatient.nameOfSpouse }}</li>
          <li><strong>Emergency Contact Number:</strong> {{ selectedPatient.emergencyContact }}</li>
          <li><strong>4ps?::</strong> {{ selectedPatient.fourMember }}</li>
          <li><strong>Philhealth Status:</strong> {{ selectedPatient.philhealthStatus }}</li>
          <li><strong>Philhealth ID Number:</strong> {{ selectedPatient.philhealthId }}</li>
          <li><strong>Menarche:</strong> {{ selectedPatient.menarche }}</li>
          <li><strong>Onset of Sexual Intercourse:</strong> {{ selectedPatient.sexualOnset }}</li>
          <li><strong>Period/Duration:</strong> {{ selectedPatient.periodDuration }}</li>
          <li><strong>Birth Control Method:</strong> {{ selectedPatient.birthControl }}</li>
          </div>
          <div>
            <li><strong>Interval/Cycle:</strong> {{ selectedPatient.intervalCycle }}</li>
          <li><strong>Menopause? (Yes/No):</strong> {{ selectedPatient.menopause }}</li>
          <li><strong>LMP (Last Menstrual Period):</strong> {{ selectedPatient.lmp }}</li>
          <li><strong>EDC (Estimated Date of Confinement):</strong> {{ selectedPatient.edc }}</li>
          <li><strong>Gravidity:</strong> {{ selectedPatient.gravidity }}</li>
          <li><strong>Parity:</strong> {{ selectedPatient.parity }}</li>
          <li><strong>Term:</strong> {{ selectedPatient.term }}</li>
          <li><strong>Preterm:</strong> {{ selectedPatient.preterm }}</li>
          <li><strong>Abortion:</strong> {{ selectedPatient.abortion }}</li>
          <li><strong>Living:</strong> {{ selectedPatient.living }}</li>
          <li><strong>Syphilis Test Result:</strong> {{ selectedPatient.syphilisResult }}</li>
          <li><strong>Penicillin Given:</strong> {{ selectedPatient.penicillin }}</li>
          <li><strong>Hemoglobin (gm/100ml):</strong> {{ selectedPatient.hemoglobin }}</li>
          <li><strong>Hematocrit (vol %):</strong> {{ selectedPatient.hematocrit }}</li>
          <li><strong>Urinalysis:</strong> {{ selectedPatient.urinalysis }}</li>
          <li><strong>TT Status:</strong> {{ selectedPatient.ttStatus }}</li>
          <li><strong>TD (Date Given):</strong> {{ selectedPatient.tdDate }}</li>
          </div>
        </ul>
      </div>
    </div>
  </div>
</template>


<script>
import TrimesterModal from "@/Components/TrimesterModal.vue";

export default {
  components: {
    TrimesterModal,
  },
  props: {
    personalInfo: {
      type: Array,
      default: () => [],
    },
    patients: {
      type: Array,
      default: () => [] // Default to empty array to prevent errors
    }
  },
  data() {
    return {
      showTrimesterModal: false,
      searchQuery: '',
      filterPrk: '',
      filterBarangay: '',
      currentPage: 1,
      itemsPerPage: 5,
      isModalOpen: false,
      selectedPatient: {},
    };
  },
  computed: {
    filteredPatients() {
      const query = this.searchQuery.toLowerCase();
      return this.personalInfo
        .filter((patient) => {
          const matchesQuery =
            patient.firstName.toLowerCase().includes(query) ||
            patient.lastName.toLowerCase().includes(query) ||
            patient.natureOfVisit.toLowerCase().includes(query) ||
            patient.visitType.toLowerCase().includes(query);

          const matchesPrk = !this.filterPrk || patient.purok === this.filterPrk;
          const matchesBarangay = !this.filterBarangay || patient.barangay === this.filterBarangay;

          return matchesQuery && matchesPrk && matchesBarangay;
        })
        .slice((this.currentPage - 1) * this.itemsPerPage, this.currentPage * this.itemsPerPage);
    },
    totalPages() {
      return Math.ceil(this.personalInfo.length / this.itemsPerPage);
    },
    purokOptions() {
      const puroks = new Set(this.personalInfo.map((patient) => patient.purok));
      return Array.from(puroks);
    },
    barangayOptions() {
      const barangays = new Set(this.personalInfo.map((patient) => patient.barangay));
      return Array.from(barangays);
    },
  },
  methods: {
    openTrimesterModal() {
      console.log("Opening Trimester Modal");
      this.showTrimesterModal = true;
    },
    closeTrimesterModal() {
      console.log("Closing Trimester Modal");
      this.showTrimesterModal = false;
    },
    confirmTrimesterSelection() {
      console.log("Confirming Trimester Selection");
      this.closeTrimesterModal();
    },
    openModal(patient) {
      this.selectedPatient = patient;
      this.isModalOpen = true;
    },
    closeModal() {
      this.isModalOpen = false;
      this.selectedPatient = {};
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
  },
};
</script>

