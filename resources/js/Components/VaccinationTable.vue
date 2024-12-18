<script>
import { ref, onMounted, onUnmounted } from "vue";
import VaccinationModal from "./VaccinationModals/VaccinationModal.vue";
import ScheduleNextAppointmentModal from "./VaccinationModals/ScheduleNextAppointmentModal.vue";
import ViewHistoryModal from "./VaccinationModals/ViewHistoryModal.vue";

export default {
  props: {
    patients: {
      type: Array,
      default: () => [],
    },
  },
  components: {
    VaccinationModal,
    ScheduleNextAppointmentModal,
    ViewHistoryModal,
  },
  data() {
    return {
      showVaccinationModal: false,
      modalKey: 0,
      showScheduleModal: false,
      showHistoryModal: false,
      activePatient: null,
      activePatientHistory: [],
      searchQuery: "",
      filterBarangay: "",
      filterAddress: "",
      paginatedPatients: [],
      currentPage: 1,
      itemsPerPage: 5, // Number of items per page
      dropdownOpen: null,
    };
  },
  computed: {
    barangayOptions() {
      return [...new Set(this.patients.map((p) => p.barangay))];
    },
    filteredPatients() {
      return this.patients.filter((patient) => {
        return (
          (!this.filterBarangay || patient.barangay === this.filterBarangay) &&
          (!this.filterAddress || patient.address.toLowerCase().includes(this.filterAddress.toLowerCase())) &&
          (!this.searchQuery || patient.name.toLowerCase().includes(this.searchQuery.toLowerCase()))
        );
      });
    },
    totalPages() {
      return Math.ceil(this.filteredPatients.length / this.itemsPerPage);
    },
  },
  methods: {
    applyFilters() {
      this.currentPage = 1; // Reset to the first page after filtering
      this.updatePagination();
    },
    updatePagination() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = this.currentPage * this.itemsPerPage;
      this.paginatedPatients = this.filteredPatients.slice(start, end);
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
        this.updatePagination();
      }
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
        this.updatePagination();
      }
    },
    openVaccinationModal() {
      this.modalKey += 1;
      this.showVaccinationModal = true;
    },
    closeVaccinationModal() {
      this.showVaccinationModal = false;
      this.$refs.vaccinationModal?.resetState?.();
    },
    openHistoryModal(patient) {
      this.activePatient = patient;
      this.activePatientHistory = patient.history || [];
      this.showHistoryModal = true;
    },
    openScheduleModal(patient) {
      this.activePatient = patient;
      this.showScheduleModal = true;
    },
    closeAllModals() {
      this.showVaccinationModal = false;
      this.showScheduleModal = false;
      this.showHistoryModal = false;
      this.activePatient = null;
      this.activePatientHistory = [];
    },
    clearFilters() {
      this.searchQuery = "";
      this.filterBarangay = "";
      this.filterAddress = "";
      this.applyFilters();
    },
  },
  mounted() {
    this.updatePagination(); // Initialize pagination on mount
  },
};
</script>




