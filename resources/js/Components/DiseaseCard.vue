<template>
    <ShortBox>
      
      <!-- Main Content -->
      <div class="flex flex-row items-center justify-between gap-4">
        <!-- Cases Title and Count -->
        <div class="flex flex-col items-start gap-2">
            <div class="flex gap-2">
                <h1 class="text-green">Cases:
                 
                  <strong class="text-red-500">   {{ selectedDisease || 'All Disease' }}</strong>
           
                </h1>
            </div>
   
          
          <!-- Cases Count (General or Filtered) -->
          <p class="text-black text-lg text-center flex items-center justify-center">
            <font-awesome-icon :icon="['fas', 'file-medical']" class="mr-3 cases-icon" />
            {{ totalCasesCount }} <!-- Display filtered cases count -->
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
            @click="filterCases('Malaria')"
          >
            Filter by Malaria
          </li>
          <li 
            class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer" 
            @click="filterCases('Covid-19')"
          >
            Filter by Covid-19
          </li>
          <li 
            class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer" 
            @click="filterCases('Flu')"
          >
            Filter by Flu
          </li>
          <li 
            class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer" 
            @click="filterCases('')"
          >
            Clear Filter
          </li>
        </ul>
      </div>
    </ShortBox>
  </template>
  
  <script>
  import { ref, computed } from 'vue';
  import ShortBox from './ShortBox.vue'; // Adjust the path if necessary
  
  export default {
    components: {
      ShortBox,
    },
    setup() {
      const isDropdownOpen = ref(false);
      const selectedDisease = ref(""); // The selected disease filter
      const casesData = ref([
        { disease: 'Malaria', count: 50 },
        { disease: 'Covid-19', count: 120 },
        { disease: 'Flu', count: 75 },
        // Add more diseases and cases here as needed
      ]);
  
      // Computed property to filter cases based on selected disease
      const filteredCases = computed(() => {
        if (!selectedDisease.value) {
          return casesData.value; // Return all cases if no disease is selected
        }
        return casesData.value.filter(
          (caseItem) => caseItem.disease.toLowerCase() === selectedDisease.value.toLowerCase()
        );
      });
  
      // Compute total cases count (based on selected filter)
      const totalCasesCount = computed(() => {
        const cases = filteredCases.value;
        // Return the sum of counts from filtered cases
        return cases.reduce((acc, caseItem) => acc + caseItem.count, 0);
      });
  
      // Toggle dropdown visibility
      const toggleDropdown = () => {
        isDropdownOpen.value = !isDropdownOpen.value;
      };
  
      // Filter cases based on selected disease
      const filterCases = (disease) => {
        selectedDisease.value = disease;
        isDropdownOpen.value = false; // Close the dropdown after selecting a disease
      };
  
      return {
        isDropdownOpen,
        toggleDropdown,
        filterCases,
        selectedDisease,
        filteredCases,
        totalCasesCount,
      };
    },
  };
  </script>
  
  <style scoped>
  .cases-icon {
    font-size: 1.5rem; /* Adjust icon size */
  }
  
  select {
    width: 200px; /* Fixed width for the select dropdown */
    padding: 0.5rem;
    font-size: 1rem;
  }
  </style>
  