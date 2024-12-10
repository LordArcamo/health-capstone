<template>
  <div class="mx-auto py-3 px-10">

    <!-- Search and Filter Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
      <!-- Search Bar -->
      <div class="w-full md:w-2/3">
        <input v-model="searchQuery" type="text" placeholder="Search by name, diagnosis, or visit type"
          class="border border-gray-300 p-3 rounded w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
      </div>

      <!-- Filter Panel Toggle -->
      <button @click="toggleFilterPanel"
        class="flex items-center px-4 py-3 bg-green-500 text-white font-medium rounded shadow hover:bg-green-600 focus:outline-none w-full md:w-1/3">
        <span>Filters</span>
        <svg class="w-4 h-4 ml-2 transform" :class="{ 'rotate-180': isFilterPanelOpen }"
          xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
    </div>

    <!-- Collapsible Filter Panel -->
    <div v-if="isFilterPanelOpen" class="mt-4 mb-4 border border-gray-300 rounded-lg p-6 shadow-md bg-white">
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Gender Filter -->
    <div>
      <label class="block text-base font-semibold mb-2">Gender</label>
      <div class="flex flex-col gap-3">
        <label class="flex items-center gap-2 cursor-pointer">
          <div class="w-5 h-5 border-2 border-green-500 rounded-full flex items-center justify-center">
            <input
              type="checkbox"
              value="Male"
              v-model="filterGender"
              class="appearance-none w-4 h-4"
            />
            <div
              v-if="filterGender.includes('Male')"
              class="w-2.5 h-2.5 bg-green-500 rounded-full"
            ></div>
          </div>
          <span class="text-gray-700">Male</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
          <div class="w-5 h-5 border-2 border-green-500 rounded-full flex items-center justify-center">
            <input
              type="checkbox"
              value="Female"
              v-model="filterGender"
              class="appearance-none w-4 h-4"
            />
            <div
              v-if="filterGender.includes('Female')"
              class="w-2.5 h-2.5 bg-green-500 rounded-full"
            ></div>
          </div>
          <span class="text-gray-700">Female</span>
        </label>
      </div>
    </div>

    <!-- Age Range Slider -->
    <div>
      <label class="block text-base font-semibold mb-2">Age Range</label>
      <div class="flex items-center gap-4">
        <span class="text-sm text-gray-500">0</span>
        <input
          type="range"
          v-model="filterAgeRange"
          min="0"
          max="100"
          step="5"
          class="w-full accent-green-500"
        />
        <span class="text-sm text-gray-500">100+</span>
      </div>
      <div class="text-sm text-gray-700 mt-1">Selected: {{ filterAgeRange }}+</div>
    </div>

    <!-- Purok Filter -->
    <div>
      <label class="block text-base font-semibold mb-2">Purok</label>
      <select
        v-model="filterPrk"
        class="border border-gray-300 p-3 rounded-lg w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-green-400"
      >
        <option value="">All Purok</option>
        <option v-for="purok in purokOptions" :key="purok" :value="purok">
          {{ purok }}
        </option>
      </select>
    </div>

    <!-- Barangay Filter -->
    <div>
      <label class="block text-base font-semibold mb-2">Barangay</label>
      <select
        v-model="filterBarangay"
        class="border border-gray-300 p-3 rounded-lg w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-green-400"
      >
        <option value="">All Barangay</option>
        <option v-for="barangay in barangayOptions" :key="barangay" :value="barangay">
          {{ barangay }}
        </option>
      </select>
    </div>

    <!-- Diagnosis Filter -->
    <div>
      <label class="block text-base font-semibold mb-2">Diagnosis</label>
      <div class="flex flex-wrap gap-3">
        <label
          v-for="diagnosis in diagnosisOptions"
          :key="diagnosis"
          class="flex items-center gap-2 cursor-pointer"
        >
          <div class="w-5 h-5 border-2 border-red-500 rounded flex items-center justify-center">
            <input
              type="checkbox"
              :value="diagnosis"
              v-model="filterDiagnosis"
              class="appearance-none w-4 h-4"
            />
            <div
              v-if="filterDiagnosis.includes(diagnosis)"
              class="w-3 h-3 bg-red-500 rounded"
            ></div>
          </div>
          <span class="text-gray-700">{{ diagnosis }}</span>
        </label>
      </div>
    </div>
  </div>
</div>


    <!-- Generate Report Button -->
    <div class="mb-6">
      <button @click="generateReport"
        class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-400 hover:text-black transition-colors">
        Generate Report
      </button>

      <h1 class="text-2xl font-bold text-start mt-6 mb-4">Individual Treatment Records</h1>
    </div>
    
<!-- Responsive Table -->
<div class="overflow-x-auto bg-gray-100 rounded-lg">
  <table class="min-w-full table-auto bg-white shadow-sm rounded-lg">
    <thead>
      <tr class="bg-gradient-to-r from-green-500 to-yellow-500 text-white uppercase text-sm font-bold">
        <th class="py-4 px-6 text-left border-b border-indigo-200">Full Name</th>
        <th class="py-4 px-6 text-left border-b border-indigo-200">Address</th>
        <th class="py-4 px-6 text-left border-b border-indigo-200">Age</th>
        <th class="py-4 px-6 text-left border-b border-indigo-200">Nature of Visit</th>
        <th class="py-4 px-6 text-left border-b border-indigo-200">Visit Type</th>
        <th class="py-4 px-6 text-left border-b border-indigo-200">Gender</th>
      </tr>
    </thead>
    <tbody class="text-gray-600 text-sm">
      <tr
        v-for="patient in paginatedPatients"
        :key="patient.personalId"
        class="border-b border-gray-200 hover:bg-gray-50 transition-colors cursor-pointer"
        @click="openModal(patient)"
      >
        <td class="py-3 px-6">{{ patient.fullName }}</td>
        <td class="py-3 px-6">{{ patient.address }}</td>
        <td class="py-3 px-6">{{ patient.age }}</td>
        <td class="py-3 px-6">{{ patient.natureOfVisit }}</td>
        <td class="py-3 px-6">{{ patient.visitType }}</td>
        <td class="py-3 px-6">{{ patient.sex }}</td>
      </tr>
    </tbody>
  </table>
