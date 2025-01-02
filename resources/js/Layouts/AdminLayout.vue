<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import TopBar from '@/Components/AdminTopbar.vue';

// Reactive variable to manage the visibility of the topbar
const showTopBar = ref(true);
let lastScrollY = window.scrollY;

// Scroll event handler
const handleScroll = () => {
  const currentScrollY = window.scrollY;

  // Show topbar when scrolling up, hide when scrolling down
  if (currentScrollY > lastScrollY) {
    showTopBar.value = false; // Scrolling down
  } else {
    showTopBar.value = true; // Scrolling up
  }
  lastScrollY = currentScrollY;
};

// Add and remove scroll listener
onMounted(() => {
  window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
  <div class="flex flex-col min-h-screen bg-gradient-to-br from-green-100 via-blue-100 to-blue-200 text-gray-800">
    <!-- Header -->
    <header v-if="$slots.header" class="bg-black-100 shadow-md border-b">
      <div class="flex justify-between items-center w-full py-4 px-4">
        <slot name="header" />
      </div>
    </header>

    <!-- Main Section -->
    <main class="flex-1">
      <!-- Sticky TopBar -->
      <div
        class="sticky top-0 z-50 transition-transform duration-300"
        :class="{ '-translate-y-full': !showTopBar, 'translate-y-0': showTopBar }"
      >
        <TopBar />
      </div>

      <!-- Main Content Area -->
      <div class="rounded-none shadow-none w-full">
        <slot />
      </div>
    </main>
  </div>
</template>
