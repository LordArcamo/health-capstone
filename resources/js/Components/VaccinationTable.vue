<template>
  <div class="w-full min-h-screen p-4">
    <!-- Filter Section -->
    <div class="flex flex-col sm:flex-row gap-4 mb-6">
      <div class="flex flex-col flex-1">
        <label for="search" class="text-sm font-medium text-gray-700">Search by Name</label>
        <input
          v-model="searchQuery"
          @input="applyFilters"
          type="text"
          id="search"
          placeholder="Enter name..."
          class="border rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-green-600 text-sm"
        />
      </div>
      <div class="flex flex-col flex-1">
        <label for="barangay" class="text-sm font-medium text-gray-700">Barangay</label>
        <select
          v-model="filterBarangay"
          @change="applyFilters"
          id="barangay"
          class="border rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-green-600 text-sm"
        >
          <option value="">Select Barangay</option>
          <option v-for="barangay in barangayOptions" :key="barangay" :value="barangay">
            {{ barangay }}
          </option>
        </select>
      </div>
      <div class="flex flex-col flex-1">
        <label for="address" class="text-sm font-medium text-gray-700">Address</label>
        <input
          v-model="filterAddress"
          @input="applyFilters"
          type="text"
          id="address"
          placeholder="Enter Address..."
          class="border rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-green-600 text-sm"
        />
      </div>
    </div>

    <!-- Add Vaccination Button -->
    <div class="flex justify-end mb-6">
      <button
        @click="openVaccinationModal"
        class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition text-sm"
      >
        <font-awesome-icon :icon="['fas', 'plus-circle']" class="mr-2" /> Add Vaccination
      </button>
    </div>

    <!-- Patients Table -->
    <div class="mt-6">
      <table class="table-auto w-full border-collapse border border-gray-300 shadow-lg rounded-lg bg-white">
        <thead class="bg-green-600 text-white">
          <tr>
            <th class="border px-4 py-2">Name</th>
            <th class="border px-4 py-2">Age</th>
            <th class="border px-4 py-2">Vaccine Type</th>
            <th class="border px-4 py-2">Next Appointment</th>
            <th class="border px-4 py-2">Address</th>
            <th class="border px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="patient in paginatedPatients" :key="patient.id">
            <td class="border px-4 py-2">{{ patient.name }}</td>
            <td class="border px-4 py-2">{{ patient.age }}</td>
            <td class="border px-4 py-2">{{ patient.vaccineType }}</td>
            <td class="border px-4 py-2">{{ patient.nextAppointment || 'N/A' }}</td>
            <td class="border px-4 py-2">{{ patient.address || 'N/A' }}</td>
            <td class="border px-4 py-2">
              <!-- Actions Button -->
              <button @click="toggleDropdown(patient.id)" class="text-blue-500 hover:underline">
                <font-awesome-icon :icon="['fas', 'ellipsis-v']" class="text-gray-600 hover:text-gray-800" />
              </button>
              <div
                v-if="dropdownOpen === patient.id"
                ref="dropdowns"
                class="absolute bg-white border shadow-lg rounded-lg mt-2 w-64 z-10"
              >
                <ul class="list-none p-2">
                  <li>
                    <button @click="openScheduleModal(patient)" class="text-gray-700 hover:text-gray-900 block px-4 py-2 flex items-center">
                      <font-awesome-icon :icon="['fas', 'calendar-check']" class="mr-2" /> Schedule Appointment
                    </button>
                  </li>
                  <li>
                    <button @click="openHistoryModal(patient)" class="text-gray-700 hover:text-gray-900 block px-4 py-2 flex items-center">
                      <font-awesome-icon :icon="['fas', 'history']" class="mr-2" /> View History
                    </button>
                  </li>
                </ul>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modals -->
    <    <VaccinationModal
      v-if="showVaccinationModal"
      :patients="patients"
      :vaccineCategories="vaccineCategories"
      :filteredVaccineTypes="filteredVaccineTypes"
      @close="closeVaccinationModal"
      @save="handleSaveVaccination"
    />

    <ScheduleNextAppointmentModal
      v-if="showScheduleModal"
      :patient="activePatient"
      @close="closeAllModals"
    />
    <ViewHistoryModal
      v-if="showHistoryModal"
      :patient="activePatient"
      @close="closeAllModals"
    />
  </div>
