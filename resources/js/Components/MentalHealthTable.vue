<template>
  <div class="container py-8 px-4">
    <!-- Sessions Dropdown -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-green-700">Patient Records</h1>
      <select
        v-model="selectedSessionId"
        class="border border-gray-300 py-2 px-4 rounded shadow-sm focus:outline-none focus:ring-green-500"
      >
        <option value="">All Sessions</option>
        <option v-for="session in sessions" :key="session.id" :value="session.id">
          {{ session.title }} ({{ session.date }})
        </option>
      </select>
    </div>

    <!-- Search and Filter Section -->
    <div class="flex flex-col md:flex-row items-start gap-4 mb-6">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search by name, diagnosis, or visit type"
        class="border border-gray-300 py-2 px-4 rounded w-full md:w-1/2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
      />
      <div class="relative">
        <button
          @click="toggleMegaFilter"
          class="bg-gray-100 text-gray-700 py-2 px-4 rounded-md shadow-md hover:bg-gray-200 transition focus:ring-2 focus:ring-green-500"
        >
          Filter Options
        </button>
        <transition name="fade">
          <div
            v-if="megaFilterOpen"
            class="absolute mt-2 bg-white border border-gray-300 rounded-lg shadow-lg w-80 p-6 z-50"
          >
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Filter Options</h3>
            <div class="space-y-6">
              <!-- Filter by Purok -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Filter by Purok</label>
                <select
                  v-model="filterPrk"
                  class="border border-gray-300 py-2 px-4 rounded shadow-sm focus:outline-none focus:ring-green-500 w-full"
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
                  class="border border-gray-300 py-2 px-4 rounded shadow-sm focus:outline-none focus:ring-green-500 w-full"
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
                class="bg-red-500 text-white py-2 px-4 rounded shadow-md hover:bg-red-600"
              >
                Clear Filters
              </button>
            </div>
          </div>
        </transition>
      </div>
    </div>

    <!-- Patient Table -->
    <div class="overflow-x-auto bg-gray-100 rounded-lg shadow-md">
      <table class="min-w-full bg-white">
        <thead>
          <tr class="bg-green-700 text-white text-sm font-bold">
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
                class="bg-green-500 text-white py-1 px-3 rounded hover:bg-yellow-300 hover:text-black transition"
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
              class="bg-red-500 text-white py-2 px-4 rounded shadow hover:bg-red-600"
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