<script setup>
import { ref } from 'vue';

// Define the props and emits
const props = defineProps({
  patient: Object, // The patient object passed from the parent
});

const emit = defineEmits(['close', 'schedule']); // Emit events to close modal or schedule appointment

// Reactive state for appointment date
const nextAppointmentDate = ref('');

// Methods
const closeModal = () => {
  emit('close'); // Emit the 'close' event to the parent to close the modal
};

const scheduleAppointment = () => {
  if (!nextAppointmentDate.value) {
    alert('Please select a date for the next appointment.');
    return;
  }
  // Emit the 'schedule' event with the selected date and patient data
  emit('schedule', { patient: props.patient, date: nextAppointmentDate.value });
  closeModal();
};
</script>

<template>
  <div class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg w-full max-w-lg">
      <h2 class="text-xl font-semibold mb-4">Schedule Next Appointment</h2>
      <p class="mb-4">
        Patient: <span class="font-semibold">{{ patient.name }}</span>
      </p>
      <div class="mb-4">
        <label for="nextAppointment" class="block text-sm font-medium text-gray-700">Select Date</label>
        <input
          type="date"
          id="nextAppointment"
          v-model="nextAppointmentDate"
          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
        />
      </div>
      <div class="flex justify-end mt-4">
        <button
          @click="closeModal"
          class="bg-gray-500 text-white px-4 py-2 rounded-md mr-2 hover:bg-gray-600"
        >
          Cancel
        </button>
        <button
          @click="scheduleAppointment"
          class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600"
        >
          Schedule
        </button>
      </div>
    </div>
  </div>
</template>
