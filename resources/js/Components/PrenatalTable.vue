<template>
  <div class="mx-auto py-8 px-10 bg-gradient-to-br from-pink-50 to-pink-200 min-h-screen">
    <!-- Header Section -->
    <div class="mb-6 text-center">
      <h1 class="text-3xl font-bold text-pink-600">Prenatal and Postpartum Records</h1>
      <p class="text-gray-700">Search, filter, and manage patient records efficiently.</p>
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
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
      <!-- Search Bar -->
      <div class="w-full md:w-2/3 flex flex-col gap-4">
        <!-- Enhanced Input Field with Clean Design -->
        <div class="relative">
          <input v-model="searchQuery" @keyup.enter="addFilter" type="text"
            placeholder="🔍 Search by name, and address..."
            class="w-full px-5 py-3 border border-gray-300 rounded-md shadow-sm bg-white focus:outline-none focus:ring-2 focus:ring-pink-400 transition duration-200 placeholder-gray-400" />
          <button @click="addFilter"
            class="absolute right-2 top-1/2 transform -translate-y-1/2 px-4 py-2 bg-pink-500 text-white text-sm font-semibold rounded-md hover:bg-pink-600 focus:outline-none transition duration-200">
            Add
          </button>
        </div>

        <!-- Dynamic Filter Tags with Soft Gradient -->
        <div v-if="filters.length" class="flex flex-wrap gap-2">
          <div v-for="(filter, index) in filters" :key="index"
            class="flex items-center gap-2 bg-gradient-to-r from-pink-300 to-pink-400 text-white px-4 py-1 rounded-md shadow hover:shadow-md transition duration-300">
            <span class="text-sm font-medium">{{ filter }}</span>
            <button @click="removeFilter(index)"
              class="flex items-center justify-center w-5 h-5 bg-white text-pink-500 rounded-md hover:bg-red-500 hover:text-white hover:scale-110 transition">
              &times;
            </button>
          </div>
        </div>

        <!-- Helper Text -->
        <p v-if="!filters.length" class="text-gray-500 text-sm italic">
          Type your search and press <strong>Enter</strong> or click <strong>Add</strong> to apply filters.
        </p>
      </div>


      <div class="flex items-center gap-4 justify-center mb-10">

        <!-- Buttons -->
        <!-- Filter Panel Toggle -->
        <button @click="toggleFilterPanel"
          class="flex items-center px-4 py-3 bg-pink-500 text-white font-medium rounded-lg shadow hover:bg-pink-600 focus:outline-none">
          <span>Filters</span>
          <svg class="w-4 h-4 ml-2 transform" :class="{ 'rotate-180': isFilterPanelOpen }"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>
        <button @click="generateReport"
          class="px-6 py-3 bg-pink-500 text-white font-semibold rounded-lg shadow hover:bg-pink-600 focus:outline-none transition">
          Generate Report
        </button>
        <button @click="triggerImport"
          class="px-6 py-3 bg-purple-500 text-white font-semibold rounded-lg shadow hover:bg-purple-600 focus:outline-none transition flex items-center gap-2">
          Import CSV
        </button>
        <input type="file" ref="fileInput" accept=".csv" @change="handleFileUpload" class="hidden" />
      </div>
    </div>

    <!-- Collapsible Filter Panel -->
    <transition name="fade">
      <div v-if="isFilterPanelOpen" class="mt-4 mb-4 border border-gray-300 rounded-lg p-6 shadow-md bg-white">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <!-- Age Range Slider -->
          <div>
            <label class="block text-base font-semibold mb-2">Age Range</label>
            <div class="flex items-center gap-4">
              <span class="text-sm text-gray-500">0</span>
              <input type="range" v-model="filterAgeRange" min="8" max="100" step="5" class="w-full accent-pink-500" />
              <span class="text-sm text-gray-500">100+</span>
            </div>
            <div class="text-sm text-gray-700 mt-1">Selected: {{ filterAgeRange }}+</div>
          </div>

          <!-- Purok Filter -->
          <div>
            <label class="block text-base font-semibold mb-2">Purok</label>
            <select v-model="filterPrk"
              class="border border-gray-300 p-3 rounded-lg w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-400">
              <option value="">All Purok</option>
              <option v-for="purok in purokOptions" :key="purok" :value="purok">{{ purok }}</option>
            </select>
          </div>

          <!-- Barangay Filter -->
          <div>
            <label class="block text-base font-semibold mb-2">Barangay</label>
            <select v-model="filterBarangay"
              class="border border-gray-300 p-3 rounded-lg w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-400">
              <option value="">All Barangay</option>
              <option v-for="barangay in barangayOptions" :key="barangay" :value="barangay">
                {{ barangay }}
              </option>
            </select>
          </div>
        </div>
      </div>
    </transition>

    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
      <table class="min-w-full bg-white">
        <thead>
          <tr class="bg-gradient-to-r from-pink-500 to-purple-500 text-white uppercase text-sm font-bold">
            <th class="py-4 px-6 text-left">Full Name</th>
            <th class="py-4 px-6 text-left">Address</th>
            <th class="py-4 px-6 text-left">Age</th>
            <th class="py-4 px-6 text-left">Emergency Contact Number</th>
            <th class="py-4 px-6 text-left">Consultation Date</th>
            <th class="py-4 px-6 text-left">Status</th>
            <th class="py-4 px-6 text-left">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 text-gray-700">
          <tr v-for="patient in filteredPatients" :key="patient.id"
            class="hover:bg-gray-50 transition-colors cursor-pointer" @click="openModal('inline', patient)">
            <td class="py-3 px-6">{{ patient.fullName }}</td>
            <td class="py-3 px-6">{{ patient.address }}</td>
            <td class="py-3 px-6">{{ patient.age }}</td>
            <td class="py-3 px-6">{{ patient.emergencyContact }}</td>
            <td class="py-3 px-6">{{ patient.consultationDate }}</td>
            <td class="text-base py-3 px-6">
              <span :class="{
                'bg-green-100 text-green-800': patient.status === 'Completed',
                'bg-yellow-100 text-yellow-800': patient.status === 'In Queue',
                'bg-red-100 text-red-800': patient.status === 'Cancelled',
                'bg-orange-100 text-orange-800': patient.status === 'Follow-up Required',
                'bg-gray-100 text-gray-800': !patient.status || !['Completed', 'In Queued', 'Cancelled', 'Follow-up Required'].includes(patient.status)
              }" class="px-3 py-1 rounded-full text-sm font-semibold shadow-sm capitalize">
                {{ patient.status || 'In Queue' }}
              </span>
            </td>
            <td class="py-3 px-6">
              <div class="flex gap-2">
                <button @click.stop="openModal('trimester', patient)"
                  class="px-4 py-2 bg-purple-500 text-white rounded-md hover:bg-purple-600 transition">
                  Trimester
                </button>
                <button @click.stop="openModal('postpartum', patient)"
                  class="px-4 py-2 bg-pink-500 text-white rounded-md hover:bg-pink-600 transition">
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
      <button @click="prevPage" :disabled="currentPage === 1"
        class="px-4 py-2 bg-pink-500 text-white font-semibold rounded-lg shadow hover:bg-pink-600 focus:outline-none transition disabled:opacity-50">
        Previous
      </button>
      <span class="text-gray-700">Page {{ currentPage }} of {{ totalPages }}</span>
      <button @click="nextPage" :disabled="currentPage === totalPages"
        class="px-4 py-2 bg-purple-500 text-white font-semibold rounded-lg shadow hover:bg-purple-600 focus:outline-none transition disabled:opacity-50">
        Next
      </button>
    </div>
  </div>

  <!-- Modal -->
  <div v-if="currentModal && selectedPatient"
    class="fixed inset-0 bg-pink-100 bg-opacity-80 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl h-[90vh] p-8 relative overflow-hidden">
      <!-- Close Button -->
      <button @click="closeModal"
        class="absolute top-4 right-4 bg-pink-600 text-white rounded-full w-10 h-10 flex items-center justify-center hover:bg-pink-700 transition">
        &times;
      </button>

      <!-- Scrollable Content -->
      <div class="overflow-y-auto h-full pr-4">
        <!-- Header Section -->
        <div class="border-b pb-4 mb-6 flex items-center gap-4">
          <div class="bg-pink-200 text-pink-600 rounded-full p-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
          </div>
          <div>
            <h2 class="text-2xl font-bold text-pink-600">Prenatal Checkup Details</h2>
            <p class="text-gray-600">Details for {{ selectedPatient.fullName }}</p>
          </div>
        </div>

        <!-- Patient Status Section -->
        <div class="mb-6">
          <h3 class="text-lg font-semibold text-gray-700">Patient Status:</h3>
          <span :class="statusBadgeClass(selectedPatient.status)"
            class="inline-block px-3 py-1 text-sm font-medium rounded-full">
            {{ selectedPatient.status || 'In Queue' }}
          </span>
        </div>

        <!-- Patient Details Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <!-- Column 1 -->
          <div>
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Basic Information</h3>
            <ul class="space-y-2">
              <li><strong>Full Name:</strong> {{ selectedPatient.firstName }} {{ selectedPatient.middleName || '' }} {{
                selectedPatient.lastName }}</li>
              <li><strong>Address:</strong> {{ selectedPatient.purok }}, {{ selectedPatient.barangay }}</li>
              <li><strong>Age:</strong> {{ selectedPatient.age }}</li>
              <li><strong>Birthday:</strong> {{ selectedPatient.birthdate }}</li>
              <li><strong>Mode of Transaction:</strong> {{ selectedPatient.modeOfTransaction }}</li>
              <li><strong>Date of Consultation:</strong> {{ selectedPatient.consultationDate }}</li>
              <li><strong>Time of Consultation:</strong> {{ selectedPatient.consultationTime }}</li>
            </ul>
          </div>

          <!-- Column 2 -->
          <div>
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Health Details</h3>
            <ul class="space-y-2">
              <li><strong>Blood Pressure:</strong> {{ selectedPatient.bloodPressure }}</li>
              <li><strong>Temperature:</strong> {{ selectedPatient.temperature }}</li>
              <li><strong>Height:</strong> {{ selectedPatient.height }}</li>
              <li><strong>Weight:</strong> {{ selectedPatient.weight }}</li>
              <li><strong>Name of Attending Provider:</strong> {{ selectedPatient.providerName }}</li>
              <li><strong>Name of Spouse:</strong> {{ selectedPatient.nameOfSpouse }}</li>
              <li><strong>Emergency Contact Number:</strong> {{ selectedPatient.emergencyContact }}</li>
            </ul>
          </div>
        </div>

        <!-- Additional Details -->
        <div class="mt-8">
          <h3 class="text-lg font-semibold text-gray-700 mb-4">Reproductive History</h3>
          <ul class="grid grid-cols-1 md:grid-cols-2 gap-2">
            <li><strong>4Ps Member:</strong> {{ selectedPatient.fourMember }}</li>
            <li><strong>Philhealth Status:</strong> {{ selectedPatient.philhealthStatus }}</li>
            <li><strong>Philhealth ID Number:</strong> {{ selectedPatient.philhealthNo }}</li>
            <li><strong>Menarche:</strong> {{ selectedPatient.menarche }}</li>
            <li><strong>Onset of Sexual Intercourse:</strong> {{ selectedPatient.sexualOnset }}</li>
            <li><strong>Period/Duration:</strong> {{ selectedPatient.periodDuration }}</li>
            <li><strong>Birth Control Method:</strong> {{ selectedPatient.birthControl }}</li>
            <li><strong>Interval/Cycle:</strong> {{ selectedPatient.intervalCycle }}</li>
            <li><strong>Menopause:</strong> {{ selectedPatient.menopause }}</li>
            <li><strong>LMP (Last Menstrual Period):</strong> {{ selectedPatient.lmp }}</li>
            <li><strong>EDC (Estimated Date of Confinement):</strong> {{ selectedPatient.edc }}</li>
            <li><strong>Gravidity:</strong> {{ selectedPatient.gravidity }}</li>
            <li><strong>Parity:</strong> {{ selectedPatient.parity }}</li>
            <li><strong>Term:</strong> {{ selectedPatient.term }}</li>
            <li><strong>Preterm:</strong> {{ selectedPatient.preterm }}</li>
            <li><strong>Abortion:</strong> {{ selectedPatient.abortion }}</li>
            <li><strong>Living:</strong> {{ selectedPatient.living }}</li>
          </ul>
        </div>

        <!-- Footer Actions -->
        <div class="mt-6 flex justify-end gap-4">
          <button @click="closeModal"
            class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
            Close
          </button>
          <!-- Edit Button -->
          <!-- <button @click="editPatient(selectedPatient)"
            class="px-6 py-2 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600 transition">
            Edit Record
          </button> -->
          <button @click="printRecord(selectedPatient)"
            class="px-6 py-2 bg-pink-600 text-white rounded-lg shadow-md hover:bg-pink-700 transition">
            Print Record
          </button>
        </div>
      </div>
    </div>
  </div>



  <TrimesterModal v-if="currentModal === 'trimester'" :key="selectedPatient?.prenatalConsultationDetailsID"
    :prenatalConsultationDetailsID="selectedPatient?.prenatalConsultationDetailsID" :prefilledData="localTrimesterData"
    @close="closeModal" :onConfirm="handleTrimesterConfirm" />

  <PostpartumModal v-if="currentModal === 'postpartum'" :patient="selectedPatient" :existingData="postpartumData"
    @close="closeModal" :onSubmit="handlePostpartumSubmit" />
