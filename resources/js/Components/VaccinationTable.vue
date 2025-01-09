<script>
import { ref, onMounted, onUnmounted, watch } from "vue";
import VaccinationModal from "./VaccinationModals/VaccinationModal.vue";
import ScheduleNextAppointmentModal from "./VaccinationModals/ScheduleNextAppointmentModal.vue";
import ViewHistoryModal from "./VaccinationModals/ViewHistoryModal.vue";
import axios from 'axios';

export default {
  props: {
    patients: {
      type: Array,
      required: true,
      default: () => [],
    },
    vaccinatedPatients: {
      type: Array,
      default: () => [],
    },
  },
  watch: {
    vaccinatedPatients: {
      handler(newVal) {
        this.updatePagination();
      },
      deep: true
    },
    filteredPatients() {
      this.currentPage = 1; // Reset to the first page
      this.updatePagination();
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
    processedVaccinatedPatients() {
      return this.vaccinatedPatients.map(patient => {
        // Group history by vaccine type and get latest entry for each
        const latestByVaccineType = {};
        if (patient.history && patient.history.length > 0) {
          patient.history.forEach(record => {
            const currentLatest = latestByVaccineType[record.vaccineType];
            if (!currentLatest || new Date(record.dateOfVisit) > new Date(currentLatest.dateOfVisit)) {
              latestByVaccineType[record.vaccineType] = record;
            }
          });
        }
        // Create a new patient object with only the latest records
        return {
          ...patient,
          history: Object.values(latestByVaccineType)
        };
      });
    },
    totalPages() {
      const total = Math.ceil(this.processedVaccinatedPatients.length / this.itemsPerPage);
      return total;
    },
  },
  methods: {
    formatDate(date) {
      if (!date) return "N/A";
      const options = { year: "numeric", month: "long", day: "numeric" };
      return new Date(date).toLocaleDateString(undefined, options);
    },
    updatePagination() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = this.currentPage * this.itemsPerPage;
      this.paginatedPatients = this.processedVaccinatedPatients.slice(start, end);
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
    applyFilters() {
      this.currentPage = 1; // Reset to the first page after filtering
      this.updatePagination();
    },
    toggleDropdown(key) {
      this.dropdownOpen = this.dropdownOpen === key ? null : key;
    },
    openHistoryModal(patient, vaccineType) {
      this.activePatient = patient;
      // Get all history records for this vaccine type
      const allHistory = this.vaccinatedPatients
        .find(p => p.personalId === patient.personalId)?.history
        .filter(record => record.vaccineType === vaccineType)
        .sort((a, b) => new Date(b.dateOfVisit) - new Date(a.dateOfVisit)) || [];
      this.activePatientHistory = allHistory;
      this.showHistoryModal = true;
    },
    openScheduleModal(patient) {
      this.activePatient = patient;
      this.showScheduleModal = true;

      // Fetch existing appointments for this vaccination
      axios.get(`/api/appointments/vaccination/${patient.vaccinationId}`)
        .then(response => {
          const { appointments } = response.data;
          // Update the activePatient with appointments
          this.activePatient = {
            ...this.activePatient,
            appointments: appointments
          };
        })
        .catch(error => {
          console.error('Error fetching appointments:', error);
        });
    },
    clearFilters() {
      this.searchQuery = "";
      this.filterBarangay = "";
      this.filterAddress = "";
      this.applyFilters();
    },
    generateReport() {
      if (!this.vaccinatedPatients || this.vaccinatedPatients.length === 0) {
        alert("No patient data available to generate the report.");
        return;
      }

      const headers = ["Name", "Age", "Vaccine Type", "Next Appointment", "Address"];
      const rows = this.vaccinatedPatients.map((patient) => [
        patient.firstName,
        patient.age,
        patient.vaccineType,
        this.formatDate(patient.nextAppointment),
        patient.barangay || "N/A",
      ]);

      const csvContent = [headers.join(","), ...rows.map((row) => row.join(","))].join("\n");
      const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });

      const link = document.createElement("a");
      link.href = URL.createObjectURL(blob);
      link.setAttribute("download", "patients_report.csv");

      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);

      alert("Report generated and downloaded successfully!");
    },
    scheduleAppointment() {
      alert("Appointment scheduled successfully!");
      this.closeAllModals();
    },
    openVaccinationModal() {
      this.modalKey++; // Increment key to force re-render if needed
      this.showVaccinationModal = true; // Show the modal
    },
    closeVaccinationModal() {
      this.showVaccinationModal = false; // Hide the modal
    },
    closeAllModals() {
      this.showScheduleModal = false;
      this.showHistoryModal = false;
      setTimeout(() => {
        this.activePatient = null; // Delay resetting to ensure proper unmount
        this.activePatientHistory = [];
      }, 300); // Add a slight delay to prevent reactive issues
    }
  },
  mounted() {
    this.updatePagination(); // Initialize pagination
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
          <template v-for="patient in paginatedPatients" :key="patient.personalId">
            <tr v-for="record in patient.history" :key="record.id" class="hover:bg-gray-100">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ patient.firstName }} {{ patient.middleName }} {{ patient.lastName }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ patient.age }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ record.vaccineType }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ formatDate(record.nextAppointment) }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ patient.barangay || 'N/A' }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ patient.purok || 'N/A' }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                <button @click="openScheduleModal({
                    ...patient,
                    vaccineType: record.vaccineType,
                    vaccinationId: record.id
                  })"
                  class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                  Schedule
                </button>
                <button @click="openHistoryModal(patient, record.vaccineType)"
                  class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-gray-400 ml-2">
                  History
                </button>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>

    <!-- Modals -->
    <VaccinationModal
      v-if="showVaccinationModal"
      :patients="patients"
      :key="modalKey"
      @close="closeVaccinationModal"
    />
    <ScheduleNextAppointmentModal
      v-if="showScheduleModal"
      :patient="activePatient"
      :vaccinationId="activePatient?.vaccinationId"
      @close="closeAllModals"
      @schedule="scheduleAppointment"
    />
    <ViewHistoryModal
      v-if="showHistoryModal"
      :patient="activePatient"
      :history="activePatientHistory"
      @close="closeAllModals"
    />

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
