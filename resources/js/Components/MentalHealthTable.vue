<template>
  <div class="container py-8 px-4">
    <!-- Sessions Dropdown -->
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-lg font-semibold text-green-700">Patient Records</h2>
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
            <th class="py-4 px-6 text-left">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="patient in filteredPatients"
            :key="patient.personalId"
            class="border-b hover:bg-gray-50 transition-colors"
          >
            <td class="py-4 px-6">{{ patient.personalId }}</td>
            <td class="py-4 px-6">{{ patient.firstName }} {{ patient.lastName }}</td>
            <td class="py-4 px-6">{{ patient.age }}</td>
            <td class="py-4 px-6">{{ patient.barangay }}</td>
            <td class="py-4 px-6">{{ patient.visitType }}</td>
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
  </div>
</template>

<script>
export default {
  data() {
    return {
      searchQuery: "",
      filterPrk: "",
      filterBarangay: "",
      currentPage: 1,
      itemsPerPage: 5,
      megaFilterOpen: false,
      selectedSessionId: "",
      sessions: [
        { id: 1, title: "Session 1", date: "2024-11-20" },
        { id: 2, title: "Session 2", date: "2024-11-21" },
      ],
      patients: [
        { personalId: 101, firstName: "John", lastName: "Doe", age: 25, barangay: "Barangay 1", purok: "Purok 1", sessionId: 1, visitType: "Consultation", diagnosis: "Flu" },
        { personalId: 102, firstName: "Jane", lastName: "Smith", age: 32, barangay: "Barangay 2", purok: "Purok 2", sessionId: 1, visitType: "Follow-up", diagnosis: "Hypertension" },
        { personalId: 103, firstName: "Alice", lastName: "Johnson", age: 45, barangay: "Barangay 3", purok: "Purok 3", sessionId: 2, visitType: "Consultation", diagnosis: "Diabetes" },
      ],
    };
  },
  computed: {
    filteredPatients() {
      const query = this.searchQuery.toLowerCase();
      const patientsFilteredBySession = this.selectedSessionId
        ? this.patients.filter((p) => p.sessionId === this.selectedSessionId)
        : this.patients;

      return patientsFilteredBySession
        .filter((patient) => {
          const matchesQuery =
            patient.firstName.toLowerCase().includes(query) ||
            patient.lastName.toLowerCase().includes(query) ||
            patient.visitType.toLowerCase().includes(query);
          const matchesPrk = !this.filterPrk || patient.purok === this.filterPrk;
          const matchesBarangay = !this.filterBarangay || patient.barangay === this.filterBarangay;

          return matchesQuery && matchesPrk && matchesBarangay;
        })
        .slice((this.currentPage - 1) * this.itemsPerPage, this.currentPage * this.itemsPerPage);
    },
    totalPages() {
      const patientsFilteredBySession = this.selectedSessionId
        ? this.patients.filter((p) => p.sessionId === this.selectedSessionId)
        : this.patients;
      return Math.ceil(patientsFilteredBySession.length / this.itemsPerPage);
    },
    purokOptions() {
      return [...new Set(this.patients.map((patient) => patient.purok))];
    },
    barangayOptions() {
      return [...new Set(this.patients.map((patient) => patient.barangay))];
    },
  },
  methods: {
    toggleMegaFilter() {
      this.megaFilterOpen = !this.megaFilterOpen;
    },
    resetFilters() {
      this.filterPrk = "";
      this.filterBarangay = "";
    },
    openModal(patient) {
      this.selectedPatient = patient;
      this.showModal = true;
    },
    prevPage() {
      if (this.currentPage > 1) this.currentPage--;
    },
    nextPage() {
      if (this.currentPage < this.totalPages) this.currentPage++;
    },
  },
};
</script>
  