</div>


<!-- Pagination -->
<div class="mt-6 flex justify-center space-x-4">
  <button @click="prevPage" :disabled="currentPage === 1"
    class="bg-red-500 text-white font-semibold py-2 px-4 rounded shadow hover:bg-red-600 disabled:opacity-50">
    Previous
  </button>
  <span class="text-gray-700">Page {{ currentPage }} of {{ totalPages }}</span>
  <button @click="nextPage" :disabled="currentPage === totalPages"
    class="bg-green-500 text-white font-semibold py-2 px-4 rounded shadow hover:bg-green-600 disabled:opacity-50">
    Next
  </button>
</div>

    <!-- Modal -->
    <div v-if="showModal && selectedPatient"
      class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-lg shadow-lg w-full max-w-lg sm:max-w-2xl p-6 relative">
        <button @click="closeModal"
          class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-700">
          &times;
        </button>

        <h2 class="text-xl sm:text-2xl font-bold mb-4">
          Details for {{ selectedPatient.fullName }}
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
      searchQuery: '', // Search query for filtering
      filterPrk: '', // Filter by purok
      filterBarangay: '', // Filter by barangay
      filterGender: [], // Array for selected genders
      filterAgeRange: '', // Filter by age range
      filterDiagnosis: [], // Array for selected diagnoses
      currentPage: 1, // Current page for pagination
      itemsPerPage: 8, // Number of items per page
      showModal: false, // Modal visibility flag
      selectedPatient: null, // Selected patient for modal display
      isFilterPanelOpen: false, // Toggle filter panel visibility
    };
  },
  computed: {
      // Paginated and filtered patients
  paginatedPatients() {
    const start = (this.currentPage - 1) * this.itemsPerPage;
    const end = start + this.itemsPerPage;
    return this.filteredPatients.slice(start, end);
  },

    filteredPatients() {
  const query = this.searchQuery.toLowerCase();
  return this.patients
    .map((patient) => ({
      ...patient,
      fullName: `${patient.firstName} ${patient.lastName}`, // Combine first and last name
      address: `${patient.purok}, ${patient.barangay}`, // Combine purok and barangay for address
    }))
    .filter((patient) => {
      // Match search query
      const matchesQuery =
        patient.fullName.toLowerCase().includes(query) ||
        patient.natureOfVisit.toLowerCase().includes(query) ||
        patient.visitType.toLowerCase().includes(query) ||
        patient.address.toLowerCase().includes(query) ||
        (patient.diagnosis && patient.diagnosis.toLowerCase().includes(query));

      // Match filters
      const matchesPrk = !this.filterPrk || patient.purok === this.filterPrk;
      const matchesBarangay =
        !this.filterBarangay || patient.barangay === this.filterBarangay;
      const matchesGender =
        this.filterGender.length === 0 || this.filterGender.includes(patient.sex);
      const matchesDiagnosis =
        this.filterDiagnosis.length === 0 || this.filterDiagnosis.includes(patient.diagnosis);

      // Match age range
      let matchesAgeRange = true;
      if (this.filterAgeRange) {
        const patientAge = parseInt(patient.age, 10);
        matchesAgeRange = patientAge >= parseInt(this.filterAgeRange, 10);
      }

      return (
        matchesQuery &&
        matchesPrk &&
        matchesBarangay &&
        matchesGender &&
        matchesDiagnosis &&
        matchesAgeRange
      );
    });
},

totalPages() {
    return Math.ceil(this.filteredPatients.length / this.itemsPerPage);
  },
    // Unique purok options
    purokOptions() {
      return Array.from(new Set(this.patients.map((p) => p.purok)));
    },
    // Unique barangay options
    barangayOptions() {
      return Array.from(new Set(this.patients.map((p) => p.barangay)));
    },
    // Unique diagnosis options
    diagnosisOptions() {
      return Array.from(new Set(this.patients.map((p) => p.diagnosis)));
    },
  },
  methods: {
    // Toggle filter panel visibility
    toggleFilterPanel() {
      this.isFilterPanelOpen = !this.isFilterPanelOpen;
    },
    // Open the modal with selected patient details
    openModal(patient) {
      this.selectedPatient = patient;
      this.showModal = true;
    },
    // Close the modal
    closeModal() {
      this.showModal = false;
      this.selectedPatient = null;
    },
    // Go to the next page
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
    // Go to the previous page
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    // Generate a CSV report of filtered patients
    generateReport() {
      const data = this.filteredPatients.map((patient) => ({
        fullName: patient.fullName,
        address: patient.address,
        age: patient.age,
        natureOfVisit: patient.natureOfVisit,
        visitType: patient.visitType,
        gender: patient.sex,
        diagnosis: patient.diagnosis || 'N/A', // Include diagnosis if available
      }));

      const csvContent =
        'data:text/csv;charset=utf-8,' +
        ['Full Name,Address,Age,Nature of Visit,Visit Type,Gender,Diagnosis']
          .concat(
            data.map((row) =>
              `${row.fullName},${row.address},${row.age},${row.natureOfVisit},${row.visitType},${row.gender},${row.diagnosis}`
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
