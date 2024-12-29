<template>
  <div>
    <!-- Search Filter -->
    <div class="flex justify-between items-center mb-6">
      <input v-model="filterName" @input="applyFilters" type="text" placeholder="Search by Name..."
        class="border border-gray-300 rounded-md px-4 py-2 shadow-md w-full sm:w-1/3 focus:outline-none focus:ring-2 focus:ring-orange-500" />
      <button @click="toggleMegaFilter"
        class="bg-orange-600 text-white px-4 py-2 rounded-md shadow-md hover:bg-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-400 flex items-center gap-2">
        Filter Options
        <font-awesome-icon :icon="['fas', megaFilterOpen ? 'caret-up' : 'caret-down']" />
      </button>
    </div>

    <!-- Mega Filter Dropdown -->
    <div class="relative mb-6">
      <!-- Use v-if to conditionally render dropdown to prevent DOM patching issues -->
      <transition name="fade">
        <div v-if="megaFilterOpen"
          class="absolute z-50 bg-white border rounded-md mt-2 w-full max-w-md sm:w-auto shadow-lg p-4"
          style="left: auto; right: 0;">
          <h3 class="text-lg font-semibold text-gray-700 mb-4">Filter Options</h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Filter by Food Intake -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Food Intake</label>
              <select v-model="filterFoodIntake" @change="applyFilters"
                class="border rounded-md px-4 py-2 w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-green-600 text-sm">
                <option value="">All</option>
                <option value="High Salt/Fat">High Salt/Fat</option>
                <option value="High Fiber">High Fiber</option>
                <option value="Moderate Salt/Fat">Moderate Salt/Fat</option>
              </select>
            </div>

            <!-- Filter by Physical Activity -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Physical Activity</label>
              <select v-model="filterPhysicalActivity" @change="applyFilters"
                class="border rounded-md px-4 py-2 w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-green-600 text-sm">
                <option value="">All</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>

            <!-- Filter by Diabetes -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Diabetes</label>
              <select v-model="filterDiabetes" @change="applyFilters"
                class="border rounded-md px-4 py-2 w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-green-600 text-sm">
                <option value="">All</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>

            <!-- Clear Filters Button -->
            <div class="flex items-end">
              <button @click="clearFilters"
                class="bg-red-600 text-white px-4 py-2 rounded-md shadow-md w-full sm:w-auto hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 text-sm">
                Clear Filters
              </button>
            </div>
          </div>
        </div>
      </transition>
    </div>

    <!-- Table -->
    <div class="overflow-hidden bg-white rounded-lg mt-6">
      <table class="w-full border-collapse">
        <thead class="bg-gradient-to-r from-orange-500 to-orange-600 text-white">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-semibold">Name</th>
            <th class="px-6 py-3 text-left text-sm font-semibold">Food Intake</th>
            <th class="px-6 py-3 text-left text-sm font-semibold">Physical Activity</th>
            <th class="px-6 py-3 text-left text-sm font-semibold">Diabetes</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="filteredPatients.length === 0">
            <td colspan="4" class="text-center py-6 text-gray-500">No data available</td>
          </tr>
          <tr v-for="patient in paginatedPatients" :key="patient.id" class="hover:bg-orange-50 transition-all">
            <td class="px-6 py-4 text-sm text-gray-800 font-medium">
              {{ patient.firstName }} {{ patient.middleName }} {{ patient.lastName }}
            </td>
            <td class="px-6 py-4 text-sm text-gray-600">{{ patient.foodIntake || 'N/A' }}</td>
            <td class="px-6 py-4 text-sm text-gray-600">{{ patient.physicalActivity || 'N/A' }}</td>
            <td class="px-6 py-4 text-sm text-gray-600">{{ patient.bloodGlucose || 'N/A' }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-between items-center mt-6">
      <button @click="prevPage" :disabled="currentPage === 1"
        class="px-4 py-2 bg-orange-600 text-white rounded-md shadow-md hover:bg-orange-500 disabled:opacity-50">
        Previous
      </button>
      <p class="text-sm text-gray-700">Page {{ currentPage }} of {{ totalPages }}</p>
      <button @click="nextPage" :disabled="currentPage === totalPages"
        class="px-4 py-2 bg-orange-600 text-white rounded-md shadow-md hover:bg-orange-500 disabled:opacity-50">
        Next
      </button>
    </div>
  </div>


  <!-- Risk Modal -->
  <RiskModal v-if="showRiskModal" @close="closeRiskModal" @saved="handleRiskSaved" />
</template>

<script>
import RiskModal from './RiskModal.vue';

export default {
  components: {
    RiskModal,
  },
  props: {
    riskPatients: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      megaFilterOpen: false,
      filterName: '',
      filterFoodIntake: '',
      filterPhysicalActivity: '',
      filterDiabetes: '',
      showRiskModal: false,
      selectedEntry: null,
      currentPage: 1,
      itemsPerPage: 5, // Number of items per page
    };
  },
  computed: {
    filteredPatients() {
      return this.riskPatients.filter((patient) => {
        return (
          (!this.filterName ||
            `${patient.firstName} ${patient.lastName}`.toLowerCase().includes(this.filterName.toLowerCase())) &&
          (!this.filterFoodIntake || patient.foodIntake === this.filterFoodIntake) &&
          (!this.filterPhysicalActivity || patient.physicalActivity === this.filterPhysicalActivity) &&
          (!this.filterDiabetes || patient.bloodGlucose === this.filterDiabetes)
        );
      });
    },
    paginatedPatients() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = this.currentPage * this.itemsPerPage;
      return this.filteredPatients.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.filteredPatients.length / this.itemsPerPage);
    },
  },
  methods: {
    toggleMegaFilter() {
      this.megaFilterOpen = !this.megaFilterOpen;
    },
    editEntry(index) {
      this.selectedEntry = this.paginatedPatients[index];
      this.showRiskModal = true;
    },
    deleteEntry(patient) {
      // Implementation for delete functionality
      console.log(`Deleting entry for ${patient.firstName} ${patient.lastName}`);
    },
    clearFilters() {
      this.filterName = '';
      this.filterFoodIntake = '';
      this.filterPhysicalActivity = '';
      this.filterDiabetes = '';
    },
    closeRiskModal() {
      this.showRiskModal = false;
      this.selectedEntry = null;
    },
    handleRiskSaved(data) {
      this.closeRiskModal();
      this.$emit('risk-saved', data);
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage -= 1;
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage += 1;
      }
    },
  },
  mounted() {
    console.log('Risk Patients:', this.riskPatients);
  },
};
</script>