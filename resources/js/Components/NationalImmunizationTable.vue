<template>
  <div class="mx-auto py-8 px-10 bg-gradient-to-br from-green-100 to-blue-100 min-h-screen">
    <!-- Header Section -->
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-green-600 text-center">National Immunization Records</h1>
      <p class="text-gray-700 text-center">Search, filter, and manage patient records efficiently.</p>
    </div>

    <!-- Search and Filter Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
      <!-- Search Bar -->
      <div class="w-full md:w-2/3">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search by name, diagnosis, or visit type"
          class="border border-gray-300 p-3 rounded-lg w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
      </div>

      <!-- Filter Panel Toggle -->
      <button
        @click="toggleFilterPanel"
        class="flex items-center px-4 py-3 bg-green-500 text-white font-medium rounded-lg shadow hover:bg-green-600 focus:outline-none w-full md:w-1/3"
      >
        <span>Filters</span>
        <svg
          class="w-4 h-4 ml-2 transform"
          :class="{ 'rotate-180': isFilterPanelOpen }"
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
    </div>

    <!-- Collapsible Filter Panel -->
    <transition name="fade">
      <div
        v-if="isFilterPanelOpen"
        class="mt-4 mb-4 border border-gray-300 rounded-lg p-6 shadow-md bg-white"
      >
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
              <option v-for="purok in purokOptions" :key="purok" :value="purok">{{ purok }}</option>
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
        </div>
      </div>
    </transition>

       <!-- Buttons -->
       <div class="mb-6 flex gap-4">
      <button
        @click="generateReport"
        class="px-6 py-3 bg-green-500 text-white font-semibold rounded-lg shadow hover:bg-green-600 focus:outline-none transition"
      >
        Generate Report
      </button>
      <button
        @click="triggerImport"
        class="px-6 py-3 bg-blue-500 text-white font-semibold rounded-lg shadow hover:bg-blue-600 focus:outline-none transition flex items-center gap-2"
      >
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
            <th class="py-4 px-6 text-left">Gender</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 text-gray-700">
          <tr
            v-for="(patient, index) in filteredPatients"
            :key="patient.id || index"
            class="hover:bg-gray-50 transition-colors cursor-pointer"
            @click="openModal(patient)"
          >
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
      <button
        @click="prevPage"
        :disabled="currentPage === 1"
        class="px-4 py-2 bg-red-500 text-white font-semibold rounded-lg shadow hover:bg-red-600 focus:outline-none transition disabled:opacity-50"
      >
        Previous
      </button>
      <span class="text-gray-700">Page {{ currentPage }} of {{ totalPages }}</span>
      <button
        @click="nextPage"
        :disabled="currentPage === totalPages"
        class="px-4 py-2 bg-green-500 text-white font-semibold rounded-lg shadow hover:bg-green-600 focus:outline-none transition disabled:opacity-50"
      >
        Next
      </button>
    </div>
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
          Details for {{ selectedPatient.fullName }}
        </h2>
        <ul class="space-y-2 flex gap-10">
          <div>
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
          </div>
          
          <div>
            <li><strong>Household No.:</strong> {{ selectedPatient.houseHoldno }}</li>
          <li><strong>4Ps Member?:</strong> {{ selectedPatient.fourpsmember }}</li>
          <li><strong>Primary Care Benefit (PCB) Member?:</strong> {{ selectedPatient.PCBMember }}</li>
          <li><strong>philhealthStatus</strong> {{ selectedPatient.philhealthStatus }}</li>
          <li><strong>Philhealth No.:</strong> {{ selectedPatient.philhealthNo }}</li>
          <li><strong>If Member, please indicate category:</strong> {{ selectedPatient.ifMember }}</li>
          <li><strong>Family Member:</strong> {{ selectedPatient.familyMember }}</li>
          <li><strong>TT Status of Mother:</strong> {{ selectedPatient.ttStatus }}</li>
          <li><strong>Date Assesed:</strong> {{ selectedPatient.dateAssesed }}</li>
          <li><strong>Date:</strong> {{ selectedPatient.date }}</li>
          <li><strong>Place:</strong> {{ selectedPatient.place }}</li>
          <li><strong>Guardiant:</strong> {{ selectedPatient.guardian }}</li>
          </div>
        </ul>
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

