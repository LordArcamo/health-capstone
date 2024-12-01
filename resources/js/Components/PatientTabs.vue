<template>
    <div class="tabs-container bg-white rounded-lg shadow-lg mt-10">
      <!-- Tab Navigation (Top Left) -->
      <div class="tabs flex space-x-4 p-4 border-b-2 border-gray-300">
        <button
          v-for="(tab, index) in tabs"
          :key="index"
          :class="[
            'tab-button text-lg font-medium px-4 py-2 rounded-md transition-all max-w-xs',
            { 
              'bg-green-500 text-white': selectedTab === index,
              'text-gray-700 hover:bg-green-100 hover:text-green-500': selectedTab !== index
            }]"
          @click="selectTab(index)"
        >
          {{ tab.name }}
        </button>
      </div>
    
      <!-- Tab Content -->
      <div class="tab-content p-6">
        <div v-show="selectedTab === 0">
          <ITRTable />
        </div>
        <div v-show="selectedTab === 1">
          <PreNatalTable />
        </div>
        <div v-show="selectedTab === 2">
          <NationalImmunizationProgram :patients="patients" />
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { ref } from 'vue';
  import ITRTable from './ItrTable.vue';
  import PreNatalTable from './PrenatalTable.vue';
  import NationalImmunizationProgram from './NationalImmunizationTable.vue';
  
  export default {
    components: {
      ITRTable,
      PreNatalTable,
      NationalImmunizationProgram,
    },
    props: {
      patients: Array,
    },
    setup() {
      const tabs = [
        { name: 'ITR Table' },
        { name: 'PreNatal Table' },
        { name: 'National Immunization Program' },
      ];
  
      const selectedTab = ref(0); // Default to the first tab (ITR Table)
  
      const selectTab = (index) => {
        selectedTab.value = index;
      };
  
      return {
        tabs,
        selectedTab,
        selectTab,
      };
    },
  };
  </script>
  
  <style scoped>
  /* No custom styles needed as we are using Tailwind CSS */
  </style>
  