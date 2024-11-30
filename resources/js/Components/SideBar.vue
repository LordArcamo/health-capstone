<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import Logo from '@/Images/RHU Logo.png'; // Import logo
</script>

<template>
  <div class="relative flex">
    <!-- Sidebar (Visible only on larger screens) -->
    <div
      class="fixed top-0 left-0 h-screen w-48 bg-white text-gray-800 pt-5 px-3 "
    >
      <!-- Logo -->
      <div @click="$inertia.visit('/dashboard')" class="cursor-pointer flex text-bold items-center justify-center mb-10">
        <img :src="Logo" alt="RHU Logo" class=" w-auto" />
        <!-- <span class="font-bold text-lg">RHU INITAO</span> -->
      </div>

      <!-- Navigation -->
      <nav class="flex flex-col space-y-2">
        <Link href="/profile" class="block py-2.5 px-4 rounded hover:bg-gray-100 flex items-center"
          :class="{ 'bg-green-100': $page.component === 'Profile' }">
          <font-awesome-icon :icon="['fas', 'heartbeat']" class="mr-3" /> Profile
        </Link>

        <Link :href="route('logout')" method="post" as="button" class="block py-2.5 px-4 rounded hover:bg-gray-100 flex items-center">
          <font-awesome-icon :icon="['fas', 'clipboard']" class="mr-3" /> Logout
        </Link>
      </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-5 lg:ml-48">
      <slot />
    </div>
  </div>
</template>

<style scoped>
/* Hide sidebar on mobile devices */
@media (max-width: 1024px) {
  /* Completely hide the sidebar on mobile */
  div {
    display: none;
  }
}
</style>
