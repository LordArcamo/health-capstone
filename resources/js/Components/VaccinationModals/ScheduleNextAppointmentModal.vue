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
        <p><strong>Name:</strong> {{ patient.firstName || "N/A" }} {{ patient.middleName || "" }} {{ patient.lastName || "N/A" }}</p>
        <p><strong>Age:</strong> {{ patient.age || "N/A" }}</p>
        <p><strong>Vaccine Category:</strong> {{ form.vaccineCategory || "N/A" }}</p>
        <p><strong>Vaccine Type:</strong> {{ form.vaccineType || "N/A" }}</p>
      </div>

      <!-- Form Fields -->
      <div class="space-y-4">
        <h3 class="text-lg font-semibold mb-6">Vaccination Record</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Date of Visit -->
          <div>
            <label for="dateOfVisit" class="block text-sm font-medium text-gray-700">
              Date of Visit
            </label>
            <input type="date" v-model="form.dateOfVisit" id="dateOfVisit" class="input" required />
            <span v-if="errors.dateOfVisit" class="text-red-600 text-sm">{{ errors.dateOfVisit }}</span>
          </div>

          <!-- Weight -->
          <div>
            <label for="weight" class="block text-sm font-medium text-gray-700">
              Weight
            </label>
            <input type="text" v-model="form.weight" id="weight" class="input" placeholder="Enter weight (e.g., 10 kg)" required />
            <span v-if="errors.weight" class="text-red-600 text-sm">{{ errors.weight }}</span>
          </div>

          <!-- Height -->
          <div>
            <label for="height" class="block text-sm font-medium text-gray-700">
              Height
            </label>
            <input type="text" v-model="form.height" id="height" class="input" placeholder="Enter height (e.g., 75 cm)" required />
            <span v-if="errors.height" class="text-red-600 text-sm">{{ errors.height }}</span>
          </div>

          <!-- Temperature -->
          <div>
            <label for="temperature" class="block text-sm font-medium text-gray-700">
              Temperature
            </label>
            <input type="text" v-model="form.temperature" id="temperature" class="input" placeholder="Enter temperature (e.g., 36.5°C)" required />
            <span v-if="errors.temperature" class="text-red-600 text-sm">{{ errors.temperature }}</span>
          </div>

          <!-- Antigen Given -->
          <div>
            <label for="antigenGiven" class="block text-sm font-medium text-gray-700">
              Antigen Given
            </label>
            <input type="text" v-model="form.antigenGiven" id="antigenGiven" class="input" placeholder="Enter antigen details" required />
            <span v-if="errors.antigenGiven" class="text-red-600 text-sm">{{ errors.antigenGiven }}</span>
          </div>


          <div v-if="isUnderOneYear">
  <label for="exclusivelyBreastfed" class="block text-sm font-medium text-gray-700">
    Exclusively Breastfed
  </label>
  <select v-model="form.exclusivelyBreastfed" id="exclusivelyBreastfed" class="input">
    <option disabled value="">Select</option>
    <option value="Yes">Yes</option>
    <option value="No">No</option>
  </select>
  <span v-if="errors.exclusivelyBreastfed" class="text-red-600 text-sm">
    {{ errors.exclusivelyBreastfed }}
  </span>
</div>


          <!-- Injected By -->
          <div>
            <label for="injectedBy" class="block text-sm font-medium text-gray-700">
              Injected By
            </label>
            <input type="text" v-model="form.injectedBy" id="injectedBy" class="input" placeholder="Enter name of injector" required />
            <span v-if="errors.injectedBy" class="text-red-600 text-sm">{{ errors.injectedBy }}</span>
          </div>

          <!-- Next Appointment -->
          <div>
            <label for="nextAppointment" class="block text-sm font-medium text-gray-700">
              Next Appointment
            </label>
            <input type="date" v-model="form.nextAppointment" id="nextAppointment" class="input" required />
            <span v-if="errors.nextAppointment" class="text-red-600 text-sm">{{ errors.nextAppointment }}</span>
          </div>
        </div>
      </div>

      <!-- Buttons -->
      <div class="flex justify-end gap-4 mt-6">
        <button
          @click="closeModal"
          type="button"
          class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 disabled:opacity-50"
        >
          Cancel
        </button>
        <button
          @click="handleSchedule"
          type="button"
          class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 disabled:opacity-50"
          :disabled="loading"
        >
          Save Appointment
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';

export default {
  props: {
    patient: {
      type: Object,
      required: true,
    },
  },
  emits: ['close'], // Declare the 'close' event
  setup(props, { emit }) {
    const form = ref({
      vaccineCategory: props.patient.vaccineCategory || '',
      vaccineType: props.patient.vaccineType || '',
      dateOfVisit: '',
      weight: '',
      height: '',
      temperature: '',
      antigenGiven: '',
      injectedBy: '',
      nextAppointment: '',
      exclusivelyBreastfed: '', // New field
    });

    const errors = ref({});
    const loading = ref(false);

    // Determine if the patient is under one year old
    const isUnderOneYear = computed(() => {
      return props.patient.age && props.patient.age < 1; // Adjust this logic based on your data structure
    });

    // Handle scheduling form submission
    const handleSchedule = async () => {
      loading.value = true;
      errors.value = {};

      try {
        await Inertia.post('/appointments/store', form.value);
        alert('Appointment saved successfully!');
        closeModal(); // Close the modal after successful submission
      } catch (error) {
        console.error(error);
        errors.value = error.response?.data?.errors || {};
      } finally {
        loading.value = false;
      }
    };

    // Close the modal and reset the form
    const closeModal = () => {
      // Reset form data
      form.value = {
        vaccineCategory: '',
        vaccineType: '',
        dateOfVisit: '',
        weight: '',
        height: '',
        temperature: '',
        antigenGiven: '',
        injectedBy: '',
        nextAppointment: '',
        exclusivelyBreastfed: '', // New field
      };

      // Emit the close event to the parent
      emit('close');
    };

    return {
      form,
      errors,
      loading,
      handleSchedule,
      isUnderOneYear,
      closeModal,
    };
  },
};
</script>

<style>
.input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 0.375rem;
  font-size: 0.875rem;
}
.input:focus {
  outline: none;
  border-color: #38b2ac;
  box-shadow: 0 0 0 3px rgba(56, 178, 172, 0.25);
}
</style>
