<template>
  <ShortBox class="bg-gradient-to-br from-blue-100 to-blue-300 text-blue-800 hover:shadow-md transition-shadow relative">
    <div class="flex flex-row items-center justify-between gap-4">
      <!-- Date Display -->
      <div class="flex flex-col items-start gap-2">
        <h1 class="text-lg font-bold">Date Range</h1>
        <p class="text-lg flex items-center">
          <font-awesome-icon :icon="['fas', 'calendar']" class="mr-2 text-blue-600" />
          {{ formattedDateRange }}
        </p>
      </div>

      <!-- Settings Icon (three dots) -->
      <div @click="toggleDropdown" class="relative cursor-pointer z-20 dropdown-toggle">
        <font-awesome-icon :icon="['fas', 'ellipsis-v']" class="text-blue-600 hover:text-blue-800" />
      </div>
    </div>

    <!-- Dropdown Menu -->
    <transition name="fade">
      <div
        v-if="isDropdownOpen"
        class="absolute right-0 mt-2 w-64 bg-white border border-gray-200 rounded-lg shadow-lg z-50 max-h-64 overflow-y-auto"
        ref="dropdownMenu"
        @click.stop
      >
        <ul class="py-1">
          <li
            class="px-4 py-2 text-sm text-gray-700 hover:bg-blue-100 cursor-pointer"
            @click="filterPatients('total')"
          >
            Show All Patients
          </li>
          <li
            class="px-4 py-2 text-sm text-gray-700 hover:bg-blue-100 cursor-pointer"
            @click="filterPatients('today')"
          >
            Filter by Today
          </li>
          <li
            class="px-4 py-2 text-sm text-gray-700 hover:bg-blue-100 cursor-pointer"
            @click="filterPatients('week')"
          >
            Filter by This Week
          </li>
          <li
            class="px-4 py-2 text-sm text-gray-700 hover:bg-blue-100 cursor-pointer"
            @click="filterPatients('month')"
          >
            Filter by This Month
          </li>
        </ul>
      </div>
    </transition>
  </ShortBox>
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

    const formatDateRange = (startDate, endDate, type) => {
  if (type === 'today') {
    return new Date(startDate).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
  } else if (type === 'week') {
    const options = { month: 'long', day: 'numeric', year: 'numeric' };
    const start = new Date(startDate).toLocaleDateString('en-US', options);
    const end = new Date(endDate).toLocaleDateString('en-US', options);
    return `${start} - ${end}`; // Use backticks for template literals
  } else if (type === 'month') {
    return new Date(startDate).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
  } else {
    return 'All Dates';
  }
};


const getStartOfWeek = () => {
  const now = new Date();
  const day = now.getDay(); // 0 (Sunday) to 6 (Saturday)
  const diff = now.getDate() - day; // Get the start of the week
  const startOfWeek = new Date(now.setDate(diff));
  startOfWeek.setHours(0, 0, 0, 0); // Ensure time is at the start of the day
  return startOfWeek;
};

const getEndOfWeek = () => {
  const startOfWeek = getStartOfWeek();
  const endOfWeek = new Date(startOfWeek);
  endOfWeek.setDate(startOfWeek.getDate() + 6); // Add 6 days to start of the week
  endOfWeek.setHours(23, 59, 59, 999); // Ensure time is at the end of the day
  return endOfWeek;
};
const getStartOfMonth = () => {
  const now = new Date();
  const startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1);
  startOfMonth.setHours(0, 0, 0, 0); // Start of the day
  return startOfMonth;
};

const getEndOfMonth = () => {
  const now = new Date();
  const endOfMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0); // Last day of the month
  endOfMonth.setHours(23, 59, 59, 999); // End of the day
  return endOfMonth;
};

// Debugging the functions
console.log('Start of Month:', getStartOfMonth());
console.log('End of Month:', getEndOfMonth());

