<template>
  <div class="relative w-full max-w-sm mx-auto">
    <!-- Dropdown Toggle -->
    <button
      @click="toggleDropdown"
      class="w-full bg-blue-500 text-white font-semibold py-3 px-4 rounded-lg shadow-md hover:bg-blue-600 transition flex items-center justify-between"
    >
      <div class="flex items-center space-x-2">
        <font-awesome-icon :icon="['fas', 'calendar']" class="text-white" />
        <span>{{ formattedDateRange || 'Select Date Range' }}</span>
      </div>
      <font-awesome-icon :icon="['fas', 'chevron-down']" class="transition-transform" :class="{'rotate-180': isDropdownOpen}" />
    </button>

    <!-- Dropdown Menu -->
    <transition name="fade">
      <div
        v-if="isDropdownOpen"
        class="absolute w-full mt-2 bg-white border border-gray-200 rounded-lg shadow-lg z-50"
        ref="dropdownMenu"
        @click.stop
      >
        <ul class="py-2">
          <li
            class="px-4 py-2 text-gray-700 hover:bg-blue-100 cursor-pointer transition"
            @click="filterPatients('total')"
          >
            Show All Patients
          </li>
          <li
            class="px-4 py-2 text-gray-700 hover:bg-blue-100 cursor-pointer transition"
            @click="filterPatients('today')"
          >
            Filter by Today
          </li>
          <li
            class="px-4 py-2 text-gray-700 hover:bg-blue-100 cursor-pointer transition"
            @click="filterPatients('week')"
          >
            Filter by This Week
          </li>
          <li
            class="px-4 py-2 text-gray-700 hover:bg-blue-100 cursor-pointer transition"
            @click="filterPatients('month')"
          >
            Filter by This Month
          </li>
        </ul>
      </div>
    </transition>
  </div>
</template>


<script>
import { ref, computed, onMounted, onBeforeUnmount, watch } from 'vue';
import ShortBox from './ShortBox.vue';

export default {
  components: { ShortBox },
  props: {
    patients: {
      type: Array,
      default: () => [],
    },
  },
  emits: ['updateStats'],
  setup(props, { emit }) {
    console.log(props.patients);

    const isDropdownOpen = ref(false);
    const currentFilter = ref('total'); // Set to "total" for default display
    const formattedDateRange = ref('');
    const dropdownContainer = ref(null);

    // Format Date Range for Display
    const formatDateRange = (startDate, endDate, type) => {
      if (type === 'today') {
        return new Date(startDate).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
      } else if (type === 'week') {
        const options = { month: 'long', day: 'numeric', year: 'numeric' };
        const start = new Date(startDate).toLocaleDateString('en-US', options);
        const end = new Date(endDate).toLocaleDateString('en-US', options);
        return `${start} - ${end}`;
      } else if (type === 'month') {
        return new Date(startDate).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
      } else {
        return 'All Dates';
      }
    };

    // Date Ranges
    const getStartOfWeek = () => {
      const now = new Date();
      const day = now.getDay();
      const diff = now.getDate() - day;
      const startOfWeek = new Date(now.setDate(diff));
      startOfWeek.setHours(0, 0, 0, 0);
      return startOfWeek;
    };

    const getEndOfWeek = () => {
      const startOfWeek = getStartOfWeek();
      const endOfWeek = new Date(startOfWeek);
      endOfWeek.setDate(startOfWeek.getDate() + 6);
      endOfWeek.setHours(23, 59, 59, 999);
      return endOfWeek;
    };

    const getStartOfMonth = () => new Date(new Date().getFullYear(), new Date().getMonth(), 1);
    const getEndOfMonth = () => {
      const end = new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0);
      end.setHours(23, 59, 59, 999);
      return end;
    };

    // Filtering Logic
    const filteredPatients = computed(() => {
      if (!props.patients.length) {
        console.warn('No patients available for filtering');
        return [];
      }

      const today = new Date();
      today.setHours(0, 0, 0, 0);

      return props.patients.filter((patient) => {
        const patientDate = new Date(patient.created_at);
        if (isNaN(patientDate)) return false;

        if (currentFilter.value === 'month') {
          return patientDate >= getStartOfMonth() && patientDate <= getEndOfMonth();
        } else if (currentFilter.value === 'today') {
          return patientDate.toDateString() === today.toDateString();
        } else if (currentFilter.value === 'week') {
          return patientDate >= getStartOfWeek() && patientDate <= getEndOfWeek();
        }

        return true; // Default to showing all patients
      });
    });

    // Update Stats
    const updateStats = () => {
      const totalPatients = filteredPatients.value.length;
      const referredPatients = filteredPatients.value.filter((patient) => patient.modeOfTransaction === 'Referral').length;

      emit('updateStats', {
        totalPatients: totalPatients || 0,
        referredPatients: referredPatients || 0,
      });
    };

    // Filter Patients
    const filterPatients = (filterType) => {
      currentFilter.value = filterType;
      isDropdownOpen.value = false;

      let startDate = null;
      let endDate = null;

      if (filterType === 'today') {
        startDate = new Date();
        endDate = new Date();
      } else if (filterType === 'week') {
        startDate = getStartOfWeek();
        endDate = getEndOfWeek();
      } else if (filterType === 'month') {
        startDate = getStartOfMonth();
        endDate = getEndOfMonth();
      }

      formattedDateRange.value = formatDateRange(startDate, endDate, filterType);

      setTimeout(() => {
        updateStats();
      }, 0);
    };

    // Toggle Dropdown
    const toggleDropdown = () => {
      isDropdownOpen.value = !isDropdownOpen.value;
    };

    // Close Dropdown When Clicking Outside
    const handleClickOutside = (event) => {
      if (dropdownContainer.value && !dropdownContainer.value.contains(event.target)) {
        isDropdownOpen.value = false;
      }
    };

    // Close Dropdown When Pressing Escape
    const handleEscKey = (event) => {
      if (event.key === 'Escape') {
        isDropdownOpen.value = false;
      }
    };

    // Lifecycle Hooks
    onMounted(() => {
      formattedDateRange.value = formatDateRange(new Date(), null, 'today');
      updateStats();
      document.addEventListener('click', handleClickOutside);
      document.addEventListener('keydown', handleEscKey);
    });

    onBeforeUnmount(() => {
      document.removeEventListener('click', handleClickOutside);
      document.removeEventListener('keydown', handleEscKey);
    });

    return {
      isDropdownOpen,
      toggleDropdown,
      filterPatients,
      formattedDateRange,
      dropdownContainer,
    };
  },
};
</script>





<style scoped>

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease-in-out;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.rotate-180 {
  transform: rotate(180deg);
}
.avatar-icon {
  font-size: 20px;
}

.absolute {
  position: absolute;
}
.text-green {
  color: #10b981; /* Tailwind green color */
}
</style>
