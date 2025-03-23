<template>
  <div class="mx-auto py-8 px-10 bg-gradient-to-br from-blue-100 to-teal-100 min-h-screen">
    <!-- Header Section -->
    <div class="mb-6 text-center">
      <h1 class="text-4xl font-bold text-teal-600">National Immunization Records</h1>
      <p class="text-gray-700">Search, filter, and manage patient immunization records effectively.</p>
    </div>
    <!-- In your template (e.g. at the top, above the search bar) -->
    <div class="flex flex-col md:flex-row md:items-center gap-10 justify-center mb-6">
      <div class="flex items-center gap-4">
        <span class="font-semibold text-gray-700">Current Date:</span>
        <span class="text-gray-900">{{ currentDateText }}</span>
      </div>
      <div class="flex items-center gap-4">
        <label for="startDate" class="font-semibold text-gray-700">Start Date:</label>
        <input type="date" id="startDate" v-model="startDate"
          class="border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400" />

        <label for="endDate" class="font-semibold text-gray-700">End Date:</label>
        <input type="date" id="endDate" v-model="endDate"
          class="border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400" />
      </div>
    </div>
    <!-- Search and Filter Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
      <!-- Search Bar-->
      <div class="w-full md:w-2/3 flex flex-col gap-4">

        <!-- Search Input with Add Button -->
        <div class="relative">
          <input v-model="searchQuery" @keyup.enter="addFilter" type="text"
            placeholder="🔍 Search by name, diagnosis, or address..."
            class="w-full px-5 py-3 border border-gray-300 rounded-md shadow-sm bg-white focus:outline-none focus:ring-2 focus:ring-teal-400 transition duration-200 placeholder-gray-400" />

          <button @click="addFilter"
            class="absolute right-2 top-1/2 transform -translate-y-1/2 px-4 py-2 bg-teal-400 text-white text-sm font-semibold rounded-md hover:bg-teal-500 focus:outline-none transition duration-200">
            Add
          </button>
        </div>

        <!-- Dynamic Filter Tags with Soft Gradient -->
        <div v-if="filters.length" class="flex flex-wrap gap-2">
          <div v-for="(filter, index) in filters" :key="index"
            class="flex items-center gap-2 bg-gradient-to-r from-teal-300 to-teal-400 text-white px-4 py-1 rounded-md shadow hover:shadow-md transition duration-300">
            <span class="text-sm font-medium">{{ filter }}</span>
            <button @click="removeFilter(index)"
              class="flex items-center justify-center w-5 h-5 bg-white text-teal-500 rounded-md hover:bg-red-500 hover:text-white hover:scale-110 transition">
              &times;
            </button>
          </div>
        </div>

        <!-- Helper Text -->
        <p v-if="!filters.length" class="text-gray-500 text-sm italic">
          Type your search and press <strong>Enter</strong> or click <strong>Add</strong> to apply filters.
        </p>
      </div>

      <div class="flex items-center gap-3 mb-10">
        <!-- Filter Panel Toggle -->
        <button @click="toggleFilterPanel"
          class="flex items-center px-4 py-3 bg-teal-500 text-white font-medium rounded-lg shadow hover:bg-teal-600 focus:outline-none">
          <span>Filters</span>
          <svg class="w-4 h-4 ml-2 transform" :class="{ 'rotate-180': isFilterPanelOpen }"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>
        <!-- Buttons -->
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
              <input type="range" v-model="filterAgeRange" min="0" max="10" step="5" class="w-full accent-teal-500" />
              <span class="text-sm text-gray-500">10+</span>
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
  <div v-if="showModal && selectedPatient"
    class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl p-8 relative">
      <!-- Close Button -->
      <button @click="closeModal"
        class="absolute top-4 right-4 bg-red-600 text-white rounded-full w-10 h-10 flex items-center justify-center hover:bg-red-700 transition">
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
          <p class="text-gray-600">Comprehensive information about {{ selectedPatient.firstName }} {{
            selectedPatient.middleName }} {{ selectedPatient.lastName }}</p>
        </div>
      </div>

      <!-- Patient Details Section -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Column 1 -->
        <div>
          <h3 class="text-lg font-semibold text-gray-700 mb-4">Basic Information</h3>
          <ul class="space-y-2">
            <li><strong>Full Name:</strong> {{ selectedPatient.firstName }} {{ selectedPatient.middleName }} {{
              selectedPatient.lastName }}</li>
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
          <button @click="closeModal"
            class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
            Close
          </button>
          <button @click="printRecord(selectedPatient)"
            class="px-6 py-2 bg-blue-500 text-white font-semibold rounded-lg shadow hover:bg-blue-600 focus:outline-none transition">
            Print Record
          </button>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';
