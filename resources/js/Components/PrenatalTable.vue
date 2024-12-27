<template>
  <div class="mx-auto py-8 px-10 bg-gradient-to-br from-green-100 to-blue-100 min-h-screen">
    <!-- Header Section -->
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-green-600 text-center">Prenatal and Postpartum</h1>
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
            <th class="py-4 px-6 text-left">Contact Number</th>
            <th class="py-4 px-6 text-left">TT Status</th>
            <th class="py-4 px-6 text-left">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 text-gray-700">
          <tr
            v-for="patient in filteredPatients"
            :key="patient.id"
            class="hover:bg-gray-50 transition-colors cursor-pointer"
             @click="openModal('inline', patient)"
          >
            <td class="py-3 px-6">{{ patient.fullName }}</td>
            <td class="py-3 px-6">{{ patient.address }}</td>
            <td class="py-3 px-6">{{ patient.age }}</td>
            <td class="py-3 px-6">{{ patient.contact }}</td>
            <td class="py-3 px-6">{{ patient.ttStatus }}</td>
            <td class="py-3 px-6">
              <div class="flex gap-2">
                <button
                    @click.stop="openModal('trimester', patient)"
                  class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-green-600 transition"
                >
                  Trimester
                </button>
                <button
                  @click.stop="openModal('postpartum', patient)"
                  class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-yellow-600 transition"
                >
                  PostPartum
                </button>
              </div>
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
     <div v-if="currentModal === 'inline'" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-lg shadow-lg w-full max-w-lg sm:max-w-2xl p-6 relative">
        <button @click="closeModal"
          class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-700">
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
            <li><strong>Address:</strong> {{ selectedPatient.purok }} {{ selectedPatient.barangay }}</li>
            <li><strong>Age:</strong> {{ selectedPatient.age }}</li>
            <li><strong>Birthday:</strong> {{ selectedPatient.birthdate }}</li>
            <li><strong>Mode of Transaction:</strong> {{ selectedPatient.modeOfTransaction }}</li>
            <li><strong>Date of Consultation:</strong> {{ selectedPatient.consultationDate }}</li>
            <li><strong>Time of Consultation:</strong> {{ selectedPatient.consultationTime }}</li>
            <li><strong>Blood Pressure:</strong> {{ selectedPatient.bloodPressure }}</li>
            <li><strong>Temperature:</strong> {{ selectedPatient.temperature }}</li>
            <li><strong>Height:</strong> {{ selectedPatient.height }}</li>
            <li><strong>Weight:</strong> {{ selectedPatient.weight }}</li>
            <li><strong>Name of Attending Provider:</strong> {{ selectedPatient.providerName }}</li>
            <li><strong>Name of Spouse:</strong> {{ selectedPatient.nameOfSpouse }}</li>
            <li><strong>Emergency Contact Number:</strong> {{ selectedPatient.emergencyContact }}</li>
            <li><strong>4ps?::</strong> {{ selectedPatient.fourMember }}</li>
            <li><strong>Philhealth Status:</strong> {{ selectedPatient.philhealthStatus }}</li>
            <li><strong>Philhealth ID Number:</strong> {{ selectedPatient.philhealthNo }}</li>
            <li><strong>Menarche:</strong> {{ selectedPatient.menarche }}</li>
            <li><strong>Onset of Sexual Intercourse:</strong> {{ selectedPatient.sexualOnset }}</li>
            <li><strong>Period/Duration:</strong> {{ selectedPatient.periodDuration }}</li>
            <li><strong>Birth Control Method:</strong> {{ selectedPatient.birthControl }}</li>
          </div>
          <div>
            <li><strong>Interval/Cycle:</strong> {{ selectedPatient.intervalCycle }}</li>
            <li><strong>Menopause? (Yes/No):</strong> {{ selectedPatient.menopause }}</li>
            <li><strong>LMP (Last Menstrual Period):</strong> {{ selectedPatient.lmp }}</li>
            <li><strong>EDC (Estimated Date of Confinement):</strong> {{ selectedPatient.edc }}</li>
            <li><strong>Gravidity:</strong> {{ selectedPatient.gravidity }}</li>
            <li><strong>Parity:</strong> {{ selectedPatient.parity }}</li>
            <li><strong>Term:</strong> {{ selectedPatient.term }}</li>
            <li><strong>Preterm:</strong> {{ selectedPatient.preterm }}</li>
            <li><strong>Abortion:</strong> {{ selectedPatient.abortion }}</li>
            <li><strong>Living:</strong> {{ selectedPatient.living }}</li>
            <li><strong>Syphilis Test Result:</strong> {{ selectedPatient.syphilisResult }}</li>
            <li><strong>Penicillin Given:</strong> {{ selectedPatient.penicillin }}</li>
            <li><strong>Hemoglobin (gm/100ml):</strong> {{ selectedPatient.hemoglobin }}</li>
            <li><strong>Hematocrit (vol %):</strong> {{ selectedPatient.hematocrit }}</li>
            <li><strong>Urinalysis:</strong> {{ selectedPatient.urinalysis }}</li>
            <li><strong>TT Status:</strong> {{ selectedPatient.ttStatus }}</li>
            <li><strong>TD (Date Given):</strong> {{ selectedPatient.tdDate }}</li>
          </div>
        </ul>

      </div>
    </div>

    <TrimesterModal
      v-if="currentModal === 'trimester'"
      :key="selectedPatient?.prenatalId"
      :prenatalId="selectedPatient?.prenatalId"
      :prefilledData="prefilledData"
      @close="closeModal"
      :onConfirm="handleTrimesterConfirm"
    />

