<script>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import ShortBox from './ShortBox.vue';

export default {
  
  components: { ShortBox },
  theme: {
    extend: {
      zIndex: {
        100: '100', // Add custom z-index values if necessary
        999: '999',
      },
    },
  },
  props: { casesData: { type: Array, default: () => [] } },
  setup(props) {
    const isDropdownOpen = ref(false);
    const selectedDisease = ref('');
    const normalizedCasesData = computed(() =>
      Array.isArray(props.casesData) ? props.casesData : []
    );
    const filteredCases = computed(() =>
      selectedDisease.value
        ? normalizedCasesData.value.filter(
            (caseItem) =>
              caseItem.diagnosis &&
              caseItem.diagnosis.toLowerCase() === selectedDisease.value.toLowerCase()
          )
        : normalizedCasesData.value
    );
    const totalCasesCount = computed(() =>
      filteredCases.value.reduce((sum, caseItem) => sum + (caseItem.count || 0), 0)
    );

    const toggleDropdown = () => {
      isDropdownOpen.value = !isDropdownOpen.value;
    };

    const closeDropdown = () => {
      isDropdownOpen.value = false;
    };

    const handleClickOutside = (event) => {
      const dropdownElement = document.querySelector('.dropdown-menu');
      if (
        isDropdownOpen.value &&
        dropdownElement &&
        !dropdownElement.contains(event.target) &&
        !event.target.closest('.dropdown-toggle')
      ) {
        closeDropdown();
      }
    };

    const handleEscKey = (event) => {
      if (isDropdownOpen.value && event.key === 'Escape') {
        closeDropdown();
      }
    };

    const filterCases = (disease) => {
      selectedDisease.value = disease;
      closeDropdown();
    };

    onMounted(() => {
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
      closeDropdown,
      filterCases,
      selectedDisease,
      filteredCases,
      totalCasesCount,
      normalizedCasesData,
    };
  },
};
</script>

<template>
  <ShortBox class="bg-gradient-to-br from-yellow-100 to-yellow-300 text-yellow-800 hover:shadow-md transition-shadow relative">
    <div class="flex flex-row items-center justify-between gap-4">
      <!-- Cases Title and Count -->
      <div class="flex flex-col items-start gap-2">
        <div class="flex gap-2">
          <h1 class="font-bold">Cases:</h1>
          <strong>{{ selectedDisease || 'All Cases' }}</strong>
        </div>
        <!-- Cases Count -->
        <p class="text-lg flex items-center">
          <font-awesome-icon :icon="['fas', 'file-medical']" class="mr-2 text-yellow-600" />
          {{ totalCasesCount }}
        </p>
      </div>

      <!-- Dropdown Trigger -->
      <div @click="toggleDropdown" class="cursor-pointer dropdown-toggle">
        <font-awesome-icon :icon="['fas', 'ellipsis-v']" class="text-yellow-600 hover:text-yellow-800" />
      </div>
    </div>

    <!-- Dropdown Menu -->
    <transition name="fade">
  <div
    v-if="isDropdownOpen && normalizedCasesData.length"
    class="absolute right-0 top-10 mt-2 w-64 bg-white border dropdown-menu border-yellow-300 rounded-lg shadow-lg z-9999 max-h-64 overflow-y-auto dropdown-menu"
    @click.stop
  >
    <ul class="py-2">
      <!-- Filter Items -->
      <li
        v-for="caseItem in normalizedCasesData"
        :key="caseItem.diagnosis"
        class="px-4 py-2 text-sm text-gray-700 hover:bg-yellow-100 cursor-pointer"
        @click="filterCases(caseItem.diagnosis)"
      >
        Filter by <span class="font-semibold">{{ caseItem.diagnosis }}</span>
      </li>
      <!-- Clear Filter -->
      <li
        class="px-4 py-2 text-sm text-gray-700 hover:bg-yellow-100 cursor-pointer"
        @click="filterCases('')"
      >
        Clear Filter
      </li>
    </ul>
  </div>
</transition>

  </ShortBox>
</template>

<style scoped>
/* Ensure the dropdown has the highest z-index */
.dropdown-menu {
  position: absolute;
  z-index: 9999 !important; /* Force the dropdown above all other elements */
}

/* Ensure the parent doesn’t create a stacking context */
.dropdown-toggle, .relative-parent {
  position: relative;
  z-index: auto; /* Reset z-index if necessary */
}
</style>