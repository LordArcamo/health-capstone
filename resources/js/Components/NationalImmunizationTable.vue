<template>
  <div class="container mx-auto py-8 px-4">
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

    <!-- Responsive Table Wrapper with Background and Padding -->
     <!-- Responsive Table -->
     <div class="overflow-x-auto bg-gray-100 rounded-lg">
      <table class="min-w-full table-auto bg-white shadow-sm rounded-lg">
        <thead>
          <tr class="bg-gradient-to-r from-green-500 via-green-500 to-yellow-500 text-white uppercase text-sm font-bold">
            <th class="py-4 px-6 text-left tracking-wider border-b border-indigo-200">First Name</th>
            <th class="py-4 px-6 text-left tracking-wider border-b border-indigo-200">Last Name</th>
            <th class="py-4 px-6 text-left tracking-wider border-b border-indigo-200">Middle Name</th>
            <th class="py-4 px-6 text-left tracking-wider border-b border-indigo-200">Suffix</th>
            <th class="py-4 px-6 text-left tracking-wider border-b border-indigo-200">Purok</th>
            <th class="py-4 px-6 text-left tracking-wider border-b border-indigo-200">Barangay</th>
            <th class="py-4 px-6 text-left tracking-wider border-b border-indigo-200">Age</th>
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
            <td class="py-3 px-6">{{ patient.purok }}</td>
            <td class="py-3 px-6">{{ patient.barangay }}</td>
            <td class="py-3 px-6">{{ patient.age }}</td>
            <td class="py-3 px-6">{{ patient.contact }}</td>
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
      default: () => [] // Default to empty array to prevent errors
    }
  },
  data() {
    return {
      searchQuery: '',
      filterPrk: '',
      filterBarangay: '',
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
  }
}
</script>

<style scoped>
/* Add any necessary styling here */
</style>
