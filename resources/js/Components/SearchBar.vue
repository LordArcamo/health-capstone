<!-- src/components/SearchBar.vue -->
<template>
    <div class="flex justify-center items-center">
      <!-- Wrapper for the search bar -->
      <div class="relative">
        <!-- Search Icon
        <span class="absolute inset-y-0 left-0 flex items-center pl-2">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-gray-400">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0a7.5 7.5 0 1110.35-10.35 7.5 7.5 0 01-10.35 10.35z" />
          </svg>
        </span> -->
  
        <!-- Search Input -->
        <input
          type="text"
          v-model="searchQuery"
          @input="emitSearchQuery"
          class="border border-gray-300 rounded-lg pl-10 pr-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          placeholder="Search..."
        />
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        searchQuery: '', // Bound to the input value
      };
    },
    methods: {
      // Emit the search query to parent components
      emitSearchQuery() {
        this.$emit('search', this.searchQuery);
      },
    },
  };
  </script>
  
  <style scoped>
  /* Optional additional styling */
  </style>
  