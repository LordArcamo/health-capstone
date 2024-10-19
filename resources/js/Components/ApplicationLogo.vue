<script lang="ts">
import { defineComponent } from 'vue'; 
import Logo from '@/Images/RHU Logo.png'; // Import your logo image

export default defineComponent({
  name: 'ApplicationLogo',
  setup() {
    return {
      logo: Logo, // Provide the logo path for binding
    };
  },
});
</script>

<template>
  <div class="flex items-center justify-center h-full">
    <img :src="logo" alt="RHU Logo" class="h-24 w-auto object-contain" />
  </div>
</template>

<style scoped>
/* Optional: Scoped styles for more customization, if needed */
</style>
