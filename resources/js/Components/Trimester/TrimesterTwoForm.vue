<template>
  <div class="max-h-screen overflow-y-scroll p-6 bg-white shadow rounded-lg">
    <form @submit.prevent="submitForm" class="space-y-4">
      <!-- Date of Visit -->
      <div>
        <label for="date_of_visit" class="block text-sm font-medium text-gray-700">Date of Visit</label>
        <input v-model="form.date_of_visit" type="date" id="date_of_visit" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required />
      </div>

      <!-- Weight -->
      <div>
        <label for="weight" class="block text-sm font-medium text-gray-700">Weight (kg)</label>
        <input v-model="form.weight" type="number" step="0.1" id="weight" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required />
      </div>

      <!-- Blood Pressure and Heart Rate -->
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="bp" class="block text-sm font-medium text-gray-700">BP</label>
          <input v-model="form.bp" type="text" id="bp" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required />
        </div>
        <div>
          <label for="heart_rate" class="block text-sm font-medium text-gray-700">Heart Rate</label>
          <input v-model="form.heart_rate" type="number" id="heart_rate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required />
        </div>
      </div>

      <!-- Age of Gestation -->
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="aog_months" class="block text-sm font-medium text-gray-700">AOG (Months)</label>
          <input v-model="form.aog_months" type="number" id="aog_months" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        </div>
        <div>
          <label for="aog_days" class="block text-sm font-medium text-gray-700">AOG (Days)</label>
          <input v-model="form.aog_days" type="number" id="aog_days" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        </div>
      </div>

      <!-- Trimester Visit -->
      <div>
        <label for="trimester" class="block text-sm font-medium text-gray-700">Trimester Visit</label>
        <input v-model="form.trimester" type="text" id="trimester" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
      </div>

      <!-- Additional Checkboxes -->
      <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700">Check Items</label>
        <div class="flex items-center">
          <input v-model="form.prenatal_record" type="checkbox" id="prenatal_record" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="prenatal_record" class="ml-2 text-sm text-gray-600">Prenatal record/book updated</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.reminded_importance" type="checkbox" id="reminded_importance" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="reminded_importance" class="ml-2 text-sm text-gray-600">Reminded on importance of FBD</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.health_teachings" type="checkbox" id="health_teachings" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="health_teachings" class="ml-2 text-sm text-gray-600">Health teachings given</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.reminded_dangers" type="checkbox" id="reminded_dangers" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="reminded_dangers" class="ml-2 text-sm text-gray-600">Reminded on the Dangers Signs of Pregnancy</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.healthy_diet" type="checkbox" id="healthy_diet" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="healthy_diet" class="ml-2 text-sm text-gray-600">Healthy diet and increase fluid intake encouraged</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.breast_feeding" type="checkbox" id="breast_feeding" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="breast_feeding" class="ml-2 text-sm text-gray-600">Breast feeding after delivery encouraged</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.compliane_routine" type="checkbox" id="compliane_routine" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="compliane_routine" class="ml-2 text-sm text-gray-600">Compliance to Routine Immunization encouraged</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.referred_utz" type="checkbox" id="referred_utz" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="referred_utz" class="ml-2 text-sm text-gray-600">Referred for UTZ – evaluation of pregnancy</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.fes04_folic" type="checkbox" id="fes04_folic" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="fes04_folic" class="ml-2 text-sm text-gray-600">FES04 + folic acid given - # tabs <input v-model="form.folic_acid" type="number" id="folic_acid" class="h-4 w-10 text-xs text-blue-600 border-gray-300 rounded" >     -given</input> </label>
        </div>
        <!-- Checkbox Items-->
      </div>


      <!-- Submit Button -->
      <button @click="submitForm" type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md mr-2 hover:bg-green-700">
        Submit
        </button>
    </form>
  </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';

export default {
  props: {
    prenatalId: {
      type: Number,
      required: true,
      default: 0,
    },
  },
  data() {
    return {
      form: {
        prenatalId: this.prenatalId,
        date_of_visit: '',
        weight: '',
        bp: '',
        heart_rate: '',
        aog_months: '',
        aog_days: '',
        trimester: '',
        prenatal_record: '',
        reminded_importance: '',
        health_teachings: '',
        reminded_dangers: '',
        healthy_diet: '',
        breast_feeding: '',
        compliane_routine: '',
        referred_utz: '',
        fes04_folic: '',
        folic_acid: '',
      },
      errors: {}
    };
  },
  methods: {
  validateForm() {
    this.errors = {}; // Reset errors
    let isValid = true;

    if (!this.form.date_of_visit) {
      this.errors.date_of_visit = 'Date of Visit is required.';
      isValid = false;
    }
    if (!this.form.weight) {
      this.errors.weight = 'Weight is required.';
      isValid = false;
    }
    if (!this.form.bp) {
      this.errors.bp = 'Blood Pressure is required.';
      isValid = false;
    }
    if (!this.form.heart_rate) {
      this.errors.heart_rate = 'Heart Rate is required.';
      isValid = false;
    }

    return isValid;
  },

  submitForm() {
  if (this.validateForm()) {
    // Ensure prenatalId is included in the form data
      const formData = { ...this.form, prenatalId: this.prenatalId };

      console.log('Form submitted with data:', formData);

      Inertia.post('/trimester2/store', formData, {
        onStart: () => {
          // Show loading indicator or disable submit button
          this.loading = true;
        },
        onFinish: () => {
          // Hide loading indicator or enable submit button
          this.loading = false;
        },
        onSuccess: () => {
          // Handle successful form submission
          console.log('Data saved successfully!');
          this.resetForm();
          alert('Form submitted successfully!');
        },
        onError: (response) => {
          // Check for errors from the server and update the errors object
          console.error('Form submission errors:', response.errors);
          this.errors = response.errors; // Update the errors object with server errors
        }
      });
    } else {
      // If the form validation fails
      this.successMessage = 'Please complete all required fields before submitting.';
      alert(this.successMessage); // Show an alert with the validation message
    }
  },

    resetForm() {
      this.form = {
        date_of_visit: '',
        weight: '',
        bp: '',
        heart_rate: '',
        aog_months: '',
        aog_days: '',
        trimester: '',
        prenatal_record: '',
        reminded_importance: '',
        health_teachings: '',
        reminded_dangers: '',
        healthy_diet: '',
        breast_feeding: '',
        compliane_routine: '',
        referred_utz: '',
        fes04_folic: '',
        folic_acid: '',
      };
      this.errors = {}; // Reset error messages
      this.successMessage = ''; // Reset success message
    }
  }
};
</script>

<style scoped>
/* Add any custom styling here */
/* Chrome, Safari, Edge, Opera */
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Firefox */
input[type="number"] {
    -moz-appearance: textfield;
}

</style>
