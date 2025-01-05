<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import TopBar from '../Components/Topbar.vue';
import { usePage } from '@inertiajs/vue3';

// Extract props from Inertia
const { props } = usePage();
const role = computed(() => props.auth?.user?.role || ''); // Computed to track reactive updates

// Log role for debugging
console.log('Role being passed to TopBar:', role.value);

// Reactive variable for topbar visibility
const showTopBar = ref(true);
let lastScrollY = window.scrollY;

// Scroll event handler
const handleScroll = () => {
  const currentScrollY = window.scrollY;
  showTopBar.value = currentScrollY <= lastScrollY; // Show when scrolling up, hide when scrolling down
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
    <header v-if="$slots.header" class="bg-black-100 shadow-md border-b">
      <div class="flex justify-between items-center w-full py-4 px-4">
        <slot name="header" />
      </div>
    </header>

    <main class="flex-1">
      <div
        class="sticky top-0 z-50 transition-transform duration-300"
        :class="{ '-translate-y-full': !showTopBar, 'translate-y-0': showTopBar }"
      >
      <TopBar :role="role" />
    </div>

      <div class="rounded-none shadow-none w-full">
        <slot />
      </div>
    </main>
  </div>
</template>
