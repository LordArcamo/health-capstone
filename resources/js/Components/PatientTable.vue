<template>
  <div class="container mx-auto py-8 px-4">
    <!-- Search and Filter Section -->
    <div class="mb-6 flex flex-col md:flex-row gap-4">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search by name, diagnosis, or visit type"
        class="border border-gray-300 p-3 rounded w-full md:w-2/3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
      />
      <div class="flex gap-4 w-full md:w-1/3">
        <select
          v-model="filterPrk"
          class="border border-gray-300 p-3 rounded w-1/2 shadow-sm focus:outline-none focus:ring-2 focus:ring-green-400"
        >
          <option value="">All Purok</option>
          <option v-for="purok in purokOptions" :key="purok" :value="purok">
            {{ purok }}
          </option>
        </select>
        <select
          v-model="filterBarangay"
          class="border border-gray-300 p-3 rounded w-1/2 shadow-sm focus:outline-none focus:ring-2 focus:ring-green-400"
        >
          <option value="">All Barangay</option>
          <option v-for="barangay in barangayOptions" :key="barangay" :value="barangay">
            {{ barangay }}
          </option>
        </select>
      </div>
    </div>

    <!-- Generate Report Button -->
    <div class="mb-6">
      <button
        @click="generateReport"
        class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-400 hover:text-black transition-colors"
      >
        Generate Report
      </button>
    </div>

    <!-- Responsive Table -->
    <div class="overflow-x-auto bg-gray-100 rounded-lg">
      <table class="min-w-full table-auto bg-white shadow-sm rounded-lg">
        <thead>
          <tr class="bg-gradient-to-r from-green-500 to-yellow-500 text-white uppercase text-sm font-bold">
            <th class="py-4 px-6 text-left border-b border-indigo-200">Patient ID</th>
            <th class="py-4 px-6 text-left border-b border-indigo-200">First Name</th>
            <th class="py-4 px-6 text-left border-b border-indigo-200">Last Name</th>
            <th class="py-4 px-6 text-left border-b border-indigo-200">Purok</th>
            <th class="py-4 px-6 text-left border-b border-indigo-200">Barangay</th>
            <th class="py-4 px-6 text-left border-b border-indigo-200">Age</th>
            <th class="py-4 px-6 text-left border-b border-indigo-200">Nature of Visit</th>
            <th class="py-4 px-6 text-left border-b border-indigo-200">Visit Type</th>
            <th class="py-4 px-6 text-left border-b border-indigo-200">Gender</th>
            <th class="py-4 px-6 text-left border-b border-indigo-200">Actions</th>
          </tr>
        </thead>
        <tbody class="text-gray-600 text-sm">
          <tr
            v-for="patient in filteredPatients"
            :key="patient.personalId"
            class="border-b border-gray-200 hover:bg-gray-50 transition-colors"
          >
          <td class="py-3 px-6">{{ patient.personalId }}</td>
          <td class="py-3 px-6">{{ patient.firstName }}</td>
          <td class="py-3 px-6">{{ patient.lastName }}</td>
          <td class="py-3 px-6">{{ patient.purok }}</td>
          <td class="py-3 px-6">{{ patient.barangay }}</td>
          <td class="py-3 px-6">{{ patient.age }}</td>
          <td class="py-3 px-6">{{ patient.natureOfVisit }}</td>
          <td class="py-3 px-6">{{ patient.visitType }}</td>
          <td class="py-3 px-6">{{ patient.sex }}</td>
          <td class="py-3 px-6">
            <button
                @click="openModal(patient)"
                class="bg-green-500 text-white px-3 py-1 rounded hover:bg-yellow-300 hover:text-black"
              >
                View More
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

    <!-- Modal -->
    <div
      v-if="showModal && selectedPatient"
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
        <ul class="flex gap-20">
          <div class="flex flex-col gap-1">
            <li>
              <strong>Full Name:</strong>
              {{ selectedPatient.firstName }} {{ selectedPatient.middleName }} {{ selectedPatient.lastName }}
            </li>
            <li><strong>Suffix:</strong> {{ selectedPatient.suffix }}</li>
            <li><strong>Address:</strong> {{ selectedPatient.purok }} {{ selectedPatient.barangay }}</li>
            <li><strong>Age:</strong> {{ selectedPatient.age }}</li>
            <li><strong>Birthday:</strong> {{ selectedPatient.birthdate }}</li>
            <li><strong>Contact:</strong> {{ selectedPatient.contact }}</li>
            <li><strong>Gender:</strong> {{ selectedPatient.sex }}</li>
            <li><strong>Time of Consultation:</strong> {{ selectedPatient.consultationTime }}</li>
            <li><strong>Date of Consultation:</strong> {{ selectedPatient.consultationDate }}</li>
            <li><strong>Mode of Transaction:</strong> {{ selectedPatient.modeOfTransaction }}</li>
            <li><strong>Blood Pressure:</strong> {{ selectedPatient.bloodPressure }}</li>
            <li><strong>Temperature:</strong> {{ selectedPatient.temperature }}</li>
          </div>

          <div class="flex flex-col gap-1">
            <li><strong>Height:</strong> {{ selectedPatient.height }}</li>
            <li><strong>Weight:</strong> {{ selectedPatient.weight }}</li>
            <li><strong>Name of Attending Provider:</strong> {{ selectedPatient.providerName }}</li>
            <li><strong>Nature of Visit:</strong> {{ selectedPatient.natureOfVisit }}</li>
            <li><strong>Type of Consultation/Purpose of Visit:</strong> {{ selectedPatient.visitType }}</li>
            <li><strong>Chief Complaints:</strong> {{ selectedPatient.chiefComplaints }}</li>
            <li><strong>Diagnosis:</strong> {{ selectedPatient.diagnosis }}</li>
            <li><strong>Medication/Treatment:</strong> {{ selectedPatient.medication }}</li>
          </div>
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
      default: () => [],
    },
  },
  data() {
    return {
      searchQuery: '',
      filterPrk: '',
      filterBarangay: '',
      currentPage: 1,
      itemsPerPage: 5, 
      showModal: false,
      selectedPatient: null,
    };
  },
  computed: {
    filteredPatients() {
      const query = this.searchQuery.toLowerCase();
      return this.patients
        .filter((patient) => {
          const matchesQuery =
            patient.firstName.toLowerCase().includes(query) ||
            patient.lastName.toLowerCase().includes(query) ||
            patient.natureOfVisit.toLowerCase().includes(query) ||
            patient.visitType.toLowerCase().includes(query);

          const matchesPrk = !this.filterPrk || patient.purok === this.filterPrk;
          const matchesBarangay = !this.filterBarangay || patient.barangay === this.filterBarangay;

          return matchesQuery && matchesPrk && matchesBarangay;
        })
        .slice((this.currentPage - 1) * this.itemsPerPage, this.currentPage * this.itemsPerPage);
    },
    totalPages() {
      return Math.ceil(this.patients.length / this.itemsPerPage);
    },
    purokOptions() {
      const puroks = new Set(this.patients.map((patient) => patient.purok));
      return Array.from(puroks);
    },
    barangayOptions() {
      const barangays = new Set(this.patients.map((patient) => patient.barangay));
      return Array.from(barangays);
    },
  },
  methods: {
    openModal(patient) {
    this.selectedPatient = patient;
    console.log("Selected Patient: ", patient); // Log patient data
    this.showModal = true;
  },
    closeModal() {
      this.showModal = false;
      this.selectedPatient = null; // Reset selectedPatient when closing the modal
    },

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
    
    generateReport() {
      const data = this.filteredPatients.map((patient) => ({
        firstName: patient.firstName,
        lastName: patient.lastName,
        purok: patient.purok,
        barangay: patient.barangay,
        age: patient.age,
        natureOfVisit: patient.natureOfVisit,
        visitType: patient.visitType,
        gender: patient.sex,
      }));

      const csvContent =
        'data:text/csv;charset=utf-8,' +
        ['First Name,Last Name,Purok,Barangay,Age,Nature of Visit,Visit Type,Gender']
          .concat(
            data.map((row) =>
              `${row.firstName},${row.lastName},${row.purok},${row.barangay},${row.age},${row.natureOfVisit},${row.visitType},${row.gender}`
            )
          )
          .join('\n');

      const encodedUri = encodeURI(csvContent);
      const link = document.createElement('a');
      link.setAttribute('href', encodedUri);
      link.setAttribute('download', 'patient_report.csv');
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    },
  },
};

</script>
