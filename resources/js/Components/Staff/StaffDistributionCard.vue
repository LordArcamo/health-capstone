<script setup>
import { computed, onMounted, watch } from 'vue';

const props = defineProps({
  distributionData: {
    type: Array,
    required: true,
    default: () => [
      { role: 'Doctor', count: 0 },
      { role: 'Staff', count: 0 }
    ]
  },
});

// Debug: Log when component mounts
onMounted(() => {
  console.log('StaffDistributionCard mounted with data:', props.distributionData);
});

// Debug: Watch for changes in distributionData
watch(() => props.distributionData, (newVal, oldVal) => {
  console.log('StaffDistributionCard distributionData changed:', {
    from: oldVal,
    to: newVal
  });
}, { deep: true });

// Sort distribution data by count (highest first)
const sortedDistribution = computed(() => {
  console.log('Computing sortedDistribution with:', props.distributionData);
  
  if (!Array.isArray(props.distributionData)) {
    console.warn('distributionData is not an array:', props.distributionData);
    return [
      { role: 'Doctor', count: 0 },
      { role: 'Staff', count: 0 }
    ];
  }
  
  const sorted = [...props.distributionData].sort((a, b) => b.count - a.count);
  console.log('Sorted distribution:', sorted);
  return sorted;
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
          <td class="p-2">{{ item.role }}</td>
          <td class="p-2 text-right">{{ item.count }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<style scoped>
/* Adjust table styling here if needed */
</style>