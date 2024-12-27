<template>
    <div>
      <!-- Search Filter -->
      <div class="flex justify-between items-center mb-4">
        <input
          v-model="filterName"
          @input="applyFilters"
          type="text"
          placeholder="Search by Name..."
          class="border rounded-md px-4 py-2 shadow-md w-full sm:w-1/3 focus:outline-none focus:ring-2 focus:ring-green-600"
        />
        <div class="flex space-x-4">
          <button
            @click="toggleMegaFilter"
            class="bg-green-700 text-white px-4 py-2 rounded-md shadow-md hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 text-sm"
          >
            Filter Options
            <font-awesome-icon :icon="['fas', megaFilterOpen ? 'caret-up' : 'caret-down']" />
          </button>
        </div>
      </div>
  
      <!-- Mega Filter Dropdown -->
      <div class="relative mb-6">
        <!-- Use v-if to conditionally render dropdown to prevent DOM patching issues -->
        <transition name="fade">
          <div
            v-if="megaFilterOpen"
            class="absolute z-50 bg-white border rounded-md mt-2 w-full max-w-md sm:w-auto shadow-lg p-4"
            style="left: auto; right: 0;" 
          >
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Filter Options</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <!-- Filter by Food Intake -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Food Intake</label>
                <select
                  v-model="filterFoodIntake"
                  @change="applyFilters"
                  class="border rounded-md px-4 py-2 w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-green-600 text-sm"
                >
                  <option value="">All</option>
                  <option value="High Salt/Fat">High Salt/Fat</option>
                  <option value="High Fiber">High Fiber</option>
                  <option value="Moderate Salt/Fat">Moderate Salt/Fat</option>
                </select>
              </div>
  
              <!-- Filter by Physical Activity -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Physical Activity</label>
                <select
                  v-model="filterPhysicalActivity"
                  @change="applyFilters"
                  class="border rounded-md px-4 py-2 w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-green-600 text-sm"
                >
                  <option value="">All</option>
                  <option value="Yes">Yes</option>
                  <option value="No">No</option>
                </select>
              </div>
  
              <!-- Filter by Diabetes -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Diabetes</label>
                <select
                  v-model="filterDiabetes"
                  @change="applyFilters"
                  class="border rounded-md px-4 py-2 w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-green-600 text-sm"
                >
                  <option value="">All</option>
                  <option value="Yes">Yes</option>
                  <option value="No">No</option>
                </select>
              </div>
  
              <!-- Clear Filters Button -->
              <div class="flex items-end">
                <button
                  @click="clearFilters"
                  class="bg-red-600 text-white px-4 py-2 rounded-md shadow-md w-full sm:w-auto hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 text-sm"
                >
                  Clear Filters
                </button>
              </div>
            </div>
          </div>
        </transition>
      </div>
  
      <!-- Table -->
      <div class="overflow-hidden bg-white rounded-lg shadow-md mt-6">
        <table class="w-full text-left border-collapse">
          <thead class="bg-green-600 text-white">
            <tr>
              <th class="px-4 py-2">Name</th>
              <th class="px-4 py-2">Food Intake</th>
              <th class="px-4 py-2">Physical Activity</th>
              <th class="px-4 py-2">Diabetes</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="filteredPatients.length === 0">
              <td colspan="5" class="text-center py-4">No data available</td>
            </tr>
            <tr
              v-for="patient in filteredPatients" :key="patient.id"
              class="hover:bg-gray-100 transition"
            >
              <td class="px-4 py-2">{{ patient.firstName  }} {{ patient.middleName }} {{ patient.lastName }}</td>
              <td class="px-4 py-2">{{ patient.foodIntake || 'N/A' }}</td>
              <td class="px-4 py-2">{{ patient.physicalActivity || 'N/A' }}</td>
              <td class="px-4 py-2">{{ patient.bloodGlucose || 'N/A' }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Risk Modal -->
    <RiskModal
      v-if="showRiskModal"
      @close="closeRiskModal"
      @saved="handleRiskSaved"
    />
  </template>
  
  <script>
  import RiskModal from './RiskModal.vue';

  export default {
    components: {
      RiskModal
    },
    props: {
      riskPatients: {
        type: Array,
        default: () => [],
      }
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
      };
    },
    computed: {
      filteredPatients() {
        return this.riskPatients.filter((patient) => {
          return (
            (!this.filterName || `${patient.firstName} ${patient.lastName}`.toLowerCase().includes(this.filterName.toLowerCase())) &&
            (!this.filterFoodIntake || patient.foodIntake === this.filterFoodIntake) &&
            (!this.filterPhysicalActivity || patient.physicalActivity === this.filterPhysicalActivity) &&
            (!this.filterDiabetes || patient.bloodGlucose === this.filterDiabetes)
          );
        });
      },
    },
    methods: {
      toggleMegaFilter() {
        this.megaFilterOpen = !this.megaFilterOpen;
      },
      editEntry(index) {
        this.selectedEntry = this.filteredPatients[index];
        this.showRiskModal = true;
      },
      deleteEntry(patient) {
        // Implementation for delete functionality
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
      }
    },
    mounted() {
      console.log("Risk Patients:", this.riskPatients);
    }
  };
  </script>