</template>

<script>
import { ref, onMounted, onUnmounted } from 'vue';
import VaccinationModal from './VaccinationModals/VaccinationModal.vue';
import ScheduleNextAppointmentModal from './VaccinationModals/ScheduleNextAppointmentModal.vue';
import ViewHistoryModal from './VaccinationModals/ViewHistoryModal.vue';

export default {
  components: {
    VaccinationModal,
    ScheduleNextAppointmentModal,
    ViewHistoryModal,
  },
  data() {
    return {
      showVaccinationModal: false,
      showScheduleModal: false,
      showHistoryModal: false,
      activePatient: null, // Stores the patient data for the modals
      searchQuery: '',
      filterBarangay: '',
      filterAddress: '',
      patients: [
        {
          id: 1,
          name: 'John Doe',
          age: 25,
          vaccineType: 'Vaccine 1',
          nextAppointment: '2024-12-01',
          address: 'Barangay 1, Initao',
          barangay: 'Barangay 1',
        },
        {
          id: 2,
          name: 'Jane Smith',
          age: 30,
          vaccineType: 'Vaccine A',
          nextAppointment: '2024-12-10',
          address: 'Barangay 2, Initao',
          barangay: 'Barangay 2',
        },
      ],
      vaccineCategories: ['Pregnant', 'Senior Citizen'],
      filteredVaccineTypes: ['Vaccine 1', 'Vaccine 2'],
      paginatedPatients: [],
      dropdownOpen: null, // Store which patient's dropdown is open
    };
  },
  computed: {
    barangayOptions() {
      return [...new Set(this.patients.map((p) => p.barangay))];
    },
  },
  methods: {
    handleSaveVaccination(patientData) {
    // Perform save logic here, like adding a new patient
    console.log("Saving vaccination data:", patientData);
    this.showVaccinationModal = false; // Close modal after saving
  },
    openVaccinationModal() {
      this.showVaccinationModal = true;
    },
    closeVaccinationModal() {
      this.showVaccinationModal = false;
    },
    applyFilters() {
      this.paginatedPatients = this.patients.filter((patient) => {
        return (
          (!this.filterBarangay || patient.barangay === this.filterBarangay) &&
          (!this.filterAddress || patient.address.includes(this.filterAddress)) &&
          (!this.searchQuery || patient.name.toLowerCase().includes(this.searchQuery.toLowerCase()))
        );
      });
    },
    toggleDropdown(patientId) {
      // Close any open dropdown when clicking a new one
      if (this.dropdownOpen === patientId) {
        this.dropdownOpen = null; // Close the dropdown if clicking the same one
      } else {
        this.dropdownOpen = patientId; // Open the clicked dropdown
      }
    },
    openEditPatientModal(patient) {
      this.activePatient = patient;
      this.showEditPatientModal = true;
    },
    openScheduleModal(patient) {
      this.activePatient = patient;
      this.showScheduleModal = true;
    },
    openHistoryModal(patient) {
      this.activePatient = patient;
      this.showHistoryModal = true;
    },
    closeAllModals() {
      this.showEditPatientModal = false;
      this.showScheduleModal = false;
      this.showHistoryModal = false;
      this.activePatient = null;
    },
    handleClickOutside(event) {
      const dropdownElement = this.$refs.dropdown;
      if (dropdownElement && !dropdownElement.contains(event.target)) {
        this.dropdownOpen = null; // Close the dropdown if clicked outside
      }
    },
  },
  mounted() {
    this.paginatedPatients = this.patients;
    // Listen for clicks outside the dropdown to close it
    document.addEventListener('click', this.handleClickOutside);
  },
  beforeUnmount() {
    // Clean up the event listener when the component is destroyed
    document.removeEventListener('click', this.handleClickOutside);
  },
};
</script>

