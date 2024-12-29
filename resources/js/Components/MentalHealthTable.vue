<template>
  <div class="container">
  <!-- Header Section -->
  <div class="bg-gradient-to-r from-blue-500 to-green-500 text-white py-8 px-6 shadow-md">
      <div class="text-left container mx-auto">
        <h1 class="text-4xl font-bold">Patient Records</h1>
        <p class="text-lg">Search, filter, and manage patient records efficiently.</p>
      </div>
    </div>

    <div class="container mx-auto py-8 px-6">

      <!-- Search and Filter Section -->
      <div class="flex flex-col md:flex-row items-start gap-4 mb-6">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search by name, diagnosis, or visit type"
          class="border border-gray-300 py-3 px-4 rounded-lg w-full md:w-1/2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
        <button
          @click="toggleMegaFilter"
          class="bg-blue-500 text-white py-3 px-6 rounded-lg shadow-md hover:bg-blue-600 transition focus:ring-2 focus:ring-blue-500"
        >
          Filter Options
        </button>
      </div>

      <!-- Collapsible Filter Panel -->
      <transition name="fade">
        <div
          v-if="megaFilterOpen"
          class="mb-6 bg-white border border-gray-300 rounded-lg p-6 shadow-lg"
        >
          <h3 class="text-lg font-semibold text-gray-700 mb-4">Filter Options</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Filter by Purok -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Filter by Purok</label>
              <select
                v-model="filterPrk"
                class="border border-gray-300 py-2 px-4 rounded-lg w-full shadow-sm focus:outline-none focus:ring-blue-500"
              >
                <option value="">All Purok</option>
                <option v-for="purok in purokOptions" :key="purok" :value="purok">
                  {{ purok }}
                </option>
              </select>
            </div>

            <!-- Filter by Barangay -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Filter by Barangay</label>
              <select
                v-model="filterBarangay"
                class="border border-gray-300 py-2 px-4 rounded-lg w-full shadow-sm focus:outline-none focus:ring-blue-500"
              >
                <option value="">All Barangay</option>
                <option v-for="barangay in barangayOptions" :key="barangay" :value="barangay">
                  {{ barangay }}
                </option>
              </select>
            </div>
          </div>
          <div class="flex justify-end mt-6">
            <button
              @click="resetFilters"
              class="bg-red-500 text-white py-3 px-6 rounded-lg shadow-md hover:bg-red-600 transition"
            >
              Clear Filters
            </button>
          </div>
        </div>
      </transition>

      <!-- Patient Table -->
      <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full bg-white">
          <thead>
            <tr class="bg-gradient-to-r from-green-500 to-blue-500 text-white uppercase text-sm font-bold">
              <th class="py-4 px-6 text-left">Patient ID</th>
              <th class="py-4 px-6 text-left">Name</th>
              <th class="py-4 px-6 text-left">Age</th>
              <th class="py-4 px-6 text-left">Barangay</th>
              <th class="py-4 px-6 text-left">Visit Type</th>
              <th class="py-4 px-6 text-left">Visit Date</th>
              <th class="py-4 px-6 text-left">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="patient in paginatedPatients"
              :key="patient.personalId"
              class="border-b hover:bg-gray-50 transition-colors"
            >
              <td class="py-4 px-6">{{ patient.personalId }}</td>
              <td class="py-4 px-6">{{ patient.firstName }} {{ patient.lastName }}</td>
              <td class="py-4 px-6">{{ patient.age }}</td>
              <td class="py-4 px-6">{{ patient.barangay }}</td>
              <td class="py-4 px-6">{{ patient.visitType }}</td>
              <td class="py-4 px-6">{{ formatDate(patient.visitDate) }}</td>
              <td class="py-4 px-6">
                <button
                  @click="openModal(patient)"
                  class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition"
                >
                  View
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="mt-6 flex justify-center space-x-4">
        <button
          @click="prevPage"
          :disabled="currentPage === 1"
          class="bg-red-500 text-white py-3 px-6 rounded-lg shadow-md hover:bg-red-600 disabled:opacity-50"
        >
          Previous
        </button>
        <span class="text-gray-700">Page {{ currentPage }} of {{ totalPages }}</span>
        <button
          @click="nextPage"
          :disabled="currentPage === totalPages"
          class="bg-green-500 text-white py-3 px-6 rounded-lg shadow-md hover:bg-green-600 disabled:opacity-50"
        >
          Next
        </button>
      </div>
    </div>

    <!-- Modal -->
    <transition name="fade">
      <div
        v-if="showModal"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
      >
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
          <h3 class="text-lg font-semibold mb-4">Patient Details</h3>
          <p><strong>Patient ID:</strong> {{ selectedPatient.personalId }}</p>
          <p><strong>Name:</strong> {{ selectedPatient.firstName }} {{ selectedPatient.lastName }}</p>
          <p><strong>Age:</strong> {{ selectedPatient.age }}</p>
          <p><strong>Barangay:</strong> {{ selectedPatient.barangay }}</p>
          <p><strong>Purok:</strong> {{ selectedPatient.purok }}</p>
          <p><strong>Visit Type:</strong> {{ selectedPatient.visitType }}</p>
          <p><strong>Visit Date:</strong> {{ formatDate(selectedPatient.visitDate) }}</p>
          <p><strong>Diagnosis:</strong> {{ selectedPatient.diagnosis }}</p>
          <div class="flex justify-end mt-4">
            <button
              @click="closeModal"
              class="bg-red-500 text-white py-2 px-4 rounded shadow-md hover:bg-red-600"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  props: {
    patients: {
      type: Array,
      required: true,
      default: () => []
    },
  },
  data() {
    return {
      searchQuery: "",
      filterPrk: "",
      filterBarangay: "",
      currentPage: 1,
      itemsPerPage: 10,
      megaFilterOpen: false,
      showModal: false,
      selectedPatient: null,
      selectedSessionId: "",
      sessions: []
    };
  },
  computed: {
    filteredPatients() {
      if (!this.patients) return [];
      
      const query = this.searchQuery.toLowerCase();
      
      return this.patients.filter((patient) => {
        const matchesSearch = 
          patient.firstName.toLowerCase().includes(query) ||
          patient.lastName.toLowerCase().includes(query) ||
          patient.diagnosis?.toLowerCase().includes(query) ||
          patient.visitType.toLowerCase().includes(query);
          
        const matchesPurok = !this.filterPrk || patient.purok === this.filterPrk;
        const matchesBarangay = !this.filterBarangay || patient.barangay === this.filterBarangay;
        
        return matchesSearch && matchesPurok && matchesBarangay;
      });
    },
    paginatedPatients() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.filteredPatients.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.filteredPatients.length / this.itemsPerPage);
    },
    purokOptions() {
      return [...new Set(this.patients.map(p => p.purok))].filter(Boolean).sort();
    },
    barangayOptions() {
      return [...new Set(this.patients.map(p => p.barangay))].filter(Boolean).sort();
    }
  },
  methods: {
    formatDate(date) {
      return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'numeric',
        day: 'numeric'
      });
    },
    openModal(patient) {
      this.selectedPatient = patient;
      this.showModal = true;
    },
    closeModal() {
      this.showModal = false;
      this.selectedPatient = null;
    },
    toggleMegaFilter() {
      this.megaFilterOpen = !this.megaFilterOpen;
    },
    resetFilters() {
      this.filterPrk = "";
      this.filterBarangay = "";
      this.searchQuery = "";
      this.megaFilterOpen = false;
    },
    prevPage() {
      if (this.currentPage > 1) this.currentPage--;
    },
    nextPage() {
      if (this.currentPage < this.totalPages) this.currentPage++;
    }
  }
};
</script>