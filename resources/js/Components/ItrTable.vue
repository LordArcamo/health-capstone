<template>
  <div class="mx-auto py-8 px-10  bg-gradient-to-br from-green-100 to-blue-100 min-h-screen">
    <!-- Header Section -->
    <div class="mb-6 flex flex-col text-center">
      <h1 class="text-4xl font-bold text-green-600 text-center">Individual Treatment Records</h1>
      <p class="text-gray-700 text-center">Search, filter, and manage patient records efficiently.</p>
    </div>
    <!-- In your template (e.g. at the top, above the search bar) -->
    <div class="flex flex-col md:flex-row md:items-center gap-10 justify-center mb-6">
      <div class="flex items-center gap-4">
        <span class="font-semibold text-gray-700">Current Date:</span>
        <span class="text-gray-900">{{ currentDateText }}</span>
      </div>
      <div class="flex items-center gap-4">
        <label for="filterDate" class="font-semibold text-gray-700">Filter Date:</label>
        <input type="date" id="filterDate" v-model="filterDate"
          class="border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400" />
      </div>
    </div>


    <!-- Search and Filter Section -->
    <div class="flex flex-col md:flex-row md:items-center gap-6 mb-8">
      <div class="w-full md:w-2/3 flex flex-col gap-4">
        <!-- Enhanced Input Field -->
        <div class="relative">
          <input v-model="searchQuery" @keyup.enter="addFilter" type="text"
            placeholder="🔍 Search by name, diagnosis, or visit type..."
            class="w-full px-5 py-3 border border-gray-300 rounded-md shadow-sm bg-white focus:outline-none focus:ring-2 focus:ring-green-400 transition duration-200" />
          <button @click="addFilter"
            class="absolute right-2 top-1/2 transform -translate-y-1/2 px-4 py-2 bg-green-500 text-white text-sm font-semibold rounded-md hover:bg-green-600 focus:outline-none transition duration-200">
            Add
          </button>
        </div>

        <!-- Dynamic Filter Tags with Modern Style -->
        <div v-if="filters.length" class="flex flex-wrap gap-3">
          <div v-for="(filter, index) in filters" :key="index"
            class="flex items-center gap-2 bg-gradient-to-r from-green-200 to-green-300 text-green-900 px-4 py-1 rounded-md shadow hover:shadow-md transition duration-300">
            <span class="text-sm font-medium">{{ filter }}</span>
            <button @click="removeFilter(index)"
              class="flex items-center justify-center w-5 h-5 bg-green-400 text-white rounded-full hover:bg-red-500 hover:scale-110 transition">
              &times;
            </button>
          </div>
        </div>

        <!-- Helper Text -->
        <p v-if="!filters.length" class="text-gray-500 text-sm italic">
          Type your search and press <strong>Enter</strong> or click <strong>Add</strong> to apply filters.
        </p>
      </div>


      <!-- Relative container for the Filters button + panel -->
      <div class="relative flex gap-1 mb-10">
        <!-- Filters Button -->
        <button @click="toggleFilterPanel"
          class="flex items-center gap-2 px-6 py-3 bg-green-500 text-white font-medium rounded-lg shadow hover:bg-green-600 transition">
          Filters
          <span v-if="isFilterPanelOpen">▲</span>
          <span v-else>▼</span>
        </button>
        <button @click="generateReport"
          class="px-6 py-3 bg-green-500 text-white font-semibold rounded-lg shadow hover:bg-green-600 transition">
          <!-- <font-awesome-icon icon="file-import" /> -->
          Generate Report
        </button>
        <button @click="triggerImport"
          class="flex items-center gap-2 px-6 py-3 bg-blue-500 text-white font-semibold rounded-lg shadow hover:bg-blue-600 transition">
          <!-- <font-awesome-icon icon="file-import" /> -->
          Import CSV
        </button>

        <!-- Absolute-positioned Filter Panel -->
        <transition name="slide-vertical">
          <div v-if="isFilterPanelOpen"
            class="absolute left-0 top-full mt-2 w-full bg-white border border-gray-300 rounded-lg shadow-md z-50 p-6">
            <div class="grid grid-cols-2 gap-6 ">
              <!-- Gender Filter -->
              <div>
                <label class="block font-semibold mb-2">Gender</label>
                <div class="flex items-center gap-4">
                  <label class="flex items-center gap-2 cursor-pointer">
                    <font-awesome-icon icon="mars" class="text-blue-500" />
                    <input type="checkbox" value="Male" v-model="filterGender"
                      class="form-checkbox text-blue-500 focus:ring-blue-500" />
                    <span class="text-gray-700">Male</span>
                  </label>
                  <label class="flex items-center gap-2 cursor-pointer">
                    <font-awesome-icon icon="venus" class="text-pink-500" />
                    <input type="checkbox" value="Female" v-model="filterGender"
                      class="form-checkbox text-pink-500 focus:ring-pink-500" />
                    <span class="text-gray-700">Female</span>
                  </label>
                </div>
              </div>

              <!-- Age Range Filter -->
              <div class="test">
                <label class="block font-semibold mb-2">Age Range</label>
                <div class="flex flex-col items-start">
                  <input type="range" v-model="filterAgeRange" min="0" max="100" step="5"
                    class="w-full accent-green-500" />
                  <p class="text-sm text-gray-500 mt-1">Selected: {{ filterAgeRange }}+</p>
                </div>
              </div>

              <!-- Purok Filter -->
              <div>
                <label class="block font-semibold mb-2">Purok</label>
                <select v-model="filterPrk"
                  class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-green-400">
                  <option value="">All Purok</option>
                  <option v-for="purok in purokOptions" :key="purok" :value="purok">
                    {{ purok }}
                  </option>
                </select>
              </div>

              <!-- Barangay Filter -->
              <div>
                <label class="block font-semibold mb-2">Barangay</label>
                <select v-model="filterBarangay"
                  class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-green-400">
                  <option value="">All Barangay</option>
                  <option v-for="barangay in barangayOptions" :key="barangay" :value="barangay">
                    {{ barangay }}
                  </option>
                </select>
              </div>

              <!-- Diagnosis Filters -->
              <div class="col-span-2">
                <button @click="toggleDiagnosisPanel"
                  class="flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded shadow hover:bg-green-600 transition font-medium">
                  Diagnosis Filters
                  <span v-if="isDiagnosisPanelOpen">▲</span>
                  <span v-else>▼</span>
                </button>

                <transition name="fade">
                  <div v-if="isDiagnosisPanelOpen" class="flex flex-col gap-2 mt-3">
                    <div v-for="diagnosis in visibleDiagnosisOptions" :key="diagnosis">
                      <label class="flex items-center gap-2 cursor-pointer">
                        <div class="w-5 h-5 border-2 border-green-500 rounded flex items-center justify-center">
                          <input type="checkbox" :value="diagnosis" v-model="filterDiagnosis"
                            class="appearance-none w-4 h-4" />
                          <div v-if="filterDiagnosis.includes(diagnosis)" class="w-3 h-3 bg-green-500 rounded"></div>
                        </div>
                        <span class="text-gray-700">{{ diagnosis }}</span>
                      </label>
                    </div>
                    <button v-if="uniqueDiagnosisOptions.length > maxVisibleDiagnoses" @click="toggleShowAllDiagnosis"
                      class="text-blue-500 underline font-medium hover:text-blue-700 mt-2">
                      {{ showAllDiagnosis ? "See Less" : "See More" }}
                    </button>
                  </div>
                </transition>
              </div>
            </div>
          </div>
        </transition>

      </div>
    </div>

    <!-- Buttons -->

    <input type="file" ref="fileInput" accept=".csv" @change="handleFileUpload" class="hidden" />

    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
      <table class="min-w-full bg-white">
        <thead>
          <tr class="bg-gradient-to-r from-green-500 to-yellow-500 text-white uppercase text-sm font-bold">
            <th class="py-4 px-6 text-left">Full Name</th>
            <th class="py-4 px-6 text-left">Address</th>
            <th class="py-4 px-6 text-left">Age</th>
            <th class="py-4 px-6 text-left">Visit Type</th>
            <th class="py-4 px-6 text-left">Consultation Date</th>
            <th class="py-4 px-6 text-left">Diagnosis</th>
            <th class="py-4 px-6 text-left">Gender</th>
            <th class="py-4 px-6 text-left">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 text-gray-700">
          <tr v-for="patient in paginatedPatients" :key="patient.personalId" class="hover:bg-gray-50 cursor-pointer"
            @click="openModal(patient)">
            <td class="py-3 px-6">{{ patient.fullName }}</td>
            <td class="py-3 px-6">{{ patient.address }}</td>
            <td class="py-3 px-6">{{ patient.age }}</td>
            <td class="py-3 px-6">{{ patient.visitType }}</td>
            <td class="py-3 px-6">
              {{ formatDate(patient.consultationDate) }}
            </td>
            <td class="py-3 px-6">{{ patient.diagnosis }}</td>
            <td class="py-3 px-6">
              {{ patient.sex }}
            </td>
            <td class="text-base py-3 px-6">
              <span :class="{
                'bg-green-100 text-green-800': patient.status === 'Completed',
                'bg-yellow-100 text-yellow-800': patient.status === 'In Queued',
                'bg-red-100 text-red-800': patient.status === 'Cancelled',
                'bg-orange-100 text-orange-800': patient.status === 'Follow-up Required',
                'bg-gray-100 text-gray-800': !patient.status || !['Completed', 'In Queued', 'Cancelled', 'Follow-up Required'].includes(patient.status)
              }" class="px-3 py-1 rounded-full text-sm font-semibold shadow-sm capitalize">
                {{ patient.status || 'Pending' }}
              </span>
            </td>

          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center gap-4">
      <button @click="prevPage" :disabled="currentPage === 1"
        class="px-4 py-2 bg-gray-500 text-white font-semibold rounded-lg shadow hover:bg-gray-600 transition">
        Previous
      </button>
      <span class="text-gray-700">Page {{ currentPage }} of {{ totalPages }}</span>
      <button @click="nextPage" :disabled="currentPage === totalPages"
        class="px-4 py-2 bg-green-500 text-white font-semibold rounded-lg shadow hover:bg-green-600 transition">
        Next
      </button>
    </div>

    <!-- Modal -->
    <div v-if="showModal && selectedPatient"
      class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 p-4 overflow-y-auto">
      <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl p-8 relative max-h-screen overflow-y-auto">

        <!-- Close Button -->
        <button @click="closeModal"
          class="absolute top-4 right-4 bg-red-600 text-white rounded-full w-10 h-10 flex items-center justify-center hover:bg-red-700 transition">
          &times;
        </button>

        <!-- Header Section -->
        <div class="border-b pb-4 mb-6 flex items-center gap-4">
          <div class="bg-green-100 text-green-700 rounded-full p-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
          </div>
          <div>
            <h2 class="text-2xl font-bold text-gray-800">Individual Treatment Record</h2>
            <p class="text-gray-600">Details for {{ selectedPatient.fullName }}</p>
          </div>
        </div>

        <!-- Patient Status Section -->
        <div class="mb-6">
          <h3 class="text-lg font-semibold text-gray-700">Patient Status:</h3>
          <span :class="statusBadgeClass(selectedPatient.status)"
            class="inline-block px-3 py-1 text-sm font-medium rounded-full">
            {{ selectedPatient.status || 'Pending' }}
          </span>
        </div>

        <!-- Patient Details Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

          <!-- Column 1: Basic Information -->
          <div>
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Basic Information</h3>
            <ul class="space-y-2">
              <li><strong>Full Name:</strong> {{ selectedPatient.firstName }} {{ selectedPatient.middleName || '' }} {{
                selectedPatient.lastName }}</li>
              <li><strong>Suffix:</strong> {{ selectedPatient.suffix || 'N/A' }}</li>
              <li><strong>Address:</strong> {{ selectedPatient.purok }}, {{ selectedPatient.barangay }}</li>
              <li><strong>Age:</strong> {{ selectedPatient.age }}</li>
              <li><strong>Birthday:</strong> {{ selectedPatient.birthdate }}</li>
              <li><strong>Contact:</strong> {{ selectedPatient.contact }}</li>
              <li><strong>Gender:</strong> {{ selectedPatient.sex }}</li>
            </ul>
          </div>

          <!-- Column 2: Consultation Details -->
          <div>
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Consultation Details</h3>
            <ul class="space-y-2">
              <li><strong>Time of Consultation:</strong> {{ selectedPatient.consultationTime }}</li>
              <li><strong>Date of Consultation:</strong> {{ selectedPatient.consultationDate }}</li>
              <li><strong>Mode of Transaction:</strong> {{ selectedPatient.modeOfTransaction }}</li>
              <li><strong>Blood Pressure:</strong> {{ selectedPatient.bloodPressure }}</li>
              <li><strong>Temperature:</strong> {{ selectedPatient.temperature }}</li>
              <li><strong>Height:</strong> {{ selectedPatient.height }}</li>
              <li><strong>Weight:</strong> {{ selectedPatient.weight }}</li>
            </ul>
          </div>
        </div>

        <!-- Additional Details -->
        <div class="mt-8">
          <h3 class="text-lg font-semibold text-gray-700 mb-4">Additional Details</h3>
          <ul class="space-y-2">
            <li><strong>Name of Attending Provider:</strong> {{ selectedPatient.providerName }}</li>
            <li><strong>Nature of Visit:</strong> {{ selectedPatient.natureOfVisit }}</li>
            <li><strong>Type of Consultation/Purpose of Visit:</strong> {{ selectedPatient.visitType }}</li>
            <li><strong>Chief Complaints:</strong> {{ selectedPatient.chiefComplaints }}</li>
            <li><strong>Diagnosis:</strong> {{ selectedPatient.diagnosis }}</li>
            <li><strong>Medication/Treatment:</strong> {{ selectedPatient.medication }}</li>
          </ul>
        </div>

        <!-- Footer Actions -->
        <div class="mt-6 flex justify-end gap-4">
          <button @click="closeModal"
            class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
            Close
          </button>

          <!-- Edit Button -->
          <button @click="editPatient(selectedPatient)"
            class="px-6 py-2 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600 transition">
            Edit Record
          </button>

          <button @click="printRecord(selectedPatient)"
            class="px-6 py-2 bg-green-600 text-white rounded-lg shadow-md hover:bg-green-700 transition">
            Print Record
          </button>
        </div>
      </div>
    </div>



  </div>
