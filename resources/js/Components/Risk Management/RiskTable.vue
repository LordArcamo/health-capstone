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
        <button
          @click="toggleMegaFilter"
          class="bg-green-700 text-white px-4 py-2 rounded-md shadow-md hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 text-sm ml-4"
        >
          Filter Options
          <font-awesome-icon :icon="['fas', megaFilterOpen ? 'caret-up' : 'caret-down']" />
        </button>
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
              <th class="px-4 py-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(entry, index) in filteredData"
              :key="index"
              class="hover:bg-gray-100 transition"
            >
              <td class="px-4 py-2">{{ entry.name }}</td>
              <td class="px-4 py-2">{{ entry.foodIntake }}</td>
              <td class="px-4 py-2">{{ entry.physicalActivity }}</td>
              <td class="px-4 py-2">{{ entry.diabetes }}</td>
              <td class="px-4 py-2">
                <button @click="editEntry(index)" class="bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600">Edit</button>
                <button @click="deleteEntry(index)" class="ml-2 bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      data: Array,
    },
    data() {
      return {
        megaFilterOpen: false,
        filterName: "",
        filterFoodIntake: "",
        filterPhysicalActivity: "",
        filterDiabetes: "",
      };
    },
    computed: {
      filteredData() {
        return this.data.filter((entry) => {
          return (
            (!this.filterName || entry.name.toLowerCase().includes(this.filterName.toLowerCase())) &&
            (!this.filterFoodIntake || entry.foodIntake === this.filterFoodIntake) &&
            (!this.filterPhysicalActivity || entry.physicalActivity === this.filterPhysicalActivity) &&
            (!this.filterDiabetes || entry.diabetes === this.filterDiabetes)
          );
        });
      },
    },
    methods: {
      toggleMegaFilter() {
        this.megaFilterOpen = !this.megaFilterOpen;
      },
      editEntry(index) {
        this.$emit("edit", index);
      },
      deleteEntry(index) {
        this.$emit("delete", index);
      },
      clearFilters() {
        this.filterName = "";
        this.filterFoodIntake = "";
        this.filterPhysicalActivity = "";
        this.filterDiabetes = "";
      },
    },
  };
  </script>
  