<template>
  <div class="mx-auto py-8 px-10  bg-gradient-to-br from-green-100 to-blue-100 min-h-screen">
    <!-- Header Section -->
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-green-600 text-center">Individual Treatment Records</h1>
      <p class="text-gray-700 text-center">Search, filter, and manage patient records efficiently.</p>
    </div>

    <!-- Search and Filter Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
      <!-- Search Bar -->
      <div class="w-full md:w-3/3 flex items-center border border-gray-300 rounded-lg shadow-sm bg-white">
        <!-- <font-awesome-icon icon="search" class="text-gray-400 mx-3" /> -->
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search by name, diagnosis, or visit type"
          class="w-full p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
        />
      </div>
    <!-- <div class="flex gap-10">
      <button
        @click="generateReport"
        class="px-6 py-3 bg-green-500 text-white font-semibold rounded-lg shadow hover:bg-green-600 transition"
      >
        Generate Report
      </button>
      <button
        @click="triggerImport"
        class="flex items-center gap-2 px-6 py-3 bg-blue-500 text-white font-semibold rounded-lg shadow hover:bg-blue-600 transition"
      >
        <font-awesome-icon icon="file-import" />
        Import CSV
      </button>
    </div> -->

      <!-- Filter Panel Toggle -->
      <button
        @click="toggleFilterPanel"
        class="flex items-center justify-center gap-2 px-6 py-3 bg-green-500 text-white font-medium rounded-lg shadow hover:bg-green-600 transition"
      >
        <!-- <font-awesome-icon icon="filter" /> -->
        Filters
      </button>
    </div>

    <!-- Collapsible Filter Panel -->
    <transition name="fade">
      <div
        v-if="isFilterPanelOpen"
        class="border border-gray-300 rounded-lg p-6 shadow-md bg-white mb-8"
      >
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <!-- Gender Filter -->
          <div>
            <label class="block font-semibold mb-2">Gender</label>
            <div class="flex gap-4">
              <label class="flex items-center gap-2 cursor-pointer">
                <font-awesome-icon icon="mars" class="text-blue-500" />
                <input
                  type="checkbox"
                  value="Male"
                  v-model="filterGender"
                  class="form-checkbox text-blue-500 focus:ring-blue-500"
                />
                Male
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <font-awesome-icon icon="venus" class="text-pink-500" />
                <input
                  type="checkbox"
                  value="Female"
                  v-model="filterGender"
                  class="form-checkbox text-pink-500 focus:ring-pink-500"
                />
                Female
              </label>
            </div>
          </div>

          <!-- Age Range Filter -->
          <div>
            <label class="block font-semibold mb-2">Age Range</label>
            <input
              type="range"
              v-model="filterAgeRange"
              min="0"
              max="100"
              step="5"
              class="w-full accent-green-500"
            />
            <p class="text-sm text-gray-500 mt-1">Selected: {{ filterAgeRange }}+</p>
          </div>

          <!-- Purok Filter -->
          <div>
            <label class="block font-semibold mb-2">Purok</label>
            <select
              v-model="filterPrk"
              class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-green-400"
            >
              <option value="">All Purok</option>
              <option v-for="purok in purokOptions" :key="purok" :value="purok">{{ purok }}</option>
            </select>
          </div>

          <!-- Barangay Filter -->
          <div>
            <label class="block font-semibold mb-2">Barangay</label>
            <select
              v-model="filterBarangay"
              class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-green-400"
            >
              <option value="">All Barangay</option>
              <option v-for="barangay in barangayOptions" :key="barangay" :value="barangay">{{ barangay }}</option>
            </select>
          </div>

          <!-- Diagnosis Filter -->
          <div>
            <label class="block font-semibold mb-2">Diagnosis</label>
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
    </transition>

    <!-- Buttons -->
    <div class="flex gap-4 mb-6">
      <button
        @click="generateReport"
        class="px-6 py-3 bg-green-500 text-white font-semibold rounded-lg shadow hover:bg-green-600 transition"
      >
      <!-- <font-awesome-icon icon="file-import" /> -->
        Generate Report
      </button>
      <button
        @click="triggerImport"
        class="flex items-center gap-2 px-6 py-3 bg-blue-500 text-white font-semibold rounded-lg shadow hover:bg-blue-600 transition"
      >
        <!-- <font-awesome-icon icon="file-import" /> -->
        Import CSV
      </button>
      <input type="file" ref="fileInput" accept=".csv" @change="handleFileUpload" class="hidden" />
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
      <table class="min-w-full bg-white">
        <thead>
          <tr class="bg-gradient-to-r from-green-500 to-yellow-500 text-white uppercase text-sm font-bold">
            <th class="py-4 px-6 text-left">Full Name</th>
            <th class="py-4 px-6 text-left">Address</th>
            <th class="py-4 px-6 text-left">Age</th>
            <th class="py-4 px-6 text-left">Nature of Visit</th>
            <th class="py-4 px-6 text-left">Visit Type</th>
            <th class="py-4 px-6 text-left">Diagnosis</th>
            <th class="py-4 px-6 text-left">Gender</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 text-gray-700">
          <tr
            v-for="patient in paginatedPatients"
            :key="patient.personalId"
            class="hover:bg-gray-50 cursor-pointer"
            @click="openModal(patient)"
          >
            <td class="py-3 px-6">{{ patient.fullName }}</td>
            <td class="py-3 px-6">{{ patient.address }}</td>
            <td class="py-3 px-6">{{ patient.age }}</td>
            <td class="py-3 px-6">{{ patient.natureOfVisit }}</td>
            <td class="py-3 px-6">{{ patient.visitType }}</td>
            <td class="py-3 px-6">{{ patient.diagnosis }}</td>
            <td class="py-3 px-6">
              {{ patient.sex }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center gap-4">
      <button
        @click="prevPage"
        :disabled="currentPage === 1"
        class="px-4 py-2 bg-red-500 text-white font-semibold rounded-lg shadow hover:bg-red-600 transition"
      >
        Previous
      </button>
      <span class="text-gray-700">Page {{ currentPage }} of {{ totalPages }}</span>
      <button
        @click="nextPage"
        :disabled="currentPage === totalPages"
        class="px-4 py-2 bg-green-500 text-white font-semibold rounded-lg shadow hover:bg-green-600 transition"
      >
        Next
      </button>
    </div>
      <!-- Modal -->
      <div
  v-if="showModal && selectedPatient"
  class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 p-4"
>
  <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl p-8 relative">
    <!-- Close Button -->
    <button
      @click="closeModal"
      class="absolute top-4 right-4 bg-red-600 text-white rounded-full w-10 h-10 flex items-center justify-center hover:bg-red-700 transition"
    >
      &times;
    </button>

    <!-- Header Section -->
    <div class="border-b pb-4 mb-6 flex items-center gap-4">
      <div class="bg-green-100 text-green-700 rounded-full p-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
      </div>
      <div>
        <h2 class="text-2xl font-bold text-gray-800">Individual Treatment Record</h2>
        <p class="text-gray-600">Details for {{ selectedPatient.fullName }}</p>
      </div>
    </div>

    <!-- Patient Details Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <!-- Column 1 -->
      <div>
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Basic Information</h3>
        <ul class="space-y-2">
          <li><strong>Full Name:</strong> {{ selectedPatient.firstName }} {{ selectedPatient.middleName || '' }} {{ selectedPatient.lastName }}</li>
          <li><strong>Suffix:</strong> {{ selectedPatient.suffix || 'N/A' }}</li>
          <li><strong>Address:</strong> {{ selectedPatient.purok }}, {{ selectedPatient.barangay }}</li>
          <li><strong>Age:</strong> {{ selectedPatient.age }}</li>
          <li><strong>Birthday:</strong> {{ selectedPatient.birthdate }}</li>
          <li><strong>Contact:</strong> {{ selectedPatient.contact }}</li>
          <li><strong>Gender:</strong> {{ selectedPatient.sex }}</li>
        </ul>
      </div>

      <!-- Column 2 -->
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
      <button
        @click="closeModal"
        class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition"
      >
        Close
      </button>
      <button
        @click="printRecord(selectedPatient)"
        class="px-6 py-2 bg-green-600 text-white rounded-lg shadow-md hover:bg-green-700 transition"
      >
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
  data() {
    return {
      searchQuery: '', // Search query for filtering
      filterPrk: '', // Filter by purok
      filterBarangay: '', // Filter by barangay
      filterGender: [], // Array for selected genders
      filterAgeRange: '', // Filter by age range
      filterDiagnosis: [], // Array for selected diagnoses
      currentPage: 1, // Current page for pagination
      itemsPerPage: 15, // Number of items per page
      showModal: false, // Modal visibility flag
      selectedPatient: null, // Selected patient for modal display
      isFilterPanelOpen: false, // Toggle filter panel visibility
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
    totalPages() {
      return Math.ceil(this.patients.length / this.itemsPerPage);
    },
  },
  methods: {

  printRecord(patient) {
    const printContent = `
      <div style="font-family: Arial, sans-serif; line-height: 1.5; padding: 20px;">
        <h2 style="text-align: center; color: #38a169;">Individual Treatment Record</h2>
        <p><strong>Full Name:</strong> ${patient.firstName} ${patient.middleName || ''} ${patient.lastName}</p>
        <p><strong>Age:</strong> ${patient.age}</p>
        <p><strong>Gender:</strong> ${patient.sex}</p>
        <p><strong>Address:</strong> ${patient.purok}, ${patient.barangay}</p>
        <p><strong>Nature of Visit:</strong> ${patient.natureOfVisit}</p>
        <p><strong>Visit Type:</strong> ${patient.visitType}</p>
        <p><strong>Date of Consultation:</strong> ${patient.consultationDate}</p>
        <p><strong>Diagnosis:</strong> ${patient.diagnosis}</p>
        <p><strong>Medication/Treatment:</strong> ${patient.medication}</p>
      </div>
    `;

    const newWindow = window.open('', '_blank', 'width=800,height=600');
    newWindow.document.write(`
      <html>
        <head>
          <title>Print Record</title>
        </head>
        <body>
          ${printContent}
        </body>
      </html>
    `);
    newWindow.document.close();
    newWindow.print();
  },

    triggerImport() {
      this.$refs.fileInput.click(); // Trigger file input click
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
        onSuccess: () => {
          alert('Patients imported successfully!');
        },
        onError: (errors) => {
          console.error('Errors during upload:', errors);
          alert('Failed to import CSV. Please check the file and try again.');
        },
      });
    },
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
