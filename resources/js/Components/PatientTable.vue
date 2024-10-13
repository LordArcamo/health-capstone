<template>
  <div class="container mx-auto py-8">
    <h2 class="text-2xl sm:text-3xl font-bold mb-4">Patient Records</h2>

    <!-- Check if personalInformation prop is empty -->
    <p v-if="!personalInformation || personalInformation.length === 0">No patient records available.</p>

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
            <th class="py-3 px-6 text-left">Suffix</th>
            <th class="py-3 px-6 text-left">Address</th>
            <th class="py-3 px-6 text-left">Age</th>
            <th class="py-3 px-6 text-left">Birthday</th>
            <th class="py-3 px-6 text-left">Contact #</th>
            <th class="py-3 px-6 text-left">Gender</th>
            <th class="py-3 px-6 text-left"></th>
          </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
          <tr v-for="patient in filteredPatients" :key="patient.id" class="border-b border-gray-200 hover:bg-gray-100">
            <td class="py-3 px-6 text-left whitespace-nowrap">{{ patient.firstName }}</td>
            <td class="py-3 px-6 text-left">{{ patient.lastName }}</td>
            <td class="py-3 px-6 text-left">{{ patient.middleName }}</td>
            <td class="py-3 px-6 text-left">{{ patient.suffix }}</td>
            <td class="py-3 px-6 text-left">{{ patient.address }}</td>
            <td class="py-3 px-6 text-left">{{ patient.age }}</td>
            <td class="py-3 px-6 text-left">{{ patient.birthdate }}</td>
            <td class="py-3 px-6 text-left">{{ patient.contact }}</td>
            <td class="py-3 px-6 text-left">{{ patient.sex }}</td>
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
      <button @click="prevPage" :disabled="currentPage === 1"
        class="bg-gray-300 text-gray-700 px-4 py-2 mx-1 rounded hover:bg-gray-400">
        Previous
      </button>
      <span class="mx-2">Page {{ currentPage }} of {{ totalPages }}</span>
      <button @click="nextPage" :disabled="currentPage === totalPages"
        class="bg-gray-300 text-gray-700 px-4 py-2 mx-1 rounded hover:bg-gray-400">
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
          <li><strong>Suffix:</strong> {{ selectedPatient.suffix }}</li>
          <li><strong>Address:</strong> {{ selectedPatient.address }}</li>
          <li><strong>Age:</strong> {{ selectedPatient.age }}</li>
          <li><strong>Birthday:</strong> {{ selectedPatient.birthdate }}</li>
          <li><strong>Contact:</strong> {{ selectedPatient.contact }}</li>
          <li><strong>Gender:</strong> {{ selectedPatient.sex }}</li>
          <li><strong>Time of Consultation:</strong> {{ selectedPatient.consultationTime }}</li>
          <li><strong>Date of Consultation:</strong> {{ selectedPatient.consultationDate }}</li>
          <li><strong>Mode of Transaction:</strong> {{ selectedPatient.modeOfTransaction }}</li>
          <li><strong>Blood Pressure:</strong> {{ selectedPatient.bloodPressure }}</li>
          <li><strong>Temperature:</strong> {{ selectedPatient.temperature }}</li>
          <li><strong>Height:</strong> {{ selectedPatient.height }}</li>
          <li><strong>Weight:</strong> {{ selectedPatient.weight }}</li>
          <li><strong>Name of Attending Provider:</strong> {{ selectedPatient.providerName }}</li>
          <li><strong>Nature of Visit:</strong> {{ selectedPatient.natureOfVisit }}</li>
          <li><strong>Type of Consultation/Purpose of Visit:</strong> {{ selectedPatient.visitType }}</li>
          <li><strong>Chief Complaints:</strong> {{ selectedPatient.chiefComplaints }}</li>
          <li><strong>Diagnosis:</strong> {{ selectedPatient.diagnosis }}</li>
          <li><strong>Medication/Treatment:</strong> {{ selectedPatient.medication }}</li>
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
    personalInformation: {
      type: Array,
      default: () => [] // Default to an empty array to prevent errors
    },
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
      return this.personalInformation
        .filter((patient) => {
          return (
            patient.firstName.toLowerCase().includes(query) ||
            patient.lastName.toLowerCase().includes(query) ||
            patient.middleName.toLowerCase().includes(query) ||
            patient.suffix.toString().includes(query) ||
            patient.address.toLowerCase().includes(query) ||
            patient.age.toString().includes(query) ||
            patient.birthdate.toLowerCase().includes(query) ||
            patient.contact.toLowerCase().includes(query) ||
            patient.height.toLowerCase().includes(query) ||
            patient.sex.toLowerCase().includes(query)
            

          );
        })
        .slice((this.currentPage - 1) * this.itemsPerPage, this.currentPage * this.itemsPerPage);
    },
    totalPages() {
      const filteredLength = this.personalInformation.filter((patient) => {
        const query = this.searchQuery.toLowerCase();
        return (
          patient.firstName.toLowerCase().includes(query) ||
          patient.lastName.toLowerCase().includes(query) ||
          patient.middleName.toLowerCase().includes(query) ||
          patient.suffix.toString().includes(query) ||
          patient.address.toLowerCase().includes(query) ||
          patient.age.toString().includes(query) ||
          patient.birthdate.toLowerCase().includes(query) ||
          patient.contact.toLowerCase().includes(query) ||
          patient.sex.toLowerCase().includes(query)
        );
      }).length;
      return Math.ceil(filteredLength / this.itemsPerPage);
    },
  },
  methods: {
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
.container {
  padding: 0 1rem;
}

.table-auto {
  width: 100%;
}

@media (min-width: 640px) {
  .table-auto {
    display: table;
  }
}

.modal-content {
  max-width: 100%;
  width: 100%;
}
</style>
