<template>
    <div
      v-if="visible"
      class="fixed top-5 right-5 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-lg z-50 flex items-center space-x-3"
      role="alert"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        class="h-6 w-6 text-green-500"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M9 12l2 2l4-4m0 4v6m-6 0v-6a9 9 0 1118 0v6a9 9 0 01-18 0v-6"
        />
      </svg>
      <span>{{ message }}</span>
      <button @click="closeAlert" class="text-green-700 hover:text-green-900">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-5 w-5"
          viewBox="0 0 20 20"
          fill="currentColor"
        >
          <path
            fill-rule="evenodd"
            d="M10 9.293l3.293-3.293a1 1 0 011.414 1.414L11.414 10l3.293 3.293a1 1 0 01-1.414 1.414L10 11.414l-3.293 3.293a1 1 0 01-1.414-1.414L8.586 10 5.293 6.707a1 1 0 111.414-1.414L10 8.586z"
            clip-rule="evenodd"
          />
        </svg>
      </button>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      message: {
        type: String,
        required: true,
      },
      duration: {
        type: Number,
        default: 1000, // Default alert duration (5 seconds)
      },
    },
    data() {
      return {
        visible: true,
      };
    },
    methods: {
      closeAlert() {
        this.visible = false;
      },
    },
    mounted() {
      setTimeout(() => {
        this.visible = false;
      }, this.duration);
    },
  };
  </script>
  
  <style scoped>
  /* Optional additional styling */
  </style>
  