</template>


<script>
import { Inertia } from '@inertiajs/inertia';

export default {
  props: {
    patients: {
      type: Array,
      default: () => [],
    },
  },
  mounted() {
    const today = new Date();
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    this.currentDateText = today.toLocaleDateString(undefined, options);
  },
  data() {
    return {
      searchQuery: '',            // User input for search
      filters: [],                // Dynamic search filters
      filterPrk: '',
      filterBarangay: '',
      filterGender: [],
      filterAgeRange: '',
      filterDiagnosis: [],
      currentPage: 1,
      itemsPerPage: 15,
      showModal: false,
      selectedPatient: null,
      isFilterPanelOpen: false,
      filterDate: '',
      currentDateText: '',
      diagnosisOptions: [],
      showAllDiagnosis: false,
      maxVisibleDiagnoses: 5,
      isDiagnosisPanelOpen: false,
    };
  },
  watch: {
    searchQuery() {
      this.currentPage = 1;
    },
    filterGender() {
      this.currentPage = 1;
    },
    filterAgeRange() {
      this.currentPage = 1;
    },
    filterPrk() {
      this.currentPage = 1;
    },
    filterBarangay() {
      this.currentPage = 1;
    },
    filterDiagnosis() {
      this.currentPage = 1;
    },
  },
  computed: {
    paginatedPatients() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.filteredPatients.slice(start, end);
    },

    uniqueDiagnosisOptions() {
      return Array.from(new Set(this.patients.map((p) => p.diagnosis)));
    },

    /*
      2) The subset of diagnoses you actually display (either first 5 or all).
         We'll rename "filteredDiagnosisOptions" => "visibleDiagnosisOptions"
         for clarity that these are the ones you physically render in checkboxes.
    */
    visibleDiagnosisOptions() {
      const all = this.uniqueDiagnosisOptions;
      return this.showAllDiagnosis ? all : all.slice(0, this.maxVisibleDiagnoses);
    },

    filteredPatients() {
      const queries = this.filters.map(f => f.toLowerCase());

      return this.patients
        .map((patient) => ({
          ...patient,
          fullName: `${patient.firstName} ${patient.lastName}`,
          address: `${patient.purok}, ${patient.barangay}`,
        }))
        .filter((patient) => {
          const matchesFilters = queries.every((filter) =>
            (patient.fullName && patient.fullName.toLowerCase().includes(filter)) ||
            (patient.natureOfVisit && patient.natureOfVisit.toLowerCase().includes(filter)) ||
            (patient.visitType && patient.visitType.toLowerCase().includes(filter)) ||
            (patient.address && patient.address.toLowerCase().includes(filter)) ||
            (patient.diagnosis && patient.diagnosis.toLowerCase().includes(filter))
          );

          const matchesPrk = !this.filterPrk || patient.purok === this.filterPrk;
          const matchesBarangay = !this.filterBarangay || patient.barangay === this.filterBarangay;
          const matchesGender = this.filterGender.length === 0 || this.filterGender.includes(patient.sex);
          const matchesDiagnosis = this.filterDiagnosis.length === 0 || this.filterDiagnosis.includes(patient.diagnosis);

          let matchesAgeRange = true;
          if (this.filterAgeRange) {
            const patientAge = parseInt(patient.age, 10);
            matchesAgeRange = patientAge >= parseInt(this.filterAgeRange, 10);
          }

          let matchesDate = true;
          if (this.filterDate) {
            matchesDate = this.sameDay(patient.consultationDate, this.filterDate);
          }

          return (
            matchesFilters &&
            matchesPrk &&
            matchesBarangay &&
            matchesGender &&
            matchesDiagnosis &&
            matchesAgeRange &&
            matchesDate
          );
        })
        .sort((a, b) => new Date(b.consultationDate) - new Date(a.consultationDate));
    },

    totalPages() {
      return Math.ceil(this.filteredPatients.length / this.itemsPerPage);
    },

    purokOptions() {
      return Array.from(new Set(this.patients.map((p) => p.purok)));
    },

    barangayOptions() {
      return Array.from(new Set(this.patients.map((p) => p.barangay)));
    },
  },

  methods: {
    // Open Edit Page
    editPatient(patient) {
      Inertia.get(`/patients/edit/${patient.personalId}`);
    },

    // Status Badge Styling
    statusBadgeClass(status) {
      switch (status) {
        case 'Completed':
          return 'bg-green-100 text-green-800';
        case 'In Progress':
          return 'bg-yellow-100 text-yellow-800';
        case 'Pending':
          return 'bg-gray-100 text-gray-800';
        case 'Cancelled':
          return 'bg-red-100 text-red-800';
        case 'Follow-up Required':
          return 'bg-orange-100 text-orange-800';
        default:
          return 'bg-gray-100 text-gray-800';
      }
    },
    addFilter() {
      const words = this.searchQuery.trim().split(/\s+/);
      words.forEach((word) => {
        const cleanWord = word.trim().toLowerCase();
        if (cleanWord && !this.filters.includes(cleanWord)) {
          this.filters.push(cleanWord);
        }
      });
      this.searchQuery = '';
    },

    removeFilter(index) {
      this.filters.splice(index, 1);
    },

    toggleFilterPanel() {
      this.isFilterPanelOpen = !this.isFilterPanelOpen;
    },

    toggleDiagnosisPanel() {
      this.isDiagnosisPanelOpen = !this.isDiagnosisPanelOpen;
    },

    formatDate(dateStr) {
      if (!dateStr) return '';
      const dateObj = new Date(dateStr);
      if (isNaN(dateObj)) return dateStr;
      const options = { year: 'numeric', month: 'long', day: '2-digit' };
      return dateObj.toLocaleDateString('en-US', options);
    },

    toggleShowAllDiagnosis() {
      this.showAllDiagnosis = !this.showAllDiagnosis;
    },

    sameDay(dateA, dateB) {
      const dA = new Date(dateA);
      const dB = new Date(dateB);
      if (isNaN(dA) || isNaN(dB)) return false;
      return dA.getFullYear() === dB.getFullYear() &&
        dA.getMonth() === dB.getMonth() &&
        dA.getDate() === dB.getDate();
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

    triggerImport() {
      this.$refs.fileInput.click();
    },

    handleFileUpload(event) {
      const file = event.target.files[0];
      if (file) {
        const formData = new FormData();
        formData.append("file", file);
        this.uploadCSV(formData);
      }
    },

    uploadCSV(formData) {
      Inertia.post('/services/patients/itrtable', formData, {
        onSuccess: () => alert('Patients imported successfully!'),
        onError: (errors) => {
          console.error('Errors during upload:', errors);
          alert('Failed to import CSV. Please check the file and try again.');
        },
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

    printRecord(patient) {
      const printContent = `
        <h2>Individual Treatment Record</h2>
        <p><strong>Name:</strong> ${patient.fullName}</p>
        <p><strong>Diagnosis:</strong> ${patient.diagnosis}</p>
      `;
      const newWindow = window.open('', '_blank');
      newWindow.document.write(printContent);
      newWindow.print();
    },
  },
};
</script>



<style>
/* Example slide-vertical transition */
.slide-vertical-enter-active,
.slide-vertical-leave-active {
  transition: max-height 0.3s ease, padding 0.3s ease, opacity 0.3s ease;
  overflow: hidden;
  /* Hide content beyond the max-height */
}

.slide-vertical-enter-from,
.slide-vertical-leave-to {
  max-height: 0;
  padding-top: 0;
  padding-bottom: 0;
  opacity: 0;
}

.slide-vertical-enter-to,
.slide-vertical-leave-from {
  max-height: 2000px;
  /* A large enough max-height to accommodate content */
  opacity: 1;
}
</style>
