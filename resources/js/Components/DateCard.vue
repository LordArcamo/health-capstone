<template>
  <ShortBox>
    <div class="flex flex-row items-center justify-between gap-4">
      <!-- Date Display -->
      <div class="flex flex-col items-start gap-2">
        <h1 class="text-green">Date</h1>
        <p class="text-black text-lg text-center flex items-center justify-center">
          <font-awesome-icon :icon="['fas', 'calendar']" class="mr-3 avatar-icon" />
          {{ currentDate }} <!-- Display the dynamic date -->
        </p>
      </div>

      <!-- Settings Icon (three dots) -->
      <div @click="toggleDropdown" class="cursor-pointer">
        <font-awesome-icon :icon="['fas', 'ellipsis-v']" class="text-gray-600 hover:text-gray-800" />
      </div>
    </div>

    <!-- Dropdown Menu -->
    <div 
      v-if="isDropdownOpen" 
      class="absolute mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50"
    >
      <ul class="py-1">
        <li 
          class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer" 
          @click="filterDate('today')"
        >
          Filter by Today
        </li>
        <li 
          class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer" 
          @click="filterDate('week')"
        >
          Filter by This Week
        </li>
        <li 
          class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer" 
          @click="filterDate('month')"
        >
          Filter by {{ currentMonth }}
        </li>
      </ul>
    </div>
  </ShortBox>
</template>

<script>
import { ref, onMounted } from 'vue';
import ShortBox from './ShortBox.vue'; // Adjust the path as needed

export default {
  components: {
    ShortBox,
  },
  setup() {
    const isDropdownOpen = ref(false);
    const currentDate = ref(''); // This will store the dynamic current date
    const currentMonth = ref(''); // This will store the current month's name

    // Update the current date and month dynamically
    const updateCurrentDate = () => {
      const date = new Date();
      
      // Format date as "Month day, Year"
      const formattedDate = date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
      });
      currentDate.value = formattedDate;

      // Get the current month name (just the month)
      const monthName = new Intl.DateTimeFormat('en-US', { month: 'long' }).format(date);
      currentMonth.value = monthName;
    };

    // Toggle dropdown visibility
    const toggleDropdown = () => {
      isDropdownOpen.value = !isDropdownOpen.value;
    };

    // Handle date filter selection
    const filterDate = (filterType) => {
      console.log(`Filtering by: ${filterType}`);
      isDropdownOpen.value = false; // Close the dropdown after selection
      // You can add your actual filtering logic here
    };

    // Update the current date on mount
    onMounted(() => {
      updateCurrentDate(); // Set the initial date when the component mounts
      setInterval(updateCurrentDate, 60000); // Update every minute to keep it dynamic
    });

    return {
      isDropdownOpen,
      toggleDropdown,
      filterDate,
      currentDate,
      currentMonth,
    };
  },
};
</script>

<style scoped>
/* Add styling for dropdown to avoid overlap */
</style>
