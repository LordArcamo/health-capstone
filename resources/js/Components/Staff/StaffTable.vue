<template>
  <div class="mx-auto py-8 px-10 bg-gradient-to-br from-green-100 to-blue-100 min-h-screen">
    <!-- Header Section -->
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-green-600 text-center">Users List</h1>
      <p class="text-gray-700 text-center">Search, filter, and manage staff records efficiently.</p>
    </div>

    <!-- Search and Filter Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
      <!-- Search Bar -->
      <div class="w-full md:w-3/3 flex items-center border border-gray-300 rounded-lg shadow-sm bg-white">
        <input v-model="searchQuery" type="text" placeholder="Search by name or position"
          class="w-full p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400" />
      </div>

      <!-- Filter Panel Toggle -->
      <button @click="toggleFilterPanel"
        class="flex items-center justify-center gap-2 px-6 py-3 bg-green-500 text-white font-medium rounded-lg shadow hover:bg-green-600 transition">
        Filters
      </button>
    </div>

    <!-- Collapsible Filter Panel -->
    <transition name="fade">
      <div v-if="isFilterPanelOpen" class="border border-gray-300 rounded-lg p-6 shadow-md bg-white mb-8">
        <div class="grid grid-cols-1 gap-6">
          <!-- Role/Position Filter -->
          <div>
            <label class="block font-semibold mb-2">Role</label>
            <select v-model="filterPosition"
              class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-green-400">
              <option value="">All Roles</option>
              <option v-for="role in positionOptions" :key="role" :value="role">
                {{ capitalize(role) }}
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
            <th class="py-4 px-6 text-left">Full Name</th>
            <th class="py-4 px-6 text-left">Email</th>
            <th class="py-4 px-6 text-left">Contact Number</th>
            <th class="py-4 px-6 text-left">Position</th>
            <th class="py-4 px-6 text-left">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 text-gray-700">
          <tr v-for="staff in paginatedStaff" :key="staff.id" class="hover:bg-gray-50 cursor-pointer"
            @click="openModal(staff)">
            <td class="py-3 px-6">{{ staff.first_name }} {{ staff.middle_name }}. {{ staff.last_name }}</td>
            <td class="py-3 px-6">{{ capitalize(staff.email) }}</td>
            <td class="py-3 px-6">{{ capitalize(staff.phone) }}</td>
            <td class="py-3 px-6">{{ capitalize(staff.role) }}</td>
            <td class="py-3 px-6">
              <!-- Edit Button -->
              <button @click.stop="editStaff(staff)"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition">
                Edit
              </button>
              <!-- Delete Button -->
              <button @click.stop="deleteStaff(staff)"
                class="bg-orange-500 text-white px-4 py-2 rounded-lg shadow hover:bg-red-600 transition ml-2">
                Deactivate
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center gap-4">
      <button @click="prevPage" :disabled="currentPage === 1"
        class="px-4 py-2 bg-red-500 text-white font-semibold rounded-lg shadow hover:bg-red-600 transition">
        Previous
      </button>
      <span class="text-gray-700">Page {{ currentPage }} of {{ totalPages }}</span>
      <button @click="nextPage" :disabled="currentPage === totalPages"
        class="px-4 py-2 bg-green-500 text-white font-semibold rounded-lg shadow hover:bg-green-600 transition">
        Next
      </button>
    </div>

    <!-- Staff Details Modal -->
    <div v-if="showModal && selectedStaff"
      class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl p-8 relative">
        <!-- Close Button -->
        <button @click="closeModal"
          class="absolute top-4 right-4 bg-red-600 text-white rounded-full w-10 h-10 flex items-center justify-center hover:bg-red-700 transition">
          &times;
        </button>

        <!-- Header -->
        <div class="border-b pb-4 mb-6 flex items-center gap-4">
          <div class="bg-green-100 text-green-700 rounded-full p-4">
            <!-- Icon or Avatar here -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
          </div>
          <div>
            <h2 class="text-2xl font-bold text-gray-800">User Details</h2>
            <p class="text-gray-600">{{ selectedStaff.name }}</p>
          </div>
        </div>

        <!-- Staff Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <!-- Basic Information -->
          <div>
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Basic Information</h3>
            <ul class="space-y-2">
              <li><strong>User ID:</strong> {{ selectedStaff.id }}</li>
              <li><strong>Full Name:</strong> {{ selectedStaff.first_name }} {{ selectedStaff.middle_name }}. {{
                selectedStaff.last_name }}</li>
              <li><strong>Position:</strong> {{ capitalize(selectedStaff.role) }}</li>
              <li><strong>Email:</strong> {{ selectedStaff.email }}</li>
              <li><strong>Phone:</strong> {{ selectedStaff.phone }}</li>
              <li><strong>Account Created at:</strong> {{ formatDateTime(selectedStaff.created_at) }}</li>
            </ul>
          </div>

          <!-- Address Information -->
          <div>
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Address Information</h3>
            <ul class="space-y-2">
              <li><strong>Purok:</strong> {{ selectedStaff.purok }}</li>
              <li><strong>Barangay:</strong> {{ selectedStaff.barangay }}</li>
              <li><strong>City:</strong> {{ selectedStaff.city }}</li>
            </ul>
          </div>

          <!-- Professional Information -->
          <div>
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Professional Information</h3>
            <ul class="space-y-2">
              <li><strong>PRC Number:</strong> {{ selectedStaff.prc_number }}</li>
              <li><strong>Specialization:</strong> {{ selectedStaff.specialization }}</li>
              <li><strong>PRC Validity:</strong> {{ selectedStaff.prc_validity }}</li>
              <!-- Permissions Cards -->
              <li class="gap-3 flex flex-col">
               <strong>Permissions:</strong>
                <div class="flex flex-wrap gap-2">
                  <div v-for="permission in selectedStaff.permissions" :key="permission"
                    class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-lg shadow">
                    {{ permission }}
                  </div>
                </div>
              </li>

            </ul>
          </div>

          <!-- Profile Picture -->
          <div>
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Profile Picture</h3>
            <div>
              <img :src="selectedStaff.profile_picture" alt="Profile Picture"
                class="w-32 h-32 rounded-full border">
            </div>
          </div>
        </div>

        <!-- Footer Actions -->
        <div class="mt-6 flex justify-end gap-4">
          <button @click="closeModal"
            class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
            Close
          </button>
        </div>
      </div>
    </div>

    <!-- Edit Staff Modal -->
    <EditStaffModal v-if="showEditModal" :show="showEditModal" :staff="editingStaff" @close="closeEditModal"
      @updated="refreshData" />
  </div>
