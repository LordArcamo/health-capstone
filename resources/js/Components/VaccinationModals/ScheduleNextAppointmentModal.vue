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

          <!-- Age in Months or Years -->
          <div>
            <label for="age" class="block text-sm font-medium text-gray-700">
              {{ isUnderOneYear ? "Age in Months" : "Age in Years" }}
            </label>
            <input
              type="number"
              id="age"
              v-model="ageInInput"
              class="input"
              :placeholder="isUnderOneYear ? 'Age in months' : 'Age in years'"
              readonly
            />
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

          <!-- Exclusively Breastfed -->
          <div v-if="isUnderOneYear">
            <label for="exclusivelyBreastfed" class="block text-sm font-medium text-gray-700">
              Exclusively Breastfed
            </label>
            <select v-model="form.exclusivelyBreastfed" id="exclusivelyBreastfed" class="input">
              <option value="None">None</option>
              <option value="Yes">Yes</option>
              <option value="No">No</option>
            </select>
            <span v-if="errors.exclusivelyBreastfed" class="text-red-600 text-sm">{{ errors.exclusivelyBreastfed }}</span>
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
import { Inertia } from '@inertiajs/inertia';

export default {
  props: {
    patient: {
      type: Object,
      required: true,
    },
    vaccinationId: {
      type: [Number, String],
      required: true,
    },
  },
  data() {
    return {
      form: {
        vaccinationId: this.vaccinationId,
        vaccineCategory: this.patient.vaccineCategory || '',
        vaccineType: this.patient.vaccineType || '',
        dateOfVisit: '',
        weight: '',
        height: '',
        temperature: '',
        antigenGiven: '',
        injectedBy: '',
        nextAppointment: '',
        exclusivelyBreastfed: 'None',
      },
      errors: {},
      loading: false,
    };
  },
  computed: {
    isUnderOneYear() {
      return this.form.vaccineCategory === 'Under 1 Year';
    },
    ageInMonths() {
      if (!this.patient.birthdate) return 'N/A';
      const birthdate = new Date(this.patient.birthdate);
      const currentDate = new Date();
      const months =
        (currentDate.getFullYear() - birthdate.getFullYear()) * 12 +
        currentDate.getMonth() -
        birthdate.getMonth();
      return months >= 0 ? months : 'Invalid date';
    },
    ageInInput() {
      return this.isUnderOneYear
        ? this.ageInMonths
        : Math.floor(this.ageInMonths / 12);
    },
  },
  methods: {
    resetForm() {
      this.form = {
        vaccinationId: this.vaccinationId,
        vaccineCategory: this.patient.vaccineCategory || '',
        vaccineType: this.patient.vaccineType || '',
        dateOfVisit: '',
        weight: '',
        height: '',
        temperature: '',
        antigenGiven: '',
        injectedBy: '',
        nextAppointment: '',
        exclusivelyBreastfed: 'None',
      };
      this.errors = {};
    },
    async handleSchedule() {
      this.loading = true;
      this.errors = {};

      try {
        // Convert form values to appropriate types
        const formData = {
          vaccinationId: parseInt(this.form.vaccinationId),
          dateOfVisit: this.form.dateOfVisit,
          weight: parseFloat(this.form.weight),
          height: parseFloat(this.form.height),
          temperature: parseFloat(this.form.temperature),
          antigenGiven: this.form.antigenGiven,
          injectedBy: this.form.injectedBy,
          nextAppointment: this.form.nextAppointment,
          exclusivelyBreastfed: this.form.exclusivelyBreastfed || 'None'
        };

        console.log('Sending appointment data:', formData);

        await Inertia.post('/appointments/store', formData, {
          onSuccess: (page) => {
            if (page.props.flash.success) {
              alert('Appointment saved successfully!');
              this.resetForm();
              this.$emit('close');
            } else if (page.props.flash.error) {
              this.errors = { general: page.props.flash.error };
            }
          },
          onError: (errors) => {
            console.error('Validation errors:', errors);
            this.errors = errors;
          },
          preserveState: true,
          preserveScroll: true
        });
      } catch (error) {
        console.error('Error saving appointment:', error);
        this.errors = { general: 'Failed to save appointment' };
      } finally {
        this.loading = false;
      }
    },
    closeModal() {
      this.resetForm();
      this.$emit('close');
    },
  },
  mounted() {
    console.log('Component mounted with patient:', this.patient);
    console.log('Vaccination ID:', this.vaccinationId);
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