// Main Filter Logic
const filteredPatients = computed(() => {
  if (!props.patients || !props.patients.length) {
    console.warn('No patients available for filtering');
    return [];
  }

  // Log total patients for debugging
  console.log('Total Patients (Including Redundancy):', props.patients.length);

  const today = new Date();
  today.setHours(0, 0, 0, 0);

  const getStartOfMonth = () => new Date(today.getFullYear(), today.getMonth(), 1);
  const getEndOfMonth = () => {
    const end = new Date(today.getFullYear(), today.getMonth() + 1, 0);
    end.setHours(23, 59, 59, 999);
    return end;
  };

  const startOfMonth = getStartOfMonth();
  const endOfMonth = getEndOfMonth();

  console.log('Current Filter:', currentFilter.value);
  console.log('Start of Month:', startOfMonth, 'End of Month:', endOfMonth);

  // Apply filtering logic without deduplication
  return props.patients.filter((patient) => {
    const patientDate = new Date(patient.created_at);
    if (isNaN(patientDate)) {
      console.warn('Skipping patient with invalid created_at:', patient.created_at);
      return false;
    }

    if (currentFilter.value === 'month') {
      return patientDate >= startOfMonth && patientDate <= endOfMonth;
    } else if (currentFilter.value === 'today') {
      return patientDate.toDateString() === today.toDateString();
    } else if (currentFilter.value === 'week') {
      const startOfWeek = getStartOfWeek();
      const endOfWeek = getEndOfWeek();
      return patientDate >= startOfWeek && patientDate <= endOfWeek;
    }

    return true; // Default to showing all patients
  });
});


// Debugging Watcher
watch(() => props.patients, (newPatients) => {
  console.log('Updated Patients:', newPatients);
  if (!Array.isArray(newPatients) || newPatients.length === 0) {
    console.warn('Patients array is empty or invalid');
  }
});
    // Update stats for Total Patients and Referred Patients
    const updateStats = () => {
  console.log('Filtered Patients:', filteredPatients.value);

  // Validate filteredPatients
  if (!Array.isArray(filteredPatients.value)) {
    console.warn('Filtered Patients is not an array');
    return;
  }

  if (filteredPatients.value.length === 0) {
    console.warn('No patients available in filteredPatients');
  }

  // Calculate stats
  const totalPatients = filteredPatients.value.length;
  const referredPatients = filteredPatients.value.filter(
    (patient) => patient.modeOfTransaction === 'Referral'
  ).length;

  console.log('Total Patients:', totalPatients);
  console.log('Referred Patients:', referredPatients);

  // Add specific warnings for edge cases
  if (totalPatients === 0) {
    console.warn('Total Patients count is zero');
  }

  if (referredPatients === 0) {
    console.warn('No referred patients found');
  }

  // Emit stats
  emit('updateStats', {
    totalPatients: totalPatients || 0,
    referredPatients: referredPatients || 0,
  });
};


    // Filter patients based on the selected filter
const filterPatients = (filterType) => {
  console.log('Filter Type:', filterType);
  currentFilter.value = filterType; // Update filter type first
  isDropdownOpen.value = false;

  // Dynamically set formatted date range for display
  let startDate = null;
  let endDate = null;

  if (filterType === 'today') {
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    startDate = today;
    endDate = today;
  } else if (filterType === 'week') {
    startDate = getStartOfWeek();
    endDate = getEndOfWeek();
  } else if (filterType === 'month') {
    startDate = getStartOfMonth();
    endDate = getEndOfMonth();
  }

  formattedDateRange.value = formatDateRange(startDate, endDate, filterType);

  // Debugging
  console.log('Start Date:', startDate, 'End Date:', endDate);
  console.log('Filtered Patients Count:', filteredPatients.value.length);

  // Ensure `filteredPatients` is up to date before stats update
  setTimeout(() => {
    updateStats();
  }, 0); // Delay to allow computed to react
};




    const toggleDropdown = () => {
      isDropdownOpen.value = !isDropdownOpen.value;
    };

    const handleClickOutside = (event) => {
      if (!event.target.closest('.dropdown-menu') && !event.target.closest('.dropdown-toggle')) {
        isDropdownOpen.value = false;
      }
    };

    const handleEscKey = (event) => {
      if (event.key === 'Escape') {
        isDropdownOpen.value = false;
      }
    };

    // Initialize with default stats and current date
    onMounted(() => {
      formattedDateRange.value = formatDateRange(new Date(), null, 'today'); // Default to "Today"
      updateStats(); // Update stats immediately on load

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
    };
  },
};
</script>




<style scoped>
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
