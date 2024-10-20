<template>
  <div class="container mx-auto py-8 px-4">
    <h2 class="text-2xl sm:text-3xl font-bold mb-6 text-center">National Immunization Records</h2>

    <!-- Check if personalInformation prop is empty -->
    <p v-if="!patients || patients.length === 0" class="text-center text-gray-500">
      No patient records available.
    </p>

    <!-- Search input -->
    <div v-else class="mb-6">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search by name, age, address, or diagnosis"
        class="border border-gray-300 p-3 rounded w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
      />
    </div>

    <!-- Responsive Table Wrapper with Background and Padding -->
    <div class="overflow-x-auto bg-gray-100 p-6 rounded-lg shadow-lg">
      <table class="min-w-full table-auto bg-white shadow-sm rounded-lg">
        <thead>
          <tr class="bg-gradient-to-r from-green-500 via-green-500 to-yellow-500 text-white uppercase text-sm font-bold">
            <th class="py-4 px-6 text-left tracking-wider border-b border-indigo-200">First Name</th>
            <th class="py-4 px-6 text-left tracking-wider border-b border-indigo-200">Last Name</th>
            <th class="py-4 px-6 text-left tracking-wider border-b border-indigo-200">Middle Name</th>
            <th class="py-4 px-6 text-left tracking-wider border-b border-indigo-200">Suffix</th>
            <th class="py-4 px-6 text-left tracking-wider border-b border-indigo-200">Address</th>
            <th class="py-4 px-6 text-left tracking-wider border-b border-indigo-200">Age</th>
            <th class="py-4 px-6 text-left tracking-wider border-b border-indigo-200">Birthday</th>
            <th class="py-4 px-6 text-left tracking-wider border-b border-indigo-200">Contact #</th>
            <th class="py-4 px-6 text-left tracking-wider border-b border-indigo-200">Gender</th>
            <th class="py-4 px-6 text-left border-b border-indigo-200"></th>
          </tr>
        </thead>

        <tbody class="text-gray-600 text-sm">
          <tr
            v-for="patient in filteredPatients"
            :key="patient.id"
            class="border-b border-gray-200 hover:bg-gray-50 transition-colors"
          >
            <td class="py-3 px-6">{{ patient.firstName }}</td>
            <td class="py-3 px-6">{{ patient.lastName }}</td>
            <td class="py-3 px-6">{{ patient.middleName }}</td>
            <td class="py-3 px-6">{{ patient.suffix }}</td>
            <td class="py-3 px-6">{{ patient.address }}</td>
            <td class="py-3 px-6">{{ patient.age }}</td>
            <td class="py-3 px-6">{{ patient.birthdate }}</td>
            <td class="py-3 px-6">{{ patient.contact }}</td>
            <td class="py-3 px-6">{{ patient.sex }}</td>
            <td class="py-3 px-6">
              <button
                @click="openModal(patient)"
                class="bg-blue-500 text-white py-2 px-3 rounded hover:bg-blue-700 transition-colors"
              >
                View More
              </button>
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
      class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50 p-4"
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
        <ul class="space-y-2">
          <li>
            <strong>Full Name:</strong>
            {{ selectedPatient.firstName }} {{ selectedPatient.middleName }} {{ selectedPatient.lastName }}
          </li>
          <li><strong>Suffix:</strong> {{ selectedPatient.suffix }}</li>
          <li><strong>Address:</strong> {{ selectedPatient.address }}</li>
          <li><strong>Age:</strong> {{ selectedPatient.age }}</li>
          <li><strong>Birthday:</strong> {{ selectedPatient.birthdate }}</li>
          <li><strong>Contact:</strong> {{ selectedPatient.contact }}</li>
          <li><strong>Gender:</strong> {{ selectedPatient.sex }}</li>
          <li><strong>Birth Place:</strong> {{ selectedPatient.birthplace }}</li>
          <li><strong>Blood Type:</strong> {{ selectedPatient.bloodtype }}</li>
          <li><strong>Mother's Name:</strong> {{ selectedPatient.mothername }}</li>
          <li><strong>DSWD NHTS:</strong> {{ selectedPatient.dswdNhts }}</li>
          <li><strong>Facility Household No.:</strong> {{ selectedPatient.facilityHouseholdno }}</li>
          <li><strong>Household No.:</strong> {{ selectedPatient.houseHoldno }}</li>
          <li><strong>4Ps Member?:</strong> {{ selectedPatient.fourpsmember }}</li>
          <li><strong>Primary Care Benefit (PCB) Member?:</strong> {{ selectedPatient.PCBMember }}</li>
          <li><strong>Philhealth Member:</strong> {{ selectedPatient.philhealthMember }}</li>
          <li><strong>Status Type:</strong> {{ selectedPatient.statusType }}</li>
          <li><strong>Philhealth No.:</strong> {{ selectedPatient.philhealthNo }}</li>
          <li><strong>If Member, please indicate category:</strong> {{ selectedPatient.ifMember }}</li>
          <li><strong>Family Member:</strong> {{ selectedPatient.familyMember }}</li>
          <li><strong>TT Status of Mother:</strong> {{ selectedPatient.ttstatus }}</li>
          <li><strong>Date Assesed:</strong> {{ selectedPatient.dateAssesed }}</li>
          <li><strong>Date:</strong> {{ selectedPatient.date }}</li>
          <li><strong>Place:</strong> {{ selectedPatient.place }}</li>
          <li><strong>Guardiant:</strong> {{ selectedPatient.guardian }}</li>
        </ul>
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
      const filteredLength = this.patients.filter((patient) => {
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
