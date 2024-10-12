<template>
  <div class="container mx-auto py-8">
    <h2 class="text-2xl sm:text-3xl font-bold mb-4">Patient Records</h2>

    <!-- Check if patients prop is empty -->
    <p v-if="!patients || patients.length === 0">No patient records available.</p>

    <!-- Search input -->
    <div v-else class="mb-4">
      <input v-model="searchQuery" type="text" placeholder="Search by name, age, address, or diagnosis"
        class="border border-gray-300 p-2 rounded w-full" />
    </div>

    <!-- Responsive Table Wrapper -->
    <div class="overflow-x-auto">
      <table class="min-w-full table-auto bg-white shadow-md rounded-lg">
        <thead>
          <tr class="bg-gray-200 text-gray-700 uppercase text-sm leading-normal">
            <th class="py-3 px-6 text-left">First Name</th>
            <th class="py-3 px-6 text-left">Last Name</th>
            <th class="py-3 px-6 text-left">Middle Name</th>
            <th class="py-3 px-6 text-left">Age</th>
            <th class="py-3 px-6 text-left">Address</th>
            <th class="py-3 px-6 text-left">Contact #</th>
            <th class="py-3 px-6 text-left">Date of Consultation</th>
            <th class="py-3 px-6 text-left">Temperature</th>
            <th class="py-3 px-6 text-left">Height</th>
            <th class="py-3 px-6 text-left">Weight</th>
            <th class="py-3 px-6 text-left">Diagnosis</th>
            <th class="py-3 px-6 text-left"></th>
          </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
          <tr v-for="patient in filteredPatients" :key="patient.id" class="border-b border-gray-200 hover:bg-gray-100">

            <td class="py-3 px-6 text-left whitespace-nowrap">{{ patient.lastName }}</td>
            <td class="py-3 px-6 text-left">{{ patient.firstName }}</td>
            <td class="py-3 px-6 text-left">{{ patient.middleName }}</td>
            <td class="py-3 px-6 text-left">{{ patient.age }}</td>
            <td class="py-3 px-6 text-left">{{ patient.address }}</td>
            <td class="py-3 px-6 text-left">{{ patient.contact }}</td>
            <td class="py-3 px-6 text-left">{{ patient.consultationDate }}</td>
            <td class="py-3 px-6 text-left">{{ patient.temperature }}</td>
            <td class="py-3 px-6 text-left">{{ patient.height }}</td>
            <td class="py-3 px-6 text-left">{{ patient.weight }}</td>
            <td class="py-3 px-6 text-left">{{ patient.diagnosis }}</td>
            <td class="py-3 px-6 text-left">
              <button @click="openModal(patient)" class="bg-blue-500 text-white py-2 px-3 rounded hover:bg-blue-700">
                View More
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination controls -->
    <div class="mt-4 flex justify-center">
      <button @click="prevPage" :disabled="currentPage === 1" class="bg-gradient-to-r from-[#FF6B6B] to-[#FF8E53] text-white font-semibold py-2 px-4 rounded shadow hover:from-red-700 hover:to-orange-500 transition-colors duration-300">
        Previous
      </button>
      <span class="mx-2">Page {{ currentPage }} of {{ totalPages }}</span>
      <button @click="nextPage" :disabled="currentPage === totalPages" class="bg-gradient-to-r from-[#0F8F46] to-[#FED035] text-white font-semibold py-2 px-4 rounded shadow hover:from-green-700 hover:to-yellow-500 transition-colors duration-300">
        Next
      </button>
    </div>

    <!-- Modal -->
    <div v-if="isModalOpen" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-lg shadow-lg w-full max-w-lg sm:max-w-2xl p-6 relative">
        <button @click="closeModal"
          class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-700">
          &times;
        </button>

        <h2 class="text-xl sm:text-2xl font-bold mb-4">Details for {{ selectedPatient.firstName }} {{ selectedPatient.lastName }}</h2>
        <ul>
          <li><strong>Full Name:</strong> {{ selectedPatient.firstName }} {{ selectedPatient.middleName }} {{ selectedPatient.lastName }}</li>
          <li><strong>Age:</strong> {{ selectedPatient.age }}</li>
          <li><strong>Address:</strong> {{ selectedPatient.address }}</li>
          <li><strong>Contact:</strong> {{ selectedPatient.contact }}</li>
          <li><strong>Date of Consultation:</strong> {{ selectedPatient.consultationDate }}</li>
          <li><strong>Temperature:</strong> {{ selectedPatient.temperature }}</li>
          <li><strong>Height:</strong> {{ selectedPatient.height }}</li>
          <li><strong>Weight:</strong> {{ selectedPatient.weight }}</li>
          <li><strong>Diagnosis:</strong> {{ selectedPatient.diagnosis }}</li>
        </ul>

        <div class="mt-4">
          <button @click="closeModal" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-700">
            Close
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    patients: {
      type: Array,
      default: () => [] // Default to empty array to prevent errors
    }
  },
  data() {
    return {
      searchQuery: '',
      currentPage: 1,
      itemsPerPage: 5,
      isModalOpen: false,
      selectedPatient: {},
    };
  },
  computed: {
    filteredPatients() {
      const query = this.searchQuery.toLowerCase();
      return this.patients
        .filter((patient) => {
          return (
            patient.firstName.toLowerCase().includes(query) ||
            patient.lastName.toLowerCase().includes(query) ||
            patient.middleName.toLowerCase().includes(query) ||
            patient.age.toString().includes(query) ||
            patient.address.toLowerCase().includes(query) ||
            patient.diagnosis.toLowerCase().includes(query)
          );
        })
        .slice((this.currentPage - 1) * this.itemsPerPage, this.currentPage * this.itemsPerPage);
    },
    totalPages() {
      const filteredLength = this.patients.filter((patient) => {
        const query = this.searchQuery.toLowerCase();
        return (
          patient.firstName.toLowerCase().includes(query) ||
          patient.lastName.toLowerCase().includes(query) ||
          patient.middleName.toLowerCase().includes(query) ||
          patient.age.toString().includes(query) ||
          patient.address.toLowerCase().includes(query) ||
          patient.diagnosis.toLowerCase().includes(query)
        );
      }).length;
      return Math.ceil(filteredLength / this.itemsPerPage);
    },
  },
  methods: {
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
        this.currentPage += 1;
      }
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage -= 1;
      }
    },
  }
}
</script>


<style scoped>
/* Add any necessary styling here */
</style>
