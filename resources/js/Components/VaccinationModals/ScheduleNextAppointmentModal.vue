<script setup>
import { defineProps, defineEmits, ref, computed } from "vue";

// Props for the patient object
const props = defineProps({
  patient: {
    type: Object,
    required: true,
    default: () => ({
      name: "Unknown",
      age: "Unknown",
      vaccineType: "Unknown",
      nextAppointment: "N/A",
    }),
  },
});

const emit = defineEmits(["close", "schedule"]);

// Local state for the appointment date
const appointmentDate = ref(props.patient.nextAppointment || "");

// Computed property to validate the date
const isDateValid = computed(() => {
  const selectedDate = new Date(appointmentDate.value);
  const today = new Date();
  return appointmentDate.value && selectedDate >= today;
});

// Method to handle scheduling the appointment
const handleSchedule = () => {
  if (!isDateValid.value) {
    alert("Please select a valid appointment date.");
    return;
  }

  emit("schedule", { patientId: props.patient.id, date: appointmentDate.value });
  emit("close");
};

// Close the modal
const closeModal = () => {
  emit("close");
};
</script>

<template>
  <div
    class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50"
    role="dialog"
    aria-modal="true"
    aria-labelledby="schedule-appointment-modal"
  >
    <div class="bg-white p-6 rounded-lg w-full max-w-lg shadow-lg">
      <!-- Header -->
      <h2 id="schedule-appointment-modal" class="text-xl font-semibold text-green-700 mb-4">
        Schedule Next Appointment for {{ patient.name }}
      </h2>

      <!-- Patient Details -->
      <div class="mb-6 border-b pb-4 text-sm text-gray-700">
        <p><strong>Name:</strong> {{ patient.name || "N/A" }}</p>
        <p><strong>Age:</strong> {{ patient.age || "N/A" }}</p>
        <p><strong>Vaccine Type:</strong> {{ patient.vaccineType || "N/A" }}</p>
      </div>

      <!-- Appointment Date Input -->
      <div class="mb-4">
        <label for="appointment-date" class="block text-sm font-medium text-gray-700">
          Appointment Date
        </label>
        <input
          id="appointment-date"
          type="date"
          v-model="appointmentDate"
          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 text-sm"
          :class="{ 'border-red-500': !isDateValid && appointmentDate }"
          aria-invalid="true"
        />
        <p v-if="!isDateValid && appointmentDate" class="text-red-500 text-xs mt-1">
          Please select a valid date that is today or later.
        </p>
      </div>

      <!-- Buttons -->
      <div class="flex justify-end gap-4 mt-6">
        <button
          @click="closeModal"
          class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600"
        >
          Cancel
        </button>
        <button
          @click="handleSchedule"
          :disabled="!isDateValid"
          class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          Save
        </button>
      </div>
    </div>
  </div>
</template>
