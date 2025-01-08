<script setup>
import { computed } from 'vue';

const props = defineProps({
  distributionData: {
    type: Array,
    default: () => [],
  },
});

// Optional computed for any sorting, grouping, etc.
const sortedDistribution = computed(() => {
  return [...props.distributionData].sort((a, b) => b.count - a.count);
});
</script>

<template>
  <div class="bg-white rounded p-6 shadow hover:shadow-md transition-shadow">
    <h2 class="text-xl font-bold mb-4">User Distribution</h2>
    <table class="w-full border-collapse text-left">
      <thead>
        <tr class="border-b border-gray-200">
          <th class="p-2">Role</th>
          <th class="p-2 text-right">Count</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(item, index) in sortedDistribution"
          :key="index"
          class="border-b border-gray-100 hover:bg-gray-50"
        >
          <td class="p-2">{{ item.department }}</td>
          <td class="p-2 text-right">{{ item.count }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<style scoped>
/* Adjust table styling here if needed */
</style>
