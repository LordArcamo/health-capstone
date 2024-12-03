<template>
    <nav class="bg-white shadow-md fixed px-2 top-0 left-0 w-full z-50">
      <div class="container mx-auto py-2 flex items-center justify-between">
        <!-- Logo Section -->
        <div @click="$inertia.visit('/')" class="cursor-pointer flex items-center space-x-4">
          <img src="/images/RHU%20Logo.png" alt="Logo" class="h-16 w-16 md:h-20 md:w-20" />
          <span class="text-xl font-bold text-black-600 hidden md:block">Initao Regional Health Office</span>
        </div>
  
        <!-- Mobile Menu Button (Hamburger) -->
        <div class="lg:hidden flex items-center">
          <button @click="toggleMenu" class="text-green-600 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
          </button>
        </div>
  
        <!-- Desktop Menu -->
        <div class="hidden lg:flex items-center space-x-6">
          <ul class="flex space-x-6">
            <li>
              <a href="#hero" @click.prevent="$inertia.visit('/')" class="text-gray-800 hover:text-green-600 transition">Home</a>
            </li>
            <li><a href="#about" class="text-gray-800 hover:text-green-600 transition">About Us</a></li>
            <li><a href="#contact" class="text-gray-800 hover:text-green-600 transition">Contact</a></li>
          </ul>
  
          <!-- Conditional Login / Dashboard Button -->
          <button 
            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold"
            @click="goToAction"
          >
            {{ isAuthenticated ? 'Dashboard' : 'Login' }}
          </button>
        </div>
      </div>
  
      <!-- Mobile Menu (Dropdown) -->
      <div v-if="isMenuOpen" class="lg:hidden absolute top-20 left-0 w-full bg-white shadow-md z-40">
        <ul class="flex flex-col space-y-4 py-4 px-6">
          <li><a href="#hero" class="text-gray-800 hover:text-green-600 transition">Home</a></li>
          <li><a href="#about" class="text-gray-800 hover:text-green-600 transition">About Us</a></li>
          <li><a href="#contact" class="text-gray-800 hover:text-green-600 transition">Contact</a></li>
          <li>
            <button 
              class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold"
              @click="goToAction"
            >
              {{ isAuthenticated ? 'Dashboard' : 'Login' }}
            </button>
          </li>
        </ul>
      </div>
    </nav>
  </template>
  
  <script>
  export default {
    name: 'Navbar',
    props: {
      isAuthenticated: {
        type: Boolean,
        required: true
      }
    },
    data() {
      return {
        isMenuOpen: false
      };
    },
    methods: {
      goToAction() {
        if (this.isAuthenticated) {
          // Redirect to dashboard
          this.$inertia.visit('/dashboard');
        } else {
          // Redirect to login page
          this.$inertia.visit('/login');
        }
      },
      toggleMenu() {
        this.isMenuOpen = !this.isMenuOpen;
      }
    }
  };
  </script>
  
  <style scoped>
  /* Scoped styles for the navigation bar */
  @media (max-width: 1024px) {
    /* Adjust logo size on mobile */
    .text-xl {
      font-size: 1.25rem;
    }
    /* Adjust button font size on mobile */
    button {
      font-size: 0.875rem;
    }
  }
  
  /* For Mobile Menu: */
  div[v-if="isMenuOpen"] {
    display: block;
    transition: all 0.3s ease-in-out;
  }
  </style>
  