</template>

<script>
import PostpartumModal from "./PostpartumForm.vue";
import TrimesterModal from "@/Components/TrimesterModal.vue";
import { Inertia } from '@inertiajs/inertia';
import axios from 'axios';
import jsPDF from 'jspdf';
import 'jspdf-autotable';


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
    prefilledData: {
      type: Object,
      default: null
    },
    prenatalConsultationDetailsID: Number,
    trimester: String,
  },

  data() {
    return {
      isModalOpen: false,
      searchQuery: '',
      filters: [],
      filterPrk: '',
      filterBarangay: '',
      filterGender: [],
      filterAgeRange: '',
      filterDiagnosis: [],
      filterDate: '',
      currentPage: 1,
      itemsPerPage: 5,
      currentModal: null,
      selectedPatient: null,
      isFilterPanelOpen: false,
      localTrimesterData: null,
      postpartumData: null,
      currentDateText: '',
    };
  },

  computed: {
    filteredPatients() {
      const queryFilters = this.filters.map(f => f.toLowerCase());

      return this.patients
        .map((patient) => ({
          ...patient,
          fullName: `${patient.firstName} ${patient.lastName}`,
          address: `${patient.purok}, ${patient.barangay}`,
        }))
        .filter((patient) => {
          const matchesFilters = queryFilters.every(filter =>
            patient.fullName.toLowerCase().includes(filter) ||
            (patient.natureOfVisit && patient.natureOfVisit.toLowerCase().includes(filter)) ||
            (patient.visitType && patient.visitType.toLowerCase().includes(filter)) ||
            patient.address.toLowerCase().includes(filter) ||
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
        .slice(
          (this.currentPage - 1) * this.itemsPerPage,
          this.currentPage * this.itemsPerPage
        )
        .sort((a, b) => new Date(b.consultationDate) - new Date(a.consultationDate));
    },

    totalPages() {
      return Math.ceil(this.filteredPatients.length / this.itemsPerPage);
    },

    purokOptions() {
      return [...new Set(this.patients.map((patient) => patient.purok))];
    },

    barangayOptions() {
      return [...new Set(this.patients.map((patient) => patient.barangay))];
    },
  },

  mounted() {
    const today = new Date();
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    this.currentDateText = today.toLocaleDateString(undefined, options);

    // 🚀 Add ESC key listener for modal close
    window.addEventListener('keydown', this.handleEscKey);
    // 🚀 Add click listener for clicking outside modal to close
    window.addEventListener('click', this.handleClickOutside);
  },

  beforeDestroy() {
    window.removeEventListener('keydown', this.handleEscKey);
    window.removeEventListener('click', this.handleClickOutside);
  },

  methods: {
    handlePostpartumSubmit(data) {
      console.log("Postpartum data submitted:", data);
      // Process the submitted data, for example, send it to an API
      this.closeModal(); // Close the modal after submission
    },
    // Open Edit Page
    editPatient(patient) {
      Inertia.get(`/patients/edit/${patient.personalId}`);
    },
    printRecord(patient) {
      const doc = new jsPDF('portrait', 'mm', 'a4');
      const dateGenerated = new Date().toLocaleDateString();
      const logoUrl = "/images/RHU%20Logo.png"; // Ensure this path is correct

      const img = new Image();
      img.src = logoUrl;

      img.onload = function () {
        // RHU Logo
        doc.addImage(img, 'PNG', 10, 10, 25, 25);

        // Header
        doc.setFont('Helvetica', 'bold');
        doc.setFontSize(16);
        doc.setTextColor(0, 102, 0);
        doc.text('Republic of the Philippines', 40, 15);
        doc.text('Department of Health', 40, 23);
        doc.text('Initao Rural Health Unit', 40, 31);
        doc.setFontSize(10);
        doc.setTextColor(50);
        doc.text(`Generated on: ${dateGenerated}`, 150, 15);

        // Title
        doc.setFontSize(14);
        doc.setTextColor(0, 0, 0);
        doc.text('Prenatal and Health Record', 105, 50, { align: 'center' });

        // Basic Information
        doc.setFontSize(12);
        doc.setTextColor(0, 102, 0);
        doc.text('Basic Information', 14, 60);
        doc.setTextColor(0);

        const basicInfo = [
          ['Full Name:', `${patient.firstName} ${patient.middleName || ''} ${patient.lastName}`],
          ['Address:', `${patient.purok}, ${patient.barangay}`],
          ['Age:', patient.age],
          ['Birthday:', patient.birthdate],
          ['Mode of Transaction:', patient.modeOfTransaction],
          ['Consultation Date:', patient.consultationDate],
          ['Consultation Time:', patient.consultationTime],
        ];

        doc.autoTable({
          startY: 65,
          body: basicInfo,
          theme: 'plain',
          styles: { fontSize: 10, textColor: 50 },
          columnStyles: {
            0: { fontStyle: 'bold', halign: 'left', cellWidth: 50 },
            1: { halign: 'left' }
          }
        });

        // Health Details
        doc.setFontSize(12);
        doc.setTextColor(0, 102, 0);
        doc.text('Health Details', 14, doc.lastAutoTable.finalY + 10);
        doc.setTextColor(0);

        const healthInfo = [
          ['Blood Pressure:', patient.bloodPressure],
          ['Temperature:', patient.temperature],
          ['Height:', patient.height],
          ['Weight:', patient.weight],
          ['Attending Provider:', patient.providerName],
          ['Name of Spouse:', patient.nameOfSpouse],
          ['Emergency Contact:', patient.emergencyContact],
        ];

        doc.autoTable({
          startY: doc.lastAutoTable.finalY + 15,
          body: healthInfo,
          theme: 'plain',
          styles: { fontSize: 10, textColor: 50 },
          columnStyles: {
            0: { fontStyle: 'bold', halign: 'left', cellWidth: 50 },
            1: { halign: 'left' }
          }
        });

        // Reproductive History
        doc.setFontSize(12);
        doc.setTextColor(0, 102, 0);
        doc.text('Reproductive History', 14, doc.lastAutoTable.finalY + 10);
        doc.setTextColor(0);

        const reproductiveInfo = [
          ['4Ps Member:', patient.fourMember],
          ['Philhealth Status:', patient.philhealthStatus],
          ['Philhealth ID Number:', patient.philhealthNo],
          ['Menarche:', patient.menarche],
          ['Onset of Sexual Intercourse:', patient.sexualOnset],
          ['Period/Duration:', patient.periodDuration],
          ['Birth Control Method:', patient.birthControl],
          ['Interval/Cycle:', patient.intervalCycle],
          ['Menopause:', patient.menopause],
          ['LMP:', patient.lmp],
          ['EDC:', patient.edc],
          ['Gravidity:', patient.gravidity],
          ['Parity:', patient.parity],
          ['Term:', patient.term],
          ['Preterm:', patient.preterm],
          ['Abortion:', patient.abortion],
          ['Living:', patient.living],
        ];

        doc.autoTable({
          startY: doc.lastAutoTable.finalY + 15,
          body: reproductiveInfo,
          theme: 'plain',
          styles: { fontSize: 10, textColor: 50 },
          columnStyles: {
            0: { fontStyle: 'bold', halign: 'left', cellWidth: 70 },
            1: { halign: 'left' }
          }
        });

        // Footer with Two Columns for Signatures
        doc.setFontSize(10);
        const finalY = doc.lastAutoTable.finalY + 30;

        doc.line(20, finalY, 80, finalY);  // Provider Signature Line
        doc.line(130, finalY, 190, finalY);  // Patient/Guardian Signature Line

        doc.text('Attending Physician Signature', 20, finalY + 5);
        doc.text('Patient/Guardian Signature', 130, finalY + 5);

        // Save the PDF
        doc.save(`Prenatal_Record_${patient.firstName}_${patient.lastName}.pdf`);
      };

      img.onerror = function () {
        alert('Failed to load the RHU logo. Please check the image path.');
      };
    },
    // Status Badge Styling
    statusBadgeClass(status) {
      switch (status) {
        case 'Completed':
          return 'bg-green-100 text-green-800';
        case 'In Progress':
          return 'bg-yellow-100 text-yellow-800';
        case 'In Queue':
          return 'bg-gray-100 text-gray-800';
        case 'Cancelled':
          return 'bg-red-100 text-red-800';
        case 'Follow-up Required':
          return 'bg-orange-100 text-orange-800';
        default:
          return 'bg-gray-100 text-gray-800';
      }
    },
    // ✅ Add Filter Tags
    addFilter() {
      const words = this.searchQuery.trim().split(/\s+/);
      words.forEach((word) => {
        const cleanWord = word.toLowerCase();
        if (cleanWord && !this.filters.includes(cleanWord)) {
          this.filters.push(cleanWord);
        }
      });
      this.searchQuery = '';
    },

    // ✅ Remove Filter Tags
    removeFilter(index) {
      this.filters.splice(index, 1);
    },

    // ✅ Date Comparison
    sameDay(dateA, dateB) {
      const dA = new Date(dateA);
      const dB = new Date(dateB);
      return dA.getFullYear() === dB.getFullYear() &&
        dA.getMonth() === dB.getMonth() &&
        dA.getDate() === dB.getDate();
    },

    // ✅ Open Modal
    async openModal(type, patient) {
      this.isModalOpen = true;
      this.currentModal = type;
      this.selectedPatient = patient;

      if (type === 'trimester') {
        await this.fetchTrimesterData(patient.prenatalConsultationDetailsID);
      } else if (type === 'postpartum') {
        await this.fetchPostpartumData(patient.prenatalConsultationDetailsID);
      }
    },

    // ✅ Close Modal
    closeModal() {
      this.isModalOpen = false;
      this.currentModal = null;
      this.selectedPatient = null;
      this.localTrimesterData = null;
      this.postpartumData = null;
    },

    // ✅ ESC Key to Close Modal
    handleEscKey(event) {
      if (event.key === 'Escape' && this.isModalOpen) {
        this.closeModal();
      }
    },

    // ✅ Click Outside to Close Modal
    handleClickOutside(event) {
      const modal = document.querySelector(".modal-content"); // Changed this.$el to document
      if (this.isModalOpen && modal && !modal.contains(event.target)) {
        this.closeModal();
      }
    },

    async fetchPostpartumData(prenatalConsultationDetailsID) {
      try {
        const response = await axios.get(`/postpartum/data/${prenatalConsultationDetailsID}`);
        if (response.data.success) {
          this.postpartumData = response.data.data;
        } else {
          this.postpartumData = null;
        }
      } catch (error) {
        console.error('Error fetching postpartum data:', error);
        this.postpartumData = null;
      }
    },
    async fetchTrimesterData(prenatalConsultationDetailsID) {
      try {
        const response = await axios.get(`/prenatal/${prenatalConsultationDetailsID}/trimester/{trimester}`);
        if (response.data.success) {
          this.localTrimesterData = response.data.data;
        } else {
          this.localTrimesterData = null;
        }
      } catch (error) {
        console.error('Error fetching trimester data:', error);
        this.localTrimesterData = null;
      }
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
      const doc = new jsPDF('landscape');
      const date = new Date().toLocaleDateString();

      // Header: RHU Logo and Title
      const logoUrl = "/images/RHU%20Logo.png"; // Ensure the path is correct

      const img = new Image();
      img.src = logoUrl;

      img.onload = () => {
        // Add RHU Logo
        doc.addImage(img, 'PNG', 10, 10, 25, 25);

        // Add Header
        doc.setFontSize(16);
        doc.setTextColor(220, 20, 60); // Pink Color
        doc.text('Republic of the Philippines', 40, 15);
        doc.setFontSize(14);
        doc.text('Department of Health', 40, 22);
        doc.setFontSize(14);
        doc.text('Initao Rural Health Unit', 40, 29);

        doc.setFontSize(10);
        doc.setTextColor(100);
        doc.text(`Generated on: ${date}`, 250, 15);

        // Title
        doc.setFontSize(18);
        doc.setTextColor(199, 21, 133); // Darker Pink
        doc.text('Prenatal and Postpartum Records', 140, 45, { align: 'center' });

        // Table Columns
        const columns = [
          'Full Name',
          'Address',
          'Age',
          'Emergency Contact',
          'Consultation Date',
          'Status'
        ];

        // Table Rows
        const rows = this.filteredPatients.map((patient) => [
          `${patient.firstName} ${patient.middleName || ''} ${patient.lastName}`,
          `${patient.purok}, ${patient.barangay}`,
          patient.age,
          patient.emergencyContact,
          patient.consultationDate,
          patient.status || 'In Queue',
        ]);

        // Auto Table for Patient Records
        doc.autoTable({
          startY: 50,
          head: [columns],
          body: rows,
          styles: {
            fontSize: 10,
            cellPadding: 3,
            textColor: 50,
            lineColor: [199, 21, 133],
            lineWidth: 0.1,
          },
          headStyles: {
            fillColor: [255, 182, 193], // Light Pink Header
            textColor: 255,
            fontSize: 12,
            halign: 'center',
          },
          alternateRowStyles: {
            fillColor: [255, 240, 245], // Soft Pink Alternate Rows
          },
          margin: { top: 50 },
        });

        // Footer (Two Columns)
        doc.setFontSize(10);
        doc.setTextColor(0);
        doc.text('_____________________________', 20, doc.lastAutoTable.finalY + 20);
        doc.text('_____________________________', 200, doc.lastAutoTable.finalY + 20);
        doc.text('Attending Physician Signature', 20, doc.lastAutoTable.finalY + 25);
        doc.text('Patient/Guardian Signature', 200, doc.lastAutoTable.finalY + 25);

        // Save PDF
        doc.save(`Prenatal_Postpartum_Report_${date}.pdf`);
      };
    },
    handleTrimesterConfirm(data) {
      console.log("Trimester data confirmed:", data);
      this.closeModal();
    },
  },
  mounted() {
    window.addEventListener('keydown', this.handleEscKey);
    window.addEventListener('click', this.handleClickOutside.bind(this)); // Bind this context
  },
  beforeDestroy() {
    window.removeEventListener('keydown', this.handleEscKey);
    window.removeEventListener('click', this.handleClickOutside.bind(this)); // Unbind properly
  },
};
</script>





<style scoped>
/* Add any necessary styles */
</style>
