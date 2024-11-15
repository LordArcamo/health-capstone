<template>
  <div class="max-h-screen overflow-y-scroll p-6 bg-white shadow rounded-lg">
    <form @submit.prevent="submitForm" class="space-y-4">
      <!-- Date of Visit -->
      <div>
        <label for="date_of_visit" class="block text-sm font-medium text-gray-700">Date of Visit</label>
        <input v-model="form.date_of_visit" type="date" id="date_of_visit" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        <p v-if="errors.date_of_visit" class="text-red-500 text-sm">{{ errors.date_of_visit }}</p>
      </div>

      <!-- Weight -->
      <div>
        <label for="weight" class="block text-sm font-medium text-gray-700">Weight (kg)</label>
        <input v-model="form.weight" type="number" step="0.1" id="weight" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        <p v-if="errors.weight" class="text-red-500 text-sm">{{ errors.weight }}</p>
      </div>

      <!-- Blood Pressure and Heart Rate -->
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="bp" class="block text-sm font-medium text-gray-700">BP</label>
          <input v-model="form.bp" type="text" id="bp" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
          <p v-if="errors.bp" class="text-red-500 text-sm">{{ errors.bp }}</p>
        </div>
        <div>
          <label for="heart_rate" class="block text-sm font-medium text-gray-700">Heart Rate</label>
          <input v-model="form.heart_rate" type="number" id="heart_rate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
          <p v-if="errors.heart_rate" class="text-red-500 text-sm">{{ errors.heart_rate }}</p>
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
          <input v-model="form.prenatal_checkup" type="checkbox" id="prenatal_checkup" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="prenatal_checkup" class="ml-2 text-sm text-gray-600">In for prenatal check-up</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.pe_done" type="checkbox" id="pe_done" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="pe_done" class="ml-2 text-sm text-gray-600">PE done, TT status assessed</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.prenatal_record" type="checkbox" id="prenatal_record" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="prenatal_record" class="ml-2 text-sm text-gray-600">Prenatal record/book given and filled up</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.birth_plan_done" type="checkbox" id="birth_plan_done" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="birth_plan_done" class="ml-2 text-sm text-gray-600">Birth plan done</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.nkfda" type="checkbox" id="nkfda" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="nkfda" class="ml-2 text-sm text-gray-600">NKFDA</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.health_teachings_given_danger_signs_pregnancy" type="checkbox" id="health_teachings_given_danger_signs_pregnancy" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="health_teachings_given_danger_signs_pregnancy" class="ml-2 text-sm text-gray-600">Health teachings given; danger signs of pregnancy imparted</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.referred_for_urinalysis_hct_hgb_count" type="checkbox" id="referred_for_urinalysis_hct_hgb_count" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="referred_for_urinalysis_hct_hgb_count" class="ml-2 text-sm text-gray-600">Referred for Urinalysis, HCT - HGB count</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.healthy_diet_increase_fluid_intake_encouraged" type="checkbox" id="healthy_diet_increase_fluid_intake_encouraged" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="healthy_diet_increase_fluid_intake_encouraged" class="ml-2 text-sm text-gray-600">Healthy diet and increase fluid intake encouraged</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.fes04_folic_acid_given" type="checkbox" id="fes04_folic_acid_given" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="fes04_folic_acid_given" class="ml-2 text-sm text-gray-600">FES04 + folic acid given - # tabs <input v-model="form.folic_acid" type="number" id="folic_acid_tabs" class="h-4 w-10 text-xs text-blue-600 border-gray-300 rounded" > -given</input></label>
        </div>
      </div>

      <!-- Observations: FHB, Position, Presentation, Fundal Height -->
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="fhb" class="block text-sm font-medium text-gray-700">FHB</label>
          <input v-model="form.fhb" type="text" id="fhb" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        </div>
        <div>
          <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
          <input v-model="form.position" type="text" id="position" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        </div>
        <div>
          <label for="presentation" class="block text-sm font-medium text-gray-700">Presentation</label>
          <input v-model="form.presentation" type="text" id="presentation" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        </div>
        <div>
          <label for="fundal_height" class="block text-sm font-medium text-gray-700">Fundal Height</label>
          <input v-model="form.fundal_height" type="text" id="fundal_height" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        </div>
      </div>

      <!-- Submit Button -->
      <button @click="submitForm" type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md mr-2 hover:bg-green-700">
          Submit
        </button>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: {
        date_of_visit: '',
        weight: '',
        bp: '',
        heart_rate: '',
        aog_months: '',
        aog_days: '',
        trimester: '',
        prenatal_checkup: '',
        pe_done: '',
        prenatal_record: '',
        birth_plan_done: '',
        nkfda: '',
        health_teachings: '',
        referred_for: '',
        healthy_diet: '',
        fes04_folic: '',
        folic_acid: '',
        fhb: '',
        position: '',
        presentation: '',
        fundal_height: ''
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
        // Form submission logic
        console.log('Form submitted:', this.form);
        // Reset form after submission
        this.resetForm();
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
        prenatal_checkup: '',
        pe_done: '',
        prenatal_record: '',
        birth_plan_done: '',
        nkfda: '',
        health_teachings: '',
        referred_for: '',
        healthy_diet: '',
        fes04_folic: '',
        folic_acid: '',
        fhb: '',
        position: '',
        presentation: '',
        fundal_height: ''
      };
    }
  }
};
</script>

<style scoped>
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