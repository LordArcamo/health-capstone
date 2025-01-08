<template>
  <div class="mx-auto py-8 px-10 bg-gradient-to-br from-green-100 to-blue-100 min-h-screen">
    <!-- Header Section -->
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-green-600 text-center">ITR Users List</h1>
      <p class="text-gray-700 text-center">Search, filter, and manage staff records efficiently.</p>
    </div>

    <!-- Search and Filter Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
      <!-- Search Bar -->
      <div class="w-full md:w-3/3 flex items-center border border-gray-300 rounded-lg shadow-sm bg-white">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search by name or position"
          class="w-full p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
        />
      </div>

      <!-- Filter Panel Toggle -->
      <button
        @click="toggleFilterPanel"
        class="flex items-center justify-center gap-2 px-6 py-3 bg-green-500 text-white font-medium rounded-lg shadow hover:bg-green-600 transition"
      >
        Filters
      </button>
    </div>

    <!-- Collapsible Filter Panel -->
    <transition name="fade">
      <div
        v-if="isFilterPanelOpen"
        class="border border-gray-300 rounded-lg p-6 shadow-md bg-white mb-8"
      >
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Department Filter (example) -->
          <div>
            <label class="block font-semibold mb-2">Department</label>
            <select
              v-model="filterDepartment"
              class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-green-400"
            >
              <option value="">All Departments</option>
              <option v-for="dept in departmentOptions" :key="dept" :value="dept">
                {{ dept }}
              </option>
            </select>
          </div>

          <!-- Position Filter (example) -->
          <div>
            <label class="block font-semibold mb-2">Position</label>
            <select
              v-model="filterPosition"
              class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-green-400"
            >
              <option value="">All Positions</option>
              <option v-for="pos in positionOptions" :key="pos" :value="pos">
                {{ pos }}
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
          <tr class="bg-gradient-to-r from-green-500 to-yellow-500 text-white uppercase text-sm font-bold">
            <th class="py-4 px-6 text-left">User ID</th>
            <th class="py-4 px-6 text-left">Full Name</th>
            <th class="py-4 px-6 text-left">Position</th>
            <th class="py-4 px-6 text-left">Department</th>
            <th class="py-4 px-6 text-left">Contact</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 text-gray-700">
          <tr
            v-for="staff in paginatedStaff"
            :key="staff.staffId"
            class="hover:bg-gray-50 cursor-pointer"
            @click="openModal(staff)"
          >
            <td class="py-3 px-6">{{ staff.staffId }}</td>
            <td class="py-3 px-6">{{ staff.fullName }}</td>
            <td class="py-3 px-6">{{ staff.position }}</td>
            <td class="py-3 px-6">{{ staff.department }}</td>
            <td class="py-3 px-6">{{ staff.contact }}</td>
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

    <!-- Modal (Staff Details) -->
    <div
      v-if="showModal && selectedStaff"
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

        <!-- Header -->
        <div class="border-b pb-4 mb-6 flex items-center gap-4">
          <div class="bg-green-100 text-green-700 rounded-full p-4">
            <!-- Icon or Avatar here -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" 
                    stroke-linejoin="round" 
                    stroke-width="2" 
                    d="M5 13l4 4L19 7" />
            </svg>
          </div>
          <div>
            <h2 class="text-2xl font-bold text-gray-800">Staff Details</h2>
            <p class="text-gray-600">{{ selectedStaff.fullName }}</p>
          </div>
        </div>

        <!-- Staff Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <div>
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Basic Information</h3>
            <ul class="space-y-2">
              <li><strong>User ID:</strong> {{ selectedStaff.staffId }}</li>
              <li><strong>Full Name:</strong> {{ selectedStaff.fullName }}</li>
              <li><strong>Position:</strong> {{ selectedStaff.position }}</li>
              <li><strong>Department:</strong> {{ selectedStaff.department }}</li>
              <li><strong>Contact:</strong> {{ selectedStaff.contact }}</li>
            </ul>
          </div>

          <div>
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Additional Information</h3>
            <ul class="space-y-2">
              <li><strong>Email:</strong> {{ selectedStaff.email }}</li>
              <li><strong>Date Instated:</strong> {{ selectedStaff.dateHired }}</li>
              <li><strong>Status:</strong> {{ selectedStaff.status }}</li>
            </ul>
          </div>
        </div>

        <!-- Footer Actions -->
        <div class="mt-6 flex justify-end gap-4">
          <button
            @click="closeModal"
            class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition"
          >
            Close
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ITRStaffTable',
  props: {
    // Suppose your Inertia controller passes an array of staff
    staffList: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      searchQuery: '',
      filterDepartment: '',
      filterPosition: '',
      isFilterPanelOpen: false,
      currentPage: 1,
      itemsPerPage: 10,
      showModal: false,
      selectedStaff: null,
    };
  },
  computed: {
    // Create computed array of staff to display (filter + search + pagination)
    filteredStaff() {
      const query = this.searchQuery.toLowerCase();

      return this.staffList
        .map((staff) => ({
          ...staff,
          fullName: `${staff.firstName} ${staff.lastName}`, // combine names if needed
        }))
        .filter((staff) => {
          const matchesQuery =
            staff.fullName.toLowerCase().includes(query) ||
            staff.position.toLowerCase().includes(query) ||
            staff.department.toLowerCase().includes(query);

          const matchesDept =
            !this.filterDepartment || staff.department === this.filterDepartment;
          const matchesPosition =
            !this.filterPosition || staff.position === this.filterPosition;

          return matchesQuery && matchesDept && matchesPosition;
        });
    },

    paginatedStaff() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      return this.filteredStaff.slice(start, start + this.itemsPerPage);
    },

    totalPages() {
      return Math.ceil(this.filteredStaff.length / this.itemsPerPage);
    },

    // Generate unique department options from staff data
    departmentOptions() {
      return Array.from(new Set(this.staffList.map((s) => s.department))).sort();
    },

    // Generate unique position options from staff data
    positionOptions() {
      return Array.from(new Set(this.staffList.map((s) => s.position))).sort();
    },
  },
  methods: {
    toggleFilterPanel() {
      this.isFilterPanelOpen = !this.isFilterPanelOpen;
    },
    openModal(staff) {
      this.selectedStaff = staff;
      this.showModal = true;
    },
    closeModal() {
      this.showModal = false;
      this.selectedStaff = null;
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
  },
};
</script>