</template>

<script>
import { router } from '@inertiajs/vue3';
import EditStaffModal from './EditStaffModal.vue';

export default {
  name: 'ITRStaffTable',
  components: {
    EditStaffModal
  },
  props: {
    staffList: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      searchQuery: '',
      isFilterPanelOpen: false,
      filterPosition: '',
      currentPage: 1,
      itemsPerPage: 10,
      showModal: false,
      selectedStaff: null,
      showEditModal: false,
      editingStaff: null
    }
  },
  computed: {
    filteredStaff() {
      if (!this.staffList) return [];

      const query = this.searchQuery ? this.searchQuery.toLowerCase() : '';

      return this.staffList.filter((staff) => {
        if (!staff) return false;

        const matchesQuery = !query ||
          staff.name?.toLowerCase().includes(query) ||
          staff.role?.toLowerCase().includes(query) ||
          staff.email?.toLowerCase().includes(query);

        const matchesPosition = !this.filterPosition || staff.role === this.filterPosition;

        return matchesQuery && matchesPosition;
      });
    },

    paginatedStaff() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      return this.filteredStaff.slice(start, start + this.itemsPerPage);
    },

    totalPages() {
      return Math.ceil(this.filteredStaff.length / this.itemsPerPage);
    },

    positionOptions() {
      if (!this.staffList) return [];
      return Array.from(new Set(this.staffList.map(s => s.role))).sort();
    }
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
    editStaff(staff) {
      event.stopPropagation();
      this.editingStaff = { ...staff };
      this.showEditModal = true;
    },
    closeEditModal() {
      this.showEditModal = false;
      this.editingStaff = null;
    },
    refreshData() {
      router.reload({ only: ['staffList'] });
    },
    deleteStaff(staff) {
      event.stopPropagation();
      if (confirm('Are you sure you want to delete this staff member?')) {
        router.delete(`/admin/staff/${staff.id}`);
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
    formatDateTime(dateTime) {
      const options = {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      };
      return new Date(dateTime).toLocaleDateString('en-US', options);
    },
    capitalize(text) {
      if (!text) return '';
      return text.charAt(0).toUpperCase() + text.slice(1);
    }
  }
};
</script>
