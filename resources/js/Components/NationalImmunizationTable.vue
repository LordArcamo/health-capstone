<template>
  <div class="mx-auto py-8 px-10 bg-gradient-to-br from-blue-100 to-teal-100 min-h-screen">
    <!-- Header Section -->
    <div class="mb-6 text-center">
      <h1 class="text-3xl font-bold text-teal-600">National Immunization Records</h1>
      <p class="text-gray-700">Search, filter, and manage patient immunization records effectively.</p>
    </div>

    <!-- Search and Filter Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
      <!-- Search Bar -->
      <div class="w-full md:w-2/3">
        <input v-model="searchQuery" type="text" placeholder="Search by name, diagnosis, or address"
          class="border border-gray-300 p-3 rounded-lg w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-400" />
      </div>

      <!-- Filter Panel Toggle -->
      <button @click="toggleFilterPanel"
        class="flex items-center px-4 py-3 bg-teal-500 text-white font-medium rounded-lg shadow hover:bg-teal-600 focus:outline-none w-full md:w-1/3">
        <span>Filters</span>
        <svg class="w-4 h-4 ml-2 transform" :class="{ 'rotate-180': isFilterPanelOpen }"
          xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
    </div>

    <!-- Collapsible Filter Panel -->
    <transition name="fade">
      <div v-if="isFilterPanelOpen" class="mt-4 mb-4 border border-gray-300 rounded-lg p-6 shadow-md bg-white">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <!-- Gender Filter -->
          <div>
            <label class="block text-base font-semibold mb-2">Gender</label>
            <div class="flex flex-col gap-3">
              <label class="flex items-center gap-2 cursor-pointer">
                <div class="w-5 h-5 border-2 border-teal-500 rounded-full flex items-center justify-center">
                  <input type="checkbox" value="Male" v-model="filterGender" class="appearance-none w-4 h-4" />
                  <div v-if="filterGender.includes('Male')" class="w-2.5 h-2.5 bg-teal-500 rounded-full"></div>
                </div>
                <span class="text-gray-700">Male</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <div class="w-5 h-5 border-2 border-teal-500 rounded-full flex items-center justify-center">
                  <input type="checkbox" value="Female" v-model="filterGender" class="appearance-none w-4 h-4" />
                  <div v-if="filterGender.includes('Female')" class="w-2.5 h-2.5 bg-teal-500 rounded-full"></div>
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
              <input type="range" v-model="filterAgeRange" min="0" max="100" step="5" class="w-full accent-teal-500" />
              <span class="text-sm text-gray-500">100+</span>
            </div>
            <div class="text-sm text-gray-700 mt-1">Selected: {{ filterAgeRange }}+</div>
          </div>

          <!-- Purok Filter -->
          <div>
            <label class="block text-base font-semibold mb-2">Purok</label>
            <select v-model="filterPrk"
              class="border border-gray-300 p-3 rounded-lg w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
              <option value="">All Purok</option>
              <option v-for="purok in purokOptions" :key="purok" :value="purok">{{ purok }}</option>
            </select>
          </div>

          <!-- Barangay Filter -->
          <div>
            <label class="block text-base font-semibold mb-2">Barangay</label>
            <select v-model="filterBarangay"
              class="border border-gray-300 p-3 rounded-lg w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
              <option value="">All Barangay</option>
              <option v-for="barangay in barangayOptions" :key="barangay" :value="barangay">
                {{ barangay }}
              </option>
            </select>
          </div>
        </div>
      </div>
    </transition>

    <!-- Buttons -->
    <div class="mb-6 flex gap-4">
      <button @click="generateReport"
        class="px-6 py-3 bg-teal-500 text-white font-semibold rounded-lg shadow hover:bg-teal-600 focus:outline-none transition">
        Generate Report
      </button>
      <button @click="triggerImport"
        class="px-6 py-3 bg-blue-500 text-white font-semibold rounded-lg shadow hover:bg-blue-600 focus:outline-none transition flex items-center gap-2">
        Import CSV
      </button>
      <input type="file" ref="fileInput" accept=".csv" @change="handleFileUpload" class="hidden" />
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
      <table class="min-w-full bg-white">
        <thead>
          <tr class="bg-gradient-to-r from-teal-500 to-blue-500 text-white uppercase text-sm font-bold">
            <th class="py-4 px-6 text-left">Full Name</th>
            <th class="py-4 px-6 text-left">Address</th>
            <th class="py-4 px-6 text-left">Age</th>
            <th class="py-4 px-6 text-left">Gender</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 text-gray-700">
          <tr v-for="(patient, index) in filteredPatients" :key="patient.id || index"
            class="hover:bg-gray-50 transition-colors cursor-pointer" @click="openModal(patient)">
            <td class="py-3 px-6">{{ patient.fullName }}</td>
            <td class="py-3 px-6">{{ patient.address }}</td>
            <td class="py-3 px-6">{{ patient.age }}</td>
            <td class="py-3 px-6">{{ patient.sex }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center gap-4">
      <button @click="prevPage" :disabled="currentPage === 1"
        class="px-4 py-2 bg-red-500 text-white font-semibold rounded-lg shadow hover:bg-red-600 focus:outline-none transition disabled:opacity-50">
        Previous
      </button>
      <span class="text-gray-700">Page {{ currentPage }} of {{ totalPages }}</span>
      <button @click="nextPage" :disabled="currentPage === totalPages"
        class="px-4 py-2 bg-teal-500 text-white font-semibold rounded-lg shadow hover:bg-teal-600 focus:outline-none transition disabled:opacity-50">
        Next
      </button>
    </div>
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
        <h2 class="text-2xl font-bold text-gray-800">Patient Details</h2>
        <p class="text-gray-600">Comprehensive information about {{ selectedPatient.firstName }} {{ selectedPatient.middleName }} {{ selectedPatient.lastName }}</p>
      </div>
    </div>

    <!-- Patient Details Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <!-- Column 1 -->
      <div>
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Basic Information</h3>
        <ul class="space-y-2">
          <li><strong>Full Name:</strong> {{ selectedPatient.firstName }} {{ selectedPatient.middleName }} {{ selectedPatient.lastName }}</li>
          <li><strong>Suffix:</strong> {{ selectedPatient.suffix || 'N/A' }}</li>
          <li><strong>Address:</strong> {{ selectedPatient.address }}</li>
          <li><strong>Age:</strong> {{ selectedPatient.age }}</li>
          <li><strong>Birthday:</strong> {{ selectedPatient.birthdate }}</li>
          <li><strong>Contact:</strong> {{ selectedPatient.contact }}</li>
          <li><strong>Gender:</strong> {{ selectedPatient.sex }}</li>
          <li><strong>Birth Place:</strong> {{ selectedPatient.birthplace }}</li>
          <li><strong>Blood Type:</strong> {{ selectedPatient.bloodtype }}</li>
        </ul>
      </div>

      <!-- Column 2 -->
      <div>
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Family & Health Information</h3>
        <ul class="space-y-2">
          <li><strong>Mother's Name:</strong> {{ selectedPatient.mothername }}</li>
          <li><strong>DSWD NHTS:</strong> {{ selectedPatient.dswdNhts || 'N/A' }}</li>
          <li><strong>Facility Household No.:</strong> {{ selectedPatient.facilityHouseholdno }}</li>
          <li><strong>Household No.:</strong> {{ selectedPatient.houseHoldno }}</li>
          <li><strong>4Ps Member?:</strong> {{ selectedPatient.fourpsmember ? 'Yes' : 'No' }}</li>
          <li><strong>Primary Care Benefit Member?:</strong> {{ selectedPatient.PCBMember ? 'Yes' : 'No' }}</li>
          <li><strong>Philhealth Status:</strong> {{ selectedPatient.philhealthStatus }}</li>
          <li><strong>Philhealth No.:</strong> {{ selectedPatient.philhealthNo }}</li>
          <li><strong>TT Status of Mother:</strong> {{ selectedPatient.ttStatus }}</li>
        </ul>
      </div>
    </div>

    <!-- Additional Information Section -->
    <div class="mt-8">
      <h3 class="text-lg font-semibold text-gray-700 mb-4">Additional Details</h3>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <ul class="space-y-2">
          <li><strong>Date Assessed:</strong> {{ selectedPatient.dateAssesed || 'N/A' }}</li>
          <li><strong>Date:</strong> {{ selectedPatient.date }}</li>
          <li><strong>Place:</strong> {{ selectedPatient.place }}</li>
          <li><strong>Guardian:</strong> {{ selectedPatient.guardian }}</li>
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
        class="px-6 py-2 bg-blue-500 text-white font-semibold rounded-lg shadow hover:bg-blue-600 focus:outline-none transition"
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
      searchQuery: '',
      filterPrk: '',
      filterBarangay: '',
      filterGender: [], // Array for selected genders
      filterAgeRange: '', // Filter by age range
      filterDiagnosis: [], // Array for selected diagnoses
      currentPage: 1,
      itemsPerPage: 10,
      showModal: false,
      selectedPatient: null,
      isFilterPanelOpen: false, // Toggle filter panel visibility
    };
  },
  computed: {
    filteredPatients() {
      const query = this.searchQuery.toLowerCase();
      return this.patients
        .map((patient) => ({
          ...patient,
          fullName: `${patient.firstName} ${patient.lastName}`,
          address: `${patient.purok}, ${patient.barangay}`,
        }))
        .filter((patient) => {
          const matchesQuery =
            patient.fullName.toLowerCase().includes(query) ||
            patient.address.toLowerCase().includes(query);

          const matchesPrk = !this.filterPrk || patient.purok === this.filterPrk;
          const matchesBarangay = !this.filterBarangay || patient.barangay === this.filterBarangay;

          let matchesAgeRange = true;
          if (this.filterAgeRange) {
            const [minAge, maxAge] = this.filterAgeRange.split('-').map(Number);
            const patientAge = patient.age;
            matchesAgeRange =
              (isNaN(minAge) || patientAge >= minAge) &&
              (isNaN(maxAge) || patientAge <= maxAge);
          }

          const matchesGender =
            this.filterGender.length === 0 || this.filterGender.includes(patient.sex);

          return (
            matchesQuery &&
            matchesPrk &&
            matchesBarangay &&
            matchesAgeRange &&
            matchesGender
          );
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
      Inertia.post('/services/patients/epi-records', formData, {
        onSuccess: () => {
          alert('Patients imported successfully!');
        },
        onError: (errors) => {
          console.error('Errors during upload:', errors);
          alert('Failed to import CSV. Please check the file and try again.');
        },
      });
    },
    toggleFilterPanel() {
      this.isFilterPanelOpen = !this.isFilterPanelOpen;
    },
    openModal(patient) {
      this.selectedPatient = patient;
      this.showModal = true;
    },
    closeModal() {
      this.showModal = false;
      this.selectedPatient = null;
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
        fullName: patient.fullName,
        address: patient.address,
        age: patient.age,
        natureOfVisit: patient.natureOfVisit,
        visitType: patient.visitType,
        gender: patient.sex,
      }));

      const csvContent =
        'data:text/csv;charset=utf-8,' +
        ['Full Name,Address,Age,Nature of Visit,Visit Type,Gender']
          .concat(
            data.map((row) =>
              `${row.fullName},${row.address},${row.age},${row.natureOfVisit},${row.visitType},${row.gender}`
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
