<template>
  <div>
    <!-- Modal Overlay -->
    <div class="fixed inset-0 bg-pink-200 bg-opacity-90 flex items-center justify-center z-50 p-4">
      <!-- Modal Content -->
      <div class="bg-white shadow-lg rounded-lg p-8 max-w-4xl w-full relative">
        <!-- Close Button -->
        <button @click="closeModal" class="absolute top-4 right-4 text-pink-500 hover:text-pink-700 text-xl font-bold">
          ✕
        </button>

        <!-- Check if existingData exists -->
        <div v-if="existingData && !isEditing">
          <h2 class="text-xl font-bold text-pink-600">Prenatal Checkup Details</h2>
          <p class="text-sm text-gray-600 mb-6">Details for {{ existingData.firstName }}  {{ existingData.middleName }} {{ existingData.lastName }}</p>

          <!-- Display Existing Data -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <h3 class="font-semibold text-pink-500">Baby Information</h3>
              <p>Full Name: {{ existingData.firstName }} {{ existingData.lastName }}</p>
              <p>Sex: {{ existingData.sex }}</p>
              <!-- Add other fields as needed -->
            </div>

            <div>
              <h3 class="font-semibold text-pink-500">Delivery Details</h3>
              <p>Birth Length: {{ existingData.birthLength }}</p>
              <p>Birth Weight: {{ existingData.birthWeight }}</p>
              <p>Delivery Date: {{ existingData.deliveryDate }}</p>
              <p>Delivery Time: {{ existingData.deliveryTime }}</p>
              <!-- Add other fields as needed -->
            </div>

            <div>
              <h3 class="font-semibold text-pink-500">Breastfeeding</h3>
              <p>Date Initiated Breastfeeding: {{ existingData.dateInitiatedBreastfeeding }}</p>
              <p>Time Initiated Breastfeeding: {{ existingData.timeInitiatedBreastfeeding }}</p>
              <!-- Add other fields as needed -->
            </div>

            <div>
              <h3 class="font-semibold text-pink-500">Breastfeeding</h3>
              <p>Date Vitamin A: {{ existingData.dateVitaminA }}</p>
              <p>Danger Signs Mother: {{ existingData.dangerSignsMother }}</p>
              <!-- Add other fields as needed -->
            </div>
          </div>

          <!-- Edit Button -->
          <div class="mt-4">
            <button
              @click="enableEdit"
              class="px-4 py-2 bg-pink-500 text-white rounded-md shadow hover:bg-pink-600 transition">
              Edit Details
            </button>
          </div>
        </div>

        <!-- Form -->
        <div v-else>
        <form @submit.prevent="handleSubmit" class="space-y-6">
          <!-- Step Navigation -->
          <div class="flex justify-between items-center pb-4 border-b border-pink-300">
            <button v-for="(step, index) in steps" :key="index" type="button"
              class="px-4 py-2 rounded-md text-sm font-semibold transition" :class="{
                'bg-pink-500 text-white shadow': currentStep === index,
                'bg-pink-100 text-pink-600 hover:bg-pink-200': currentStep !== index,
              }" @click="navigateToStep(index)">
              {{ step.title }}
            </button>

          </div>

          <!-- Step 1: Prenatal Outcome -->
          <div v-if="currentStep === 0" class="mt-6 space-y-4">
            <h2 class="text-lg font-semibold text-pink-600">Prenatal Outcome</h2>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label for="first_name" class="block text-sm font-medium text-pink-700">First Name</label>
                <input v-model="form.firstName" type="text" id="first_name" placeholder="John"
                  class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" />
                <p v-if="errors.firstName" class="text-red-500 text-sm">{{ errors.firstName }}</p>
              </div>
              <div>
                <label for="last_name" class="block text-sm font-medium text-pink-700">Last Name</label>
                <input v-model="form.lastName" type="text" id="last_name" placeholder="Dela Cruz"
                  class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" />
                <p v-if="errors.lastName" class="text-red-500 text-sm">{{ errors.lastName }}</p>
              </div>
              <div>
                <label for="middle_name" class="block text-sm font-medium text-pink-700">Middle Name</label>
                <input v-model="form.middleName" type="text" id="middle_name" placeholder="Delos Santos"
                  class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" />
              </div>
              <div>
                <label for="sex" class="block text-sm font-medium text-pink-700">Sex (M / F)</label>
                <select v-model="form.sex" id="sex"
                  class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500">
                  <option value="">Select</option>
                  <option value="M">Male</option>
                  <option value="F">Female</option>
                </select>
                <p v-if="errors.sex" class="text-red-500 text-sm">{{ errors.sex }}</p>
              </div>
            </div>
          </div>

          <!-- Step 2: Delivery Details -->
          <div v-if="currentStep === 1" class="mt-6 space-y-4">
            <h2 class="text-lg font-semibold text-pink-600">Delivery Details</h2>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label for="birth_length" class="block text-sm font-medium text-pink-700">Birth Length</label>
                <input v-model="form.birthLength" type="number" step="0.01" id="birth_length"
                  placeholder="Enter birth length in cm"
                  class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" />
                <p v-if="errors.birthLength" class="text-red-500 text-sm">{{ errors.birthLength }}</p>
              </div>
              <div>
                <label for="birth_weight" class="block text-sm font-medium text-pink-700">Birth Weight</label>
                <input v-model="form.birthWeight" type="number" step="0.01" id="birth_weight"
                  placeholder="Enter birth weight in kg"
                  class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" />
                <p v-if="errors.birthWeight" class="text-red-500 text-sm">{{ errors.birthWeight }}</p>
              </div>
              <div>
                <label for="delivery_date" class="block text-sm font-medium text-pink-700">Delivery Date</label>
                <input v-model="form.deliveryDate" type="date" id="delivery_date" placeholder="Select delivery date"
                  class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" />
                <p v-if="errors.deliveryDate" class="text-red-500 text-sm">{{ errors.deliveryDate }}</p>
              </div>
              <div>
                <label for="delivery_time" class="block text-sm font-medium text-pink-700">Delivery Time</label>
                <input v-model="form.deliveryTime" type="time" id="delivery_time" placeholder="Select delivery time"
                  class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" />
                <p v-if="errors.deliveryTime" class="text-red-500 text-sm">{{ errors.deliveryTime }}</p>
              </div>
            </div>
          </div>


          <!-- Step 3: Breastfeeding -->
          <div v-if="currentStep === 2" class="mt-6 space-y-4">
            <h2 class="text-lg font-semibold text-pink-600">Breastfeeding</h2>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label for="date_initiated_breastfeeding" class="block text-sm font-medium text-pink-700">Date Initiated
                  Breastfeeding</label>
                <input v-model="form.dateInitiatedBreastfeeding" type="date" id="date_initiated_breastfeeding"
                  class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" />
                <p v-if="errors.dateInitiatedBreastfeeding" class="text-red-500 text-sm">{{
                  errors.dateInitiatedBreastfeeding }}</p>
              </div>
              <div>
                <label for="time_initiated_breastfeeding" class="block text-sm font-medium text-pink-700">Time Initiated
                  Breastfeeding</label>
                <input v-model="form.timeInitiatedBreastfeeding" type="time" id="time_initiated_breastfeeding"
                  class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" />
                <p v-if="errors.timeInitiatedBreastfeeding" class="text-red-500 text-sm">{{
                  errors.timeInitiatedBreastfeeding }}</p>
              </div>
            </div>
          </div>

          <!-- Step 4: Postpartum Details -->
          <div v-if="currentStep === 3" class="mt-6 space-y-4">
            <h2 class="text-lg font-semibold text-pink-600">Postpartum Details</h2>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label for="date_vitamin_a" class="block text-sm font-medium text-pink-700">Date Vitamin A Given</label>
                <input v-model="form.dateVitaminA" type="date" id="date_vitamin_a"
                  placeholder="Select date Vitamin A was given"
                  class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" />
                <p v-if="errors.dateVitaminA" class="text-red-500 text-sm">{{ errors.dateVitaminA }}</p>
              </div>
              <div>
                <label for="danger_signs_mother" class="block text-sm font-medium text-pink-700">Danger Signs
                  (Mother)</label>
                <textarea v-model="form.dangerSignsMother" id="danger_signs_mother"
                  placeholder="Describe any danger signs for the mother"
                  class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500"></textarea>
                <p v-if="errors.dangerSignsMother" class="text-red-500 text-sm">{{ errors.dangerSignsMother }}</p>

              </div>
            </div>
          </div>


          <!-- Navigation Buttons -->
          <div class="flex justify-between items-center pt-4 border-t border-pink-300">
            <button type="button"
              class="px-4 py-2 bg-pink-100 text-pink-600 rounded-md shadow hover:bg-pink-200 transition"
              :disabled="currentStep === 0" @click="handlePreviousStep">
              Previous
            </button>
            <button v-if="currentStep < steps.length - 1" type="button"
              class="px-4 py-2 bg-pink-500 text-white rounded-md shadow hover:bg-pink-600 transition"
              @click="handleNextStep">
              Next
            </button>
            <button v-else type="submit"
              class="px-4 py-2 bg-pink-500 text-white rounded-md shadow hover:bg-pink-600 transition">
              Submit
            </button>
          </div>

        </form>
      </div>
      </div>
    </div>
  </div>
