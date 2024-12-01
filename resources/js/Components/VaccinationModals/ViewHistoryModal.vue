<script setup>
import { defineProps, defineEmits } from 'vue';

// Define the props and emits
const props = defineProps({
  patient: Object, // The patient object passed from the parent
  history: {
    type: Array,
    default: () => [], // Default to empty array if history is not provided
  },
});
const emit = defineEmits(['close']); // Emit event to close the modal

// Methods
const closeModal = () => {
  emit('close');
};
</script>

<template>
  <div class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg w-full max-w-lg">
      <h2 class="text-xl font-semibold mb-4">History for {{ patient.name }}</h2>

      <!-- Check if history has records -->
      <div v-if="history.length > 0" class="space-y-4">
        <div v-for="record in history" :key="record.id" class="border p-4 rounded-lg shadow-sm">
          <p><span class="font-semibold">Date:</span> {{ record.date }}</p>
          <p><span class="font-semibold">Details:</span> {{ record.details }}</p>
        </div>
      </div>
      
      <!-- Fallback message if no history -->
      <div v-else class="text-center text-gray-500">
        <p>No history records available for this patient.</p>
      </div>

      <div class="flex justify-end mt-4">
        <button
          @click="closeModal"
          class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600"
        >
          Close
        </button>
      </div>
    </div>
  </div>
</template>
