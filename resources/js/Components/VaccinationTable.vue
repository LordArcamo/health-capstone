<script>
import { ref, onMounted, onUnmounted } from "vue";
import VaccinationModal from "./VaccinationModals/VaccinationModal.vue";
import ScheduleNextAppointmentModal from "./VaccinationModals/ScheduleNextAppointmentModal.vue";
import ViewHistoryModal from "./VaccinationModals/ViewHistoryModal.vue";

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
  
  components: {
    VaccinationModal,
    ScheduleNextAppointmentModal,
    ViewHistoryModal,
  },
  data() {
    return {
      showVaccinationModal: false, // State to show/hide the modal
      modalKey: 0, // Key to force re-render of the modal
      showScheduleModal: false,
      showHistoryModal: false,
      activePatient: null,
      activePatientHistory: [],
      searchQuery: "",
      filterBarangay: "",
      filterAddress: "",
      // patients: [
      //   {
      //     id: 1,
      //     name: "John Doe",
      //     age: 25,
      //     vaccineType: "Vaccine A",
      //     nextAppointment: "2024-12-01",
      //     address: "Barangay 1, Initao",
      //     barangay: "Barangay 1",
      //     history: [
      //       {
      //         id: 1,
      //         dateOfVisit: "2024-01-15",
      //         ageInYears: 25,
      //         weight: "70 kg",
      //         height: "170 cm",
      //         temperature: "37°C",
      //         antigenGiven: "Measles Vaccine",
      //         injectedBy: "Dr. Smith",
      //         nextAppointment: "2024-02-15",
      //       },
      //     ],
      //   },
      //   {
      //     id: 2,
      //     name: "Jane Smith",
      //     age: 30,
      //     vaccineType: "Vaccine B",
      //     nextAppointment: "2024-12-10",
      //     address: "Barangay 2, Initao",
      //     barangay: "Barangay 2",
      //     history: [
      //       {
      //         id: 1,
      //         dateOfVisit: "2024-01-10",
      //         ageInYears: 30,
      //         weight: "65 kg",
      //         height: "165 cm",
      //         temperature: "36.5°C",
      //         antigenGiven: "Polio Vaccine",
      //         injectedBy: "Dr. Adam",
      //         nextAppointment: "2024-02-10",
      //       },
      //     ],
      //   },
      // ],
      paginatedPatients: [],
      dropdownOpen: null,
    };
  },
  computed: {
    barangayOptions() {
      return [...new Set(this.vaccinatedPatients.map((p) => p.barangay))];
    },
  },
  methods: {
    // Function to format date
    formatDate(dateString) {
      if (!dateString) return "N/A";
      return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'numeric',
        day: 'numeric'
      });
    },
    scheduleAppointment({ patientId, date }) {
      const patient = this.vaccinatedPatients.find((p) => p.id === patientId);
      if (!patient) {
        console.error(`Patient with ID ${patientId} not found.`);
        return;
      }
      if (new Date(date) <= new Date()) {
        alert("Appointment date must be in the future.");
        return;
      }
      patient.nextAppointment = date;
      alert(`Next appointment for ${patient.firstName} scheduled on ${date}.`);
    },
    openVaccinationModal() {
      // Reset the key to reinitialize the child component
      this.modalKey += 1;
      this.showVaccinationModal = true;
    },
    closeVaccinationModal() {
      this.showVaccinationModal = false;
      this.$refs.vaccinationModal?.resetState?.();
    },
    applyFilters() {
      const searchQuery = this.searchQuery.trim().toLowerCase();
      const filterBarangay = this.filterBarangay.trim().toLowerCase();
      const filterAddress = this.filterAddress.trim().toLowerCase();

      this.paginatedPatients = this.vaccinatedPatients.filter((patient) => {
        const fullName = `${patient.firstName || ""} ${patient.lastName || ""}`.trim().toLowerCase();
        const barangay = (patient.barangay || "").trim().toLowerCase();
        const address = (patient.address || "").trim().toLowerCase();

        return (
          (!filterBarangay || barangay.includes(filterBarangay)) &&
          (!filterAddress || address.includes(filterAddress)) &&
          (!searchQuery || fullName.includes(searchQuery))
        );
      });
    },
    toggleDropdown(key) {
      this.dropdownOpen = this.dropdownOpen === key ? null : key;
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
    handleClickOutside(event) {
      const dropdown = document.querySelector(`[data-dropdown="${this.dropdownOpen}"]`);
      if (dropdown && !dropdown.contains(event.target)) {
        this.dropdownOpen = null;
      }
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
  },
  mounted() {
    this.paginatedPatients = [...this.vaccinatedPatients];
    this.applyFilters();
    document.addEventListener("click", this.handleClickOutside);
  },
  beforeUnmount() {
    document.removeEventListener("click", this.handleClickOutside);
  },
};
</script>



<template>
  <div class="w-full min-h-screen p-4">
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
          <tr v-for="patient in vaccinatedPatients" :key="patient.id" class="hover:bg-gray-100">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ patient.firstName }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ patient.age }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ patient.vaccineType }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ formatDate(patient.nextAppointment) }}</td>
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
    <VaccinationModal
      v-if="showVaccinationModal"
      :patients="patients"
      :key="modalKey"
      @close="closeVaccinationModal"
    />
    <ScheduleNextAppointmentModal v-if="showScheduleModal" :patient="activePatient" @close="closeAllModals"
      @schedule="scheduleAppointment" />
    <ViewHistoryModal v-if="showHistoryModal" :patient="activePatient" :history="activePatientHistory"
      @close="closeAllModals" />
  </div>
</template>