import jsPDF from 'jspdf';
import 'jspdf-autotable';

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
      filters: [],  // Dynamic filter tags
      filterPrk: '',
      filterBarangay: '',
      filterGender: [],
      filterAgeRange: '',
      filterDiagnosis: [],
      currentPage: 1,
      itemsPerPage: 10,
      showModal: false,
      selectedPatient: null,
      isFilterPanelOpen: false,
      startDate: '',
      endDate: '',
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
            (patient.diagnosis && patient.diagnosis.toLowerCase().includes(filter)) ||
            patient.address.toLowerCase().includes(filter)
          );

          const matchesPrk = !this.filterPrk || patient.purok === this.filterPrk;
          const matchesBarangay = !this.filterBarangay || patient.barangay === this.filterBarangay;

          let matchesAgeRange = true;
          if (this.filterAgeRange) {
            const [minAge, maxAge] = this.filterAgeRange.split('-').map(Number);
            const patientAge = patient.age;
            const effectiveMaxAge = Math.min(maxAge || Infinity, 10);
            matchesAgeRange = (isNaN(minAge) || patientAge >= minAge) &&
              (isNaN(effectiveMaxAge) || patientAge <= effectiveMaxAge);
          }

          const matchesGender = this.filterGender.length === 0 || this.filterGender.includes(patient.sex);

          let matchesDate = true;
          const dateAssesed = new Date(patient.dateAssesed);

          if (this.startDate) {
            const start = new Date(this.startDate);
            matchesDate = dateAssesed >= start;
          }

          if (this.endDate) {
            const end = new Date(this.endDate);
            matchesDate = matchesDate && dateAssesed <= end;
          }

          return (
            matchesFilters &&
            matchesPrk &&
            matchesBarangay &&
            matchesAgeRange &&
            matchesGender &&
            matchesDate
          );
        })
        .slice((this.currentPage - 1) * this.itemsPerPage, this.currentPage * this.itemsPerPage)
        .sort((a, b) => new Date(b.dateAssesed) - new Date(a.dateAssesed));
    },

    totalPages() {
      return Math.ceil(this.filteredPatients.length / this.itemsPerPage);
    },

    purokOptions() {
      return Array.from(new Set(this.patients.map((patient) => patient.purok)));
    },

    barangayOptions() {
      return Array.from(new Set(this.patients.map((patient) => patient.barangay)));
    },
  },

  mounted() {
    const today = new Date();
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    this.currentDateText = today.toLocaleDateString(undefined, options);

    window.addEventListener('keydown', this.handleEscKey);
  },

  beforeUnmount() {
    window.removeEventListener('keydown', this.handleEscKey);
  },

  methods: {
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

    removeFilter(index) {
      this.filters.splice(index, 1);
    },

    formatDate(dateStr) {
      if (!dateStr) return '';
      const dateObj = new Date(dateStr);
      if (isNaN(dateObj)) return dateStr;
      const options = { year: 'numeric', month: 'long', day: '2-digit' };
      return dateObj.toLocaleDateString('en-US', options);
    },

    sameDay(dateA, dateB) {
      const dA = new Date(dateA);
      const dB = new Date(dateB);
      return dA.getFullYear() === dB.getFullYear() &&
        dA.getMonth() === dB.getMonth() &&
        dA.getDate() === dB.getDate();
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
      document.addEventListener('click', this.handleClickOutside);
    },

    closeModal() {
      this.showModal = false;
      this.selectedPatient = null;
      document.removeEventListener('click', this.handleClickOutside);
    },

    handleClickOutside(event) {
      const modal = this.$refs.modal;
      if (modal && !modal.contains(event.target)) {
        this.closeModal();
      }
    },

    handleEscKey(event) {
      if (event.key === "Escape") {
        this.closeModal();
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
      const doc = new jsPDF('portrait', 'mm', 'a4');

      // RHU Logo Path
      const logoUrl = "/images/RHU%20Logo.png"; // Ensure the path is correct

      const dateGenerated = new Date().toLocaleDateString();

      // Load Logo
      const img = new Image();
      img.src = logoUrl;

      img.onload = () => {
        // 🏥 RHU Header
        doc.addImage(img, 'PNG', 14, 10, 30, 30); // Logo Position

        // Header Text
        doc.setFont("helvetica", "bold");
        doc.setFontSize(14);
        doc.text("Republic of the Philippines", 105, 15, { align: "center" });
        doc.text("Department of Health", 105, 22, { align: "center" });
        doc.text("Initao Rural Health Unit", 105, 29, { align: "center" });

        // Subheader
        doc.setFont("helvetica", "normal");
        doc.setFontSize(11);
        doc.text("Poblacion, Initao, Misamis Oriental", 105, 36, { align: "center" });
        doc.text("Contact: +63 918 811 1213 | Email: rhu.initao@gmail.com", 105, 42, { align: "center" });

        // Divider
        doc.setLineWidth(0.5);
        doc.line(14, 48, 196, 48);

        // 📋 Report Title
        doc.setFont("helvetica", "bold");
        doc.setFontSize(16);
        doc.text("National Immunization Records", 105, 58, { align: "center" });

        // 📆 Date Generated
        doc.setFontSize(10);
        doc.text(`Date Generated: ${dateGenerated}`, 14, 66);

        // 📊 Table Columns
        const columns = [
          "Full Name",
          "Address",
          "Age",
          "Gender",
          "Consultation Date",
          "Status"
        ];

        // 📑 Table Rows (Filtered Data)
        const rows = this.filteredPatients.map((patient) => [
          `${patient.firstName} ${patient.middleName || ''} ${patient.lastName}`,
          `${patient.purok}, ${patient.barangay}`,
          patient.age,
          patient.sex,
          this.formatDate(patient.dateAssesed),
          patient.status || "In Queue"
        ]);

        // 📄 Table with AutoTable Plugin
        doc.autoTable({
          head: [columns],
          body: rows,
          startY: 72,
          styles: {
            fontSize: 9,
            cellPadding: 3,
            valign: "middle",
          },
          headStyles: {
            fillColor: [0, 150, 136],  // Teal Header
            textColor: 255,
            fontStyle: "bold",
          },
          alternateRowStyles: {
            fillColor: [240, 240, 240],  // Alternate Row Color
          },
          margin: { top: 70 },
        });

        // 🖊️ Footer - Signature Block
        const finalY = doc.lastAutoTable.finalY + 20;

        doc.setFont("helvetica", "normal");
        doc.setFontSize(11);
        doc.text("Prepared by:", 14, finalY);
        doc.text("__________________________", 14, finalY + 8);
        doc.text("RHU Officer", 14, finalY + 15);

        doc.text("Reviewed by:", 130, finalY);
        doc.text("__________________________", 130, finalY + 8);
        doc.text("Municipal Health Officer", 130, finalY + 15);

        // 📥 Save the PDF
        doc.save(`Immunization_Report_${dateGenerated}.pdf`);
      };

      img.onerror = () => {
        alert("Failed to load the RHU logo. Please check the image path.");
      };
    },

  },
};
</script>
