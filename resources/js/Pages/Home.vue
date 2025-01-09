<script setup>
import { defineProps , ref} from 'vue';
import { Head } from '@inertiajs/vue3';
import Carousel from '@/Components/Carousel.vue';
import Navbar from '@/Components/Navbar.vue';
import About from '@/Components/AboutSection.vue';
import Contact from '@/Components/ContactForm.vue';

// Get the authentication status passed from the parent component (controller)
const props = defineProps({
  isAuthenticated: Boolean,
  userRole: String,
});

console.log (props.userRole)
</script>

<template>
  <Head title="Home" />
  <div class="min-h-screen bg-white text-gray-800">
    <!-- Navigation Bar with dynamic isAuthenticated prop -->
    <Navbar :isAuthenticated="props.isAuthenticated" :userRole="props.userRole" />

    <!-- Hero Section with Carousel -->
    <section id="hero" class="bg-green-600 text-white mt-16">
      <Carousel />
    </section>

    <!-- About Us Section -->
    <section id="about" class="bg-gray-100">
      <About />
    </section>

    <!-- Contact Section -->
    <section id="contact" class=" bg-white">
      <Contact />
    </section>
  </div>
</template>