<PostpartumModal
  v-if="currentModal === 'postpartum'"
  :patient="selectedPatient"
  @close="closeModal"
  :onSubmit="handlePostpartumSubmit"
/>
</template>

<script>
import PostpartumModal from "./PostpartumForm.vue";
import TrimesterModal from "@/Components/TrimesterModal.vue";
import { Inertia } from '@inertiajs/inertia';


export default {
  components: {
    TrimesterModal,
    PostpartumModal,
  },
  props: {
    patients: {
      type: Array,
      default: () => [],
    },
    prefilledData: Object,
    prenatalId: Number,
    trimester: String,
  },
  data() {
    return {
      isModalOpen: false, // Initialize modal visibility state
      searchQuery: '', // Search query for filtering
      filterPrk: '', // Filter by purok
      filterBarangay: '', // Filter by barangay
      filterGender: [], // Array for selected genders
      filterAgeRange: '', // Filter by age range
      filterDiagnosis: [], // Array for selected diagnoses
      currentPage: 1, // Current page for pagination
      itemsPerPage: 5, // Number of items per page
      currentModal: null, // Stores the type of modal ('trimester', 'postpartum')
      selectedPatient: null, // Selected patient for modal display
      isFilterPanelOpen: false, // Toggle filter panel visibility
      // prefilledData: {}, // Store trimester data
      // prenatalId: null, // Store the current prenatal ID
    };
  },
  computed: {
    filteredPatients() {
  const query = this.searchQuery.toLowerCase();
  return this.patients
    .map((patient) => ({
      ...patient,
      fullName: `${patient.firstName} ${patient.lastName}`, // Combine first and last name
      address: `${patient.purok}, ${patient.barangay}`, // Combine purok and barangay for address
    }))
    .filter((patient) => {
      const matchesQuery =
        patient.fullName.toLowerCase().includes(query) ||
        (patient.natureOfVisit && patient.natureOfVisit.toLowerCase().includes(query)) ||
        (patient.visitType && patient.visitType.toLowerCase().includes(query)) ||
        patient.address.toLowerCase().includes(query);

      const matchesPrk = !this.filterPrk || patient.purok === this.filterPrk;
      const matchesBarangay = !this.filterBarangay || patient.barangay === this.filterBarangay;
      const matchesAgeRange = this.filterAgeRange
        ? parseInt(patient.age) >= parseInt(this.filterAgeRange)
        : true;
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
    .slice(
      (this.currentPage - 1) * this.itemsPerPage,
      this.currentPage * this.itemsPerPage
    );
},

    totalPages() {
      return Math.ceil(this.patients.length / this.itemsPerPage);
    },
    purokOptions() {
      return [...new Set(this.patients.map((patient) => patient.purok))];
    },
    barangayOptions() {
      return [...new Set(this.patients.map((patient) => patient.barangay))];
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
      Inertia.post('/services/patients/prenatal-postpartum', formData, {
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
    openModal(type, patient) {
      console.log("Opening modal with patient:", patient);
    console.log("Patient ID (prenatalId):", patient.prenatalId);
      this.selectedPatient = patient;
      this.currentModal = type; // 'trimester' or 'postpartum'
      console.log("Selected patient:", this.selectedPatient);
    },
    closeModal() {
      this.currentModal = null;
      this.selectedPatient = null;
    },
    handlePostpartumSubmit(formData) {
      console.log("Submitted Postpartum Data:", formData);
      this.closeModal();
    },
    handleTrimesterConfirm() {
      console.log("Trimester confirmed for:", this.selectedPatient);
      this.closeModal();
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
        contact: patient.contact,
      }));

      const csvContent =
        'data:text/csv;charset=utf-8,' +
        ['Full Name,Address,Age,Contact']
          .concat(
            data.map((row) =>
              `${row.fullName},${row.address},${row.age},${row.contact}`
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



<style scoped>
/* Add any necessary styles */
</style>
