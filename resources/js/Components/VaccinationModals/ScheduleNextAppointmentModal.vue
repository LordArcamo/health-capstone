<script>
import { defineProps, defineEmits, ref, computed, onMounted } from "vue";
import { Inertia } from '@inertiajs/inertia';

export default {
  props: {
    patient: {
      type: Object,
      required: true,
      default: () => ({
        personalId: null,
        firstName: "Unknown",
        middleName: "",
        lastName: "Unknown",
        suffix: "",
        age: "Unknown",
        vaccineType: "Unknown",
        nextAppointment: "",
      }),
    },
  },
  emits: ["close", "schedule"],
  setup(props, { emit }) {
    const appointmentDate = ref("");
    const notes = ref("");
    const loading = ref(false);
    const error = ref(null);
    const vaccineType = ref(props.patient.vaccineType || "");

    const isDateValid = computed(() => {
      if (!appointmentDate.value) return false;
      const selectedDate = new Date(appointmentDate.value);
      const today = new Date();
      today.setHours(0, 0, 0, 0);
      return selectedDate >= today;
    });

    // Format date to YYYY-MM-DD for input field
    const formatDateForInput = (dateString) => {
      if (!dateString) return "";
      const date = new Date(dateString);
      return date.toISOString().split('T')[0];
    };

    // Fetch latest appointment data
    const fetchLatestAppointment = async () => {
      try {
        const response = await fetch(`/appointments/latest/${props.patient.personalId}`);
        const data = await response.json();
        
        if (data.success && data.appointment) {
          vaccineType.value = data.appointment.vaccineType || props.patient.vaccineType;
          notes.value = data.appointment.notes || "";
          appointmentDate.value = formatDateForInput(data.appointment.appointmentDate);
        }
      } catch (e) {
        console.error('Failed to fetch latest appointment:', e);
      }
    };

    onMounted(() => {
      fetchLatestAppointment();
    });

    const handleSchedule = async () => {
      if (!isDateValid.value) {
        error.value = "Please select a valid appointment date.";
        return;
      }

      loading.value = true;
      error.value = null;

      try {
        await Inertia.post('/appointments', {
          personalId: props.patient.personalId,
          appointmentDate: appointmentDate.value,
          vaccineType: vaccineType.value,
          notes: notes.value
        });
        
        emit("schedule", { 
          patientId: props.patient.personalId, 
          date: appointmentDate.value 
        });
        emit("close");
      } catch (e) {
        error.value = "An error occurred while scheduling the appointment.";
      } finally {
        loading.value = false;
      }
    };

    const closeModal = () => {
      emit("close");
    };

    return {
      appointmentDate,
      notes,
      loading,
      error,
      vaccineType,
      isDateValid,
      handleSchedule,
      closeModal,
    };
  },
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
        Schedule Next Appointment
      </h2>

      <!-- Patient Details -->
      <div class="mb-6 border-b pb-4 text-sm text-gray-700">
        <p><strong>Name:</strong> {{ patient.firstName || "N/A" }} {{ patient.middleName || "" }} {{ patient.lastName || "N/A" }} {{ patient.suffix || "" }}</p>
        <p><strong>Age:</strong> {{ patient.age || "N/A" }}</p>
        <p><strong>Vaccine Type:</strong> {{ vaccineType || "N/A" }}</p>
      </div>

      <!-- Error Message -->
      <div v-if="error" class="mb-4 p-2 bg-red-100 border border-red-400 text-red-700 rounded">
        {{ error }}
      </div>

      <!-- Form Fields -->
      <div class="space-y-4">
        <!-- Appointment Date Input -->
        <div>
          <label for="appointment-date" class="block text-sm font-medium text-gray-700">
            Appointment Date *
          </label>
          <input
            id="appointment-date"
            type="date"
            v-model="appointmentDate"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 text-sm"
            :class="{ 'border-red-500': !isDateValid && appointmentDate }"
            :aria-invalid="!isDateValid"
            required
          />
          <p v-if="!isDateValid && appointmentDate" class="text-red-500 text-xs mt-1">
            Please select a valid date that is today or later.
          </p>
        </div>

        <!-- Vaccine Type Input -->
        <div>
          <label for="vaccine-type" class="block text-sm font-medium text-gray-700">
            Vaccine Type
          </label>
          <input
            id="vaccine-type"
            type="text"
            v-model="vaccineType"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 text-sm"
          />
        </div>

        <!-- Notes Input -->
        <div>
          <label for="notes" class="block text-sm font-medium text-gray-700">
            Notes (Optional)
          </label>
          <textarea
            id="notes"
            v-model="notes"
            rows="3"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 text-sm"
            placeholder="Add any notes about this appointment..."
          ></textarea>
        </div>
      </div>

      <!-- Buttons -->
      <div class="flex justify-end gap-4 mt-6">
        <button
          @click="closeModal"
          type="button"
          class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 disabled:opacity-50"
          :disabled="loading"
        >
          Cancel
        </button>
        <button
          @click="handleSchedule"
          type="button"
          class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 disabled:opacity-50"
          :disabled="loading || !isDateValid"
        >
          {{ loading ? 'Scheduling...' : 'Schedule Appointment' }}
        </button>
      </div>
    </div>
  </div>
</template>