<template>
  <div class="w-full min-h-screen px-11 py-5 bg-gradient-to-br from-green-100 to-blue-100 p-4">
     <!-- Header Section -->
     <div class="mb-6">
      <h1 class="text-3xl font-bold text-green-600 text-center">Vaccination Records</h1>
      <p class="text-gray-700 text-center">Search, filter, and manage vaccination records efficiently.</p>
    </div>

    <!-- Top Action Bar -->
    <div class="flex flex-wrap justify-between items-center gap-4 mb-4">
      <!-- Search Bar -->
      <div class="flex-1">
        <input v-model="searchQuery" @input="applyFilters" type="text" placeholder="Search by Name..."
          class="border rounded-md px-4 py-2 shadow-sm w-full sm:w-1/2 lg:w-1/3 focus:outline-none focus:ring-2 focus:ring-green-600" />
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-wrap items-center gap-4">
        <button @click="openVaccinationModal"
          class="bg-green-600 text-white px-6 py-2 rounded shadow hover:bg-green-700 transition text-sm">
          <font-awesome-icon :icon="['fas', 'plus-circle']" class="mr-2" /> Add Vaccination
        </button>
        <button @click="generateReport"
          class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition text-sm">
          <font-awesome-icon :icon="['fas', 'file-download']" class="mr-2" /> Generate Report
        </button>
      </div>
    </div>

    <!-- Mega Filter Dropdown -->
    <div class="relative mb-6" @click.stop>
      <button @click="toggleDropdown('megaFilter')" :aria-expanded="dropdownOpen === 'megaFilter'"
        aria-controls="megaFilterDropdown"
        class="flex justify-between items-center bg-gray-100 text-gray-700 px-4 py-2 rounded-md shadow-md w-full sm:w-auto hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500 text-sm">
        Filter Options
        <font-awesome-icon :icon="['fas', dropdownOpen === 'megaFilter' ? 'caret-up' : 'caret-down']" />
      </button>

      <transition name="fade">
        <div v-if="dropdownOpen === 'megaFilter'" id="megaFilterDropdown"
          class="absolute z-20 bg-white border rounded-md mt-2 w-full max-w-md sm:w-2/3 lg:w-1/3 shadow-lg p-4"
          data-dropdown="megaFilter" @click.stop>
          <h3 class="text-lg font-semibold text-gray-700 mb-4">Filter Options</h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Filter by Barangay -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Barangay</label>
              <select v-model="filterBarangay" @change="applyFilters"
                class="border rounded-md px-4 py-2 w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-green-600 text-sm">
                <option value="">Select Barangay</option>
                <option v-for="barangay in barangayOptions" :key="barangay" :value="barangay">{{ barangay }}</option>
              </select>
            </div>

            <!-- Filter by Address -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
              <input v-model="filterAddress" @input="applyFilters" type="text" placeholder="Enter Address..."
                class="border rounded-md px-4 py-2 w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-green-600 text-sm" />
            </div>

            <!-- Clear Filters Button -->
            <div class="flex items-end">
              <button @click="clearFilters"
                class="bg-red-600 text-white px-4 py-2 rounded-md shadow-md w-full sm:w-auto hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 text-sm">
                Clear Filters
              </button>
            </div>
          </div>
        </div>
      </transition>
    </div>



    <!-- Patients Table -->
    <div class="overflow-hidden shadow-md rounded-lg">
      <table class="min-w-full divide-y divide-gray-200 bg-white">
        <thead>
          <tr class="bg-gradient-to-r from-green-500 to-yellow-500 text-white uppercase text-s font-bold">
            <th class="px-6 py-3 text-left font-medium text-white uppercase tracking-wider">Name</th>
            <th class="px-6 py-3 text-left font-medium text-white uppercase tracking-wider">Age</th>
            <th class="px-6 py-3 text-left  font-medium text-white uppercase tracking-wider">Vaccine Type</th>
            <th class="px-6 py-3 text-left font-medium text-white uppercase tracking-wider">Next Appointment</th>
            <th class="px-6 py-3 text-left  font-medium text-white uppercase tracking-wider">Barangay</th>
            <th class="px-6 py-3 text-left  font-medium text-white uppercase tracking-wider">Purok</th>
            <th class="px-6 py-3 text-left font-medium text-white uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="patient in paginatedPatients" :key="patient.id" class="hover:bg-gray-100">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ patient.firstName }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ patient.age }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ patient.vaccineType }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ patient.nextAppointment || 'N/A' }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ patient.barangay || 'N/A' }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ patient.purok || 'N/A' }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
              <button @click="openScheduleModal(patient)"
                class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                Schedule
              </button>
              <button @click="openHistoryModal(patient)"
                class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-gray-400 ml-2">
                History
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modals -->
    <VaccinationModal v-if="showVaccinationModal" :key="modalKey" @close="closeVaccinationModal" />
    <ScheduleNextAppointmentModal v-if="showScheduleModal" :patient="activePatient" @close="closeAllModals"
      @schedule="scheduleAppointment" />
    <ViewHistoryModal v-if="showHistoryModal" :patient="activePatient" :history="activePatientHistory"
      @close="closeAllModals" />

          <!-- Pagination -->
    <div class="flex justify-center gap-5 items-center mt-6">
      <button
        @click="prevPage"
        :disabled="currentPage === 1"
        class="bg-red-500 text-white px-4 py-2 rounded-lg shadow hover:bg-red-600 disabled:opacity-50 disabled:cursor-not-allowed"
      >
        Previous
      </button>
      <span class="text-sm text-gray-700">Page {{ currentPage }} of {{ totalPages }}</span>
      <button
        @click="nextPage"
        :disabled="currentPage === totalPages"
        class="bg-green-500 text-white px-4 py-2 rounded-lg shadow hover:bg-green-600 disabled:opacity-50 disabled:cursor-not-allowed"
      >
        Next
      </button>
    </div>

  </div>
</template>