</template>



<script>
import { router } from '@inertiajs/vue3';

export default {
  props: {
    patient: {
      type: Object,
      required: true,
    },
    existingData: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      isEditing: false, // Determines if we are in edit mode
      currentStep: 0,
      steps: [
        { title: 'Basic Info' },
        { title: 'Delivery Details' },
        { title: 'Breastfeeding' },
        { title: 'Postpartum Details' },
      ],
      form: {
        prenatalId: this.patient?.prenatalId || '',
        lastName: '',
        firstName: '',
        middleName: '',
        sex: '',
        birthLength: '',
        birthWeight: '',
        deliveryDate: '',
        deliveryTime: '',
        dateInitiatedBreastfeeding: '',
        timeInitiatedBreastfeeding: '',
        dateVitaminA: '',
        dangerSignsMother: '',
      },
      errors: {},
    };
  },
  watch: {
    existingData: {
      immediate: true,
      handler(newData) {
        if (newData) {
          this.form = {
            ...this.form,
            ...newData,
          };
        }
      },
    },
  },
  methods: {
    enableEdit() {
      this.isEditing = true;
      this.form = { ...this.existingData }; // Pre-fill form with existing data
    },
    navigateToStep(index) {
      if (index > this.currentStep && !this.validateStep()) {
        // Prevent moving to the next step if validation fails
        return;
      }
      this.currentStep = index;
    },
    validateStep() {
      this.errors = {};

      // Step 1: Basic Info validations
      if (this.currentStep === 0) {
        if (!this.form.firstName) this.errors.firstName = 'First name is required.';
        if (!this.form.lastName) this.errors.lastName = 'Last name is required.';
        if (!this.form.sex) this.errors.sex = 'Please select a valid sex.';
      }

      // Step 2: Delivery Details validations
      if (this.currentStep === 1) {
        if (!this.form.birthLength) this.errors.birthLength = 'Birth length is required.';
        if (!this.form.birthWeight) this.errors.birthWeight = 'Birth weight is required.';
        if (!this.form.deliveryDate) this.errors.deliveryDate = 'Delivery date is required.';
        if (!this.form.deliveryTime) this.errors.deliveryTime = 'Delivery time is required.';
      }

      // Step 3: Breastfeeding validations
      if (this.currentStep === 2) {
        if (!this.form.dateInitiatedBreastfeeding) {
          this.errors.dateInitiatedBreastfeeding = 'Date breastfeeding initiated is required.';
        }
        if (!this.form.timeInitiatedBreastfeeding) {
          this.errors.timeInitiatedBreastfeeding = 'Time breastfeeding initiated is required.';
        }
      }

      // Step 4: Postpartum Details validations
      if (this.currentStep === 3) {
        if (!this.form.dateVitaminA) this.errors.dateVitaminA = 'Date Vitamin A given is required.';
        if (!this.form.dangerSignsMother) {
          this.errors.dangerSignsMother = 'Please describe any danger signs for the mother.';
        }
      }

      return Object.keys(this.errors).length === 0;
    },
    handleNextStep() {
      if (this.validateStep()) {
        this.currentStep++;
      }
    },
    handlePreviousStep() {
      if (this.currentStep > 0) {
        this.currentStep--;
      }
    },
    handleSubmit() {
      if (this.existingData) {
        // No validation for existing data; directly submit it
        const url = `/postpartum/${this.existingData.postpartumId}`;
        router.post(url, this.form, {
          onSuccess: (response) => {
            console.log('Form updated successfully:', response);
            this.$emit('close');
          },
          onError: (errors) => {
            console.error('Error updating form:', errors);
            if (errors.response && errors.response.data) {
              this.errors = errors.response.data.errors || {}; // Handle server-side validation errors
            }
          },
        });
      } else {
        // Validate new data before submission
        if (this.validateStep()) {
          const url = '/postpartum/store';
          router.post(url, this.form, {
            onSuccess: (response) => {
              console.log('Form submitted successfully:', response);
              this.$emit('close');
            },
            onError: (errors) => {
              console.error('Error submitting form:', errors);
              if (errors.response && errors.response.data) {
                this.errors = errors.response.data.errors || {};
              }
            },
          });
        }
      }
    },

    closeModal() {
      this.$emit('close');
    },
  },
};
</script>
